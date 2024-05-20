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
            [
                'nama_kategori' => 'Cake',
                'gambar_kategori' => 'cake-category.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Roti',
                'gambar_kategori' => 'bread-category.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Minuman',
                'gambar_kategori' => 'drinks-category.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Titipan',
                'gambar_kategori' => 'other-category.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
