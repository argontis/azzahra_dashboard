<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'costomer';

    protected $primaryKey = 'id_costomer';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_costomer',
        'cos_nama',
        'username',
        'password',
        'cos_alamat',
        'cos_hp',
        'cos_cabang',
        'cos_device',
        'cos_tipe',
        'cos_model',
        'cos_no_seri',
        'cos_asesoris',
        'cos_status',
        'cos_pswd',
        'cos_pswd_type',
        'cos_pswd_canvas',
        'cos_keluhan',
        'cos_keterangan',
        'cos_tgl_lahir',
        'cos_tanggal',
        'cos_jam',
        'cos_poin',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'cos_kode', 'id_costomer');
    }
}
