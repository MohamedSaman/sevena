<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendances = [
            // Attendance for August 05, 2025 (Today)
            [
                'employee_id' => 1,
                'fingerprint_id' => 'FP001-'.rand(1000, 9999),
                'date' => Carbon::today('Asia/Colombo')->format('Y-m-d'),
                'check_in' => '08:30',
                'break_start' => '12:00',
                'break_end' => '12:30',
                'check_out' => '17:00',
                'time_worked' => 7.5,
                'status' => 'present',
                'created_at' => Carbon::now('Asia/Colombo'),
                'updated_at' => Carbon::now('Asia/Colombo'),
            ],
            [
                'employee_id' => 2,
                'fingerprint_id' => 'FP002-'.rand(1000, 9999),
                'date' => Carbon::today('Asia/Colombo')->format('Y-m-d'),
                'check_in' => '09:00',
                'break_start' => '13:00',
                'break_end' => '13:30',
                'check_out' => null,
                'time_worked' => null,
                'status' => 'late',
                'created_at' => Carbon::now('Asia/Colombo'),
                'updated_at' => Carbon::now('Asia/Colombo'),
            ],
            [
                'employee_id' => 3,
                'fingerprint_id' => null,
                'date' => Carbon::today('Asia/Colombo')->format('Y-m-d'),
                'check_in' => null,
                'break_start' => null,
                'break_end' => null,
                'check_out' => null,
                'time_worked' => null,
                'status' => 'absent',
                'created_at' => Carbon::now('Asia/Colombo'),
                'updated_at' => Carbon::now('Asia/Colombo'),
            ],

            // Attendance for August 04, 2025 (Yesterday)
            [
                'employee_id' => 1,
                'fingerprint_id' => 'FP001-'.rand(1000, 9999),
                'date' => Carbon::yesterday('Asia/Colombo')->format('Y-m-d'),
                'check_in' => '08:45',
                'break_start' => '12:15',
                'break_end' => '12:45',
                'check_out' => '16:30',
                'time_worked' => 7.0,
                'status' => 'present',
                'created_at' => Carbon::yesterday('Asia/Colombo'),
                'updated_at' => Carbon::yesterday('Asia/Colombo'),
            ],
            [
                'employee_id' => 2,
                'fingerprint_id' => 'FP002-'.rand(1000, 9999),
                'date' => Carbon::yesterday('Asia/Colombo')->format('Y-m-d'),
                'check_in' => '10:00',
                'break_start' => '14:00',
                'break_end' => '14:30',
                'check_out' => '17:15',
                'time_worked' => 6.5,
                'status' => 'late',
                'created_at' => Carbon::yesterday('Asia/Colombo'),
                'updated_at' => Carbon::yesterday('Asia/Colombo'),
            ],
            [
                'employee_id' => 3,
                'fingerprint_id' => null,
                'date' => Carbon::yesterday('Asia/Colombo')->format('Y-m-d'),
                'check_in' => null,
                'break_start' => null,
                'break_end' => null,
                'check_out' => null,
                'time_worked' => null,
                'status' => 'leave',
                'created_at' => Carbon::yesterday('Asia/Colombo'),
                'updated_at' => Carbon::yesterday('Asia/Colombo'),
            ],
        ];

        foreach ($attendances as $attendance) {
            Attendance::create($attendance);
        }
    }
}