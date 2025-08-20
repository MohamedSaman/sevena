<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'employee_id',
        'fingerprint_id',
        'date',
        'check_in',
        'break_start',
        'break_end',
        'check_out',
        'time_worked',
        'status',
        'late_hours', 
        'over_time', // Added for over time tracking
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'break_start' => 'datetime:H:i',
        'break_end' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }
}