<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title("Attendance Management")]
#[Layout("components.layouts.staff")]
class AttendanceManagement extends Component
{
    use WithPagination;

    public $employees;
    public $form = [
        'date_filter' => 'today',
        'search' => '',
    ];
    public $editMode = false;
    public $editForm = [
        'attendance_id' => '',
        'employee_id' => '',
        'date' => '',
        'check_in' => '',
        'break_start' => '',
        'break_end' => '',
        'check_out' => '',
        'time_worked' => '',
        'status' => 'present',
    ];
    public $perPage = 8;

    public function mount()
    {
        $this->employees = Employee::select('emp_id', 'fname')->get();
    }

    public function updated($property)
    {
        if (in_array($property, ['form.date_filter', 'form.search'])) {
            $this->resetPage();
        }
    }

    public function editAttendance($attendanceId)
    {
        $attendance = Attendance::find($attendanceId);
        if ($attendance) {
            $this->editMode = true;
            $this->editForm = [
                'attendance_id' => $attendance->attendance_id,
                'employee_id' => $attendance->employee_id,
                'date' => $attendance->date,
                'check_in' => $attendance->check_in,
                'break_start' => $attendance->break_start,
                'break_end' => $attendance->break_end,
                'check_out' => $attendance->check_out,
                'time_worked' => $attendance->time_worked,
                'status' => $attendance->status,
            ];
        }
    }

    public function saveAttendance()
    {
        $this->validate([
            'editForm.employee_id' => 'required|exists:employees,emp_id',
            'editForm.date' => 'required|date|before_or_equal:' . Carbon::now()->format('Y-m-d'),
            'editForm.check_in' => 'nullable|date_format:H:i',
            'editForm.break_start' => 'nullable|date_format:H:i|after:editForm.check_in',
            'editForm.break_end' => 'nullable|date_format:H:i|after:editForm.break_start',
            'editForm.check_out' => 'nullable|date_format:H:i|after:editForm.break_end',
            'editForm.time_worked' => 'nullable|numeric|min:0|max:24',
            'editForm.status' => 'required|in:present,absent,late,leave',
        ]);

        $attendance = Attendance::find($this->editForm['attendance_id']);
        if ($attendance) {
            $attendance->update([
                'employee_id' => $this->editForm['employee_id'],
                'date' => $this->editForm['date'],
                'check_in' => $this->editForm['check_in'],
                'break_start' => $this->editForm['break_start'],
                'break_end' => $this->editForm['break_end'],
                'check_out' => $this->editForm['check_out'],
                'time_worked' => $this->editForm['time_worked'] ?: null,
                'status' => $this->editForm['status'],
            ]);
            $this->editMode = false;
            $this->reset('editForm');
            session()->flash('message', 'Attendance updated successfully.');
        }
    }

    public function cancelEdit()
    {
        $this->editMode = false;
        $this->reset('editForm');
    }

    public function getAttendanceRecordsProperty()
    {
        $query = Attendance::with(['employee' => function ($query) {
            $query->select('emp_id', 'fname');
        }]);

        $today = Carbon::now('Asia/Colombo'); // Use Colombo time zone (+0530)
        switch ($this->form['date_filter']) {
            case 'yesterday':
                $query->whereDate('date', $today->subDay()->format('Y-m-d'));
                break;
            case 'this_week':
                $query->whereBetween('date', [$today->startOfWeek()->format('Y-m-d'), $today->endOfWeek()->format('Y-m-d')]);
                break;
            case 'today':
            default:
                $query->whereDate('date', $today->format('Y-m-d'));
                break;
        }

        if (!empty($this->form['search'])) {
            $query->whereHas('employee', function ($q) {
                $q->where('fname', 'like', '%' . $this->form['search'] . '%');
            });
        }

        return $query->orderBy('date', 'desc')->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.staff.attendance-management', [
            'attendanceRecords' => $this->attendanceRecords,
        ]);
    }
}