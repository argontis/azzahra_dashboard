<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiReturn extends Model
{
    use HasFactory;

    protected $table = 'transaksi_return';
    protected $primaryKey = 'ret_kode';

    protected $fillable = [
        'trans_kode',
        'dtl_kode',
        'ret_jml',
        'ret_tanggal',
        'ret_jam',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'trans_kode', 'trans_kode');
    }

    public function transaksiDetail()
    {
        return $this->belongsTo(TransaksiDetail::class, 'dtl_kode', 'dtl_kode');
    }
}
