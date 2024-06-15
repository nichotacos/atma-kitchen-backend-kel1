<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenggunaanBahanBaku extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_penggunaan';

    public $timestamps = true;

    protected $fillable = [
        'jumlah_penggunaan',
        'tanggal_penggunaan',
        'id_bahan_baku',
        'id_unit',
        'id_transaksi'
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }
}
