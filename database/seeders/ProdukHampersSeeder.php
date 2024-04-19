<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukHampersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('produk_hampers')->insert([
            [
                'id_hampers' => 1,
                'id_produk' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => 1,
                'id_produk' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => 2,
                'id_produk' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => 2,
                'id_produk' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => 3,
                'id_produk' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hampers' => 3,
                'id_produk' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
