<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Attendance;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

#[Title("Attendance Dashboard")]
#[Layout("components.layouts.admin")]
class AttendanceManagement extends Component
{
    use WithFileUploads, WithPagination;

    public $form = [
        'date_filter' => 'today',
        'search' => '',
    ];
    protected $paginationTheme = 'tailwind';

    public $file;
    public $statusUpdates = [];
    public $searchTerm;
    public $search = '';

    public function mount()
    {
        $this->statusUpdates = [];
    }

    public function updatedForm()
    {
        $this->resetPage();
    }

    protected function ensureAbsentRecords($date)
    {
        $employees = Employee::all();
        foreach ($employees as $employee) {
            $exists = Attendance::where('employee_id', $employee->emp_id)
                ->whereDate('date', $date)
                ->exists();

            if (!$exists) {
                Attendance::create([
                    'employee_id' => $employee->emp_id,
                    'fingerprint_id' => $employee->fingerprint_id,
                    'date' => $date,
                    'status' => 'absent',
                ]);
            }
        }
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xls,xlsx|max:2048',
        ]);

        Log::info('Starting Excel import', ['file' => $this->file->getClientOriginalName()]);

        $path = $this->file->store('temp', 'local');
        $fullPath = storage_path('app/' . $path);

        try {
            $successCount = 0;
            $failedCount = 0;

            Excel::import(new class($successCount, $failedCount) implements \Maatwebsite\Excel\Concerns\ToCollection, \Maatwebsite\Excel\Concerns\WithHeadingRow {
                public $successCount;
                public $failedCount;

                public function __construct(&$successCount, &$failedCount)
                {
                    $this->successCount = &$successCount;
                    $this->failedCount = &$failedCount;
                }

                public function collection(\Illuminate\Support\Collection $rows)
                {
                    Log::info('Processing Excel rows', ['rows_count' => $rows->count()]);
                    foreach ($rows as $row) {
                        Log::info('Processing row', $row->toArray());

                        $fingerprint_id = $row['user_id'];
                        $employee = Employee::where('fingerprint_id', $fingerprint_id)
                            ->orWhere('fingerprint_id', 'LIKE', 'FP001-' . $fingerprint_id)
                            ->first();

                        if (!$employee) {
                            Log::warning("Employee not found for fingerprint_id: {$fingerprint_id}");
                            $this->failedCount++;
                            continue;
                        }

                        $date = Carbon::createFromFormat('d-m-Y', $row['date'])->format('Y-m-d');
                        $check_in = $row['1'] ? Carbon::createFromFormat('H:i:s', $row['1'])->format('H:i') : null;
                        $break_start = $row['2'] ? Carbon::createFromFormat('H:i:s', $row['2'])->format('H:i') : null;
                        $break_end = $row['3'] ? Carbon::createFromFormat('H:i:s', $row['3'])->format('H:i') : null;
                        $check_out = $row['4'] ? Carbon::createFromFormat('H:i:s', $row['4'])->format('H:i') : null;

                        // Calculate time_worked
                        $time_worked = null;
                        if ($check_in && $check_out) {
                            $check_in_time = Carbon::parse($date . ' ' . $check_in);
                            $check_out_time = Carbon::parse($date . ' ' . $check_out);
                            $total_minutes = $check_out_time->diffInMinutes($check_in_time);

                            if ($break_start && $break_end) {
                                $break_start_time = Carbon::parse($date . ' ' . $break_start);
                                $break_end_time = Carbon::parse($date . ' ' . $break_end);
                                $break_minutes = $break_end_time->diffInMinutes($break_start_time);
                                $total_minutes -= $break_minutes;
                            }

                            $time_worked = $total_minutes / 60; // Convert to hours
                        }

                        // Determine status
                        $status = 'absent';
                        if ($check_in && $check_out) {
                            $check_in_time = Carbon::parse($check_in);
                            $check_out_time = Carbon::parse($check_out);
                            if ($check_in_time->gt(Carbon::parse('08:00'))) {
                                $status = 'late';
                            } elseif ($check_out_time->lt(Carbon::parse('17:00'))) {
                                $status = 'early';
                            } else {
                                $status = 'present';
                            }
                        }

                        Attendance::updateOrCreate(
                            [
                                'employee_id' => $employee->emp_id,
                                'date' => $date,
                            ],
                            [
                                'fingerprint_id' => $fingerprint_id,
                                'check_in' => $check_in,
                                'break_start' => $break_start,
                                'break_end' => $break_end,
                                'check_out' => $check_out,
                                'time_worked' => $time_worked,
                                'status' => $status,
                            ]
                        );

                        $this->successCount++;
                    }
                    Log::info('Import results', ['success' => $this->successCount, 'failed' => $this->failedCount]);
                }

                public function headingRow(): int
                {
                    return 1;
                }
            }, $fullPath);

            Storage::disk('local')->delete($path);

            if ($successCount > 0 && $failedCount > 0) {
                session()->flash('message', "$successCount Employee records added, $failedCount not found");
            } elseif ($successCount > 0) {
                session()->flash('message', "$successCount Employee Attendance records added successfully");
            } else {
                session()->flash('error', 'No attendance records added. ' . ($failedCount > 0 ? "$failedCount records failed due to missing employees." : 'Invalid data.'));
            }
        } catch (\Exception $e) {
            Log::error('Excel import failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to import attendance data: ' . $e->getMessage());
        }
    }

    public function updateStatus($attendanceId, $employeeId)
    {
        $status = $this->statusUpdates[$attendanceId ?? 'new_' . $employeeId] ?? null;
        if ($status && in_array($status, ['present', 'late', 'early', 'absent', 'leave'])) {
            if ($attendanceId) {
                Attendance::where('attendance_id', $attendanceId)->update(['status' => $status]);
            } else {
                $date = $this->getSelectedDate();
                Attendance::create([
                    'employee_id' => $employeeId,
                    'fingerprint_id' => Employee::find($employeeId)->fingerprint_id,
                    'date' => $date,
                    'status' => $status,
                ]);
            }
            session()->flash('message', 'Status updated successfully.');
        } else {
            session()->flash('error', 'Invalid status selected.');
        }
    }

    private function getSelectedDate()
    {
        $timezone = config('app.timezone', 'UTC');
        switch ($this->form['date_filter']) {
            case 'yesterday':
                return Carbon::yesterday($timezone)->format('Y-m-d');
            case 'this_week':
                return Carbon::now($timezone)->startOfWeek()->format('Y-m-d');
            case 'last_week':
                return Carbon::now($timezone)->subWeek()->startOfWeek()->format('Y-m-d');
            case 'this_month':
                return Carbon::now($timezone)->startOfMonth()->format('Y-m-d');
            case 'last_month':
                return Carbon::now($timezone)->subMonth()->startOfMonth()->format('Y-m-d');
            case 'today':
            default:
                return Carbon::today($timezone)->format('Y-m-d');
        }
    }

    // Change the render method
    public function render()
    {
        try {
            $dateFilter = $this->form['date_filter'] ?? 'today';

            // Determine date range based on filter
            $timezone = config('app.timezone', 'UTC');
            switch ($dateFilter) {
                case 'today':
                    $startDate = Carbon::today($timezone);
                    $endDate = Carbon::today($timezone);
                    break;
                case 'yesterday':
                    $startDate = Carbon::yesterday($timezone);
                    $endDate = Carbon::yesterday($timezone);
                    break;
                case 'this_week':
                    $startDate = Carbon::now($timezone)->startOfWeek();
                    $endDate = Carbon::now($timezone)->endOfWeek();
                    break;
                case 'last_week':
                    $startDate = Carbon::now($timezone)->subWeek()->startOfWeek();
                    $endDate = Carbon::now($timezone)->subWeek()->endOfWeek();
                    break;
                case 'this_month':
                    $startDate = Carbon::now($timezone)->startOfMonth();
                    $endDate = Carbon::now($timezone)->endOfMonth();
                    break;
                case 'last_month':
                    $startDate = Carbon::now($timezone)->subMonth()->startOfMonth();
                    $endDate = Carbon::now($timezone)->subMonth()->endOfMonth();
                    break;
                default:
                    $startDate = Carbon::today($timezone);
                    $endDate = Carbon::today($timezone);
                    break;
            }

            $isMultiDay = $startDate->ne($endDate);

            // Only ensure absent records for single-day filters
            if (!$isMultiDay) {
                $this->ensureAbsentRecords($startDate);
            }

            $query = Employee::query();

            if (!empty($this->search)) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('fname', 'like', $searchTerm)
                      ->orWhere('lname', 'like', $searchTerm)
                      ->orWhere('empCode', 'like', $searchTerm);
                });
            }

            // Load attendances based on date range
            $query->with(['attendances' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate])
                  ->orderBy('date', 'desc');
            }]);

            $employees = $query->paginate(10);

            return view('livewire.admin.attendance-management', [
                'employees' => $employees,
                'date_filter' => $dateFilter
            ]);
        } catch (\Throwable $e) {
            Log::error('Error rendering attendance management: ' . $e->getMessage());
            session()->flash('error', 'Unable to load attendance data.');
            return view('livewire.admin.attendance-management', [
                'employees' => collect(),
                'date_filter' => $this->form['date_filter'] ?? 'today'
            ]);
        }
    }
}