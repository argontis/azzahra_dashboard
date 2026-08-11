<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KetersediaanSparepart extends Model
{
    protected $table = 'ketersediaan_sparepart';
    
    protected $fillable = [
        'trans_kode', 'cos_nama', 'barang_nama', 'ketersediaan', 'status'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'trans_kode', 'trans_kode');
    }
}
