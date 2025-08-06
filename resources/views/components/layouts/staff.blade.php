<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sevena Payroll Dashboard</title>

    <!-- Fonts and Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @livewireStyles

    <!-- Styles -->
    <style>
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

        .card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .tabs-container {
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
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
                min-width: 640px;
            }

            .flex.flex-col.sm\\:flex-row {
                flex-direction: column;
            }

            .w-full.sm\\:w-1\/3,
            .w-full.sm\\:w-2\/3 {
                width: 100%;
            }
        }

        .overflow-y-auto::-webkit-scrollbar {
            display: none;
        }

        .overflow-y-auto {
            scrollbar-width: none;
        }
    </style>
</head>

<body>
    <div class="relative flex size-full min-h-screen flex-col bg-slate-50 overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="gap-1 px-1 flex flex-1 justify-center py-2">
                <!-- Sidebar -->
                <div id="sidebar"
                    class="hidden lg:flex flex-col justify-between bg-white p-4 shadow-md rounded-lg sidebar min-h-[500px] w-[260px]">
                    <div class="flex flex-col gap-4">
                        <div class="flex justify-center py-2 border-b border-gray-200">
                            <div class="bg-center bg-no-repeat bg-cover w-[6.5rem] h-[3.5rem]"
                                style='background-image: url("https://sevena.lk/img/logo.png");'></div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('staff.dashboard') }}"
                                class="nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-home text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Dashboard</p>
                            </a>
                            <a href="{{ route('staff.employee-management') }}"
                                class="nav-item {{ request()->routeIs('staff.employee-management') ? 'active' : '' }} flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-users text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Employee</p>
                            </a>
                            <a href="{{ route('staff.production-management') }}"
                                class="nav-item {{ request()->routeIs('staff.production-management') ? 'active' : '' }} flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-industry text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Production</p>
                            </a>
                            <a href="{{ route('staff.salary-management') }}"
                                class="nav-item {{ request()->routeIs('staff.salary-management') ? 'active' : '' }} flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-money-bill-wave text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Salary</p>
                            </a>
                            <a href="{{ route('staff.attendance-management') }}"
                                class="nav-item {{ request()->routeIs('staff.attendance-management') ? 'active' : '' }} flex items-center gap-3 px-3 py-2"
                                data-module="attendance">
                                <i class="fas fa-calendar-check text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Attendance</p>
                            </a>
                            <a href="{{ route('staff.loan-management') }}"
                                class="nav-item {{ request()->routeIs('staff.loan-management') ? 'active' : '' }} flex items-center gap-3 px-3 py-2"
                                data-module="loan">
                                <i class="fas fa-hand-holding-usd text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Loan</p>
                            </a>
                            <a href="#" class="nav-item flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-boxes text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Stock</p>
                            </a>
                            <a href="#" class="nav-item flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-chart-bar text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Reports</p>
                            </a>
                            <a href="{{ route('staff.setting-management') }}"
                                class="nav-item flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-cog text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Settings</p>
                            </a>
                        </div>
                    </div>
                    <div class="py-4 border-t border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left">
                                <div
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                                    <i class="fas fa-sign-out-alt text-gray-500 w-6 text-center"></i>
                                    <p class="text-[#0d151c] text-sm font-medium leading-normal">Logout</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="layout-content-container flex flex-col max-w-80% flex-1">
                    <!-- Header -->
                    <header class="header flex items-center justify-between px-4 lg:px-10 py-4">
                        <!-- Burger Menu -->
                        <button id="burger" class="block lg:hidden text-2xl text-blue-700">
                            <i class="fas fa-bars"></i>
                        </button>

                        <!-- Title -->
                        <div class="flex items-center gap-2 text-[#0d151c]">
                            <i class="fas fa-utensils text-blue-700"></i>
                            <h2 class="text-lg font-bold">Sevena Production Co.</h2>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex flex-1 justify-end gap-4">
                            <label class="flex items-center min-w-[200px] h-10 rounded-xl bg-[#e7edf4]">
                                <div class="flex items-center justify-center pl-4 text-[#49749c]">
                                    <i class="fas fa-search"></i>
                                </div>
                                <input placeholder="Search"
                                    class="flex w-full bg-transparent px-3 text-base border-none focus:outline-none" />
                            </label>
                            <button
                                class="relative flex items-center justify-center w-10 h-10 rounded-full bg-[#e7edf4] text-[#0d151c] hover:bg-[#d1e0f0]">
                                <i class="fas fa-bell"></i>
                                <div class="notification-badge">3</div>
                            </button>
                            <div class="bg-center bg-no-repeat bg-cover rounded-full size-10"
                                style='background-image: url("https://lh3.googleusercontent.com/a-/AFdZucpWq8JhKRS4ikNB9Ln1EXz7J--2nLyvXsGrKH8=s96-c");'>
                            </div>
                        </div>
                    </header>

                    <!-- Module Content -->
                    <div class="module-content-container p-2">
                        {{ $slot ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Burger Toggle Script -->
    <script>
        document.getElementById('burger').addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        });
    </script>
<script>
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
                                <span>ETF (3%):</span>
                                <span>LKR ${etf.toFixed(2)}</span>
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


    @livewireScripts
</body>

</html>