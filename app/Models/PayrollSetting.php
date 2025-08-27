<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'epf_rate',
        'etf_rate',
        'tax_threshold',
        'tax_rate',
    ];
}