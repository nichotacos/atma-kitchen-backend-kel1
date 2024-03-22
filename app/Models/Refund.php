<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_refund';

    protected $fillable = [
        'nama_bank_tujuan',
        'no_rekening_tujuan',
        'nominal_refund',
        'tanggal_refund',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
}
