<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::updateOrCreate(
            ['nip' => '061072'], // Kunci pencarian (NIP)
            [
                'name' => 'Admin1',
                'email' => 'admin@test.com',
                'password' => Hash::make('admin123'),
                'jenis_kelamin' => 'L',
                'status' => 'aktif',
                'role' => 'admin',
            ]
        );

        // Akun Hengky
        User::updateOrCreate(
            ['nip' => '061105'], 
            [
                'name' => 'hengky123',
                'email' => 'hengky123@test.com',
                'password' => Hash::make('gblk'),
                'jenis_kelamin' => 'L',
                'status' => 'aktif',
                'role' => 'pegawai',
            ]
        );

        // 2. Akun Pegawai Perulangan
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['nip' => '10000' . $i],
                [
                    'name' => 'Pegawai Ke-' . $i,
                    'email' => 'pegawai' . $i . '@test.com',
                    'password' => Hash::make('password123'),
                    'jenis_kelamin' => ($i % 2 == 0) ? 'P' : 'L',
                    'status' => 'aktif',
                    'role' => 'pegawai',
                ]
            );
        }
    }
}
