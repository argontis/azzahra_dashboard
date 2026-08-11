<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'trans_kode';

    protected $fillable = [
        'cos_kode',
        'kry_kode',
        'trans_status',
        'cos_tanggal',
        'cos_jam',
        'trans_discount',
        'trans_total',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cos_kode', 'id_costomer');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'kry_kode', 'kry_kode');
    }
}
