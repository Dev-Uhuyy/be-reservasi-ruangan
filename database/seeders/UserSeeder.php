<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@reservasi.com',
            'password' => Hash::make('admin123'),
            'floor' => 'Lantai 1',
            'nim_nip' => 'ADM001',
            'program' => 'Administrator',
            'profile_picture' => null,
        ]);
        $admin->assignRole('admin');

        // Mahasiswa
        $mahasiswa1 = User::create([
            'name' => 'Muhammad Rizki',
            'email' => 'rizki.muhammad@student.university.ac.id',
            'password' => Hash::make('mahasiswa123'),
            'floor' => 'Lantai 1',
            'nim_nip' => '2021001',
            'program' => 'Teknik Informatika',
            'profile_picture' => null,
        ]);
        $mahasiswa1->assignRole('student');

        $mahasiswa2 = User::create([
            'name' => 'Sari Dewi',
            'email' => 'sari.dewi@student.university.ac.id',
            'password' => Hash::make('mahasiswa123'),
            'floor' => 'Lantai 1',
            'nim_nip' => '2021002',
            'program' => 'Sistem Informasi',
            'profile_picture' => null,
        ]);
        $mahasiswa2->assignRole('student');

        $mahasiswa3 = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@student.university.ac.id',
            'password' => Hash::make('mahasiswa123'),
            'floor' => 'Lantai 1',
            'nim_nip' => '2021003',
            'program' => 'Teknik Komputer',
            'profile_picture' => null,
        ]);
        $mahasiswa3->assignRole('student');

        // Staff Administrasi
        $staff1 = User::create([
            'name' => 'Bambang Sutrisno',
            'email' => 'bambang.sutrisno@university.ac.id',
            'password' => Hash::make('staff123'),
            'floor' => 'Lantai 1',
            'nim_nip' => 'STF001',
            'program' => 'Administrasi',
            'profile_picture' => null,
        ]);
        $staff1->assignRole('staff');

        $staff2 = User::create([
            'name' => 'Rina Sari',
            'email' => 'rina.sari@university.ac.id',
            'password' => Hash::make('staff123'),
            'floor' => 'Lantai 2',
            'nim_nip' => 'STF002',
            'program' => 'Administrasi',
            'profile_picture' => null,
        ]);
        $staff2->assignRole('staff');

        // Test User untuk Login
        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'floor' => 'Lantai 1',
            'nim_nip' => 'TEST001',
            'program' => 'Test Program',
            'profile_picture' => null,
        ]);
        $testUser->assignRole('student');
    }
}