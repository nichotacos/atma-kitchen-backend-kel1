<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('carts')->insert([
            [
                'harga_total_cart' => 910000
            ],
            [
                'harga_total_cart' => 500000
            ],
            [
                'harga_total_cart' => 1450000
            ],
            [
                'harga_total_cart' => 650000
            ],
            [
                'harga_total_cart' => 325000
            ],
        ]);
    }
}
