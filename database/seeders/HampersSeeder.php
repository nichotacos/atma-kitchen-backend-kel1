<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HampersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hampers')->insert([
            [
                'id_kemasan' => 5,
                'harga_hampers' => 650000,
                'nama_hampers' => 'Paket A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kemasan' => 5,
                'harga_hampers' => 500000,
                'nama_hampers' => 'Paket B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kemasan' => 5,
                'harga_hampers' => 350000,
                'nama_hampers' => 'Paket C',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
