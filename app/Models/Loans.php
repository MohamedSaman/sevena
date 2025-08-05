<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loans extends Model
{
     use HasFactory;

    protected $primaryKey = 'loan_id';

    protected $fillable = [
        'employee_id', 'loan_amount', 'interest_rate', 'start_date',
        'term_month', 'remaining_balance', 'status', 'monthly_payment',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'emp_id');
    }
}
