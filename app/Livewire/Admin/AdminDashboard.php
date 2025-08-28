<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;  
use App\Models\Employee;
use App\Models\Loans;
use App\Models\ProductionSalaries;
use App\Models\Salaries;
use Carbon\Carbon;

#[Layout('components.layouts.admin')]
#[Title('Dashboard')]
class AdminDashboard extends Component
{
    
     public $totalEmployees;
    public $totalloans;
    public $totalloanamount;
    public $totalactiveemployees;
    public $magiProduction;
    public $papadamProduction;
    public $pendingSalary;
    public $recentActivities;
    public $sidebarOpen = false;

    public function mount()
    {
        // Total Employees
        $this->totalEmployees = Employee::count();

        // Total Active Employees (assuming 'status' field exists)
        $this->totalactiveemployees = Employee::where('status', 'active')->count();

        // Total Loans and Loan Amount
        $this->totalloans = Loans::count();
        $this->totalloanamount = Loans::sum('loan_amount');

        // Magi and Papadam Production (sum of quantities from ProductionSalaries for current month)
        $this->magiProduction = ProductionSalaries::where('category', 'magi')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_salary'); // Adjust if quantity is a separate field
        $this->papadamProduction = ProductionSalaries::where('category', 'papadam')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_salary'); // Adjust if quantity is a separate field

        // Pending Salary (sum of net_salary for unpaid salaries)
        $this->pendingSalary = salaries::where('payment_status', 'unpaid')
            ->sum('net_salary');

        // Recent Activities (from ProductionSalaries, salaries, and loans)
        $this->recentActivities = collect()
            ->merge(
                ProductionSalaries::with(['employee' => function ($query) {
                    $query->select('emp_id', 'fname');
                }])
                ->select('employee_id', 'category', 'total_salary as quantity', 'created_at')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->get()
                ->map(function ($item) {
                    return [
                        'employee' => $item->employee ? $item->employee->fname : 'Unknown',
                        'activity' => 'Production Entry',
                        'product' => ucfirst($item->category),
                        'quantity' => number_format($item->quantity, 0) . ' kg',
                        'status' => 'Completed',
                        'date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                    ];
                })
            )
            ->merge(
                Salaries::with(['employee' => function ($query) {
                    $query->select('emp_id', 'fname');
                }])
                ->select('employee_id', 'payment_status', 'created_at')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->get()
                ->map(function ($item) {
                    return [
                        'employee' => $item->employee ? $item->employee->fname : 'Unknown',
                        'activity' => 'Salary Calculation',
                        'product' => '-',
                        'quantity' => '-',
                        'status' => ucfirst($item->payment_status),
                        'date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                    ];
                })
            )
            ->merge(
                Loans::with(['employee' => function ($query) {
                    $query->select('emp_id', 'fname');
                }])
                ->select('employee_id', 'status', 'created_at')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->get()
                ->map(function ($item) {
                    return [
                        'employee' => $item->employee ? $item->employee->fname : 'Unknown',
                        'activity' => 'Loan Request',
                        'product' => '-',
                        'quantity' => '-',
                        'status' => ucfirst($item->status),
                        'date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                    ];
                })
            )
            ->sortByDesc('date')
            ->take(5); // Limit to 5 recent activities
    }

    public function toggleSidebar()
    {
        $this->sidebarOpen = !$this->sidebarOpen;
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard', [
            'totalEmployees' => $this->totalEmployees,
            'totalloans' => $this->totalloans,
            'totalloanamount' => $this->totalloanamount,
            'totalactiveemployees' => $this->totalactiveemployees,
            'magiProduction' => $this->magiProduction,
            'papadamProduction' => $this->papadamProduction,
            'pendingSalary' => $this->pendingSalary,
            'recentActivities' => $this->recentActivities,
        ]);
    }
}

