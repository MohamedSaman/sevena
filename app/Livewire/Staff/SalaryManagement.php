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
use App\Models\Attendance;
use Carbon\Carbon;

#[Title("Staff Dashboard")]
#[Layout("components.layouts.staff")]
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
    public  $totalWorkedHours = 0;

    public function mount()

    {

        $this->form['month'] = date('n');
        $this->form['year'] = date('Y');
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

        // Only consider loans with remaining balance
        $loanDetails = Loans::where('employee_id', $employeeId)
            ->where('remaining_balance', '>', 0)
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

        // Calculate total worked hours for the month
        $totalWorkedHours = Attendance::where('employee_id', $employeeId)
            ->whereYear('date', $this->form['year'])
            ->whereMonth('date', $this->form['month'])
            ->sum('time_worked');

        // Calculate basic salary and overtime
        $basicHoursPermonth = 195;
        $perHourAmount = $employee->basic_salary / $basicHoursPermonth;

        if ($totalWorkedHours <= $basicHoursPermonth) {
            $basicSalaryPay = $totalWorkedHours * $perHourAmount;
            $overtime = 0;
            $overTimeHours = 0;
        } else {
            $basicSalaryPay = $basicHoursPermonth * $perHourAmount;
            $overTimeHours = $totalWorkedHours - $basicHoursPermonth;
            $overtime = $overTimeHours * 1.5 * $perHourAmount;
        }
        $this->totalWorkedHours = $totalWorkedHours;

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

            $allowances = $employeeSalaryType === 'monthly' ? 5000 : 200;
            $grossSalary = $basicSalaryPay + $productionBonus + $overtime + $allowances;
            $epf = ($basicSalaryPay + $allowances) * 0.08;
            $etf = $grossSalary * 0.03;

            // Calculate loan deductions - only deduct if it won't make net salary negative
            $loanDeductions = 0;
            if ($loanDetails && $loanDetails->remaining_balance > 0) {
                $potentialNetSalary = $grossSalary - $epf - $etf - $loanDetails->monthly_payment;
                if ($potentialNetSalary >= 0) {
                    $loanDeductions = $loanDetails->monthly_payment;
                }
            }

            $otherDeductions = 0;
            $netSalary = $grossSalary - $epf - $etf - $loanDeductions - $otherDeductions;

            $this->salaryBreakdown = [
                'basic_salary' => $basicSalaryPay,
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
                'total_hours' => $this->totalWorkedHours,
                'overtime_hours' => $overTimeHours ?? 0,
            ];

            $salary = Salaries::create([
                'employee_id' => $employeeId,
                'salary_month' => $salaryMonth,
                'salary_type' => $salaryType,
                'basic_salary' => $basicSalaryPay,
                'bonus' => $productionBonus,
                'allowance' => $allowances,
                'overtime' => $overtime,
                'deductions' => $loanDeductions + $otherDeductions,
                'net_salary' => $netSalary,
                'payment_status' => 'unpaid',
                'total_hours' => $totalWorkedHours,
                'overtime_hours' => $overTimeHours ?? 0,
            ]);

            $this->currentSalaryRecord = $salary;
        } else {
            $loanDeductions = 0;
            if ($loanDetails && $loanDetails->remaining_balance > 0) {
                $potentialNetSalary = $salary->gross_salary - $salary->epf - $salary->etf - $loanDetails->monthly_payment;
                if ($potentialNetSalary >= 0) {
                    $loanDeductions = $loanDetails->monthly_payment;
                }
            }

            $this->salaryBreakdown = [
                'basic_salary' => $salary->basic_salary,
                'production_bonus' => $salary->bonus,
                'overtime' => $salary->overtime,
                'allowances' => $salary->allowance,
                'gross_salary' => $salary->basic_salary + $salary->bonus + $salary->overtime + $salary->allowance,
                'epf' => ($salary->basic_salary + $salary->allowance) * 0.08,
                'etf' => ($salary->basic_salary + $salary->bonus + $salary->allowance) * 0.03,
                'loan_deductions' => $loanDeductions,
                'other_deductions' => 0,
                'net_salary' => ($salary->basic_salary + $salary->bonus + $salary->overtime + $salary->allowance) -
                    (($salary->basic_salary + $salary->allowance) * 0.08) -
                    (($salary->basic_salary + $salary->bonus + $salary->allowance) * 0.03) -
                    $loanDeductions,
                'employee_name' => $employee->fname,
                'month_name' => Carbon::create()->month($this->form['month'])->format('F'),
                'year' => $this->form['year'],
                'total_hours' => $salary->total_hours,
                'overtime_hours' => $salary->overtime_hours,
            ];

            $this->currentSalaryRecord = $salary;
        }

        session()->flash('message', 'Salary calculated successfully.');
    }

    public function markAsPaid()
    {
        if ($this->currentSalaryRecord) {
            $salary = $this->currentSalaryRecord;
            $employeeId = $salary->employee_id;

            $loanDetails = Loans::where('employee_id', $employeeId)
                ->where('remaining_balance', '>', 0)
                ->first();

            // Only deduct loan if it was actually applied to the salary
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

        return view('livewire.staff.salary-management', [
            'salaries' => $salaries,
        ]);
    }
}
