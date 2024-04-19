<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nama_role' => 'Owner',
            'created_at' => now(),
            'updated_at' => now(),],
            ['nama_role' => 'Karyawan',
            'created_at' => now(),
            'updated_at' => now(),],
            ['nama_role' => 'Admin',
            'created_at' => now(),
            'updated_at' => now(),],
            ['nama_role' => 'Manager Operasional',
            'created_at' => now(),
            'updated_at' => now(),],
        ]);
    }
}
