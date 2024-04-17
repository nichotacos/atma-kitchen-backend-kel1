<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_role';

    public $timestamps = false;

    protected $fillable = [
        'nama_role',
    ];

    public function karyawan(): HasMany
    {
        return $this->hasMany(Karyawan::class);
    }
}
