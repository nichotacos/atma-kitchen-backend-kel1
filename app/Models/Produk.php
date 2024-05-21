<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    use HasFactory;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_produk';

    public $timestamps = true;

    protected $fillable = [
        'nama_produk',
        'deskripsi_produk',
        'harga_produk',
        'stok',
        'kuota_harian',
        'gambar_produk',
        'id_jenis_ketersediaan',
        'id_ukuran',
        'id_kategori',
        'id_kemasan',
        'id_penitip',
        'is_deleted'
    ];

    public function DetailResep(): hasMany
    {
        return $this->hasMany(DetailResep::class, 'id_detail_resep');
    }

    public function Hampers(): BelongsToMany
    {
        return $this->belongsToMany(Produk::class, 'produk_hampers', 'id_hampers', 'id_produk');
    }

    public function JenisKetersediaan(): BelongsTo
    {
        return $this->belongsTo(JenisKetersediaan::class, 'id_jenis_ketersediaan');
    }

    public function UkuranProduk(): BelongsTo
    {
        return $this->belongsTo(UkuranProduk::class, 'id_ukuran');
    }

    public function Kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function Kemasan(): BelongsTo
    {
        return $this->belongsTo(Kemasan::class, 'id_kemasan');
    }

    public function Penitip(): BelongsTo
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
}
