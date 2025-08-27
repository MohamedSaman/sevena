<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\ProductionSalaries;
use App\Models\WorkTypeRate;

#[Title("Admin Production Management")]
#[Layout("components.layouts.admin")]
class ProductionManagement extends Component
{
    public $activeTab = 'magi';
    public $employees = [];
    public $showAll = false;
    public $dailyemployees;
    public $workTypeRates = [];
    
    public $form = [
        'employee_id' => '',
        'work_type' => '',
        'category' => 'magi',
        'quantity' => '',
        'worked_quantity' => '',
        'additional_salary' => '',
        'description' => '',
        'total_salary' => 0,
        'per_rate' => 0,
        'date' => '',
    ];

    public $editingRecordId = null;
    public $confirmingDeletionId = null;

    public function mount()
    {
        // Load all work type rates with both magi_rate and papadam_rate
        $this->workTypeRates = WorkTypeRate::all()->keyBy('work_type');
        $this->dailyemployees = Employee::where('salary_type', 'daily')->get();
        $this->loadEmployees();
        $this->form['date'] = now()->format('Y-m-d');
    }
    
    public function loadEmployees()
    {
        $this->employees = $this->showAll
            ? Employee::all()
            : Employee::where('salary_type', 'daily')->get();
    }
    
    public function showAllEmployees()
    {
        $this->showAll = true;
        $this->loadEmployees();
    }
    
    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->form['category'] = $tab;
        
        // Update the rate when tab changes if a work type is selected
        if (!empty($this->form['work_type']) && isset($this->workTypeRates[$this->form['work_type']])) {
            $this->updateRateBasedOnTab();
            $this->calculateTotalSalary();
        }
    }
    
    public function updated($property)
    {
        // Update per_rate when work_type changes
        if ($property === 'form.work_type' && !empty($this->form['work_type'])) {
            $this->updateRateBasedOnTab();
        }
        
        // Recalculate salary when these properties change
        if (in_array($property, [
            'form.quantity', 
            'form.worked_quantity', 
            'form.additional_salary',
            'form.work_type',
            'form.per_rate'
        ])) {
            $this->calculateTotalSalary();
        }
    }
    
    protected function updateRateBasedOnTab()
    {
        if (isset($this->workTypeRates[$this->form['work_type']])) {
            $rateRecord = $this->workTypeRates[$this->form['work_type']];
            $this->form['per_rate'] = ($this->activeTab === 'magi') 
                ? $rateRecord->magi_rate 
                : $rateRecord->papadam_rate;
        }
    }
    
    public function calculateTotalSalary()
    {
        $qty = $this->form['worked_quantity'] ?: ($this->form['quantity'] ?: 0);
        $extra = $this->form['additional_salary'] ?: 0;
        $rate = $this->form['per_rate'] ?: 0;
        
        $this->form['total_salary'] = ($qty * $rate) + $extra;
    }
    
    public function saveProductionEntry()
    {
        // Recalculate before saving to ensure accuracy
        $this->calculateTotalSalary();
        
        // Validate form input
        $this->validate([
            'form.employee_id' => 'required|exists:employees,emp_id',
            'form.work_type' => 'required|in:cutter,roller,dryer,packer,worker',
            'form.category' => 'required|in:magi,papadam',
            'form.quantity' => 'required|numeric|min:0',
            'form.worked_quantity' => 'nullable|numeric|min:0|lte:form.quantity',
            'form.additional_salary' => 'nullable|numeric|min:0',
            'form.description' => 'nullable|string',
            'form.date' => 'required|date',
            'form.per_rate' => 'required|numeric|min:0',
        ]);

        // Create new production entry
        ProductionSalaries::create([
            'employee_id' => $this->form['employee_id'],
            'work_type' => $this->form['work_type'],
            'category' => $this->form['category'],
            'quantity' => $this->form['quantity'],
            'worked_quantity' => $this->form['worked_quantity'] ?: $this->form['quantity'],
            'per_rate' => $this->form['per_rate'],
            'additional_salary' => $this->form['additional_salary'] ?: 0,
            'bonus' => 0,
            'allowance' => 0,
            'total_salary' => $this->form['total_salary'],
            'description' => $this->form['description'],
            'date' => $this->form['date'],
        ]);

        // Reset form while preserving category and date
        $this->form = array_merge($this->form, [
            'employee_id' => '',
            'work_type' => '',
            'quantity' => '',
            'worked_quantity' => '',
            'additional_salary' => '',
            'description' => '',
            'total_salary' => 0,
            'per_rate' => 0,
        ]);

        session()->flash('message', 'Production entry saved successfully.');
    }

    public function editRecord($recordId)
    {
        $record = ProductionSalaries::find($recordId);
        if ($record) {
            $this->editingRecordId = $recordId;
            $this->form = [
                'employee_id' => $record->employee_id,
                'work_type' => $record->work_type,
                'category' => $record->category,
                'quantity' => $record->quantity,
                'worked_quantity' => $record->worked_quantity,
                'additional_salary' => $record->additional_salary,
                'description' => $record->description,
                'date' => $record->date,
                'total_salary' => $record->total_salary,
                'per_rate' => $record->per_rate,
            ];
        }
    }

    public function updateRecord()
    {
        $this->calculateTotalSalary();
        
        $this->validate([
            'form.employee_id' => 'required|exists:employees,emp_id',
            'form.work_type' => 'required|in:cutter,roller,dryer,packer,worker',
            'form.category' => 'required|in:magi,papadam',
            'form.quantity' => 'required|numeric|min:0',
            'form.worked_quantity' => 'nullable|numeric|min:0|lte:form.quantity',
            'form.additional_salary' => 'nullable|numeric|min:0',
            'form.description' => 'nullable|string',
            'form.date' => 'required|date',
            'form.per_rate' => 'required|numeric|min:0',
        ]);

        $record = ProductionSalaries::find($this->editingRecordId);
        if ($record) {
            $record->update([
                'employee_id' => $this->form['employee_id'],
                'work_type' => $this->form['work_type'],
                'category' => $this->form['category'],
                'quantity' => $this->form['quantity'],
                'worked_quantity' => $this->form['worked_quantity'] ?: $this->form['quantity'],
                'per_rate' => $this->form['per_rate'],
                'additional_salary' => $this->form['additional_salary'] ?: 0,
                'total_salary' => $this->form['total_salary'],
                'description' => $this->form['description'],
                'date' => $this->form['date'],
            ]);

            $this->cancelEdit();
            session()->flash('message', 'Production record updated successfully.');
        }
    }

    public function cancelEdit()
    {
        $this->editingRecordId = null;
        $this->form = [
            'employee_id' => '',
            'work_type' => '',
            'category' => $this->activeTab,
            'quantity' => '',
            'worked_quantity' => '',
            'additional_salary' => '',
            'description' => '',
            'total_salary' => 0,
            'per_rate' => 0,
            'date' => now()->format('Y-m-d'),
        ];
    }

    public function confirmDelete($recordId)
    {
        $this->confirmingDeletionId = $recordId;
    }

    public function deleteRecord()
    {
        $record = ProductionSalaries::find($this->confirmingDeletionId);
        if ($record) {
            $record->delete();
            $this->confirmingDeletionId = null;
            session()->flash('message', 'Production record deleted successfully.');
        }
    }

    public function cancelDelete()
    {
        $this->confirmingDeletionId = null;
    }

    public function render()
    {
        $records = ProductionSalaries::with('employee')
            ->where('category', $this->activeTab)
            ->get();

        return view('livewire.admin.production-management', [
            'records' => $records,
        ]);
    }
}