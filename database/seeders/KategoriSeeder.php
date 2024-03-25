<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategoris')->insert([
            ['nama_kategori' => 'Cake'],
            ['nama_kategori' => 'Roti'],
            ['nama_kategori' => 'Minuman'],
            ['nama_kategori' => 'Titipan'],
        ]);
    }
}
