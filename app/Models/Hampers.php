<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class   Hampers extends Model
{
    use HasFactory;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_hampers';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'harga_hampers',
        'nama_hampers',
        'gambar_hampers',
        'id_kemasan'
    ];

    public function produk(): BelongsToMany
    {
        return $this->belongsToMany(Produk::class, 'produk_hampers', 'id_hampers', 'id_produk');
    }

    public function kemasan(): BelongsTo
    {
        return $this->belongsTo(Kemasan::class, 'id_kemasan');
    }
}
