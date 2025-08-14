<div>
    <!-- Employee Management Module -->
    <h2 class="text-[#0d151c] text-2xl font-bold leading-tight tracking-[-0.015em] px-4 py-3">Employee Management</h2>
    <div class="flex justify-between items-center px-4 pb-4">
        <div class="flex gap-3">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200"
                wire:click="$dispatch('openModal')">Add Employee</button>
            <button
                class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition duration-200">Export
                Data</button>
        </div>
        <div class="flex items-center">
            <!-- Replace the existing search input with this -->
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search employees..."
                class="w-64 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>
    <div class="px-4 py-2">
        <div class="table-container overflow-hidden rounded-xl shadow-sm">
            <table class="w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">ID</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Name</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Role</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Department</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Join Date</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Status</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $employee->empCode }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $employee->fname }} {{ $employee->lname }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $employee->designation }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $employee->department }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('Y-m-d')
                            : 'N/A' }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span
                                class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <button wire:click="openViewModal({{ $employee->emp_id }})"
                                class="text-green-600 hover:text-green-800 mr-3">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button wire:click="openEditModal({{ $employee->emp_id }})"
                                class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="confirmDelete({{ $employee->emp_id }})"
                                class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div id="employeeModal"
        class="{{ $showEditModal || $showDeleteModal || $showViewModal ? 'hidden' : '' }} fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden"
        wire:ignore.self>
        <div
            class="bg-white rounded-xl shadow-lg w-full max-w-4xl mx-4 sm:mx-6 max-h-[90vh] overflow-y-auto p-4 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Add New Employee</h2>
                <button wire:click="closeModal"
                    class="text-gray-500 hover:text-red-500 text-xl sm:text-2xl">&times;</button>
            </div>
            <form wire:submit.prevent="addEmployee" class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <!-- Personal Information -->
                <div class="space-y-3">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-700">Personal Information</h3>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Employee Code</label>
                        <input type="text" wire:model.defer="empCode"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('empCode') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" wire:model.defer="fname"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('fname') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" wire:model.defer="lname"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('lname') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Gender</label>
                        <select wire:model.defer="gender"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        @error('gender') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" wire:model.defer="dob"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('dob') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">NIC</label>
                        <input type="text" wire:model.defer="nic"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('nic') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Email</label>
                        <input type="email" wire:model.defer="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" wire:model.defer="phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Address</label>
                        <input type="text" wire:model.defer="address"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <!-- Job Information -->
                <div class="space-y-3">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-700">Job Information</h3>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Department</label>
                        <input type="text" wire:model.defer="department"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('department') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Designation</label>
                        <input type="text" wire:model.defer="designation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('designation') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Salary Type</label>
                        <select wire:model.defer="salary_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                            <option value="">Select Salary Type</option>
                            <option value="daily">Daily</option>
                            <option value="monthly">Monthly</option>
                        </select>
                        @error('salary_type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Basic Salary</label>
                        <input type="number" step="0.01" wire:model.defer="basic_salary"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('basic_salary') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Fixed Allowance</label>
                        <input type="number" step="0.01" wire:model.defer="allowance"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('basic_salary') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Joining Date</label>
                        <input type="date" wire:model.defer="joining_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                        @error('joining_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Status</label>
                        <select wire:model.defer="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Fingerprint ID
                            (Optional)</label>
                        <input type="text" wire:model.defer="fingerprint_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @error('fingerprint_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Photo (Optional)</label>
                        <input type="file" wire:model="photo" accept="image/jpeg,image/png,image/jpg"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @error('photo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-span-1 sm:col-span-2 flex justify-end mt-4">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 sm:px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 text-sm sm:text-base">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div id="editModal"
        class="{{ $showEditModal ? '' : 'hidden' }} fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        wire:ignore.self>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl mx-4 sm:mx-6 max-h-[90vh] overflow-y-auto p-4 sm:p-6"
            style="scrollbar-width: none; -ms-overflow-style: none;">
            <div class="flex justify-end mb-4">
                <button wire:click="closeModal"
                    class="text-gray-500 hover:text-red-500 text-xl sm:text-2xl">&times;</button>
            </div>
            <div class="flex flex-col sm:flex-row gap-6">
                <!-- Left Side: Photo and Basic Info -->
                <div class="w-full sm:w-1/3 bg-gray-100 p-4 rounded-lg">
                    <div class="flex items-center mb-4">
                        @if($existingPhoto && Storage::disk('public')->exists($existingPhoto))
                        <img src="{{ Storage::url($existingPhoto) }}" alt="Employee Photo"
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover mr-4">
                        @else
                        <div
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gray-300 mr-4 flex items-center justify-center">
                            <span class="text-gray-500 text-sm">No Photo</span>
                        </div>
                        @endif
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-gray-800">{{ $fname }} {{ $lname }}</h2>
                            <p class="text-sm text-green-600">{{ $designation }}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600"><span class="font-medium">Employee ID:</span> {{ $empCode }}
                        </p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Status:</span> {{ ucfirst($status) }}
                        </p>
                    </div>
                </div>
                <!-- Right Side: Editable Form -->
                <div class="w-full sm:w-2/3 space-y-4">
                    <form wire:submit.prevent="updateEmployee" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Personal Information -->
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-700">Personal Information</h3>
                                <div class="space-y-3 mt-2">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">First
                                            Name</label>
                                        <input type="text" wire:model="fname"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('fname') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Last
                                            Name</label>
                                        <input type="text" wire:model="lname"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('lname') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Gender</label>
                                        <select wire:model="gender"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                        @error('gender') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Date of
                                            Birth</label>
                                        <input type="date" wire:model="dob"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('dob') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">NIC</label>
                                        <input type="text" wire:model="nic"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('nic') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" wire:model="email"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('email') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Phone</label>
                                        <input type="text" wire:model="phone"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('phone') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs sm:text-sm font-medium text-gray-700">Address</label>
                                        <input type="text" wire:model="address"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('address') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- Job Information -->
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-700">Job Information</h3>
                                <div class="space-y-3 mt-2">
                                    <div>
                                        <label
                                            class="block text-xs sm:text-sm font-medium text-gray-700">Department</label>
                                        <input type="text" wire:model="department"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('department') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs sm:text-sm font-medium text-gray-700">Designation</label>
                                        <input type="text" wire:model="designation"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('designation') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Salary
                                            Type</label>
                                        <select wire:model="salary_type"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                            <option value="">Select Salary Type</option>
                                            <option value="daily">Daily</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                        @error('salary_type') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- Allowance -->


                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Basic
                                            Salary</label>
                                        <input type="number" step="0.01" wire:model="basic_salary"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('basic_salary') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1">Allowance</label>
                                        <input type="number" wire:model="allowance"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('allowance')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Joining
                                            Date</label>
                                        <input type="date" wire:model="joining_date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                        @error('joining_date') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Status</label>
                                        <select wire:model="status"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        @error('status') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Fingerprint ID
                                            (Optional)</label>
                                        <input type="text" wire:model="fingerprint_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                        @error('fingerprint_id') <span class="text-xs text-red-500">{{ $message
                                            }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Photo
                                            (Optional)</label>
                                        <input type="file" wire:model="photo" accept="image/jpeg,image/png,image/jpg"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                        @error('photo') <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 sm:px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 text-sm sm:text-base">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- view model --}}
    <div id="viewModal"
        class="{{ $showViewModal ? '' : 'hidden' }} fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        wire:ignore.self>
        <div
            class="bg-white rounded-xl shadow-lg w-full max-w-4xl mx-2 sm:mx-2 max-h-[90vh] overflow-y-auto p-2 sm:p-3">
            <div class="flex justify-end mt-0">
                <button wire:click="closeModal"
                    class="bg-gray-300 text-gray-700 px-4 sm:px-6 py-2 rounded-lg hover:bg-gray-400 transition duration-200 text-sm sm:text-base">Close</button>
            </div>
            <div class="flex flex-col sm:flex-row gap-6">
                <!-- Left Side: Photo and Basic Info -->
                <div class="w-full sm:w-1/3 bg-gray-100 p-4 mb-2 rounded-lg">
                    <div class="flex items-center mb-4">
                        @if($existingPhoto && Storage::disk('public')->exists($existingPhoto))
                        <img src="{{ Storage::url($existingPhoto) }}" alt="Employee Photo"
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover mr-4">
                        @else
                        <div
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gray-300 mr-4 flex items-center justify-center">
                            <span class="text-gray-500">No Photo</span>
                        </div>
                        @endif
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-gray-800">{{ $fname }} {{ $lname }}</h2>
                            <p class="text-sm text-green-600">{{ $designation }}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600"><span class="font-medium">Employee ID:</span> {{ $empCode }}
                        </p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Status:</span> {{ ucfirst($status) }}
                        </p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Joining Date:</span> {{ $joining_date
                            }}</p>
                    </div>
                </div>
                <!-- Right Side: Detailed Information -->
                <div class="w-full sm:w-2/3 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-700">Personal Information</h3>
                            <div class="space-y-2 mt-2">
                                <p class="text-sm text-gray-600"><span class="font-medium">Gender:</span> {{ $gender ?
                                    ucfirst($gender) : 'N/A' }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Date of Birth:</span> {{ $dob
                                    }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">NIC:</span> {{ $nic }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Email:</span> {{ $email }}
                                </p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Phone:</span> {{ $phone }}
                                </p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Address:</span> {{ $address
                                    }}</p>
                            </div>
                        </div>
                        <!-- Job Information -->
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-700">Job Information</h3>
                            <div class="space-y-2 mt-2">
                                <p class="text-sm text-gray-600"><span class="font-medium">Department:</span> {{
                                    $department }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Salary Type:</span> {{
                                    $salary_type ? ucfirst($salary_type) : 'N/A' }}</p>
                                
                                <p class="text-sm text-gray-600"><span class="font-medium">Basic Salary:</span> {{
                                    $basic_salary ? number_format($basic_salary, 2) : 'N/A' }}</p>

                                <p class="text-sm text-gray-600"><span class="font-medium">Allowance:</span> {{
                                    $allowance ? number_format($allowance, 2) : 'N/A' }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Fingerprint ID:</span> {{
                                    $fingerprint_id ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal"
        class="{{ $showDeleteModal ? '' : 'hidden' }} fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        wire:ignore.self>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 text-center">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Delete Employee</h2>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this employee?</p>
            <div class="flex justify-center gap-4">
                <button wire:click="deleteEmployee"
                    class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-200">Yes,
                    Delete</button>
                <button wire:click="closeModal"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition duration-200">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('openModal', () => {
            document.getElementById('employeeModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('viewModal').classList.add('hidden');
        });

        Livewire.on('openEditModal', () => {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('employeeModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('viewModal').classList.add('hidden');
        });

        Livewire.on('openDeleteModal', () => {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('employeeModal').classList.add('hidden');
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('viewModal').classList.add('hidden');
        });

        Livewire.on('openViewModal', () => {
            document.getElementById('viewModal').classList.remove('hidden');
            document.getElementById('employeeModal').classList.add('hidden');
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.add('hidden');
        });

        Livewire.on('closeModal', () => {
            document.getElementById('employeeModal').classList.add('hidden');
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('viewModal').classList.add('hidden');
        });
    });

    window.addEventListener('click', function(e) {
        const employeeModal = document.getElementById('employeeModal');
        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');
        const viewModal = document.getElementById('viewModal');
        if (e.target === employeeModal || e.target === editModal || e.target === deleteModal || e.target === viewModal) {
            Livewire.dispatch('closeModal');
        }
    });
</script>