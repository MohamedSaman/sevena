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
                        <select wire:model="form.employee_id" class="form-control w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->emp_id }}">{{ $employee->fname }}</option>
                            @endforeach
                        </select>
                        @error('form.employee_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Role</label>
                        <select wire:model="form.work_type" class="form-control w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select Role</option>
                            <option value="worker">Production Worker</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="quality">Quality Control</option>
                        </select>
                        @error('form.work_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Date</label>
                        <input type="date" wire:model="form.date" class="form-control w-full border border-gray-300 rounded-md p-2" value="{{ now()->format('Y-m-d') }}">
                        @error('form.date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Total Quantity (kg)</label>
                        <input type="number" wire:model="form.quantity" class="form-control w-full border border-gray-300 rounded-md p-2" placeholder="Enter quantity">
                        @error('form.quantity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Worked Quantity (kg)</label>
                        <input type="number" wire:model="form.worked_quantity" class="form-control w-full border border-gray-300 rounded-md p-2" placeholder="Enter worked quantity">
                        @error('form.worked_quantity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Extra Salary (LKR)</label>
                        <input type="number" wire:model="form.additional_salary" class="form-control w-full border border-gray-300 rounded-md p-2" placeholder="Enter extra amount">
                        @error('form.additional_salary') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea wire:model="form.description" class="form-control w-full border border-gray-300 rounded-md p-2" rows="3" placeholder="Enter production details"></textarea>
                        @error('form.description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Total Amount (LKR)</label>
                        <input type="text" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md p-2" value="{{ number_format($form['total_salary'], 2) }}" readonly>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Save Production Entry</button>
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
                                <td class="px-4 py-3">
                                    <button class="text-blue-600 hover:text-blue-800 mr-2"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-3 text-center text-gray-500">No records found for {{ ucfirst($activeTab) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

