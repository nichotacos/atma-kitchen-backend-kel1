<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_penggajian';

    protected $fillable = [
        'jumlah_hadir',
        'jumlah_bolos',
        'bonus',
        'total_gaji',
        'tanggal_penggajian',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
