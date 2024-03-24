<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $primaryKey = [
        'id_produk',
    ];

    protected $fillable = [
        'nama_produk',
        'deskripsi_produk',
        'kategori_produk',
        'harga_produk',
        'ukuran_produk',
        'password_karyawan',
        'stok',
        'kuota_harian',
        'id_penitip',
        'id_pengambilan',
        'id_kategori',
        'id_ukuran',
        'id_kemasan',
    ];
    
}
