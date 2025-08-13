<div class="p-2 bg-gray-50 min-h-screen">
    <div class="mx-auto bg-white rounded-xl shadow-md">
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-800">
                {{ ucfirst(str_replace('_', ' ', $form['date_filter'])) }} Attendance
            </h2>
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <form wire:submit.prevent="import" enctype="multipart/form-data"
                    class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                    <input type="file" wire:model="file" accept=".xls,.xlsx" class="text-sm text-gray-700 
                            file:mr-4 file:py-2 file:px-3
                            file:rounded-md file:border-0
                            file:text-sm file:font-medium
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100
                            border border-gray-300 rounded-lg shadow-sm w-full">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 whitespace-nowrap">
                        Import
                    </button>
                    @error('file')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </form>
                <div class="flex gap-2 w-full md:w-auto">
                    <select wire:model="form.date_filter" wire:change="$refresh"
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-auto">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="this_week">This Week</option>
                        <option value="last_week">Last Week</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                    </select>
                    <!-- Replace the search input with this -->
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or ID..."
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full">
                </div>
            </div>
        </div>


        @if (session('message'))
        <div class="p-4 bg-green-100 text-green-700 rounded-lg mx-4 mb-4">
            {{ session('message') }}
        </div>
        @endif
        @if (session('error'))
        <div class="p-4 bg-red-100 text-red-700 rounded-lg mx-4 mb-4">
            {{ session('error') }}
        </div>
        @endif

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
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                    @if (in_array($form['date_filter'], ['this_week', 'last_week', 'this_month', 'last_month']))
                    {{-- Multi-day view - show all attendance records --}}
                    @forelse ($employee->attendances as $attendance)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @if($employee->photo && Storage::disk('public')->exists($employee->photo))
                            <img class="w-10 h-10 rounded-full object-cover"
                                src="{{ asset('storage/' . $employee->photo) }}"
                                alt="{{ $employee->fname ?? 'Unknown' }}"
                                onerror="this.src='https://via.placeholder.com/40'">
                            @else
                            <img class="w-10 h-10 rounded-full object-cover" src="https://via.placeholder.com/40"
                                alt="No Photo">
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $employee->fname . ' ' . $employee->lname }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '--' }}
                            <div class="text-xs text-gray-400">
                                {{ $attendance->date->format('D, M d') }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            @if($attendance->break_start)
                            {{ $attendance->break_start ? $attendance->break_start->format('H:i') : '--' }} -
                            {{ $attendance->break_end ? $attendance->break_end->format('H:i') : '--' }}
                            @else
                            --
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '--' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $attendance->time_worked ? number_format($attendance->time_worked, 1) . ' hrs' : '--' }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                            $status = $attendance->status ?? 'absent';
                            $statusClass = match ($status) {
                            'present' => 'bg-green-100 text-green-600',
                            'late' => 'bg-yellow-100 text-yellow-600',
                            'early' => 'bg-blue-100 text-blue-600',
                            'leave', 'absent', null => 'bg-red-100 text-red-600',
                            default => 'bg-gray-100 text-gray-600',
                            };
                            @endphp

                            <div class="relative w-max">
                                <select wire:model="statusUpdates.{{ $attendance->attendance_id }}"
                                    wire:change="updateStatus({{ $attendance->attendance_id }}, {{ $employee->emp_id }})"
                                    class="text-xs font-semibold rounded-full px-3 py-1 appearance-none cursor-pointer {{ $statusClass }}"
                                    style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1em; background-image: none;">
                                    <option value="{{ $status }}" selected>{{ ucfirst($status) }}</option>
                                    <option value="absent" class="text-red-600">Absent</option>
                                    <option value="present" class="text-green-600">Present</option>
                                    <option value="late" class="text-yellow-600">Late</option>
                                    <option value="early" class="text-blue-600">Early</option>
                                    <option value="leave" class="text-red-600">Leave</option>
                                </select>

                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                    <svg class="h-3 w-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.5 7l4.5 4 4.5-4H5.5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @if($employee->photo && Storage::disk('public')->exists($employee->photo))
                            <img class="w-10 h-10 rounded-full object-cover"
                                src="{{ asset('storage/' . $employee->photo) }}"
                                alt="{{ $employee->fname ?? 'Unknown' }}"
                                onerror="this.src='https://via.placeholder.com/40'">
                            @else
                            <img class="w-10 h-10 rounded-full object-cover" src="https://via.placeholder.com/40"
                                alt="No Photo">
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $employee->fname . ' ' . $employee->lname }}
                        </td>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                            No attendance records for {{ str_replace('_', ' ', $form['date_filter']) }}
                        </td>
                    </tr>
                    @endforelse
                    @else
                    {{-- Single-day view --}}
                    @php
                    $attendance = $employee->attendances->first();
                    @endphp
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @if($employee->photo && Storage::disk('public')->exists($employee->photo))
                            <img class="w-10 h-10 rounded-full object-cover"
                                src="{{ asset('storage/' . $employee->photo) }}"
                                alt="{{ $employee->fname ?? 'Unknown' }}"
                                onerror="this.src='https://via.placeholder.com/40'">
                            @else
                            <img class="w-10 h-10 rounded-full object-cover" src="https://via.placeholder.com/40"
                                alt="No Photo">
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $employee->fname . ' ' . $employee->lname }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '--' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            @if($attendance && $attendance->break_start)
                            {{ $attendance->break_start ? $attendance->break_start->format('H:i') : '--' }} -
                            {{ $attendance->break_end ? $attendance->break_end->format('H:i') : '--' }}
                            @else
                            --
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '--' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $attendance && $attendance->time_worked ? number_format($attendance->time_worked, 1) . '
                            hrs' : '--' }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                            $status = $attendance->status ?? 'absent';
                            $statusClass = match ($status) {
                            'present' => 'bg-green-100 text-green-600',
                            'late' => 'bg-yellow-100 text-yellow-600',
                            'early' => 'bg-blue-100 text-blue-600',
                            'leave', 'absent', null => 'bg-red-100 text-red-600',
                            default => 'bg-gray-100 text-gray-600',
                            };
                            @endphp

                            <div class="relative w-max">
                                <select
                                    wire:model="statusUpdates.{{ $attendance ? $attendance->attendance_id : 'new_' . $employee->emp_id }}"
                                    wire:change="updateStatus({{ $attendance ? $attendance->attendance_id : 'null' }}, {{ $employee->emp_id }})"
                                    class="text-xs font-semibold rounded-full px-3 py-1 appearance-none cursor-pointer {{ $statusClass }}"
                                    style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1em; background-image: none;">
                                    <option value="{{ $status }}" selected>{{ ucfirst($status) }}</option>
                                    <option value="absent" class="text-red-600">Absent</option>
                                    <option value="present" class="text-green-600">Present</option>
                                    <option value="late" class="text-yellow-600">Late</option>
                                    <option value="early" class="text-blue-600">Early</option>
                                    <option value="leave" class="text-red-600">Leave</option>
                                </select>

                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                    <svg class="h-3 w-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.5 7l4.5 4 4.5-4H5.5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-gray-500">No employees found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col md:flex-row items-center justify-between p-4 gap-2">
            <span class="text-sm text-gray-500">
                Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }}
                entries
            </span>
            <div class="inline-flex -space-x-px text-sm">
                <button wire:click="previousPage" wire:loading.attr="disabled"
                    class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 {{ $employees->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ $employees->onFirstPage() ? 'disabled' : '' }}>
                    Prev
                </button>
                <span class="px-3 py-2 leading-tight text-blue-600 bg-blue-50 border border-gray-300">
                    {{ $employees->currentPage() }}
                </span>
                <button wire:click="nextPage" wire:loading.attr="disabled"
                    class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 {{ $employees->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}"
                    {{ $employees->hasMorePages() ? '' : 'disabled' }}>
                    Next
                </button>
            </div>
        </div>
    </div>
</div>