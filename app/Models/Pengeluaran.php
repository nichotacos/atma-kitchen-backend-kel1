<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_pengeluaran',
        'nominal_pengeluaran',
    ];

    public function jenis_pengeluaran(): BelongsTo
    {
        return $this->belongsTo(JenisPengeluaran::class, 'id_jenis_pengeluaran');
    }
}
