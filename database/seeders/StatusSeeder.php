<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([
            ['detail_status' => 'Belum dibayar',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Sudah dibayar',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Batal',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Pembayaran valid',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Diterima',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Ditolak',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Diproses',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Siap di pick-up',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Diterima oleh kurir',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Sudah di pick-up',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Selesai',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Sedang diproses',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_status' => 'Sudah ditransfer',
            'created_at' => now(),
            'updated_at' => now(),]
        ]);
    }
}
