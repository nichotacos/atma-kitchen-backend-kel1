<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisKetersediaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_ketersediaans')->insert([
            ["detail_ketersediaan" => "Ready Stock",
            'created_at' => now(),
            'updated_at' => now(),
            ],
            ["detail_ketersediaan" => "Pre-Order",
            'created_at' => now(),
            'updated_at' => now(),
            ]
        ]);
    }
}
