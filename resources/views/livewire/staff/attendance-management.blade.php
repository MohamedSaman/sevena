<div class="p-2 bg-gray-50 min-h-screen">
    <div class="mx-auto bg-white rounded-xl shadow-md">
        <div class="p-4 flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-800">
                {{ ucfirst(str_replace('_', ' ', $form['date_filter'])) }}'s Attendance
            </h2>
            <div class="flex items-center space-x-2">
                <select wire:model="form.date_filter" class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="this_week">This Week</option>
                </select>
                <input type="text" wire:model.debounce.300ms="form.search" placeholder="Search by name..." class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-500">
                <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3">Photo</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Check In</th>
                        <th class="px-4 py-3">Break</th>
                        <th class="px-4 py-3">Check Out</th>
                        <th class="px-4 py-3">Time Worked</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendanceRecords as $record)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <img class="w-10 h-10 rounded-full object-cover" src="{{ $record->employee ? 'https://randomuser.me/api/portraits/men/' . rand(1, 99) . '.jpg' : 'https://via.placeholder.com/40' }}" alt="{{ $record->employee->fname ?? 'Unknown' }}">
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $record->employee ? $record->employee->fname : 'Unknown' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $record->check_in ?? '--' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $record->break_start ? ($record->break_start . ' - ' . ($record->break_end ?? '--')) : '--' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $record->check_out ?? '--' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $record->time_worked ? number_format($record->time_worked, 1) . ' hrs' : '--' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="{
                                        'bg-red-100 text-red-600': ['absent', 'leave'].includes('{{ $record->status }}'),
                                        'bg-yellow-100 text-yellow-600': '{{ $record->status }}' === 'late',
                                        'bg-green-100 text-green-600': '{{ $record->status }}' === 'present'
                                    }">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="#" wire:click.prevent="editAttendance({{ $record->attendance_id }})" class="text-blue-600 hover:underline text-xs">Attendance</a>
                                <button wire:click.prevent="editAttendance({{ $record->attendance_id }})" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l4 4 8-8m-5-5a2.828 2.828 0 014 4l-8 8-4-4-8 8V7l8-8z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-3 text-center text-gray-500">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between p-4">
            <span class="text-sm text-gray-500">Showing {{ $attendanceRecords->firstItem() ?? 0 }} to {{ $attendanceRecords->lastItem() ?? 0 }} of {{ $attendanceRecords->total() ?? 0 }} entries</span>
            <div class="inline-flex -space-x-px text-sm">
                <button wire:click="previousPage" wire:loading.attr="disabled" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 {{ $attendanceRecords->onFirstPage() ? 'cursor-not-allowed' : '' }}">Prev</button>
                <span class="px-3 py-2 leading-tight text-blue-600 bg-blue-50 border border-gray-300">{{ $attendanceRecords->currentPage() }}</span>
                <button wire:click="nextPage" wire:loading.attr="disabled" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 {{ $attendanceRecords->onLastPage() ? 'cursor-not-allowed' : '' }}">Next</button>
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