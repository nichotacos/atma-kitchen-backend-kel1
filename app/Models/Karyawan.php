<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Karyawan extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    public $timestamps = true;

    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'id_role',
        'nama_karyawan',
        'nomor_telepon_karyawan',
        'email',
        'username',
        'password',
        'tanggal_rekrut',
        'gaji_harian',
        'bonus_rajin',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $cast = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }

    public function penggajian(): HasMany
    {
        return $this->hasMany(Penggajian::class);
    }
}
