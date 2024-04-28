<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BahanBaku extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_bahan_baku';

    public $timestamps = true;

    protected $fillable = [
        'id_unit',
        'nama_bahan_baku',
        'stok_bahan_baku'
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    public function detailResep(): HasMany
    {
        return $this->hasMany(DetailResep::class, 'id_detail_resep');
    }
}
