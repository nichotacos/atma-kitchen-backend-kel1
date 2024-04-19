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
            ['detail_jenis_pengeluaran' => 'Listrik',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_jenis_pengeluaran' => 'Gaji Karyawan',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_jenis_pengeluaran' => 'Bahan Baku',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_jenis_pengeluaran' => 'Iuran RT',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_jenis_pengeluaran' => 'Bensin',
            'created_at' => now(),
            'updated_at' => now(),],
            ['detail_jenis_pengeluaran' => 'Gas',
            'created_at' => now(),
            'updated_at' => now(),]
        ]);
    }
}
