<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPengeluaran extends Model
{
    use HasFactory;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $primaryKey = [
        'id_pengeluaran',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'detail_jenis_pengeluaran',
    ];

    public function pengeluaran(): HasMany
    {
        return $this->hasMany(Pengeluaran::class);
    }

    //masih salah


}
