<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_pengeluarans')->insert([
            ['detail_jenis_pengeluaran' => 'Listrik'],
            ['detail_jenis_pengeluaran' => 'Gaji Karyawan'],
            ['detail_jenis_pengeluaran' => 'Bahan Baku'],
            ['detail_jenis_pengeluaran' => 'Iuran RT'],
            ['detail_jenis_pengeluaran' => 'Bensin'],
            ['detail_jenis_pengeluaran' => 'Gas']
        ]);
    }
}
