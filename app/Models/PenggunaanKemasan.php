<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenggunaanKemasan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_penggunaan_kemasan';

    public $timestamps = true;

    protected $fillable = [
        'jumlah_penggunaan',
        'tanggal_penggunaan',
        'id_kemasan',
    ];
}
