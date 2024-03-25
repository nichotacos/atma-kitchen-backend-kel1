<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengadaanBahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengadaan_bahan_bakus')->insert([
            [
                'id_bahan_baku' => 2,
                'id_unit' => 1,
                'jumlah_pengadaan' => 2000,
                'harga_per_unit' => 30,
                'harga_total' => 2000 * 30,
                'tanggal_pengadaan' => '2023-11-07'
            ],
            [
                'id_bahan_baku' => 6,
                'id_unit' => 1,
                'jumlah_pengadaan' => 2000,
                'harga_per_unit' => 40,
                'harga_total' => 2000 * 40,
                'tanggal_pengadaan' => '2024-01-21'
            ],
            [
                'id_bahan_baku' => 7,
                'id_unit' => 1,
                'jumlah_pengadaan' => 1200,
                'harga_per_unit' => 20,
                'harga_total' => 1200 * 20,
                'tanggal_pengadaan' => '2024-02-01'
            ],
            [
                'id_bahan_baku' => 11,
                'id_unit' => 3,
                'jumlah_pengadaan' => 1100,
                'harga_per_unit' => 35,
                'harga_total' => 1100 * 35,
                'tanggal_pengadaan' => '2024-02-01'
            ],
            [
                'id_bahan_baku' => 3,
                'id_unit' => 2,
                'jumlah_pengadaan' => 23,
                'harga_per_unit' => 2500,
                'harga_total' => 23 * 2500,
                'tanggal_pengadaan' => '2024-02-06'
            ],
        ]);
    }
}
