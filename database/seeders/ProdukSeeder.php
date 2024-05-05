<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('produks')->insert([
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 850000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 2,
                'id_kategori' => 1,
                'id_kemasan' => 1,
                'id_penitip' => 6,
                'id_ukuran' => 1,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Lapis Legit',
                'stok' => 0 //karna PO
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 450000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 2,
                'id_kategori' => 1,
                'id_kemasan' => 2,
                'id_penitip' => 6,
                'id_ukuran' => 2,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Lapis Legit',
                'stok' => 0 //karna PO
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 550000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 2,
                'id_kategori' => 1,
                'id_kemasan' => 1,
                'id_penitip' => 6,
                'id_ukuran' => 1,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Lapis Surabaya',
                'stok' => 0 //karna PO
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 350000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 2,
                'id_kategori' => 1,
                'id_kemasan' => 2,
                'id_penitip' => 6,
                'id_ukuran' => 2,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Lapis Surabaya',
                'stok' => 0 //karna PO
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 250000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 2,
                'id_kategori' => 1,
                'id_kemasan' => 1,
                'id_penitip' => 6,
                'id_ukuran' => 1,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Brownies',
                'stok' => 0 //karna PO
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 150000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 2,
                'id_kategori' => 1,
                'id_kemasan' => 2,
                'id_penitip' => 6,
                'id_ukuran' => 2,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Brownies',
                'stok' => 0 //karna PO
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 450000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 2,
                'id_kategori' => 1,
                'id_kemasan' => 1,
                'id_penitip' => 6,
                'id_ukuran' => 1,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Mandarin',
                'stok' => 0 //karna PO
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 250000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 2,
                'id_kategori' => 1,
                'id_kemasan' => 2,
                'id_penitip' => 6,
                'id_ukuran' => 2,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Mandarin',
                'stok' => 0 //karna PO
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 350000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 2,
                'id_kategori' => 1,
                'id_kemasan' => 1,
                'id_penitip' => 6,
                'id_ukuran' => 1,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Spikoe',
                'stok' => 0 //karna PO
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 200000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 2,
                'id_kategori' => 1,
                'id_kemasan' => 2,
                'id_penitip' => 6,
                'id_ukuran' => 2,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Spikoe',
                'stok' => 0 //karna PO
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 180000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 1,
                'id_kategori' => 2,
                'id_kemasan' => 3,
                'id_penitip' => 6,
                'id_ukuran' => 3,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Roti Sosis',
                'stok' => 20
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 120000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 1,
                'id_kategori' => 2,
                'id_kemasan' => 3,
                'id_penitip' => 6,
                'id_ukuran' => 3,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Milk Bun',
                'stok' => 20
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 150000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 1,
                'id_kategori' => 2,
                'id_kemasan' => 3,
                'id_penitip' => 6,
                'id_ukuran' => 3,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Roti Keju',
                'stok' => 20
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 75000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 1,
                'id_kategori' => 3,
                'id_kemasan' => 4,
                'id_penitip' => 6,
                'id_ukuran' => 4,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Choco Creamy Latte',
                'stok' => 20
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 100000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 1,
                'id_kategori' => 3,
                'id_kemasan' => 4,
                'id_penitip' => 6,
                'id_ukuran' => 4,
                'kuota_harian' => 10, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Matcha Creamy Latte',
                'stok' => 20
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 75000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 1,
                'id_kategori' => 4,
                'id_kemasan' => 7,
                'id_penitip' => 1,
                'id_ukuran' => 5,
                'kuota_harian' => 0, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Kripik Kentang 250 gr',
                'stok' => 15
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 250000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 1,
                'id_kategori' => 4,
                'id_kemasan' => 7,
                'id_penitip' => 2,
                'id_ukuran' => 5,
                'kuota_harian' => 0, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Kopi Luwak Bubuk 250 gr',
                'stok' => 15
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 300000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 1,
                'id_kategori' => 4,
                'id_kemasan' => 7,
                'id_penitip' => 3,
                'id_ukuran' => 5,
                'kuota_harian' => 0, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Matcha Organik Bubuk 100 gr',
                'stok' => 15
            ],
            [
                'deskripsi_produk' => 'nanti diisi deh',
                'harga_produk' => 120000,
                'gambar_produk' => '56750061d41d399455453db13de972ae.jpg',
                'id_jenis_ketersediaan' => 1,
                'id_kategori' => 4,
                'id_kemasan' => 7,
                'id_penitip' => 4,
                'id_ukuran' => 5,
                'kuota_harian' => 0, //masih dummy, gatau ada di dokumen apa kaga
                'nama_produk' => 'Chocolate Bar 100 gr',
                'stok' => 15
            ],
        ]);
    }
}
