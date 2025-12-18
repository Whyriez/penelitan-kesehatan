<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
        $now = Carbon::now();

        DB::table('users')->insert([
            // 1. Akun Admin (Dinas Kesehatan / Admin Sistem)
            [
                'name' => 'Administrator Sistem',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123'), // Ganti password yang aman nanti
                'role' => 'admin',
                'nomor_telepon' => '081200000001',
                'institusi' => 'Dinas Kesehatan Prov. Gorontalo',
                'nomor_identitas' => 'ADMIN001',
                'gelar_jabatan' => 'Super Admin',
                'department' => 'IT & Data',
                'alamat' => 'Jl. Jendral Sudirman No. 1',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 2. Akun Operator (Verifikator Berkas - Opsional)
            [
                'name' => 'Operator Verifikator',
                'email' => 'operator@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'operator',
                'nomor_telepon' => '081200000002',
                'institusi' => 'Dinas Kesehatan Prov. Gorontalo',
                'nomor_identitas' => 'OPR001',
                'gelar_jabatan' => 'Staf SDK',
                'department' => 'Sumber Daya Kesehatan',
                'alamat' => 'Jl. Jendral Sudirman No. 1',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Akun Pengawas
            [
                'name' => 'Pengawas Dinas',
                'email' => 'pengawas@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'pengawas',
                'nomor_telepon' => '081200000003',
                'institusi' => 'Dinas Kesehatan Prov. Gorontalo',
                'nomor_identitas' => 'WAS001',
                'gelar_jabatan' => 'Kepala Bidang',
                'department' => 'Manajemen',
                'alamat' => 'Jl. Jendral Sudirman No. 1',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 3. Akun User (Mahasiswa Peneliti)
            [
                'name' => 'Pengguna1',
                'email' => 'user@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'user',
                'nomor_telepon' => '085212345678',
                'institusi' => 'Universitas Negeri Gorontalo',
                'nomor_identitas' => '531400000',
                'gelar_jabatan' => 'Mahasiswa',
                'department' => 'Teknik Informatika',
                'alamat' => 'Jl. Pangeran Hidayat',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
