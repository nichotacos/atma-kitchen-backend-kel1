<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenggunaanBahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('penggunaan_bahan_bakus')->insert([
            [
                'id_bahan_baku' => 1,
                'id_transaksi' => 1,
                'id_unit' => 1,
                'jumlah_penggunaan' => 600,
                'tanggal_penggunaan' => '2024-01-11 00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_bahan_baku' => 2,
                'id_transaksi' => 1,
                'id_unit' => 1,
                'jumlah_penggunaan' => 50,
                'tanggal_penggunaan' => '2024-01-11 00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_bahan_baku' => 3,
                'id_transaksi' => 1,
                'id_unit' => 2,
                'jumlah_penggunaan' => 43,
                'tanggal_penggunaan' => '2024-01-11 00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_bahan_baku' => 4,
                'id_transaksi' => 1,
                'id_unit' => 1,
                'jumlah_penggunaan' => 360,
                'tanggal_penggunaan' => '2024-01-11 00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_bahan_baku' => 6,
                'id_transaksi' => 1,
                'id_unit' => 1,
                'jumlah_penggunaan' => 600,
                'tanggal_penggunaan' => '2024-01-11 00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_bahan_baku' => 5,
                'id_transaksi' => 1,
                'id_unit' => 1,
                'jumlah_penggunaan' => 100,
                'tanggal_penggunaan' => '2024-01-11 00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_bahan_baku' => 7,
                'id_transaksi' => 1,
                'id_unit' => 1,
                'jumlah_penggunaan' => 12,
                'tanggal_penggunaan' => '2024-01-11 00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_bahan_baku' => 8,
                'id_transaksi' => 1,
                'id_unit' => 1,
                'jumlah_penggunaan' => 25,
                'tanggal_penggunaan' => '2024-01-11 00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_bahan_baku' => 9,
                'id_transaksi' => 1,
                'id_unit' => 1,
                'jumlah_penggunaan' => 100,
                'tanggal_penggunaan' => '2024-01-11 00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_bahan_baku' => 15,
                'id_transaksi' => 1,
                'id_unit' => 3,
                'jumlah_penggunaan' => 300,
                'tanggal_penggunaan' => '2024-01-11 00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_bahan_baku' => 20,
                'id_transaksi' => 1,
                'id_unit' => 4,
                'jumlah_penggunaan' => 20,
                'tanggal_penggunaan' => '2024-01-11 00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
