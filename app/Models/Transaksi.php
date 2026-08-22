<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $primaryKey = 'trans_kode';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'trans_kode',
        'cos_kode',
        'kry_kode',
        'trans_status',
        'trans_tanggal',
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

    public function tindakan()
    {
        return $this->hasMany(Tindakan::class, 'trans_kode', 'trans_kode');
    }

    public function transaksi_details()
    {
        return $this->hasMany(TransaksiDetail::class, 'trans_kode', 'trans_kode');
    }

    public function order_list()
    {
        return $this->hasOne(OrderList::class, 'trans_kode', 'trans_kode');
    }
}
