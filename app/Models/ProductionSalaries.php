<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSalaries extends Model
{
    use HasFactory;

    protected $primaryKey = 'production_id';

    protected $fillable = [
        'employee_id', 'work_type', 'category', 'quantity', 'per_rate',
        'additional_salary', 'bonus', 'allowance', 'total_salary',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }
}
