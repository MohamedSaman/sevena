<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingProduct extends Model
{
    use HasFactory;

    protected $table = 'packing_product';

    protected $fillable = [
        'product_name',
        'per_rate',
        'date',
    ];

    public function salaries()
    {
        return $this->hasMany(PackingSalary::class, 'product_id');
    }
}
