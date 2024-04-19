<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transaksis')->insert([
            [
                //id =  1
                'bukti_pembayaran' => 'nota',
                'id_alamat' => 2,
                'id_cart' => 1,
                'id_customer' => 1,
                'id_pengambilan' => 1,
                'id_status' => 11,
                'jarak_pengiriman' => 30,
                'nominal_tip' => 0,
                'ongkos_kirim' => 25000,
                'perolehan_poin' => 138,
                'poin_digunakan' => 12,
                'tanggal_ambil' => '2024-01-17',
                'tanggal_pelunasan' => '2024-01-10',
                'tanggal_pemesanan' => '2024-01-10',
                'total_harga_final' => 933800,
                'total_harga_produk' => 910000,
                'total_setelah_ongkir' => 935000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                //id =  2
                'bukti_pembayaran' => 'nota2',
                'id_alamat' => 2,
                'id_cart' => 2,
                'id_customer' => 2,
                'id_pengambilan' => 1,
                'id_status' => 6,
                'jarak_pengiriman' => 0,
                'nominal_tip' => 0,
                'ongkos_kirim' => 0,
                'perolehan_poin' => 75,
                'poin_digunakan' => 0,
                'tanggal_ambil' => null,
                'tanggal_pelunasan' => '2024-02-17',
                'tanggal_pemesanan' => '2024-02-10',
                'total_harga_final' => 500000,
                'total_harga_produk' => 500000,
                'total_setelah_ongkir' => 500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                //id =  3
                'bukti_pembayaran' => 'nota3',
                'id_alamat' => null,
                'id_cart' => 3,
                'id_customer' => 3,
                'id_pengambilan' => 2,
                'id_status' => 6,
                'jarak_pengiriman' => 0,
                'nominal_tip' => 0,
                'ongkos_kirim' => 0,
                'perolehan_poin' => 235,
                'poin_digunakan' => 200,
                'tanggal_ambil' => null,
                'tanggal_pelunasan' => '2024-03-25',
                'tanggal_pemesanan' => '2024-03-18',
                'total_harga_final' => 1250000,
                'total_harga_produk' => 1450000,
                'total_setelah_ongkir' => 1450000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                //id =  3
                'bukti_pembayaran' => 'nota4',
                'id_alamat' => null,
                'id_cart' => 4,
                'id_customer' => 4,
                'id_pengambilan' => 2,
                'id_status' => 6,
                'jarak_pengiriman' => 0,
                'nominal_tip' => 0,
                'ongkos_kirim' => 0,
                'perolehan_poin' => 94,
                'poin_digunakan' => 50,
                'tanggal_ambil' => null,
                'tanggal_pelunasan' => '2024-03-25',
                'tanggal_pemesanan' => '2024-03-18',
                'total_harga_final' => 645000,
                'total_harga_produk' => 650000,
                'total_setelah_ongkir' => 650000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                //id =  3
                'bukti_pembayaran' => 'nota4',
                'id_alamat' => null,
                'id_cart' => 5,
                'id_customer' => 4,
                'id_pengambilan' => 2,
                'id_status' => 11,
                'jarak_pengiriman' => 0,
                'nominal_tip' => 0,
                'ongkos_kirim' => 0,
                'perolehan_poin' => 94,
                'poin_digunakan' => 50,
                'tanggal_ambil' => '2024-04-25',
                'tanggal_pelunasan' => '2024-04-25',
                'tanggal_pemesanan' => '2024-04-18',
                'total_harga_final' => 645000,
                'total_harga_produk' => 650000,
                'total_setelah_ongkir' => 650000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
