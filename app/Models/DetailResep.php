<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DetailResep extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $primaryKey = 'id_detail_resep';

    protected $fillable = [
        'id_bahan_baku',
        'jumlah'
    ];

    public function produk(): BelongsToMany
    {
        return $this->belongsToMany(Produk::class, 'id_produk');
    }

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }
}
