<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UkuranProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ukuran_produks')->insert([
            ["detail_ukuran" => 'Per Loyang'],
            ["detail_ukuran" => 'Per 1/2 Loyang'],
            ["detail_ukuran" => 'Per Box'],
            ["detail_ukuran" => 'Per Liter'],
            ["detail_ukuran" => 'Per Bungkus'],
        ]);
    }
}
