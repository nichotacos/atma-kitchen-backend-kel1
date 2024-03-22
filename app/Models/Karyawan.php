<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_karyawan',
        'nomor_telepon_karyawan',
        'email',
        'username',
        'password',
        'tanggal_rekrut',
        'gaji_harian',
        'bonus_rajin',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}
