<div>
    <!-- Settings Module -->
    <h2 class="text-[#0d151c] text-2xl font-bold leading-tight tracking-[-0.015em] px-3 py-2">System Settings</h2>

    <div class="p-2">
        <!-- Payroll Configuration -->
        <div class="card p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Payroll Configuration</h3>
            @if (session()->has('payrollMessage'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 text-green-600">
                {{ session('payrollMessage') }}
            </div>
            @endif
            <form wire:submit.prevent="savePayrollSettings">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">EPF Rate (%)</label>
                        <input type="number" wire:model="epfRate" class="form-control w-full" step="0.01">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">ETF Rate (%)</label>
                        <input type="number" wire:model="etfRate" class="form-control w-full" step="0.01">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tax Threshold (LKR)</label>
                        <input type="number" wire:model="taxThreshold" class="form-control w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tax Rate (%)</label>
                        <input type="number" wire:model="taxRate" class="form-control w-full" step="0.01">
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Save Settings</button>
                </div>
            </form>
        </div>

        <!-- Production Rate Configuration -->
        <div class="card p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Production Rate Configuration</h3>
            @if (session()->has('productionMessage'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)" class="mb-4 text-green-600">
                {{ session('productionMessage') }}
            </div>
            @endif
            
            <!-- Add New Work Type Form -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-md font-medium mb-3">Add New Work Type</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Work Type</label>
                        <input type="text" wire:model="newWorkType" class="form-control w-full" placeholder="Enter work type">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Magi Rate (LKR/kg)</label>
                        <input type="number" wire:model="newMagiRate" class="form-control w-full" placeholder="Magi rate">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Papadam Rate (LKR/kg)</label>
                        <input type="number" wire:model="newPapadamRate" class="form-control w-full" placeholder="Papadam rate">
                    </div>
                    <div class="flex items-end">
                        <button wire:click="addWorkType" type="button" class="btn-primary px-4 py-2 rounded-lg">Add Work Type</button>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-left px-4 py-3">Role</th>
                            <th class="text-left px-4 py-3">Magi (LKR/kg)</th>
                            <th class="text-left px-4 py-3">Papadam (LKR/kg)</th>
                            <th class="text-left px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workTypes as $index => $workType)
                        <tr>
                            <td class="px-4 py-3">{{ $workType['work_type'] }}</td>
                            <td class="px-4 py-3">
                                <input type="number" 
                                       value=""
                                       wire:change="updateWorkTypeRate({{ $workType['id'] }}, 'magi_rate', $event.target.value)"
                                       class="form-control w-24"
                                       placeholder="{{ $workType['magi_rate'] }}">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" 
                                       value=""
                                       wire:change="updateWorkTypeRate({{ $workType['id'] }}, 'papadam_rate', $event.target.value)"
                                       class="form-control w-24"
                                       placeholder="{{ $workType['papadam_rate'] }}">
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="deleteWorkType({{ $workType['id'] }})" 
                                        class="text-red-600 hover:text-red-800 ml-2">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Preferences -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4">System Preferences</h3>
            @if (session()->has('systemMessage'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 text-green-600">
                {{ session('systemMessage') }}
            </div>
            @endif
            <form wire:submit.prevent="saveSystemPreferences">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center mb-4">
                            <input type="checkbox" wire:model="enableEmailNotifications" class="form-checkbox h-5 w-5 text-blue-600" value="1">
                            <span class="ml-2 text-sm">Enable Email Notifications</span>
                        </label>
                        <label class="flex items-center mb-4">
                            <input type="checkbox" wire:model="autoCalculateSalary" class="form-checkbox h-5 w-5 text-blue-600" value="1">
                            <span class="ml-2 text-sm">Auto-calculate Salary on Production Entry</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="enableTwoFactor" class="form-checkbox h-5 w-5 text-blue-600" value="1">
                            <span class="ml-2 text-sm">Enable Two-Factor Authentication</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Default Currency</label>
                        <select wire:model="defaultCurrency" class="form-control w-full">
                            <option value="LKR">Sri Lankan Rupee (LKR)</option>
                            <option value="USD">US Dollar (USD)</option>
                            <option value="EUR">Euro (EUR)</option>
                        </select>

                        <label class="block text-sm font-medium mb-1 mt-4">Date Format</label>
                        <select wire:model="dateFormat" class="form-control w-full">
                            <option value="YYYY-MM-DD">YYYY-MM-DD (2024-03-28)</option>
                            <option value="DD/MM/YYYY">DD/MM/YYYY (28/03/2024)</option>
                            <option value="MM/DD/YYYY">MM/DD/YYYY (03/28/2024)</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Save Preferences</button>
                </div>
            </form>
        </div>
    </div>
</div>