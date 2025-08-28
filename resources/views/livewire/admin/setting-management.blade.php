<div x-data="{ openSection: 'payroll' }">
    <!-- Settings Module -->
    <h2 class="text-[#0d151c] text-2xl font-bold leading-tight tracking-[-0.015em] px-3 py-2">System Settings</h2>

    <div class="p-2">
        <!-- Payroll Configuration -->
        <div class="card p-6 mb-6">
            <button type="button" class="w-full text-left text-lg font-semibold mb-4 flex justify-between items-center"
                @click="openSection = openSection === 'payroll' ? null : 'payroll'">
                Payroll Configuration
                <i :class="openSection === 'payroll' ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
            </button>
            <div x-show="openSection === 'payroll'" x-transition>
                @if (session()->has('payrollMessage'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 text-green-600">
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
        </div>

        <!-- Production Rate Configuration -->
        <div class="card p-6 mb-6">
            <button type="button" class="w-full text-left text-lg font-semibold mb-4 flex justify-between items-center"
                @click="openSection = openSection === 'production' ? null : 'production'">
                Production Rate Configuration
                <i :class="openSection === 'production' ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
            </button>
            <div x-show="openSection === 'production'" x-transition>
                @if (session()->has('productionMessage'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                    class="mb-4 text-green-600">
                    {{ session('productionMessage') }}
                </div>
                @endif

                <!-- Add New Work Type Form -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-md font-medium mb-3">Add New Work Type</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Work Type</label>
                            <input type="text" wire:model="newWorkType" class="form-control w-full"
                                placeholder="Enter work type">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Magi Rate (LKR/kg)</label>
                            <input type="number" wire:model="newMagiRate" class="form-control w-full"
                                placeholder="Magi rate">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Papadam Rate (LKR/kg)</label>
                            <input type="number" wire:model="newPapadamRate" class="form-control w-full"
                                placeholder="Papadam rate">
                        </div>
                        <div class="flex items-end">
                            <button wire:click="addWorkType" type="button" class="btn-primary px-4 py-2 rounded-lg">Add
                                Work Type</button>
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
                            @foreach ($workTypes as $workType)
                            <tr>
                                <td class="px-4 py-3">{{ $workType['work_type'] }}</td>
                                <td class="px-4 py-3">
                                    <input type="number" value=""
                                        wire:change="updateWorkTypeRate({{ $workType['id'] }}, 'magi_rate', $event.target.value)"
                                        class="form-control w-24" placeholder="{{ $workType['magi_rate'] }}">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" value=""
                                        wire:change="updateWorkTypeRate({{ $workType['id'] }}, 'papadam_rate', $event.target.value)"
                                        class="form-control w-24" placeholder="{{ $workType['papadam_rate'] }}">
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
        </div>

        <!-- Packing Product Configuration -->
        <div class="card p-6 mb-6">
            <button type="button" class="w-full text-left text-lg font-semibold mb-4 flex justify-between items-center"
                @click="openSection = openSection === 'packing' ? null : 'packing'">
                Packing Product Configuration
                <i :class="openSection === 'packing' ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
            </button>
            <div x-show="openSection === 'packing'" x-transition>
                @if (session()->has('packingMessage'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                    class="mb-4 text-green-600">
                    {{ session('packingMessage') }}
                </div>
                @endif

                <!-- Add New Product Form -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-md font-medium mb-3">Add New Packing Product</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Product Name</label>
                            <input type="text" wire:model="newProductName" class="form-control w-full"
                                placeholder="Enter product name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Rate (LKR)</label>
                            <input type="number" wire:model="newProductRate" class="form-control w-full"
                                placeholder="Rate">
                        </div>
                        <div class="flex items-end">
                            <button wire:click="addPackingProduct" type="button"
                                class="btn-primary px-4 py-2 rounded-lg">Add Product</button>
                        </div>
                    </div>
                </div>

                <!-- Product List -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left px-4 py-3">Product</th>
                                <th class="text-left px-4 py-3">Rate (LKR)</th>
                                <th class="text-left px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($packingProducts as $product)
                            <tr>
                                <td class="px-4 py-3">{{ $product['product_name'] }}</td>
                                <td class="px-4 py-3">
                                    <input type="number"
                                        wire:change="updatePackingProductRate({{ $product['id'] }}, $event.target.value)"
                                        class="form-control w-24" placeholder="{{ $product['per_rate'] }}">
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="deletePackingProduct({{ $product['id'] }})"
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
        </div>

        <!-- Department Management -->
        <div class="card p-6 mb-6">
            <button type="button" class="w-full text-left text-lg font-semibold mb-4 flex justify-between items-center"
                @click="openSection = openSection === 'department' ? null : 'department'">
                Department Management
                <i :class="openSection === 'department' ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
            </button>
            <div x-show="openSection === 'department'" x-transition>
                @if (session()->has('departmentMessage'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 text-green-600">
                    {{ session('departmentMessage') }}
                </div>
                @endif

                <!-- Add New Department -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-md font-medium mb-3">Add New Department</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Department Name</label>
                            <input type="text" wire:model="newDepartmentName" class="form-control w-full"
                                placeholder="Enter department name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <select wire:model="newDepartmentStatus" class="form-control w-full">
                                <option value="">Active</option>
                                <option value="">Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button wire:click="addDepartment" type="button"
                                class="btn-primary px-4 py-2 rounded-lg">Add Department</button>
                        </div>
                    </div>
                </div>

                <!-- Department List -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left px-4 py-3">Department Name</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-left px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                            <tr>
                                <td class="px-4 py-3">{{ $department['department_name'] }}</td>
                                <td class="px-4 py-3">
                                    <select
                                        wire:change="updateDepartmentStatus({{ $department['id'] }}, $event.target.value)"
                                        class="form-control w-32">
                                        <option value="Active" {{ $department['status']==='Active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="Inactive" {{ $department['status']==='Inactive' ? 'selected' : ''
                                            }}>Inactive</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="deleteDepartment({{ $department['id'] }})"
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
        </div>

        <!-- Designation Management -->
        <div class="card p-6 mb-6">
            <button type="button" class="w-full text-left text-lg font-semibold mb-4 flex justify-between items-center"
                @click="openSection = openSection === 'designation' ? null : 'designation'">
                Designation Management
                <i :class="openSection === 'designation' ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
            </button>
            <div x-show="openSection === 'designation'" x-transition>
                @if (session()->has('designationMessage'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 text-green-600">
                    {{ session('designationMessage') }}
                </div>
                @endif

                <!-- Add New Designation -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-md font-medium mb-3">Add New Designation</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Designation Name</label>
                            <input type="text" wire:model="newDesignationName" class="form-control w-full"
                                placeholder="Enter designation name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <select wire:model="newDesignationStatus" class="form-control w-full">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button wire:click="addDesignation" type="button"
                                class="btn-primary px-4 py-2 rounded-lg">Add Designation</button>
                        </div>
                    </div>
                </div>

                <!-- Designation List -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left px-4 py-3">Designation Name</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-left px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($designations as $designation)
                            <tr>
                                <td class="px-4 py-3">{{ $designation['designation'] }}</td>
                                <td class="px-4 py-3">
                                    <select
                                        wire:change="updateDesignationStatus({{ $designation['id'] }}, $event.target.value)"
                                        class="form-control w-32">
                                        <option value="active" {{ $designation['status']==='active' ? 'selected' : ''
                                            }}>
                                            Active
                                        </option>
                                        <option value="inactive" {{ $designation['status']==='inactive' ? 'selected'
                                            : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="deleteDesignation({{ $designation['id'] }})"
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
        </div>

        <!-- System Preferences -->
        <div class="card p-6">
            <button type="button" class="w-full text-left text-lg font-semibold mb-4 flex justify-between items-center"
                @click="openSection = openSection === 'system' ? null : 'system'">
                System Preferences
                <i :class="openSection === 'system' ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
            </button>
            <div x-show="openSection === 'system'" x-transition>
                @if (session()->has('systemMessage'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 text-green-600">
                    {{ session('systemMessage') }}
                </div>
                @endif
                <form wire:submit.prevent="saveSystemPreferences">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="flex items-center mb-4">
                                <input type="checkbox" wire:model="enableEmailNotifications"
                                    class="form-checkbox h-5 w-5 text-blue-600" value="1">
                                <span class="ml-2 text-sm">Enable Email Notifications</span>
                            </label>
                            <label class="flex items-center mb-4">
                                <input type="checkbox" wire:model="autoCalculateSalary"
                                    class="form-checkbox h-5 w-5 text-blue-600" value="1">
                                <span class="ml-2 text-sm">Auto-calculate Salary on Production Entry</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="enableTwoFactor"
                                    class="form-checkbox h-5 w-5 text-blue-600" value="1">
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
</div>