<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DetailResep extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $primaryKey = 'id_detail_resep';

    protected $fillable = [
        'id_bahan_baku',
        'jumlah'
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
