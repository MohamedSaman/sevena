<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkTypeRate;

class WorkTypeRateSeeder extends Seeder
{
   public function run()
{
    $rates = [
        ['work_type' => 'cutter', 'magi_rate' => 40, 'papadam_rate' => 45],
        ['work_type' => 'roller', 'magi_rate' => 30, 'papadam_rate' => 35],
        ['work_type' => 'dryer', 'magi_rate' => 50, 'papadam_rate' => 55],
        ['work_type' => 'packer', 'magi_rate' => 80, 'papadam_rate' => 85],
        ['work_type' => 'worker', 'magi_rate' => 80, 'papadam_rate' => 85],
    ];

    foreach ($rates as $rate) {
        WorkTypeRate::create($rate);
    }
}
}