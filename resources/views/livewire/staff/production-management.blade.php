<div>
    <!-- Production Management Module -->
    <h2 class="text-[#0d151c] text-2xl font-bold leading-tight tracking-[-0.015em] px-3 py-2">Production Management</h2>
    <div class="tabs-container flex px-4 border-b border-gray-200 mb-4">
        <div wire:click="setTab('magi')"
            class="tab px-4 py-2 cursor-pointer font-medium text-gray-600 hover:text-gray-800 border-b-2 {{ $activeTab === 'magi' ? 'border-blue-500 text-blue-600' : 'border-transparent' }}">
            Magi Production
        </div>
        <div wire:click="setTab('papadam')"
            class="tab px-4 py-2 cursor-pointer font-medium text-gray-600 hover:text-gray-800 border-b-2 {{ $activeTab === 'papadam' ? 'border-blue-500 text-blue-600' : 'border-transparent' }}">
            Papadam Production
        </div>
    </div>

    <div class="p-2">
        <div class="card p-6 mb-6 bg-white rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Add Production Entry</h3>
            @if (session()->has('message'))
            <div class="mb-4 text-green-600">{{ session('message') }}</div>
            @endif
            <form wire:submit.prevent="saveProductionEntry" id="production-form">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">Employee Name</label>
                        <div class="flex gap-2 items-center">
                            <select wire:model="form.employee_id"
                                class="form-control flex-1 border border-gray-300 rounded-md p-2">
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                <option value="{{ $employee->emp_id }}">{{ $employee->fname }}</option>
                                @endforeach
                            </select>
                            <button wire:click="showAllEmployees" type="button"
                                class="p-2 px-3 rounded-md bg-blue-500 text-white text-xl leading-none hover:bg-blue-600">
                                +
                            </button>
                        </div>
                        @error('form.employee_id')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Role</label>
                        <select wire:model="form.work_type"
                            class="form-control w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select Role</option>
                            <option value="cutter">Cutter</option>
                            <option value="roller">Roller</option>
                            <option value="dryer">Dryer</option>
                            <option value="packer">Packer</option>
                            <option value="worker">Worker</option>
                        </select>
                        @error('form.work_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Date</label>
                        <input type="date" wire:model="form.date"
                            class="form-control w-full border border-gray-300 rounded-md p-2">
                        @error('form.date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Total Quantity (kg)</label>
                        <input type="number" wire:model="form.quantity"
                            class="form-control w-full border border-gray-300 rounded-md p-2"
                            placeholder="Enter quantity"
                            wire:change="calculateTotalSalary">
                        @error('form.quantity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Worked Quantity</label>
                        <input type="number" wire:model="form.worked_quantity"
                            class="form-control w-full border border-gray-300 rounded-md p-2"
                            placeholder="Enter worked quantity"
                            wire:change="calculateTotalSalary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Additional Salary</label>
                        <input type="number" wire:model="form.additional_salary"
                            class="form-control w-full border border-gray-300 rounded-md p-2"
                            placeholder="Enter additional salary"
                            wire:change="calculateTotalSalary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Total Amount (LKR)</label>
                        <input type="text" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md p-2"
                            value="{{ number_format($form['total_salary'], 2) }}" readonly>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea wire:model="form.description"
                            class="form-control w-full border border-gray-300 rounded-md p-2" rows="3"
                            placeholder="Enter production details"></textarea>
                        @error('form.description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit"
                        class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Save
                        Production Entry</button>
                </div>
            </form>
        </div>

 <div class="card p-6 bg-white rounded-xl shadow-sm">
        <h3 class="text-lg font-semibold mb-4">Production Records</h3>
        <div class="table-container overflow-hidden rounded-xl">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-3">Date</th>
                        <th class="text-left px-4 py-3">Employee</th>
                        <th class="text-left px-4 py-3">Role</th>
                        <th class="text-left px-4 py-3">Total Qty (kg)</th>
                        <th class="text-left px-4 py-3">Worked Qty (kg)</th>
                        <th class="text-left px-4 py-3">Extra Salary</th>
                        <th class="text-left px-4 py-3">Total Amount</th>
                        <th class="text-left px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                    <tr>
                        <td class="px-4 py-3">{{ $record->date ?? $record->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ $record->employee ? $record->employee->fname : 'Unknown' }}</td>
                        <td class="px-4 py-3">{{ ucfirst($record->work_type) }}</td>
                        <td class="px-4 py-3">{{ $record->quantity }}</td>
                        <td class="px-4 py-3">{{ $record->worked_quantity ?? $record->quantity }}</td>
                        <td class="px-4 py-3">{{ number_format($record->additional_salary, 0) }}</td>
                        <td class="px-4 py-3">{{ number_format($record->total_salary, 0) }}</td>
                        <td class="px-4 py-3 flex space-x-2">
                            <button wire:click="editRecord({{ $record->production_id }})" 
                                class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="confirmDelete({{ $record->production_id }})" 
                                class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                            No records found for {{ ucfirst($activeTab) }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    @if($editingRecordId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg w-full max-w-4xl mx-4 overflow-auto max-h-[90vh]">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Edit Production Entry</h3>
                    <form wire:submit.prevent="updateRecord">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Same form fields as the add form, pre-filled with editing data -->
                            @include('livewire.staff.partials.production-form-fields')
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="button" wire:click="cancelEdit" 
                                class="px-4 py-2 bg-gray-500 text-white rounded mr-2">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded">
                                Update Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeletionId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-semibold mb-4">Confirm Deletion</h3>
                <p class="mb-6">Are you sure you want to delete this production record? This action cannot be undone.</p>
                <div class="flex justify-end">
                    <button wire:click="cancelDelete" 
                        class="px-4 py-2 bg-gray-500 text-white rounded mr-2">
                        Cancel
                    </button>
                    <button wire:click="deleteRecord" 
                        class="px-4 py-2 bg-red-600 text-white rounded">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
    </div>
</div>