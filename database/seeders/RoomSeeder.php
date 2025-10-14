<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Import Schema facade

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key constraint, hapus data, lalu aktifkan kembali
        Schema::disableForeignKeyConstraints();
        DB::table('rooms')->truncate();
        Schema::enableForeignKeyConstraints();

        $rooms = [];
        $facilitiesOptions = ['Proyektor', 'AC', 'Papan Tulis', 'Meja Rapat', 'Kursi', 'Sound System'];

        // Loop untuk setiap lantai dari 1 sampai 7
        for ($floor = 1; $floor <= 7; $floor++) {

            // Loop untuk setiap ruangan di lantai tersebut, dari 1 sampai 6
            for ($roomNumber = 1; $roomNumber <= 6; $roomNumber++) {

                // Pilih 2 atau 3 fasilitas secara acak untuk variasi
                $randomFacilitiesKeys = array_rand($facilitiesOptions, rand(2, 3));
                $selectedFacilities = [];
                foreach ($randomFacilitiesKeys as $key) {
                    $selectedFacilities[] = $facilitiesOptions[$key];
                }

                $rooms[] = [
                    'room_name' => 'H.' . $floor . '.' . $roomNumber,
                    'floor' => 'L' . $floor,
                    'capacity' => 30, // Kapasitas diatur pasti 30
                    'facilities' => implode(', ', $selectedFacilities), // Gabungkan fasilitas menjadi string
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Masukkan semua data yang sudah dibuat ke dalam database sekaligus
        DB::table('rooms')->insert($rooms);
    }
}

