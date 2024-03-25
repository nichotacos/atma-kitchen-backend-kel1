<?php

namespace Database\Seeders;

use App\Models\Penggajian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenggajianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penggajian::factory()->count(5)->create();
    }
}
