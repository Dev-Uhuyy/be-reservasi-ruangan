<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwals = [
            [
            'room_id' => 1,
            'date' => '2024-07-01',
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
            'status' => 'available',
            ],
            [
            'room_id' => 2,
            'date' => '2024-07-01',
            'start_time' => '12:00:00',
            'end_time' => '14:00:00',
            'status' => 'available',
            ],
            [
            'room_id' => 3,
            'date' => '2024-07-02',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'status' => 'booked',
            ],
            [
            'room_id' => 1,
            'date' => '2024-07-02',
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'status' => 'canceled',
            ],
            [
            'room_id' => 2,
            'date' => '2024-07-03',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'status' => 'canceled',
            ],
            [
            'room_id' => 3,
            'date' => '2024-07-03',
            'start_time' => '13:00:00',
            'end_time' => '15:00:00',
            'status' => 'available',
            ],
            [
            'room_id' => 1,
            'date' => '2024-07-04',
            'start_time' => '11:00:00',
            'end_time' => '13:00:00',
            'status' => 'booked',
            ],
            [
            'room_id' => 2,
            'date' => '2024-07-04',
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'status' => 'available',
            ],
            [
            'room_id' => 2,
            'date' => '2024-07-04',
            'start_time' => '15:00:00',
            'end_time' => '17:00:00',
            'status' => 'canceled',
            ],
            [
            'room_id' => 3,
            'date' => '2024-07-05',
            'start_time' => '09:30:00',
            'end_time' => '11:30:00',
            'status' => 'booked',
            ],
            [
            'room_id' => 1,
            'date' => '2024-07-05',
            'start_time' => '16:00:00',
            'end_time' => '18:00:00',
            'status' => 'available',
            ],
        ];

        foreach($jadwals as $jadwal){
            Schedule::create($jadwal);
        }

        $this->command->comment('Jadwal seeder has been executed successfully.');
    }
}
