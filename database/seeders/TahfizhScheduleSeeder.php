<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahfizhScheduleSeeder extends Seeder
{
    public function run()
    {
        $schedules = [];
        $days = [1, 2, 3, 4, 6]; // Senin, Selasa, Rabu, Kamis, Sabtu (Hari Normal)
        $friday = 5; // Jumat (Hari Pendek)

        // 1. Generate Jadwal Hari Normal (3 Sesi)
        foreach ($days as $day) {
            $schedules[] = [
                'session_name' => 'Qabla Shubuh',
                'day_of_week' => $day,
                'start_time' => '04:15:00', // Default awal
                'end_time' => '05:21:00',
                'order_index' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
            $schedules[] = [
                'session_name' => "Ba'da Shubuh",
                'day_of_week' => $day,
                'start_time' => '05:45:00',
                'end_time' => '06:50:00',
                'order_index' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ];
            $schedules[] = [
                'session_name' => 'Dhuha',
                'day_of_week' => $day,
                'start_time' => '10:00:00',
                'end_time' => '11:20:00',
                'order_index' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ];
        }

        // 2. Generate Jadwal Jumat (Cuma 2 Sesi)
        $schedules[] = [
            'session_name' => 'Qabla Shubuh',
            'day_of_week' => $friday,
            'start_time' => '04:15:00',
            'end_time' => '05:21:00',
            'order_index' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ];
        $schedules[] = [
            'session_name' => "Ba'da Shubuh",
            'day_of_week' => $friday,
            'start_time' => '05:45:00',
            'end_time' => '06:50:00',
            'order_index' => 2,
            'created_at' => now(), 'updated_at' => now(),
        ];

        DB::table('tahfizh_schedules')->insert($schedules);
    }
}