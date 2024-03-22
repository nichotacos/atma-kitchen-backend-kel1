<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengadaanBahanBaku extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pengadaan';

    protected $fillable = [
        'jumlah_pengadaan',
        'harga_per_unit',
        'harga_total',
        'tanggal_pengadaan'
    ];

    public function bahan_baku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }

    public function unit(): HasMany
    {
        return $this->hasMany(Unit::class, 'id_unit');
    }
}
