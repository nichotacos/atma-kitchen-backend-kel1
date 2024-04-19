<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bahan_bakus')->insert([
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Butter',
                'stok_bahan_baku' => 12500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Creamer',
                'stok_bahan_baku' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 2,
                'nama_bahan_baku' => 'Telur',
                'stok_bahan_baku' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Gula Pasir',
                'stok_bahan_baku' => 8200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Susu Bubuk',
                'stok_bahan_baku' => 6000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Tepung Terigu',
                'stok_bahan_baku' => 18000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Garam',
                'stok_bahan_baku' => 5500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Coklat Bubuk',
                'stok_bahan_baku' => 6000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Selai Strawberry',
                'stok_bahan_baku' => 5900,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Coklat Batang',
                'stok_bahan_baku' => 10500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 3,
                'nama_bahan_baku' => 'Minyak Goreng',
                'stok_bahan_baku' => 8600,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Tepung Maizena',
                'stok_bahan_baku' => 14200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Baking Powder',
                'stok_bahan_baku' => 3200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Kacang Kenari',
                'stok_bahan_baku' => 7300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 3,
                'nama_bahan_baku' => 'Susu Cair',
                'stok_bahan_baku' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 4,
                'nama_bahan_baku' => 'Sosis Blackpepper',
                'stok_bahan_baku' => 120,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Ragi',
                'stok_bahan_baku' => 1200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 3,
                'nama_bahan_baku' => 'Whipped Cream',
                'stok_bahan_baku' => 7300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 3,
                'nama_bahan_baku' => 'Susu Full Cream',
                'stok_bahan_baku' => 12100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Keju Mozzarella',
                'stok_bahan_baku' => 4700,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 1,
                'nama_bahan_baku' => 'Matcha Bubuk',
                'stok_bahan_baku' => 6900,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
