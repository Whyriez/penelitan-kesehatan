<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
                'nomor_telepon' => '080000000001',
                'institusi' => 'Administrator Sistem',
                'nomor_identitas' => 'ADMIN001',
                'gelar_jabatan' => 'Administrator',
                'department' => 'IT',
            ],
            [
                'name' => 'Operator',
                'email' => 'operator@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'operator',
                'nomor_telepon' => '080000000002',
                'institusi' => 'Layanan Operator',
                'nomor_identitas' => 'OPERATOR001',
                'gelar_jabatan' => 'Operator',
                'department' => 'Pelayanan',
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'user',
                'nomor_telepon' => '081234567890',
                'institusi' => 'Universitas Contoh',
                'nomor_identitas' => '123456789',
                'gelar_jabatan' => 'Mahasiswa',
                'department' => 'Teknik Informatika',
            ],
        ]);
    }
}
