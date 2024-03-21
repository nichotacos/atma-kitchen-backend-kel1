<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

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
}