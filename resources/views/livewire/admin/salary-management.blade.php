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
                        <select wire:model="form.employee_id"
                            class="form-control w-full border border-gray-300 rounded-md p-2">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                            <option value="{{ $employee->emp_id }}">{{ $employee->fname }} ({{
                                ucfirst($employee->salary_type) }})</option>
                            @endforeach
                        </select>
                        @error('form.employee_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Month</label>
                        <select wire:model="form.month"
                            class="form-control w-full border border-gray-300 rounded-md p-2">
                            @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" {{ $month==$form['month'] ? 'selected' : '' }}>{{
                                \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                            @endforeach
                        </select>
                        @error('form.month') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Year</label>
                        <select wire:model="form.year"
                            class="form-control w-full border border-gray-300 rounded-md p-2">
                            @foreach (range(date('Y'), date('Y') - 5) as $year)
                            <option value="{{ $year }}" {{ $year==$form['year'] ? 'selected' : '' }}>{{ $year }}
                            </option>
                            @endforeach
                        </select>
                        @error('form.year') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit"
                        class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Calculate
                        Salary</button>
                </div>
            </form>
        </div>

        <div class="card p-6 mb-6 bg-white rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Salary Breakdown</h3>
            @if ($salaryBreakdown)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>

                    <div class="flex justify-between py-3 border-b">
                        <span>
                            Basic Salary ({{ number_format($salaryBreakdown['total_hours'], 2) }} hours )
                            {{-- {{ $salaryBreakdown['work_days'] }} days --}}
                        </span>
                        <span>LKR {{ number_format($salaryBreakdown['basic_salary'], 2) }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b">
                        <span>
                            @if($salaryBreakdown['overtime'] > 0)
                            Overtime
                            @if($salaryBreakdown['overtime_hours'] > 0)
                      
                                {{ $salaryBreakdown['overtime_hours'] }} hours @ 1.5x rate
                            
                            @endif
                            @else
                            Additional Salary
                            @endif
                        </span>

                        <span>
                            LKR
                            @if($salaryBreakdown['overtime'] > 0)
                            {{ number_format($salaryBreakdown['overtime'], 2) }}
                            @else
                            {{-- {{ number_format($additional_salary, 2) ?? 0.00}} --}}
                            @endif
                        </span>
                    </div>

                    <div class="flex justify-between py-3 border-b">
                        <span> Bonus</span>
                        <span>LKR {{ number_format($salaryBreakdown['production_bonus'], 2) }}</span>
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
                <button wire:click="showPayslip"
                    class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 mr-3">View
                    Payslip</button>
                <button wire:click="markAsPaid"
                    class="btn-outline px-6 py-2 rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-50">Mark
                    as Paid</button>
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
                            <th class="text-left px-4 py-3">Deduction</th>
                            <th class="text-left px-4 py-3">Net Salary</th>
                            <th class="text-left px-4 py-3">Status</th>
                            <th class="text-left px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salaries as $salary)
                        <tr>
                            <td class="px-4 py-3">{{ $salary->employee ? $salary->employee->fname : 'Unknown' }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($salary->salary_month)->format('F Y') }}</td>
                            <td class="px-4 py-3">LKR {{ number_format($salary->basic_salary, 0) }}</td>
                            <td class="px-4 py-3">LKR {{ number_format($salary->bonus, 0) }}</td>
                            <td class="px-4 py-3">LKR {{ number_format($salary->deductions, 0) }}</td>
                            <td class="px-4 py-3">LKR {{ number_format($salary->net_salary, 0) }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium {{ $salary->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($salary->payment_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 flex space-x-2">
                                <button wire:click="showPayslipForRecord({{ $salary->salary_id }})"
                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                    View Payslip
                                </button>
                                @if($salary->payment_status !== 'paid')
                                <button wire:click="markRecordAsPaid({{ $salary->salary_id }})"
                                    class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                    Mark Paid
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                                No salary records found for {{ ucfirst($activeTab) }}.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Payslip Modal -->
    @if($showPayslipModal && $salaryBreakdown)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg w-70% max-w-4xl mx-4 overflow-auto max-h-[90vh]">
            <div class="p-6" id="payslip-content">
                <style>
                    @page {
                        size: A6;
                        margin: 100%;
                    }

                    body {
                        background: #f0f0f0;
                        padding: 5px;
                    }

                    .payslip-container {
                        font-family: Arial, sans-serif;
                        width: 100%;
                        max-width: 100%;
                        background: white;
                        color: black;
                        overflow: hidden;
                        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
                        padding: 1cm;
                        box-sizing: border-box;
                    }

                    .payslip-header {
                        margin-bottom: 1rem;
                        text-align: center;
                    }

                    .payslip-header h1 {
                        font-size: 1.2rem;
                        font-weight: bold;
                        margin-bottom: 0.25rem;
                    }

                    .employee-info {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 0.75rem;
                        font-size: 0.85rem;
                    }

                    .payslip-table {
                        width: 100%;
                        font-size: 0.75rem;
                        border-collapse: collapse;
                        margin-bottom: 1rem;
                    }

                    .payslip-table th,
                    .payslip-table td {
                        padding: 0.4rem;
                        border: 1px solid #ccc;
                        text-align: left;
                    }

                    .payslip-table th {
                        background-color: #F9FAFB;
                        font-weight: 600;
                    }

                    .text-right {
                        text-align: right;
                    }

                    .bg-green-100 {
                        background-color: #D1FAE5;
                    }

                    .font-bold {
                        font-weight: 700;
                    }

                    .text-lg {
                        font-size: 1rem;
                    }

                    .text-sm {
                        font-size: 0.7rem;
                    }

                    .text-gray-500 {
                        color: #6B7280;
                    }

                    @media print {
                        body {
                            background: none;
                            padding: 0;
                        }

                        .payslip-container {
                            box-shadow: none;
                            margin: 0;
                            width: 100%;
                            padding: 1cm;
                        }

                        .no-print {
                            display: none !important;
                        }
                    }
                </style>

                <div class="payslip-container">
                    <div class="relative">
                        <div class="payslip-header">
                            <h1>Salary Payslip</h1>
                        </div>

                        <div class="employee-info">
                            <div>
                                <p><strong>{{ $employeeDetails->fname ?? 'Unknown' }}</strong></p>
                                <p>{{ $employeeDetails->designation ?? 'Employee' }}</p>
                            </div>
                            <div class="text-right">
                                <p><strong>Pay Slip:</strong> {{ $salaryBreakdown['month_name'] ?? '' }} {{
                                    $salaryBreakdown['year'] ?? '' }}</p>
                                <p>EMP ID: {{ $employeeDetails->emp_id ?? '' }}</p>
                                <p>NIC: {{ $employeeDetails->nic ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Earnings Table -->
                        <table class="payslip-table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th class="text-right">Amount (LKR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Earnings</td>
                                    <td>Basic Salary ({{ $salaryBreakdown['total_hours'] }} hours)</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['basic_salary'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Overtime @if($salaryBreakdown['overtime_hours'] > 0) {{
                                        $salaryBreakdown['overtime_hours'] }} hours @ 1.5x rate @endif</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['overtime'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Allowances</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['allowances'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Production Bonus</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['production_bonus'], 2) }}
                                    </td>
                                </tr>
                                <tr class="font-bold">
                                    <td></td>
                                    <td>Total Earnings</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['gross_salary'], 2) }}</td>
                                </tr>

                                <tr>
                                    <td>Deductions</td>
                                    <td>EPF (8%)</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['epf'], 2) }}</td>
                                </tr>


                                <tr>
                                    <td></td>
                                    <td>Loan Deductions</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['loan_deductions'], 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Other Deductions</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['other_deductions'], 2) }}
                                    </td>
                                </tr>
                                <tr class="font-bold">
                                    <td></td>
                                    <td>Total Deductions</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['epf'] +
                                        $salaryBreakdown['loan_deductions'] +
                                        $salaryBreakdown['other_deductions'], 2) }}</td>
                                </tr>

                                <tr>
                                    <td>Loan</td>
                                    <td>Remming Balance</td>
                                    <td class="text-right">{{ number_format($loanbalance ?? 0, 2) }}</td>
                                </tr>

                                <tr class="font-bold bg-green-100 text-lg">
                                    <td></td>
                                    <td>Net Salary</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['net_salary'], 2) }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <h3 class="font-semibold mb-1">Employer's Contributions</h3>
                        <table class="payslip-table">
                            <tbody>
                                <tr>
                                    <td>EPF 12%</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['basic_salary'] * 0.12, 2)
                                        }}</td>
                                </tr>
                                <tr>
                                    <td>ETF 3%</td>
                                    <td class="text-right">{{ number_format($salaryBreakdown['basic_salary'] * 0.03, 2)
                                        }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="text-sm text-gray-500 mt-3">
                            <p>Printed Date: {{ now()->format('Y-m-d') }} | Time: {{ now()->format('H:i') }}</p>
                        </div>
                    </div>
                </div>

                <button wire:click="$set('showPayslipModal', false)"
                    class="px-6 py-2 bg-gray-500 text-white rounded my-6 ml-32 mr-10 ">Close</button>
                <button onclick="printPayslip()" class="px-4 py-2 bg-green-600 text-white rounded ">Print
                    Payslip</button>

            </div>

        </div>
    </div>
    @endif

    <script>
        function printPayslip() {
            // Create a clone of the payslip content
            const originalContent = document.getElementById('payslip-content').cloneNode(true);
            
            // Create a new window
            const printWindow = window.open('', '_blank');
            
            // Write the HTML content
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Payslip - {{ $employeeDetails->fname ?? 'Employee' }}</title>
                    <style>
                        @page {
                            size: A6;
                            margin: 1cm;
                        }

                        body {
                            background: #f0f0f0;
                            padding: 10px;
                        }
                        .payslip-container {
                            max-width: 800px;
                            margin: 0 auto;
                        }
                        .payslip-header {
                            margin-bottom: 1.5rem;
                        }
                        .payslip-header h1 {
                            font-size: 1.5rem;
                            font-weight: bold;
                        }
                        .payslip-logo {
                            position: absolute;
                            top: 1.5rem;
                            right: 1.5rem;
                            background: #10B981;
                            border-radius: 9999px;
                            width: 3rem;
                            height: 3rem;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-size: 1.25rem;
                            font-weight: bold;
                        }
                        .employee-info {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 1rem;
                        }
                        .payslip-table {
                            width: 100%;
                            font-size: 0.875rem;
                            border-collapse: collapse;
                            margin-bottom: 1rem;
                        }
                        .payslip-table th, .payslip-table td {
                            padding: 0.5rem;
                            border: 1px solid #E5E7EB;
                            text-align: left;
                        }
                        .payslip-table th {
                            background-color: #F9FAFB;
                            font-weight: 600;
                        }
                        .payslip-table td.text-right {
                            text-align: right;
                        }
                        .bg-green-100 {
                            background-color: #D1FAE5;
                        }
                        .font-bold {
                            font-weight: 700;
                        }
                        .text-lg {
                            font-size: 1.125rem;
                        }
                        .no-print {
                            display: none;
                        }
                    </style>
                </head>
                <body>
                    <div class="payslip-container">
                        ${originalContent.innerHTML}
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() {
                                window.close();
                            }, 1000);
                        };
                    <\/script>
                </body>
                </html>
            `);
            
            printWindow.document.close();
        }
 
                
        document.addEventListener('livewire:init', function () {
        Livewire.on('print-payslip', function (salaryBreakdown) {
            console.log('Received salary breakdown:', salaryBreakdown);
            
            // Create a helper function to safely access and format values
            const getValue = (key, defaultValue = 0) => {
                if (!salaryBreakdown || !salaryBreakdown.hasOwnProperty(key)) {
                    return defaultValue;
                }
                const value = salaryBreakdown[key];
                
                // Handle numeric values that might be strings
                if (typeof value === 'string' && value.trim() !== '') {
                    return parseFloat(value) || defaultValue;
                }
                
                return value || defaultValue;
            };

            try {
                // Use the helper function to get all values
                const employee_name = getValue('employee_name', 'Unknown');
                const month_name = getValue('month_name', 'N/A');
                const year = getValue('year', 'N/A');
                const basic_salary = getValue('basic_salary');
                const production_bonus = getValue('production_bonus');
                const overtime = getValue('overtime');
                const allowances = getValue('allowances');
                const gross_salary = getValue('gross_salary');
                const epf = getValue('epf');
                const etf = getValue('etf');
                const loan_deductions = getValue('loan_deductions');
                const other_deductions = getValue('other_deductions');
                const net_salary = getValue('net_salary');

                const printContent = `
                    <div style="font-family: Arial, sans-serif; padding: 20px; width: 100%; max-width: 600px; margin: 0 auto;">
                        <h1 style="text-align: center; margin-bottom: 20px;">Payslip</h1>
                        <div style="border: 1px solid #ccc; padding: 20px; border-radius: 5px;">
                            <p><strong>Employee Name:</strong> ${employee_name}</p>
                            <p><strong>Period:</strong> ${month_name} ${year}</p>
                            
                            <h3 style="margin-top: 20px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Earnings</h3>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Basic Salary:</span>
                                <span>LKR ${basic_salary.toFixed(2)}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Production Bonus:</span>
                                <span>LKR ${production_bonus.toFixed(2)}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Overtime:</span>
                                <span>LKR ${overtime.toFixed(2)}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Allowances:</span>
                                <span>LKR ${allowances.toFixed(2)}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-weight: bold; margin-top: 10px;">
                                <span>Gross Salary:</span>
                                <span>LKR ${gross_salary.toFixed(2)}</span>
                            </div>
                            
                            <h3 style="margin-top: 20px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Deductions</h3>
                            <div style="display: flex; justify-content: space-between;">
                                <span>EPF (8%):</span>
                                <span>LKR ${epf.toFixed(2)}</span>
                            </div>
                          
                            <div style="display: flex; justify-content: space-between;">
                                <span>Loan Deductions:</span>
                                <span>LKR ${loan_deductions.toFixed(2)}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Other Deductions:</span>
                                <span>LKR ${other_deductions.toFixed(2)}</span>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; font-weight: bold; margin-top: 10px; border-top: 1px solid #eee; padding-top: 10px;">
                                <span>Net Salary:</span>
                                <span>LKR ${net_salary.toFixed(2)}</span>
                            </div>
                            
                            <div style="margin-top: 30px; text-align: center; font-style: italic;">
                                Generated on ${new Date().toLocaleDateString()}
                            </div>
                        </div>
                    </div>
                `;

                const printWindow = window.open('', '_blank', 'height=700,width=800');
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Payslip - ${employee_name}</title>
                            <style>
                                body { 
                                    font-family: Arial, sans-serif; 
                                    margin: 0; 
                                    padding: 20px; 
                                    color: #333;
                                    background-color: white;
                                }
                                h1, h2, h3 { 
                                    color: #2c3e50; 
                                }
                                @media print {
                                    body { 
                                        -webkit-print-color-adjust: exact; 
                                        print-color-adjust: exact;
                                    }
                                }
                            </style>
                        </head>
                        <body>${printContent}</body>
                    </html>
                `);
                printWindow.document.close();
                
                // Wait for content to load before printing
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 500);
            } catch (error) {
                console.error('Error printing payslip:', error);
                alert('Failed to print payslip. Please check console for details.');
            }
        });
    });
    </script>
</div>