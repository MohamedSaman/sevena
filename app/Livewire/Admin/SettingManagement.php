<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\PayrollSetting;
use App\Models\SystemSetting;
use App\Models\WorkTypeRate;
use App\Models\PackingProduct;
use App\Models\Department;
use App\Models\Designation; // ✅ Import Designation model

#[Title("Admin Setting Management")]
#[Layout("components.layouts.admin")]
class SettingManagement extends Component
{
    // Payroll settings
    public $epfRate, $etfRate, $taxThreshold, $taxRate;

    // Production rates
    public $workTypes = [];
    public $newWorkType = '', $newMagiRate = '', $newPapadamRate = '';

    // Packing products
    public $packingProducts = [];
    public $newProductName = '', $newProductRate = '';

    // Departments
    public $departments = [];
    public $newDepartmentName = '';
    public $newDepartmentStatus = 'Active';

    // Designations
    public $designations = [];
    public $newDesignationName = '';
    public $newDesignationStatus = 'active';

    // System preferences
    public $enableEmailNotifications, $autoCalculateSalary, $enableTwoFactor;
    public $defaultCurrency, $dateFormat;

    public function mount()
    {
        // Payroll settings
        $payrollSettings = PayrollSetting::first();
        if ($payrollSettings) {
            $this->epfRate = $payrollSettings->epf_rate;
            $this->etfRate = $payrollSettings->etf_rate;
            $this->taxThreshold = $payrollSettings->tax_threshold;
            $this->taxRate = $payrollSettings->tax_rate;
        }

        // Load work types
        $this->refreshWorkTypes();

        // Load packing products
        $this->refreshPackingProducts();

        // Load departments
        $this->refreshDepartments();

        // Load designations
        $this->refreshDesignations();

        // System preferences
        $this->enableEmailNotifications = SystemSetting::where('key', 'enable_email_notifications')->value('value') ?? '1';
        $this->autoCalculateSalary = SystemSetting::where('key', 'auto_calculate_salary')->value('value') ?? '1';
        $this->enableTwoFactor = SystemSetting::where('key', 'enable_two_factor')->value('value') ?? '0';
        $this->defaultCurrency = SystemSetting::where('key', 'default_currency')->value('value') ?? 'LKR';
        $this->dateFormat = SystemSetting::where('key', 'date_format')->value('value') ?? 'YYYY-MM-DD';
    }

    // 🔹 Refresh helpers
    protected function refreshWorkTypes()
    {
        $this->workTypes = WorkTypeRate::all()->toArray();
    }
    
    protected function refreshPackingProducts()
    {
        $this->packingProducts = PackingProduct::all()->toArray();
    }
    
    protected function refreshDepartments()
    {
        $this->departments = Department::all()->toArray();
    }
    
    protected function refreshDesignations()
    {
        $this->designations = Designation::all()->toArray();
    }

    // Payroll save
    public function savePayrollSettings()
    {
        $this->validate([
            'epfRate' => 'required|numeric|min:0|max:100',
            'etfRate' => 'required|numeric|min:0|max:100',
            'taxThreshold' => 'required|numeric|min:0',
            'taxRate' => 'required|numeric|min:0|max:100',
        ]);

        PayrollSetting::updateOrCreate(
            ['id' => 1],
            [
                'epf_rate' => $this->epfRate,
                'etf_rate' => $this->etfRate,
                'tax_threshold' => $this->taxThreshold,
                'tax_rate' => $this->taxRate,
            ]
        );

        session()->flash('payrollMessage', 'Payroll settings saved successfully.');
    }

    // System preferences save
    public function saveSystemPreferences()
    {
        SystemSetting::updateOrCreate(['key' => 'enable_email_notifications'], ['value' => $this->enableEmailNotifications ? '1' : '0']);
        SystemSetting::updateOrCreate(['key' => 'auto_calculate_salary'], ['value' => $this->autoCalculateSalary ? '1' : '0']);
        SystemSetting::updateOrCreate(['key' => 'enable_two_factor'], ['value' => $this->enableTwoFactor ? '1' : '0']);
        SystemSetting::updateOrCreate(['key' => 'default_currency'], ['value' => $this->defaultCurrency]);
        SystemSetting::updateOrCreate(['key' => 'date_format'], ['value' => $this->dateFormat]);

        session()->flash('systemMessage', 'System preferences saved successfully.');
    }

    // 🔹 Work Type CRUD
    public function updateWorkTypeRate($id, $field, $value)
    {
        $value = (float) $value;
        $workType = WorkTypeRate::find($id);
        if ($workType) {
            $workType->update([$field => $value]);
            $this->refreshWorkTypes();
            session()->flash('productionMessage', 'Work type rate updated successfully.');
        }
    }

    public function addWorkType()
    {
        $this->validate([
            'newWorkType' => 'required|string|max:255|unique:worktype_rates,work_type',
            'newMagiRate' => 'required|numeric|min:0',
            'newPapadamRate' => 'required|numeric|min:0',
        ]);

        WorkTypeRate::create([
            'work_type' => $this->newWorkType,
            'magi_rate' => $this->newMagiRate,
            'papadam_rate' => $this->newPapadamRate,
        ]);

        $this->newWorkType = $this->newMagiRate = $this->newPapadamRate = '';
        $this->refreshWorkTypes();
        session()->flash('productionMessage', 'Work type added successfully.');
    }

    public function deleteWorkType($id)
    {
        $workType = WorkTypeRate::find($id);
        if ($workType) {
            $workType->delete();
            $this->refreshWorkTypes();
            session()->flash('productionMessage', 'Work type deleted successfully.');
        }
    }

    // 🔹 Packing Product CRUD
    public function addPackingProduct()
    {
        $this->validate([
            'newProductName' => 'required|string|max:255|unique:packing_product,product_name',
            'newProductRate' => 'required|numeric|min:0',
        ]);

        PackingProduct::create([
            'product_name' => $this->newProductName,
            'per_rate' => $this->newProductRate,
            'date' => now(),
        ]);

        $this->newProductName = $this->newProductRate = '';
        $this->refreshPackingProducts();
        session()->flash('packingMessage', 'Packing product added successfully.');
    }

    public function updatePackingProductRate($id, $value)
    {
        $product = PackingProduct::find($id);
        if ($product) {
            $product->update(['per_rate' => (float) $value]);
            $this->refreshPackingProducts();
            session()->flash('packingMessage', 'Product rate updated successfully.');
        }
    }

    public function deletePackingProduct($id)
    {
        $product = PackingProduct::find($id);
        if ($product) {
            $product->delete();
            $this->refreshPackingProducts();
            session()->flash('packingMessage', 'Packing product deleted successfully.');
        }
    }

    // 🔹 Department CRUD
    public function addDepartment()
    {
        $this->validate([
            'newDepartmentName' => 'required|string|max:255',
            'newDepartmentStatus' => 'required|in:Active,Inactive',
        ]);

        Department::create([
            'department_name' => $this->newDepartmentName,
            'status' => $this->newDepartmentStatus,
        ]);

        session()->flash('departmentMessage', 'Department added successfully.');

        // Reset form inputs
        $this->reset(['newDepartmentName', 'newDepartmentStatus']);
        $this->newDepartmentStatus = 'Active';

        // Refresh departments
        $this->refreshDepartments();
    }

    public function updateDepartmentStatus($id, $status)
    {
        $department = Department::find($id);

        if ($department) {
            $department->status = $status;
            $department->save();
            session()->flash('departmentMessage', 'Department status updated successfully.');
            $this->refreshDepartments();
        }
    }

    public function deleteDepartment($id)
    {
        $department = Department::find($id);

        if ($department) {
            $department->delete();
            session()->flash('departmentMessage', 'Department deleted successfully.');
            $this->refreshDepartments();
        }
    }

    // 🔹 Designation CRUD
    public function addDesignation()
    {
        $this->validate([
            'newDesignationName' => 'required|string|max:255|unique:designations,designation',
            'newDesignationStatus' => 'required|in:active,inactive',
        ]);

        Designation::create([
            'designation' => $this->newDesignationName,
            'status' => $this->newDesignationStatus,
        ]);

        session()->flash('designationMessage', 'Designation added successfully.');

        // Reset form inputs
        $this->reset(['newDesignationName', 'newDesignationStatus']);
        $this->newDesignationStatus = 'active';

        // Refresh designations
        $this->refreshDesignations();
    }

    public function updateDesignationStatus($id, $status)
    {
        $designation = Designation::find($id);

        if ($designation) {
            $designation->status = $status;
            $designation->save();
            session()->flash('designationMessage', 'Designation status updated successfully.');
            $this->refreshDesignations();
        }
    }

    public function deleteDesignation($id)
    {
        $designation = Designation::find($id);

        if ($designation) {
            $designation->delete();
            session()->flash('designationMessage', 'Designation deleted successfully.');
            $this->refreshDesignations();
        }
    }

    public function render()
    {
        return view('livewire.admin.setting-management');
    }
}