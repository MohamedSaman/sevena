<div>
    <!-- Loan Management Module -->
    <h2 class="text-[#0d151c] text-2xl font-bold leading-tight tracking-[-0.015em] px-3 py-2">Loan Management</h2>

    <div class="p-2">
        <!-- Add Loan Form -->
        <div class="card p-6 mb-6 bg-white rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Add New Loan</h3>
            @if (session()->has('message'))
                <div class="mb-4 text-green-600">{{ session('message') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="mb-4 text-red-600">{{ session('error') }}</div>
            @endif
            <form wire:submit.prevent="addLoan">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">Select Employee</label>
                        <select wire:model="form.employee_id" class="form-control w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->emp_id }}">{{ $employee->fname }}</option>
                            @endforeach
                        </select>
                        @error('form.employee_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Loan Amount (LKR)</label>
                        <input type="number" wire:model="form.loan_amount" class="form-control w-full border border-gray-300 rounded-md p-2" placeholder="Enter loan amount">
                        @error('form.loan_amount') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Interest Rate (%)</label>
                        <input type="number" step="0.01" wire:model="form.interest_rate" class="form-control w-full border border-gray-300 rounded-md p-2" placeholder="Enter interest rate">
                        @error('form.interest_rate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Start Date</label>
                        <input type="date" wire:model="form.start_date" class="form-control w-full border border-gray-300 rounded-md p-2">
                        @error('form.start_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Term (Months)</label>
                        <input type="number" wire:model="form.term_month" class="form-control w-full border border-gray-300 rounded-md p-2" placeholder="Enter term in months">
                        @error('form.term_month') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Add Loan</button>
                    <button type="button" wire:click="resetForm" class="btn-outline px-6 py-2 rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-50 ml-3">Reset</button>
                </div>
            </form>
        </div>

        <!-- Loan Breakdown -->
        @if ($loanBreakdown)
            <div class="card p-6 mb-6 bg-white rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold mb-4">Loan Breakdown</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Employee</span>
                            <span>{{ $employees->firstWhere('emp_id', $loanBreakdown['employee_id'])->fname ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Loan Amount</span>
                            <span>LKR {{ number_format($loanBreakdown['loan_amount'], 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Interest Rate</span>
                            <span>{{ number_format($loanBreakdown['interest_rate'], 2) }}%</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Start Date</span>
                            <span>{{ \Carbon\Carbon::parse($loanBreakdown['start_date'])->format('F d, Y') }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Term (Months)</span>
                            <span>{{ $loanBreakdown['term_month'] }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Monthly Payment</span>
                            <span>LKR {{ number_format($loanBreakdown['monthly_payment'], 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b font-semibold">
                            <span>Remaining Balance</span>
                            <span>LKR {{ number_format($loanBreakdown['remaining_balance'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Loan Records -->
        <div class="card p-6 bg-white rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Loan Records</h3>
            <div class="table-container overflow-hidden rounded-xl">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left px-4 py-3">Employee</th>
                            <th class="text-left px-4 py-3">Loan Amount</th>
                            <th class="text-left px-4 py-3">Interest Rate</th>
                            <th class="text-left px-4 py-3">Start Date</th>
                            <th class="text-left px-4 py-3">Term (Months)</th>
                            <th class="text-left px-4 py-3">Monthly Payment</th>
                            <th class="text-left px-4 py-3">Remaining Balance</th>
                            <th class="text-left px-4 py-3">Status</th>
                            <th class="text-left px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $loan)
                            <tr>
                                <td class="px-4 py-3">{{ $loan->employee ? $loan->employee->fname : 'Unknown' }}</td>
                                <td class="px-4 py-3">LKR {{ number_format($loan->loan_amount, 0) }}</td>
                                <td class="px-4 py-3">{{ number_format($loan->interest_rate, 2) }}%</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($loan->start_date)->format('F d, Y') }}</td>
                                <td class="px-4 py-3">{{ $loan->term_month }}</td>
                                <td class="px-4 py-3">LKR {{ number_format($loan->monthly_payment, 0) }}</td>
                                <td class="px-4 py-3">LKR {{ number_format($loan->remaining_balance, 0) }}</td>
                                <td class="px-4 py-3">{{ ucfirst($loan->status) }}</td>
                                <td class="px-4 py-3">
                                    @if ($loan->status === 'active')
                                        <button wire:click="markAsPaid({{ $loan->loan_id }})" class="btn-outline px-4 py-1 rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-50">Mark as Paid</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-3 text-center text-gray-500">No loan records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- <style>
    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem;
        font-size: 0.875rem;
    }
    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    .btn-primary {
        background-color: #3b82f6;
        color: white;
        font-weight: 500;
        border: none;
        transition: background-color 0.2s;
    }
    .btn-primary:hover {
        background-color: #2563eb;
    }
    .btn-outline {
        background-color: transparent;
        transition: background-color 0.2s;
    }
    .card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .table-container {
        overflow-x: auto;
    }
    table {
        border-collapse: collapse;
    }
    th, td {
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.875rem;
    }
    th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
    }
    @media (max-width: 640px) {
        .table-container {
            width: 100%;
            overflow-x: auto;
        }
        table {
            min-width: 800px;
        }
    }
</style> --}}