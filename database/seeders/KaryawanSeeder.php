<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('karyawans')->insert([
            [
                'id_role' => 1,
                'nama_karyawan' => 'Ahmad Sudirman',
                'nomor_telepon_karyawan' => '081234567890',
                'email' => 'ahmad@example.com',
                'username' => 'ahmad_sudirman',
                'password' => 'password123',
                'tanggal_rekrut' => '2019-04-10',
                'gaji_harian' => 0,
                'bonus_rajin' => 0,
            ],
            [
                'id_role' => 2,
                'nama_karyawan' => 'Siti Nurhaliza',
                'nomor_telepon_karyawan' => '085678901234',
                'email' => 'siti@example.com',
                'username' => 'siti_nurhaliza',
                'password' => 'siti123',
                'tanggal_rekrut' => '2022-03-15',
                'gaji_harian' => 80000,
                'bonus_rajin' => 50000,
            ],
            [
                'id_role' => 3,
                'nama_karyawan' => 'Dewi Purnama',
                'nomor_telepon_karyawan' => '081112223344',
                'email' => 'dewi@example.com',
                'username' => 'dewi_purnama',
                'password' => 'dewi123',
                'tanggal_rekrut' => '2023-05-20',
                'gaji_harian' => 120000,
                'bonus_rajin' => 75000,
            ],
            [
                'id_role' => 4,
                'nama_karyawan' => 'Agus Pratama',
                'nomor_telepon_karyawan' => '081334455667',
                'email' => 'agus@example.com',
                'username' => 'agus_pratama',
                'password' => 'agus123',
                'tanggal_rekrut' => '2023-08-10',
                'gaji_harian' => 160000,
                'bonus_rajin' => 100000,
            ],
            [
                'id_role' => 4,
                'nama_karyawan' => 'Rina Setiawan',
                'nomor_telepon_karyawan' => '081556677889',
                'email' => 'rina@example.com',
                'username' => 'rina_setiawan',
                'password' => 'rina123',
                'tanggal_rekrut' => '2024-01-05',
                'gaji_harian' => 160000,
                'bonus_rajin' => 100000,
            ],
        ]);
    }
}
