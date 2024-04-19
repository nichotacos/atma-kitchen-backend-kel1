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
                'harga_total_cart' => 910000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'harga_total_cart' => 500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'harga_total_cart' => 1450000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'harga_total_cart' => 650000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'harga_total_cart' => 325000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
