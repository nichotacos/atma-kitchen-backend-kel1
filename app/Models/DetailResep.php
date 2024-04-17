<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailResep extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $primaryKey = 'id_detail_resep';

    protected $fillable = [
        'id_bahan_baku',
        'jumlah'
    ];

    public function produk(): BelongsToMany
    {
        return $this->belongsToMany(Produk::class, 'id_produk');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }
}
