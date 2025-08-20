<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\PayrollSetting;
use App\Models\SystemSetting;
use App\Models\WorkTypeRate;

#[Title("Admin Setting Management")]
#[Layout("components.layouts.admin")]
class SettingManagement extends Component
{
    // Payroll settings
    public $epfRate;
    public $etfRate;
    public $taxThreshold;
    public $taxRate;
    
    // Production rates
    public $workTypes = [];
    public $newWorkType = '';
    public $newMagiRate = '';
    public $newPapadamRate = '';
    
    // System preferences
    public $enableEmailNotifications;
    public $autoCalculateSalary;
    public $enableTwoFactor;
    public $defaultCurrency;
    public $dateFormat;

    public function mount()
    {
        // Load payroll settings
        $payrollSettings = PayrollSetting::first();
        if ($payrollSettings) {
            $this->epfRate = $payrollSettings->epf_rate;
            $this->etfRate = $payrollSettings->etf_rate;
            $this->taxThreshold = $payrollSettings->tax_threshold;
            $this->taxRate = $payrollSettings->tax_rate;
        }
        
        // Load work type rates as array for better reactivity
        $this->refreshWorkTypes();
        
        // Load system settings
        $this->enableEmailNotifications = SystemSetting::where('key', 'enable_email_notifications')->value('value') ?? '1';
        $this->autoCalculateSalary = SystemSetting::where('key', 'auto_calculate_salary')->value('value') ?? '1';
        $this->enableTwoFactor = SystemSetting::where('key', 'enable_two_factor')->value('value') ?? '0';
        $this->defaultCurrency = SystemSetting::where('key', 'default_currency')->value('value') ?? 'LKR';
        $this->dateFormat = SystemSetting::where('key', 'date_format')->value('value') ?? 'YYYY-MM-DD';
    }
    
    // Helper method to refresh work types data
    protected function refreshWorkTypes()
    {
        $this->workTypes = WorkTypeRate::all()->toArray();
    }

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
    
    public function saveSystemPreferences()
    {
        SystemSetting::updateOrCreate(
            ['key' => 'enable_email_notifications'],
            ['value' => $this->enableEmailNotifications ? '1' : '0']
        );
        
        SystemSetting::updateOrCreate(
            ['key' => 'auto_calculate_salary'],
            ['value' => $this->autoCalculateSalary ? '1' : '0']
        );
        
        SystemSetting::updateOrCreate(
            ['key' => 'enable_two_factor'],
            ['value' => $this->enableTwoFactor ? '1' : '0']
        );
        
        SystemSetting::updateOrCreate(
            ['key' => 'default_currency'],
            ['value' => $this->defaultCurrency]
        );
        
        SystemSetting::updateOrCreate(
            ['key' => 'date_format'],
            ['value' => $this->dateFormat]
        );
        
        session()->flash('systemMessage', 'System preferences saved successfully.');
    }
    
    public function updateWorkTypeRate($id, $field, $value)
    {
        $value = (float) $value;
        
        $workType = WorkTypeRate::find($id);
        if ($workType) {
            $workType->update([$field => $value]);
            
            // Update the local data to reflect changes immediately
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
        
        $this->newWorkType = '';
        $this->newMagiRate = '';
        $this->newPapadamRate = '';
        
        // Refresh the local data
        $this->refreshWorkTypes();
        
        session()->flash('productionMessage', 'Work type added successfully.');
    }
    
    public function deleteWorkType($id)
    {
        $workType = WorkTypeRate::find($id);
        if ($workType) {
            $workType->delete();
            
            // Refresh the local data
            $this->refreshWorkTypes();
            
            session()->flash('productionMessage', 'Work type deleted successfully.');
        }
    }

    public function render()
    {
        return view('livewire.admin.setting-management');
    }
}