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
                'id_produk' => 2
            ],
            [
                'id_hampers' => 1,
                'id_produk' => 6
            ],
            [
                'id_hampers' => 2,
                'id_produk' => 4
            ],
            [
                'id_hampers' => 2,
                'id_produk' => 11
            ],
            [
                'id_hampers' => 3,
                'id_produk' => 10
            ],
            [
                'id_hampers' => 3,
                'id_produk' => 15
            ],
        ]);
    }
}
