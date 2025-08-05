<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Loans;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title("Staff Dashboard")]
#[Layout("components.layouts.staff")]

class LoanManagement extends Component
{
   public $employees;
    public $form = [
        'employee_id' => '',
        'loan_amount' => '',
        'interest_rate' => '',
        'start_date' => '',
        'term_month' => '',
    ];
    public $loanBreakdown = null;

    public function mount()
    {
        $this->employees = Employee::select('emp_id', 'fname')->get();
    }

    public function addLoan()
    {
        $this->validate([
            'form.employee_id' => 'required|exists:employees,emp_id',
            'form.loan_amount' => 'required|numeric|min:1000|max:1000000',
            'form.interest_rate' => 'required|numeric|min:0|max:20',
            'form.start_date' => 'required|date|before_or_equal:today',
            'form.term_month' => 'required|integer|min:1|max:60',
        ]);

        $employeeId = $this->form['employee_id'];
        $loanAmount = $this->form['loan_amount'];
        $interestRate = $this->form['interest_rate'] / 100; // Convert to decimal
        $startDate = $this->form['start_date'];
        $termMonth = $this->form['term_month'];

        // Calculate monthly payment (simple interest formula: P * (1 + rt) / t)
        $totalInterest = $loanAmount * $interestRate * ($termMonth / 12);
        $totalAmount = $loanAmount + $totalInterest;
        $monthlyPayment = $totalAmount / $termMonth;
        $remainingBalance = $totalAmount;

        $this->loanBreakdown = [
            'employee_id' => $employeeId,
            'loan_amount' => $loanAmount,
            'interest_rate' => $this->form['interest_rate'],
            'start_date' => $startDate,
            'term_month' => $termMonth,
            'monthly_payment' => $monthlyPayment,
            'remaining_balance' => $remainingBalance,
        ];

        // Save to loans table
        Loans::create([
            'employee_id' => $employeeId,
            'loan_amount' => $loanAmount,
            'interest_rate' => $this->form['interest_rate'],
            'start_date' => $startDate,
            'term_month' => $termMonth,
            'monthly_payment' => $monthlyPayment,
            'remaining_balance' => $remainingBalance,
            'status' => 'active',
        ]);

        session()->flash('message', 'Loan added successfully.');
        $this->resetForm();
    }

    public function markAsPaid($loanId)
    {
        $loan = Loans::find($loanId);
        if ($loan) {
            $loan->update([
                'status' => 'paid',
                'remaining_balance' => 0,
            ]);
            session()->flash('message', 'Loan marked as paid.');
        }
    }

    public function resetForm()
    {
        $this->form = [
            'employee_id' => '',
            'loan_amount' => '',
            'interest_rate' => '',
            'start_date' => '',
            'term_month' => '',
        ];
        $this->loanBreakdown = null;
    }

    public function render()
    {
        $loans = Loans::with(['employee' => function ($query) {
            $query->select('emp_id', 'fname');
        }])->get();

        return view('livewire.staff.loan-management', [
            'loans' => $loans,
        ]);
    }
}
