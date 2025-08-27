<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingSalary extends Model
{
    use HasFactory;

    protected $table = 'packing_salary';

    protected $fillable = [
        'employee_id',
        'date_packed',
        'product_id',
        'quentity',
        'session',
        'salary',
        'adjusment',
        'total_salary',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }

    public function product()
    {
        return $this->belongsTo(PackingProduct::class, 'product_id');
    }
}
