<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sevena Payroll Dashboard</title>

    <!-- Fonts and Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script> --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

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
    
        [x-cloak] {
            display: none;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>

    @livewireStyles

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
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-home text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Dashboard</p>
                            </a>
                            <a href="{{ route('admin.employee-management') }}"
                                class="nav-item {{ request()->routeIs('admin.employee-management') ? 'active' : '' }} flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-users text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Employee</p>
                            </a>
                            <a href="{{ route('admin.production-management') }}"
                                class="nav-item {{ request()->routeIs('admin.production-management') ? 'active' : '' }} flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-industry text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Production</p>
                            </a>
                            <a href="{{ route('admin.salary-management') }}"
                                class="nav-item {{ request()->routeIs('admin.salary-management') ? 'active' : '' }} flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-money-bill-wave text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Salary</p>
                            </a>
                            <a href="{{ route('admin.attendance-management') }}"
                                class="nav-item {{ request()->routeIs('admin.attendance-management') ? 'active' : '' }} flex items-center gap-3 px-3 py-2"
                                data-module="attendance">
                                <i class="fas fa-calendar-check text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Attendance</p>
                            </a>
                            <a href="{{ route('admin.loan-management') }}"
                                class="nav-item {{ request()->routeIs('admin.loan-management') ? 'active' : '' }} flex items-center gap-3 px-3 py-2"
                                data-module="loan">
                                <i class="fas fa-hand-holding-usd text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Loan</p>
                            </a>
                            <a href="{{ route('admin.stock-management') }}" class="nav-item {{ request()->routeIs('admin.stock-management') ? 'active' : '' }} flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-boxes text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Stock</p>
                            </a>
                            <a href="#" class="nav-item flex items-center gap-3 px-3 py-2">
                                <i class="fas fa-chart-bar text-gray-500 w-6 text-center"></i>
                                <p class="text-[#0d151c] text-sm font-medium leading-normal">Reports</p>
                            </a>
                            <a href="{{ route('admin.setting-management') }}"
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


    @livewireScripts
</body>

</html>