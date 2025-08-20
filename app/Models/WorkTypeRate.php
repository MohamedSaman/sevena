<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTypeRate extends Model
{
    use HasFactory;

    protected $table = 'worktype_rates'; // Explicitly set the table name

    protected $fillable = [
        'work_type',
        'magi_rate',
        'papadam_rate',
    ];

    // Add this to prevent any container-related issues
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}