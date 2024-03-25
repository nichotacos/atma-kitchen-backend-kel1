<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPengambilanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_pengambilans')->insert([
            ["detail_pengambilan" => "Delivery"],
            ["detail_pengambilan" => "Pick-Up"],
        ]);
    }
}
