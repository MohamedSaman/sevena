<div>
    <!-- Dashboard Module -->
    <h2 class="text-[#0d151c] text-2xl font-bold leading-tight tracking-[-0.015em] px-3 py-2">Dashboard Overview</h2>
    <div class="flex flex-wrap gap-4 p-2">
        <div class="card dashboard-card flex-1 min-w-[180px] p-6">
            <p class="text-[#0d151c] text-base font-medium leading-normal">Total Employees</p>
            <p class="text-[#0d151c] text-2xl font-bold leading-tight mt-2">{{ number_format($totalEmployees, 0) }}</p>
        </div>
        <div class="card dashboard-card flex-1 min-w-[180px] p-6">
            <p class="text-[#0d151c] text-base font-medium leading-normal">Active Employees</p>
            <p class="text-[#0d151c] text-2xl font-bold leading-tight mt-2">{{ number_format($totalactiveemployees, 0) }}</p>
        </div>
        <div class="card dashboard-card magi flex-1 min-w-[180px] p-6">
            <p class="text-[#0d151c] text-base font-medium leading-normal">Magi Production</p>
            <p class="text-[#0d151c] text-2xl font-bold leading-tight mt-2">{{ number_format($magiProduction, 0) }} </p>
        </div>
        <div class="card dashboard-card papadam flex-1 min-w-[180px] p-6">
            <p class="text-[#0d151c] text-base font-medium leading-normal">Papadam Production</p>
            <p class="text-[#0d151c] text-2xl font-bold leading-tight mt-2">{{ number_format($papadamProduction, 0) }} </p>
        </div>
        <div class="card dashboard-card flex-1 min-w-[180px] p-6">
            <p class="text-[#0d151c] text-base font-medium leading-normal">Pending Salary</p>
            <p class="text-[#0d151c] text-2xl font-bold leading-tight mt-2">LKR {{ number_format($pendingSalary, 0) }}</p>
        </div>

        <div class="card dashboard-card flex-1 min-w-[180px] p-6">
            <p class="text-[#0d151c] text-base font-medium leading-normal">Total Loan Amount</p>
            <p class="text-[#0d151c] text-2xl font-bold leading-tight mt-2">LKR {{ number_format($totalloanamount, 0) }}</p>                 
    
             
                <span class="text-sm font-normal">  Total Loans : {{ number_format($totalloans, 0) }}</span>
            

        </div>
    </div>

    <h2 class="text-[#0d151c] text-2xl font-bold leading-tight tracking-[-0.015em] px-3 py-2">Recent Activity</h2>
    <div class="px-2 py-1">
        <div class="table-container overflow-hidden rounded-xl">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-3">Employee</th>
                        <th class="text-left px-4 py-3">Activity</th>
                        <th class="text-left px-4 py-3">Product</th>
                        <th class="text-left px-4 py-3">Quantity</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentActivities as $activity)
                        <tr>
                            <td class="px-4 py-3">{{ $activity['employee'] }}</td>
                            <td class="px-4 py-3">{{ $activity['activity'] }}</td>
                            <td class="px-4 py-3">{{ $activity['product'] }}</td>
                            <td class="px-4 py-3">{{ $activity['quantity'] }}</td>
                            <td class="px-4 py-3">
                                <span class="status-badge {{ 'status-' . strtolower($activity['status']) }}">
                                    {{ $activity['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-[#49749c]">{{ $activity['date'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500">No recent activities found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- <style>
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
    .status-badge {
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .status-completed {
        background-color: #d1fae5;
        color: #065f46;
    }
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    .status-in-progress {
        background-color: #dbeafe;
        color: #1e40af;
    }
    @media (max-width: 640px) {
        .table-container {
            width: 100%;
            overflow-x: auto;
        }
        table {
            min-width: 600px;
        }
    }
</style> --}}