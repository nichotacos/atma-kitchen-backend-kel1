<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengadaanBahanBaku extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pengadaan';

    public $timestamps = true;

    protected $fillable = [
        'jumlah_pengadaan',
        'harga_per_unit',
        'harga_total',
        'tanggal_pengadaan',
        'id_bahan_baku',
        'id_unit'
    ];

    public function bahan_baku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }
}
