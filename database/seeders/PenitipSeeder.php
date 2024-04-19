<?php

namespace Database\Seeders;

use App\Models\Penitip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenitipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penitip::factory()->count(5)->create();
        DB::table('penitips')->insert([
            'nama_penitip' => 'Tanpa Penitip',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
