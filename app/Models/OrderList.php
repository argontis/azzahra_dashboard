<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderList extends Model
{
    protected $table = 'order_list';
    
    protected $fillable = [
        'trans_kode', 'cos_kode', 'kry_kode', 'trans_total', 'trans_discount',
        'trans_tanggal', 'trans_status', 'merek', 'device', 'status_garansi',
        'seri', 'ket_keluhan', 'email', 'alamat'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'trans_kode', 'trans_kode');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cos_kode', 'id_costomer');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'kry_kode', 'kry_kode');
    }
}
