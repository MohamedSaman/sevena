<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\Salaries;
use App\Models\ProductionSalaries;
use App\Models\Loans;
use App\Models\Attendance;
use Carbon\Carbon;

#[Title("Admin Dashboard")]
#[Layout("components.layouts.admin")]
class SalaryManagement extends Component
{
    public $activeTab = 'monthly';
    public $employees;
    public $form = [
        'employee_id' => '',
        'month' => '',
        'year' => '',
    ];
    public $perMomthLoanAmount = 0;
    public $salaryBreakdown = null;
    public $loanDetails = 0;
    public $showPayslipModal = false;
    public $employeeDetails = null;
    public $currentSalaryRecord = null;
    public $totalWorkedHours = 0;
    public $loanbalance = 0;

    public function mount()
    {
        $this->form['month'] = date('n');
        $this->form['year'] = date('Y');
        $this->employees = Employee::select(
            'emp_id',
            'fname',
            'salary_type',
            'basic_salary',
            'designation',
            'nic',
            'allowance' // NEW: fetch allowance from employee table
        )->get();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->salaryBreakdown = null;
        $this->form['employee_id'] = '';
    }

public function calculateSalary()
{ $this->validate([
        'form.employee_id' => 'required|exists:employees,emp_id',
        'form.month' => 'required|integer|between:1,12',
        'form.year' => 'required|integer|min:2000|max:' . date('Y'),
    ]);

    $employeeId = $this->form['employee_id'];
    $salaryMonth = sprintf('%s-%02d', $this->form['year'], $this->form['month']);
    $employee = $this->employees->firstWhere('emp_id', $employeeId);
    $this->employeeDetails = $employee;

    $employeeSalaryType = $employee->salary_type;

    // DAILY workers salary calculation from production_salaries table
    if ($employeeSalaryType === 'daily') {
        $totalProductionSalary = ProductionSalaries::where('employee_id', $employeeId)
            ->whereMonth('created_at', $this->form['month'])
            ->whereYear('created_at', $this->form['year'])
            ->sum('total_salary');
        // dd($totalProductionSalary);
        $allowances = ProductionSalaries::where('employee_id', $employeeId)
            ->whereMonth('created_at', $this->form['month'])
            ->whereYear('created_at', $this->form['year'])
            ->sum('allowance');

        $bonus = ProductionSalaries::where('employee_id', $employeeId)
            ->whereMonth('created_at', $this->form['month'])
            ->whereYear('created_at', $this->form['year'])
            ->sum('bonus');

        $additionalSalary = ProductionSalaries::where('employee_id', $employeeId)
            ->whereMonth('created_at', $this->form['month'])
            ->whereYear('created_at', $this->form['year'])
            ->sum('additional_salary');

        $epf = 0; // No EPF for daily workers
        $etf = 0; // No ETF for daily workers
        
        $grossSalary = $totalProductionSalary + $allowances + $bonus + $additionalSalary;

        $this->salaryBreakdown = [
            'basic_salary' => $totalProductionSalary,
            'production_bonus' => $bonus,
            'additional_salary' => $additionalSalary,
            'allowances' => $allowances,
            'gross_salary' => $grossSalary,
            'epf' => $epf,
            'etf' => $etf,
            'loan_deductions' => 0,
            'other_deductions' => 0,
            'net_salary' => $grossSalary,
            'employee_name' => $employee->fname . ' ' . $employee->lname,
            'month_name' => Carbon::create()->month($this->form['month'])->format('F'),
            'year' => $this->form['year'],
            'total_hours' => 0,
            'overtime_hours' => 0,
        ];

        $this->currentSalaryRecord = Salaries::updateOrCreate(
            [
                'employee_id' => $employeeId,
                'salary_month' => $salaryMonth,
                'salary_type' => 'daily',
            ],
            [
                'basic_salary' => $totalProductionSalary,
                'bonus' => $bonus,
                'allowance' => $allowances,
                'additional_salary' => $additionalSalary,
                'deductions' => 0,
                'net_salary' => $grossSalary,
                'payment_status' => 'unpaid',
                'total_hours' => 0,
                'overtime_hours' => 0,
            ]
        );

        session()->flash('message', 'Daily worker salary calculated successfully.');
        return;
    }


    // --------- Your existing MONTHLY workers salary logic ---------
    // Example (replace with your actual monthly calculation logic)
    $basicSalary = $employee->basic_salary;
    $allowances = 5000; // replace with actual calc
    $bonus = 2000; // replace with actual calc
    $overtime = 0; // replace with actual calc
    $epf = ($basicSalary * 0.08); // example EPF deduction
    $etf = ($basicSalary * 0.03); // example ETF deduction
    $grossSalary = $basicSalary + $allowances + $bonus + $overtime;
    $netSalary = $grossSalary - $epf - $etf;

    $this->salaryBreakdown = [
        'basic_salary' => $basicSalary,
        'production_bonus' => $bonus,
        'overtime' => $overtime,
        'allowances' => $allowances,
        'gross_salary' => $grossSalary,
        'epf' => $epf,
        'etf' => $etf,
        'loan_deductions' => 0,
        'other_deductions' => 0,
        'net_salary' => $netSalary,
        'employee_name' => $employee->fname . ' ' . $employee->lname,
        'month_name' => Carbon::create()->month($this->form['month'])->format('F'),
        'year' => $this->form['year'],
        'total_hours' => 0,
        'overtime_hours' => 0,
    ];

    $this->currentSalaryRecord = Salaries::updateOrCreate(
        [
            'employee_id' => $employeeId,
            'salary_month' => $salaryMonth,
            'salary_type' => 'monthly',
        ],
        [
            'basic_salary' => $basicSalary,
            'bonus' => $bonus,
            'allowance' => $allowances,
            'overtime' => $overtime,
            'deductions' => ($epf + $etf),
            'net_salary' => $netSalary,
            'payment_status' => 'unpaid',
            'total_hours' => 0,
            'overtime_hours' => 0,
        ]
    );

    session()->flash('message', 'Monthly worker salary calculated successfully.');
}


    public function markAsPaid()
    {
        if ($this->currentSalaryRecord) {
            $salary = $this->currentSalaryRecord;
            $employeeId = $salary->employee_id;

            $loanDetails = Loans::where('employee_id', $employeeId)
                ->where('remaining_balance', '>', 0)
                ->first();

            if ($this->salaryBreakdown['loan_deductions'] > 0 && $loanDetails) {
                $loanDetails->remaining_balance -= $this->salaryBreakdown['loan_deductions'];
                if ($loanDetails->remaining_balance < 0) {
                    $loanDetails->remaining_balance = 0;
                }
                $loanDetails->save();
            }

            $salary->update(['payment_status' => 'paid']);
            session()->flash('message', 'Salary marked as paid' . ($this->salaryBreakdown['loan_deductions'] > 0 ? ' and loan updated' : ''));
            $this->currentSalaryRecord = null;
        }
    }

    public function showPayslip()
    {
        $this->calculateSalary();

        if ($this->salaryBreakdown) {
            $this->showPayslipModal = true;
        } else {
            session()->flash('error', 'Please calculate the salary first.');
        }
    }

    public function markRecordAsPaid($salaryId)
    {
        $salary = Salaries::find($salaryId);
        if ($salary) {
            $employeeId = $salary->employee_id;
            $loanDetails = Loans::where('employee_id', $employeeId)->first();

            $loanDeductions = $loanDetails && $loanDetails->remaining_balance > 0 ?
                $loanDetails->monthly_payment : 0;

            $salary->update(['payment_status' => 'paid']);

            if ($loanDetails && $loanDeductions > 0) {
                $loanDetails->remaining_balance -= $loanDeductions;
                $loanDetails->save();
            }

            session()->flash('message', 'Salary marked as paid and loan updated.');
        }
    }

    public function showPayslipForRecord($salaryId)
    {
        $salary = Salaries::find($salaryId);
        if ($salary) {
            $employee = Employee::find($salary->employee_id);
            $this->employeeDetails = $employee;

            $salaryMonth = Carbon::parse($salary->salary_month);

            $gross_salary = $salary->basic_salary + $salary->bonus + $salary->overtime + $salary->allowance;
            $epf = ($salary->basic_salary + $salary->allowance) * 0.08;
            $etf = $gross_salary * 0.03;
            $this->loanbalance = Loans::where('employee_id', $salary->employee_id)
                ->value('remaining_balance');
            $this->salaryBreakdown = [
                'basic_salary' => $salary->basic_salary,
                'production_bonus' => $salary->bonus,
                'overtime' => $salary->overtime,
                'allowances' => $salary->allowance,
                'gross_salary' => $gross_salary,
                'epf' => $epf,
                'etf' => $etf,
                'loan_deductions' => $salary->deductions,
                'other_deductions' => 0,
                'net_salary' => $salary->net_salary,
                'employee_name' => $employee->fname,
                'month_name' => $salaryMonth->format('F'),
                'year' => $salaryMonth->format('Y'),
                'total_hours' => $salary->total_hours,
                'overtime_hours' => $salary->overtime_hours,
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

        return view('livewire.admin.salary-management', [
            'salaries' => $salaries,
        ]);
    }
}
