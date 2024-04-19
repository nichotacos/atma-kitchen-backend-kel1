<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoPoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('promo_poins')->insert([
                ['batas_kelipatan' => '10000',
                'poin_diterima'   => '1',
                'created_at' => now(),
                'updated_at' => now(),],
                ['batas_kelipatan' => '100000',
                'poin_diterima'   => '15',
                'created_at' => now(),
                'updated_at' => now(),],
                ['batas_kelipatan' => '500000',
                'poin_diterima'   => '75',
                'created_at' => now(),
                'updated_at' => now(),],
                ['batas_kelipatan' => '1000000',
                'poin_diterima'   => '200',
                'created_at' => now(),
                'updated_at' => now(),]
            ]
        );
    }
}
