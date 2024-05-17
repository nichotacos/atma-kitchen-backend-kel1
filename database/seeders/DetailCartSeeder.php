<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailCartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('detail_carts')->insert([
            [
                'id_hampers' => null,
                'id_produk' => 3,
                'id_cart' => 1,
                'id_jenis_ketersediaan' => 2,
                'jumlah_produk' => 1,
                'harga_produk_terkini' => 550000,
                'harga_total_terkini' => 550000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => null,
                'id_produk' => 11,
                'id_cart' => 1,
                'id_jenis_ketersediaan' => 2,
                'jumlah_produk' => 2,
                'harga_produk_terkini' => 180000,
                'harga_total_terkini' => 360000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => 2,
                'id_produk' => null,
                'id_cart' => 2,
                'id_jenis_ketersediaan' => 2,
                'jumlah_produk' => 1,
                'harga_produk_terkini' => 500000,
                'harga_total_terkini' => 500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => null,
                'id_produk' => 1,
                'id_cart' => 3,
                'id_jenis_ketersediaan' => 2,
                'jumlah_produk' => 1,
                'harga_produk_terkini' => 850000,
                'harga_total_terkini' => 850000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => null,
                'id_produk' => 4,
                'id_cart' => 3,
                'id_jenis_ketersediaan' => 2,
                'jumlah_produk' => 2,
                'harga_produk_terkini' => 300000,
                'harga_total_terkini' => 600000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => 1,
                'id_produk' => null,
                'id_cart' => 4,
                'id_jenis_ketersediaan' => 2,
                'jumlah_produk' => 1,
                'harga_produk_terkini' => 650000,
                'harga_total_terkini' => 650000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => null,
                'id_produk' => 16,
                'id_cart' => 5,
                'id_jenis_ketersediaan' => 1,
                'jumlah_produk' => 1,
                'harga_produk_terkini' => 75000,
                'harga_total_terkini' => 75000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => null,
                'id_produk' => 17,
                'id_cart' => 5,
                'id_jenis_ketersediaan' => 1,
                'jumlah_produk' => 1,
                'harga_produk_terkini' => 250000,
                'harga_total_terkini' => 250000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
