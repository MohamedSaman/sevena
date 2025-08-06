<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_id';

    protected $fillable = [
        'empCode', 'fingerprint_id', 'photo', 'fname', 'lname', 'gender', 'dob',
        'nic', 'email', 'phone', 'address', 'department', 'designation',
        'salary_type', 'basic_salary', 'joining_date', 'status',
    ];

    // Relationships
    public function productionSalaries()
    {
        return $this->hasMany(ProductionSalaries::class, 'employee_id', 'emp_id');
    }

    public function salaries()
    {
        return $this->hasMany(Salaries::class, 'employee_id', 'emp_id');
    }

    public function loans()
    {
        return $this->hasMany(Loans::class, 'employee_id', 'emp_id');
    }

    public function workLogs()
    {
        return $this->hasMany(WorkLogs::class, 'employee_id', 'emp_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'emp_id');
    }

}
