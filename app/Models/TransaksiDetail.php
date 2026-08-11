<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $table = 'transaksi_detail';
    protected $primaryKey = 'dtl_kode';
    
    protected $fillable = [
        'trans_kode',
        'kry_kode',
        'dtl_jml_bayar',
        'dtl_jenis_bayar',
        'dtl_bank',
        'dtl_status',
        'dtl_tanggal',
        'dtl_jam',
        'dtl_stt_stor',
        'dtl_payment_method',
        'dtl_transfer_status',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'trans_kode', 'trans_kode');
    }
}
