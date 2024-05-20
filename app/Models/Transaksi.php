<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaksi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_transaksi';

    public $timestamps = true;

    protected $fillable = [
        'tanggal_pemesanan',
        'tanggal_pelunasan',
        'tanggal_ambil',
        'total_harga_produk',
        'jarak_pengiriman',
        'ongkos_kirim',
        'total_setelah_ongkir',
        'poin_digunakan',
        'total_harga_final',
        'perolehan_poin',
        'nominal_tip',
        'bukti_pembayaran',
        'nomor_nota',
        'id_customer',
        'id_pengambilan',
        'id_cart',
        'id_status',
        'id_alamat',
    ];

    public function generateNomorNota()
    {
        $year = date('y');
        $month = date('m');
        return "{$year}-{$month}-{$this->id_transaksi}";
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'id_cart');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function jenisPengambilan(): BelongsTo
    {
        return $this->belongsTo(JenisPengambilan::class, 'id_pengambilan');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'id_status');
    }

    public function alamat(): BelongsTo
    {
        return $this->belongsTo(Alamat::class, 'id_alamat');
    }
}
