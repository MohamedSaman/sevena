<?php

namespace Database\Seeders;

use Database\Seeders\WorkTypeRateSeeder as SeedersWorkTypeRateSeeder;
use Illuminate\Database\Seeder;
use WorkTypeRateSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            AttendanceSeeder::class,
            SeedersWorkTypeRateSeeder::class, // This should be the seeder class, not the model
        ]);
    }
}