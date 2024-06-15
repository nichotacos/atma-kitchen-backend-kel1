<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

Carbon::setLocale('id');

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'nama' => 'Budi Raharjo',
                'nomor_telepon' => '085678901234',
                'email' => 'budi@example.com',
                'username' => 'budi88',
                'password' => Hash::make('budi123'),
                'tanggal_registrasi' => Carbon::now()->format('Y-m-d'),
                'tanggal_lahir' => '1988-03-22',
                'poin' => 0,
                'saldo' => 0,
                'fcm_token' => 'eGD1gHL3SeqKcnGfsavhU7:APA91bGlWVeGYXAysabsV6O9tTBf-8Vw24HTV-rlbWNoOIkl1i4GLq4N3MDfIjtlb97awUOkJ-KqL-2GvCaSknzzZjnmI2dhc4MDfv5lxI31iHVCIKlarjTEQBrCylfXn54wiSu3DA7q',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Cindy Wijaya',
                'nomor_telepon' => '081112223344',
                'email' => 'cindy@example.com',
                'username' => 'cindy89',
                'password' => Hash::make('cindy123'),
                'tanggal_registrasi' => Carbon::now()->format('Y-m-d'),
                'tanggal_lahir' => '1989-07-05',
                'poin' => 0,
                'saldo' => 0,
                'fcm_token' => 'eGD1gHL3SeqKcnGfsavhU7:APA91bGlWVeGYXAysabsV6O9tTBf-8Vw24HTV-rlbWNoOIkl1i4GLq4N3MDfIjtlb97awUOkJ-KqL-2GvCaSknzzZjnmI2dhc4MDfv5lxI31iHVCIKlarjTEQBrCylfXn54wiSu3DA7q',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dewi Cahyani',
                'nomor_telepon' => '081334455667',
                'email' => 'dewi@example.com',
                'username' => 'dewi90',
                'password' => Hash::make('dewi123'),
                'tanggal_registrasi' => Carbon::now()->format('Y-m-d'),
                'tanggal_lahir' => '1990-11-18',
                'poin' => 0,
                'saldo' => 0,
                'fcm_token' => 'eGD1gHL3SeqKcnGfsavhU7:APA91bGlWVeGYXAysabsV6O9tTBf-8Vw24HTV-rlbWNoOIkl1i4GLq4N3MDfIjtlb97awUOkJ-KqL-2GvCaSknzzZjnmI2dhc4MDfv5lxI31iHVCIKlarjTEQBrCylfXn54wiSu3DA7q',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Eka Sari',
                'nomor_telepon' => '081556677889',
                'email' => 'eka@example.com',
                'username' => 'eka91',
                'password' => Hash::make('eka123'),
                'tanggal_registrasi' => Carbon::now()->format('Y-m-d'),
                'tanggal_lahir' => '1991-04-30',
                'poin' => 0,
                'saldo' => 0,
                'fcm_token' => 'eGD1gHL3SeqKcnGfsavhU7:APA91bGlWVeGYXAysabsV6O9tTBf-8Vw24HTV-rlbWNoOIkl1i4GLq4N3MDfIjtlb97awUOkJ-KqL-2GvCaSknzzZjnmI2dhc4MDfv5lxI31iHVCIKlarjTEQBrCylfXn54wiSu3DA7q',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Fani Lestari',
                'nomor_telepon' => '081778899001',
                'email' => 'fani@example.com',
                'username' => 'fani92',
                'password' => Hash::make('fani123'),
                'tanggal_registrasi' => Carbon::now()->format('Y-m-d'),
                'tanggal_lahir' => '1992-10-15',
                'poin' => 0,
                'saldo' => 0,
                'fcm_token' => 'eGD1gHL3SeqKcnGfsavhU7:APA91bGlWVeGYXAysabsV6O9tTBf-8Vw24HTV-rlbWNoOIkl1i4GLq4N3MDfIjtlb97awUOkJ-KqL-2GvCaSknzzZjnmI2dhc4MDfv5lxI31iHVCIKlarjTEQBrCylfXn54wiSu3DA7q',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
