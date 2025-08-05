<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salaries extends Model
{
    use HasFactory;

    protected $primaryKey = 'salary_id';

    protected $fillable = [
        'employee_id', 'salary_month', 'salary_type', 'basic_salary',
        'bonus', 'allowance', 'deductions', 'net_salary', 'payment_status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }

}
