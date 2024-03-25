<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KemasanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kemasans')->insert([
            [
                'detail_kemasan' => 'Box 20x20 cm',
                'stok_kemasan' => 5
            ],
            [
                'detail_kemasan' => 'Box 20x10 cm',
                'stok_kemasan' => 4
            ],
            [
                'detail_kemasan' => 'Box 10x10 cm',
                'stok_kemasan' => 6
            ],
            [
                'detail_kemasan' => 'Botol 1 Liter',
                'stok_kemasan' => 5
            ],
            [
                'detail_kemasan' => 'Box premium & kartu ucapan',
                'stok_kemasan' => 5
            ],
            [
                'detail_kemasan' => 'Tas spundbound',
                'stok_kemasan' => 10
            ],
            [
                'detail_kemasan' => 'Tanpa kemasan',
                'stok_kemasan' => 0
            ],
        ]);
    }
}
