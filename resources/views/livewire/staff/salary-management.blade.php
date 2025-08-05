<div>
    <!-- Salary Management Module -->
    <h2 class="text-[#0d151c] text-2xl font-bold leading-tight tracking-[-0.015em] px-3 py-2">Salary Management</h2>
    <div class="tabs-container flex px-4 border-b border-gray-200 mb-4">
        <div wire:click="setTab('monthly')"
             class="tab px-4 py-2 cursor-pointer font-medium text-gray-600 hover:text-gray-800 border-b-2 {{ $activeTab === 'monthly' ? 'border-blue-500 text-blue-600' : 'border-transparent' }}">
            Monthly Salary
        </div>
        <div wire:click="setTab('daily')"
             class="tab px-4 py-2 cursor-pointer font-medium text-gray-600 hover:text-gray-800 border-b-2 {{ $activeTab === 'daily' ? 'border-blue-500 text-blue-600' : 'border-transparent' }}">
            Daily Salary
        </div>
        <!-- Optional: Keep weekly tab if needed -->
        <div wire:click="setTab('weekly')"
             class="tab px-4 py-2 cursor-pointer font-medium text-gray-600 hover:text-gray-800 border-b-2 {{ $activeTab === 'weekly' ? 'border-blue-500 text-blue-600' : 'border-transparent' }}">
            Weekly Salary
        </div>
    </div>

    <div class="p-2">
        <div class="card p-6 mb-6 bg-white rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Calculate Salary</h3>
            @if (session()->has('message'))
                <div class="mb-4 text-green-600">{{ session('message') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="mb-4 text-red-600">{{ session('error') }}</div>
            @endif
            <form wire:submit.prevent="calculateSalary">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">Select Employee</label>
                        <select wire:model="form.employee_id" class="form-control w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->emp_id }}">{{ $employee->fname }} ({{ ucfirst($employee->salary_type) }})</option>
                            @endforeach
                        </select>
                        @error('form.employee_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Month</label>
                        <select wire:model="form.month" class="form-control w-full border border-gray-300 rounded-md p-2">
                            @foreach (range(1, 12) as $month)
                                <option value="{{ $month }}" {{ $month == $form['month'] ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                            @endforeach
                        </select>
                        @error('form.month') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Year</label>
                        <select wire:model="form.year" class="form-control w-full border border-gray-300 rounded-md p-2">
                            @foreach (range(date('Y'), date('Y') - 5) as $year)
                                <option value="{{ $year }}" {{ $year == $form['year'] ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        @error('form.year') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Calculate Salary</button>
                </div>
            </form>
        </div>

        <div class="card p-6 mb-6 bg-white rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Salary Breakdown</h3>
            @if ($salaryBreakdown)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Basic Salary</span>
                            <span>LKR {{ number_format($salaryBreakdown['basic_salary'], 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Production Bonus</span>
                            <span>LKR {{ number_format($salaryBreakdown['production_bonus'], 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Overtime</span>
                            <span>LKR {{ number_format($salaryBreakdown['overtime'], 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Allowances</span>
                            <span>LKR {{ number_format($salaryBreakdown['allowances'], 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b font-semibold">
                            <span>Gross Salary</span>
                            <span>LKR {{ number_format($salaryBreakdown['gross_salary'], 2) }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between py-3 border-b">
                            <span>EPF (8%)</span>
                            <span>LKR {{ number_format($salaryBreakdown['epf'], 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b">
                            <span>ETF (3%)</span>
                            <span>LKR {{ number_format($salaryBreakdown['etf'], 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Loan Deductions</span>
                            <span>LKR {{ number_format($salaryBreakdown['loan_deductions'], 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b">
                            <span>Other Deductions</span>
                            <span>LKR {{ number_format($salaryBreakdown['other_deductions'], 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b font-semibold">
                            <span>Net Salary</span>
                            <span>LKR {{ number_format($salaryBreakdown['net_salary'], 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button wire:click="printPayslip" class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 mr-3">Print Payslip</button>
                    <button wire:click="markAsPaid" class="btn-outline px-6 py-2 rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-50">Mark as Paid</button>
                </div>
            @else
                <div class="text-gray-500 text-center">Select an employee and period to calculate salary.</div>
            @endif
        </div>

        <div class="card p-6 bg-white rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Salary Records</h3>
            <div class="table-container overflow-hidden rounded-xl">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left px-4 py-3">Employee</th>
                            <th class="text-left px-4 py-3">Month</th>
                            <th class="text-left px-4 py-3">Basic Salary</th>
                            <th class="text-left px-4 py-3">Bonus</th>
                            <th class="text-left px-4 py-3">Net Salary</th>
                            <th class="text-left px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salaries as $salary)
                            <tr>
                                <td class="px-4 py-3">{{ $salary->employee ? $salary->employee->fname : 'Unknown' }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($salary->salary_month)->format('F Y') }}</td>
                                <td class="px-4 py-3">LKR {{ number_format($salary->basic_salary, 0) }}</td>
                                <td class="px-4 py-3">LKR {{ number_format($salary->bonus, 0) }}</td>
                                <td class="px-4 py-3">LKR {{ number_format($salary->net_salary, 0) }}</td>
                                <td class="px-4 py-3">{{ ucfirst($salary->payment_status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">No salary records found for {{ ucfirst($activeTab) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

