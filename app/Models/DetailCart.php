<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailCart extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_detail_cart';

    public $timestamps = true;

    protected $fillable = [
        'jumlah_produk',
        'harga_produk_terkini',
        'harga_total_terkini',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function hampers(): BelongsTo
    {
        return $this->belongsTo(Hampers::class, 'id_hampers');
    }

    public function JenisKetersediaan(): BelongsTo
    {
        return $this->belongsTo(JenisKetersediaan::class, 'id_jenis_ketersediaan');
    }
}
