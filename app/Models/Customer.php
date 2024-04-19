<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasApiTokens, Notifiable, CanResetPassword;

    protected $primaryKey = 'id_customer';

    public $timestamps = true;

    protected $fillable = [
        'nama',
        'nomor_telepon',
        'email',
        'username',
        'password',
        'tanggal_registrasi',
        'tanggal_lahir',
        'poin',
        'saldo'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $cast = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];

    public function alamat(): HasMany
    {
        return $this->hasMany(Alamat::class, 'id_alamat');
    }

    public function refund(): HasMany
    {
        return $this->hasMany(Refund::class, 'id_refund');
    }
}
