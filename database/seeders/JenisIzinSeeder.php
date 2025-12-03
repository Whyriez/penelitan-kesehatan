<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisIzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'nama' => 'SIP Dokter',
                'kategori' => 'Praktik Nakes',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'SIP FISIOTERAPIS',
                'kategori' => 'Praktik Nakes',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'SIP TTK',
                'kategori' => 'Praktik Nakes',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'SIPA (Surat Izin Praktik Apoteker)',
                'kategori' => 'Praktik Nakes',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'SIP Bidan',
                'kategori' => 'Praktik Nakes',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('jenis_izins')->insert($data);
    }
}
