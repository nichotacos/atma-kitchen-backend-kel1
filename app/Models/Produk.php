<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'harga_produk',
        'stok',
        'kuota_harian',
    ];

    public function DetailResep(): BelongsToMany
    {
        return $this->belongsToMany(DetailResep::class, 'id_detail_resep');
    }

    public function Hampers(): BelongsToMany
    {
        return $this->belongsToMany(Hampers::class, 'id_hampers');
    }
}
