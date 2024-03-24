<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DetailResep extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_detail_resep';

    protected $fillable = [
        'jumlah'
    ];

    public function produk(): BelongsToMany
    {
        return $this->belongsToMany(Produk::class, 'id_produk');
    }
}
