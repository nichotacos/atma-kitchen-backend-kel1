<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('units')->insert([
            ['nama_unit' => 'gram',
            'created_at' => now(),
            'updated_at' => now(),],
            ['nama_unit' => 'butir',
            'created_at' => now(),
            'updated_at' => now(),],
            ['nama_unit' => 'ml',
            'created_at' => now(),
            'updated_at' => now(),],
            ['nama_unit' => 'buah',
            'created_at' => now(),
            'updated_at' => now(),],
        ]);
    }
}
