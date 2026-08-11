<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'costomer';
    protected $primaryKey = 'id_costomer';

    protected $fillable = [
        'cos_nama',
        'cos_alamat',
        'cos_hp',
        'cos_tipe',
        'cos_model',
        'cos_no_seri',
        'cos_asesoris',
        'cos_status',
        'cos_pswd',
        'cos_keluhan',
        'cos_keterangan',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'cos_kode', 'id_costomer');
    }
}
