<?php

namespace Database\Seeders;

use App\Models\JenisKetersediaan;
use App\Models\JenisPengeluaran;
use App\Models\Karyawan;
use App\Models\Penggajian;
use App\Models\PenggunaanBahanBaku;
use App\Models\Presensi;
use App\Models\ProdukHampers;
use App\Models\UkuranProduk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            JenisPengeluaranSeeder::class,
            JenisPengambilanSeeder::class,
            JenisKetersediaanSeeder::class,
            KemasanSeeder::class,
            KategoriSeeder::class,
            UkuranProdukSeeder::class,
            PenitipSeeder::class,
            StatusSeeder::class,
            RoleSeeder::class,
            CustomerSeeder::class,
            AlamatSeeder::class,
            KaryawanSeeder::class,
            UnitSeeder::class,
            BahanBakuSeeder::class,
            PengadaanBahanBakuSeeder::class,
            ProdukSeeder::class,
            DetailResepSeeder::class,
            HampersSeeder::class,
            ProdukHampersSeeder::class,
            CartSeeder::class,
            DetailCartSeeder::class,
            TransaksiSeeder::class,
            PengeluaranSeeder::class,
            PenggajianSeeder::class,
            PresensiSeeder::class,
            RefundSeeder::class,
            PromoPoinSeeder::class,
            PenggunaanBahanBakuSeeder::class
        ]);
    }
}
