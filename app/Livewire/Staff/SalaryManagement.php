<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\salaries;
use App\Models\ProductionSalaries;

#[Title("Staff Dashboard")]
#[Layout("components.layouts.staff")]
class SalaryManagement extends Component
{


   public $activeTab = 'monthly'; // Default tab
    public $employees;
    public $form = [
        'employee_id' => '',
        'month' => '3', // Default to March
        'year' => '2024', // Default to 2024
    ];
    public $salaryBreakdown = null;

    public function mount()
    {
        $this->employees = Employee::select('emp_id', 'fname', 'salary_type', 'basic_salary')->get();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->salaryBreakdown = null; // Reset breakdown
        $this->form['employee_id'] = ''; // Reset employee selection
    }

    public function calculateSalary()
    {
        $this->validate([
            'form.employee_id' => 'required|exists:employees,emp_id',
            'form.month' => 'required|integer|between:1,12',
            'form.year' => 'required|integer|min:2000|max:'.date('Y'),
        ]);

        $employeeId = $this->form['employee_id'];
        $salaryMonth = sprintf('%s-%02d', $this->form['year'], $this->form['month']);
        $employee = $this->employees->firstWhere('emp_id', $employeeId);

        // Map UI tab to salaries.salary_type (only 'monthly' or 'daily' allowed)
        $salaryTypeMap = [
            'monthly' => 'monthly',
            'daily' => 'daily',
            'weekly' => 'monthly', // Map weekly to monthly (adjust if needed)
        ];
        $employeeSalaryType = $employee->salary_type; // 'monthly' or 'daily'
        $salaryType = $salaryTypeMap[$this->activeTab] ?? 'monthly';

        // Validate salary_type compatibility
        if ($salaryType !== $employeeSalaryType) {
            session()->flash('error', 'Employee salary type (' . $employeeSalaryType . ') does not match the selected tab (' . $this->activeTab . ').');
            return;
        }

        // Fetch existing salary record or calculate new one
        $salary = salaries::where('employee_id', $employeeId)
            ->where('salary_month', $salaryMonth)
            ->where('salary_type', $salaryType)
            ->first();
      
        if (!$salary) {
            // Calculate production bonus from ProductionSalaries
            $productionBonus = ProductionSalaries::where('employee_id', $employeeId)
                ->where('category', $this->activeTab === 'monthly' ? 'magi' : 'papadam')
                ->whereMonth('created_at', $this->form['month'])
                ->whereYear('created_at', $this->form['year'])
                ->sum('total_salary');

            // Use basic_salary from employees table
            $basicSalary = $employee->basic_salary ;
            // dd($basicSalary, $productionBonus, $employeeSalaryType);
            $overtime = $employeeSalaryType === 'monthly' ? 3200 : 500; // Example
            $allowances = $employeeSalaryType === 'monthly' ? 5000 : 200; // Example
            $grossSalary = $basicSalary + $productionBonus + $overtime + $allowances;
            $epf = $grossSalary * 0.08; // 8% EPF
            $etf = $grossSalary * 0.03; // 3% ETF
            $loanDeductions = $employeeSalaryType === 'monthly' ? 2500 : 100; // Example
            $otherDeductions = $employeeSalaryType === 'monthly' ? 1200 : 50; // Example
            $netSalary = $grossSalary - $epf - $etf - $loanDeductions - $otherDeductions;

            $this->salaryBreakdown = [
                'basic_salary' => $basicSalary,
                'production_bonus' => $productionBonus,
                'overtime' => $overtime,
                'allowances' => $allowances,
                'gross_salary' => $grossSalary,
                'epf' => $epf,
                'etf' => $etf,
                'loan_deductions' => $loanDeductions,
                'other_deductions' => $otherDeductions,
                'net_salary' => $netSalary,
            ];

            // Save to salaries table
            salaries::create([
                'employee_id' => $employeeId,
                'salary_month' => $salaryMonth,
                'salary_type' => $salaryType, // Use mapped salary_type
                'basic_salary' => $basicSalary,
                'bonus' => $productionBonus,
                'allowance' => $allowances,
                'deductions' => $loanDeductions + $otherDeductions,
                'net_salary' => $netSalary,
                'payment_status' => 'unpaid',
            ]);
        } else {
            $this->salaryBreakdown = [
                'basic_salary' => $salary->basic_salary,
                'production_bonus' => $salary->bonus,
                'overtime' => $employeeSalaryType === 'monthly' ? 3200 : 500,
                'allowances' => $salary->allowance,
                'gross_salary' => $salary->basic_salary + $salary->bonus + ($employeeSalaryType === 'monthly' ? 3200 : 500) + $salary->allowance,
                'epf' => ($salary->basic_salary + $salary->bonus + ($employeeSalaryType === 'monthly' ? 3200 : 500) + $salary->allowance) * 0.08,
                'etf' => ($salary->basic_salary + $salary->bonus + ($employeeSalaryType === 'monthly' ? 3200 : 500) + $salary->allowance) * 0.03,
                'loan_deductions' => $salary->deductions - ($employeeSalaryType === 'monthly' ? 1200 : 50),
                'other_deductions' => $employeeSalaryType === 'monthly' ? 1200 : 50,
                'net_salary' => $salary->net_salary,
            ];
        }

        session()->flash('message', 'Salary calculated successfully.');
    }

    public function markAsPaid()
    {
        if ($this->salaryBreakdown) {
            $salary = salaries::where('employee_id', $this->form['employee_id'])
                ->where('salary_month', sprintf('%s-%02d', $this->form['year'], $this->form['month']))
                ->where('salary_type', $this->activeTab)
                ->first();

            if ($salary) {
                $salary->update(['payment_status' => 'paid']);
                session()->flash('message', 'Salary marked as paid.');
            }
        }
    }

    public function render()
    {
        $salaries = salaries::with(['employee' => function ($query) {
            $query->select('emp_id', 'fname');
        }])
            ->where('salary_type', $this->activeTab)
            ->get();

        return view('livewire.staff.salary-management', [
            'salaries' => $salaries,
        ]);
    }
}
