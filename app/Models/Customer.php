<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $primaryKey = 'id_customer';

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

    public function alamat(): HasMany
    {
        return $this->hasMany(Alamat::class, 'id_alamat');
    }

    public function refund(): HasMany
    {
        return $this->hasMany(Refund::class, 'id_refund');
    }
}
