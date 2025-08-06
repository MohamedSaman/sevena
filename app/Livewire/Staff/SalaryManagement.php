<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\Salaries;
use App\Models\ProductionSalaries;
use App\Models\Loans;
use Carbon\Carbon;

#[Title("Staff Dashboard")]
#[Layout("components.layouts.staff")]
class SalaryManagement extends Component
{
    public $activeTab = 'monthly';
    public $employees;
    public $form = [
        'employee_id' => '',
        'month' => '3',
        'year' => '2024',
    ];
    public $perMomthLoanAmount = 0;
    public $salaryBreakdown = null;
    public $loanDetails = 0;
    public $showPayslipModal = false;
    public $employeeDetails = null;
    public $currentSalaryRecord = null; // Track which salary record is selected

    public function mount()
    {
        $this->employees = Employee::select('emp_id', 'fname', 'salary_type', 'basic_salary', 'designation', 'nic')->get();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->salaryBreakdown = null;
        $this->form['employee_id'] = '';
    }

    public function calculateSalary()
    {
        $this->validate([
            'form.employee_id' => 'required|exists:employees,emp_id',
            'form.month' => 'required|integer|between:1,12',
            'form.year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $employeeId = $this->form['employee_id'];
        $salaryMonth = sprintf('%s-%02d', $this->form['year'], $this->form['month']);
        $employee = $this->employees->firstWhere('emp_id', $employeeId);
        $this->employeeDetails = $employee;
        $loanDetails = Loans::where('employee_id', $employeeId)
            ->first(['monthly_payment', 'remaining_balance']);

        $salaryTypeMap = [
            'monthly' => 'monthly',
            'daily' => 'daily',
            'weekly' => 'monthly',
        ];
        $employeeSalaryType = $employee->salary_type;
        $salaryType = $salaryTypeMap[$this->activeTab] ?? 'monthly';

        if ($salaryType !== $employeeSalaryType) {
            session()->flash('error', 'Employee salary type (' . $employeeSalaryType . ') does not match the selected tab (' . $this->activeTab . ').');
            return;
        }

        $salary = Salaries::where('employee_id', $employeeId)
            ->where('salary_month', $salaryMonth)
            ->where('salary_type', $salaryType)
            ->first();

        if (!$salary) {
            $productionBonus = ProductionSalaries::where('employee_id', $employeeId)
                ->where('category', $this->activeTab === 'monthly' ? 'magi' : 'papadam')
                ->whereMonth('created_at', $this->form['month'])
                ->whereYear('created_at', $this->form['year'])
                ->sum('total_salary');

            $basicSalary = $employee->basic_salary;
            $overtime = $employeeSalaryType === 'monthly' ? 3200 : 500;
            $allowances = $employeeSalaryType === 'monthly' ? 5000 : 200;
            $grossSalary = $basicSalary + $productionBonus + $overtime + $allowances;
            $epf = ($basicSalary + $allowances) * 0.08;
            $etf = $grossSalary * 0.03;
            
            $loanDeductions = 0;
            if ($loanDetails && $loanDetails->remaining_balance > 0) {
                $loanDeductions = $loanDetails->monthly_payment;
            }

            $otherDeductions = 0;
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
                'employee_name' => $employee->fname,
                'month_name' => Carbon::create()->month($this->form['month'])->format('F'),
                'year' => $this->form['year'],
            ];

            $salary = Salaries::create([
                'employee_id' => $employeeId,
                'salary_month' => $salaryMonth,
                'salary_type' => $salaryType,
                'basic_salary' => $basicSalary,
                'bonus' => $productionBonus,
                'allowance' => $allowances,
                'deductions' => $loanDeductions + $otherDeductions,
                'net_salary' => $netSalary,
                'payment_status' => 'unpaid',
            ]);
            
            // Set as current salary record
            $this->currentSalaryRecord = $salary;
        } else {
            $loanDeductions = 0;
            if ($loanDetails && $loanDetails->remaining_balance > 0) {
                $loanDeductions = $loanDetails->monthly_payment;
            }

            $this->salaryBreakdown = [
                'basic_salary' => $salary->basic_salary,
                'production_bonus' => $salary->bonus,
                'overtime' => $employeeSalaryType === 'monthly' ? 3200 : 500,
                'allowances' => $salary->allowance,
                'gross_salary' => $salary->basic_salary + $salary->bonus + ($employeeSalaryType === 'monthly' ? 3200 : 500) + $salary->allowance,
                'epf' => ($salary->basic_salary + $salary->allowance) * 0.08,
                'etf' => ($salary->basic_salary + $salary->bonus + $salary->allowance) * 0.03,
                'loan_deductions' => $loanDeductions,
                'other_deductions' => 0,
                'net_salary' => $salary->net_salary,
                'employee_name' => $employee->fname,
                'month_name' => Carbon::create()->month($this->form['month'])->format('F'),
                'year' => $this->form['year'],
            ];
            
            // Set as current salary record
            $this->currentSalaryRecord = $salary;
        }

        session()->flash('message', 'Salary calculated successfully.');
    }

    public function markAsPaid()
    {
        if ($this->currentSalaryRecord) {
            $salary = $this->currentSalaryRecord;
            $employeeId = $salary->employee_id;
            $loanDetails = Loans::where('employee_id', $employeeId)->first();

            $loanDeductions = $loanDetails && $loanDetails->remaining_balance > 0 ? 
                $loanDetails->monthly_payment : 0;

            // 1. Update salary payment status
            $salary->update(['payment_status' => 'paid']);

            // 2. Deduct loan amount from remaining balance (only if applicable)
            if ($loanDetails && $loanDeductions > 0) {
                $loanDetails->remaining_balance -= $loanDeductions;
                $loanDetails->save();
            }

            session()->flash('message', 'Salary marked as paid and loan updated.');
            $this->currentSalaryRecord = null;
        }
    }
       public function showPayslip()
    {
        if ($this->salaryBreakdown) {
            $this->showPayslipModal = true;
        } else {
            session()->flash('error', 'Please calculate the salary first.');
        }
    }
    
    // New method to mark a specific record as paid
 public function markRecordAsPaid($salaryId)
    {
        $salary = Salaries::find($salaryId);
        if ($salary) {
            $employeeId = $salary->employee_id;
            $loanDetails = Loans::where('employee_id', $employeeId)->first();

            $loanDeductions = $loanDetails && $loanDetails->remaining_balance > 0 ? 
                $loanDetails->monthly_payment : 0;

            // Update salary payment status
            $salary->update(['payment_status' => 'paid']);

            // Deduct loan amount from remaining balance
            if ($loanDetails && $loanDeductions > 0) {
                $loanDetails->remaining_balance -= $loanDeductions;
                $loanDetails->save();
            }

            session()->flash('message', 'Salary marked as paid and loan updated.');
        }
    }

    public function showPayslipForRecord($salaryId)
    {
        // dd($salaryId);
        $salary = Salaries::find($salaryId);
        if ($salary) {
            $employee = Employee::find($salary->employee_id);
            $this->employeeDetails = $employee;
            
            $salaryMonth = Carbon::parse($salary->salary_month);
            
            $this->salaryBreakdown = [
                'basic_salary' => $salary->basic_salary,
                'production_bonus' => $salary->bonus,
                'overtime' => $employee->salary_type === 'monthly' ? 3200 : 500,
                'allowances' => $salary->allowance,
                'gross_salary' => $salary->basic_salary + $salary->bonus + ($employee->salary_type === 'monthly' ? 3200 : 500) + $salary->allowance,
                'epf' => ($salary->basic_salary + $salary->allowance) * 0.08,
                'etf' => ($salary->basic_salary + $salary->bonus + $salary->allowance) * 0.03,
                'loan_deductions' => $salary->deductions,
                'other_deductions' => 0,
                'net_salary' => $salary->net_salary,
                'employee_name' => $employee->fname,
                'month_name' => $salaryMonth->format('F'),
                'year' => $salaryMonth->format('Y'),
            ];
            
            $this->currentSalaryRecord = $salary;
            $this->showPayslipModal = true;
        }
    }

    public function render()
    {
        $salaries = Salaries::with(['employee' => function ($query) {
            $query->select('emp_id', 'fname');
        }])
            ->where('salary_type', $this->activeTab)
            ->get();

        return view('livewire.staff.salary-management', [
            'salaries' => $salaries,
        ]);
    }
}