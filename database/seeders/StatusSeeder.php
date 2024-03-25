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
            ['detail_status' => 'Belum dibayar'],
            ['detail_status' => 'Sudah dibayar'],
            ['detail_status' => 'Batal'],
            ['detail_status' => 'Pembayaran valid'],
            ['detail_status' => 'Diterima'],
            ['detail_status' => 'Ditolak'],
            ['detail_status' => 'Diproses'],
            ['detail_status' => 'Siap di pick-up'],
            ['detail_status' => 'Diterima oleh kurir'],
            ['detail_status' => 'Sudah di pick-up'],
            ['detail_status' => 'Selesai'],
            ['detail_status' => 'Sedang diproses'],
            ['detail_status' => 'Sudah ditransfer']
        ]);
    }
}
