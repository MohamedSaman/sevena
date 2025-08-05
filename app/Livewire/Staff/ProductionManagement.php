<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\ProductionSalaries;

#[Title("Staff Dashboard")]
#[Layout("components.layouts.staff")]
class ProductionManagement extends Component
{

   public $activeTab = 'magi'; // Default tab
    public $employees;
    public $form = [
        'employee_id' => '',
        'work_type' => '',
        'category' => 'magi',
        'quantity' => '',
        'worked_quantity' => '',
        'additional_salary' => '',
        'description' => '',
        'total_salary' => 0,
    ];

    public function mount()
    {
        // Fetch employees for the form dropdown
        $this->employees = Employee::select('emp_id', 'fname')->get();
        
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->form['category'] = $tab; // Update form category when tab changes
    }

    public function saveProductionEntry()
    {
        // Validate form input
        $this->validate([
            'form.employee_id' => 'required|exists:employees,emp_id',
            'form.work_type' => 'required|in:worker,supervisor,quality',
            'form.category' => 'required|in:magi,papadam',
            'form.quantity' => 'required|numeric|min:0',
            'form.worked_quantity' => 'required|numeric|min:0|lte:form.quantity',
            'form.additional_salary' => 'nullable|numeric|min:0',
            'form.description' => 'nullable|string',
        ]);

        // Create new production entry
        ProductionSalaries::create([
            'employee_id' => $this->form['employee_id'],
            'work_type' => $this->form['work_type'],
            'category' => $this->form['category'],
            'quantity' => $this->form['quantity'],
            'per_rate' => 80, // Example rate, adjust as needed
            'additional_salary' => $this->form['additional_salary'] ?: 0,
            'bonus' => 0, // Example, adjust as needed
            'allowance' => 0, // Example, adjust as needed
            'total_salary' => ($this->form['quantity'] * 80) + ($this->form['additional_salary'] ?: 0), // Example calculation
        ]);

        // Reset form
        $this->form = [
            'employee_id' => '',
            'work_type' => '',
            'category' => $this->activeTab,
            'quantity' => '',
            'worked_quantity' => '',
            'additional_salary' => '',
            'description' => '',
            'total_salary' => 0,
        ];

        session()->flash('message', 'Production entry saved successfully.');
    }

    public function render()
    {
        // Fetch production records filtered by active tab
        $records = ProductionSalaries::with('employee')
            ->where('category', $this->activeTab)
            ->get();

        return view('livewire.staff.production-management', [
            'records' => $records,
        ]);
    }
}
