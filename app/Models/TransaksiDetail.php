<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $table = 'transaksi_detail';

    protected $primaryKey = 'dtl_kode';

    public $timestamps = false;

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
        'xendit_invoice_id',
        'xendit_invoice_url',
        'xendit_status',
        'xendit_payment_method',
        'xendit_paid_at',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'trans_kode', 'trans_kode');
    }
}
