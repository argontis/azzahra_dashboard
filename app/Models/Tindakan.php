<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    use HasFactory;

    protected $table = 'tindakan';
    protected $primaryKey = 'tdkn_kode';

    protected $fillable = [
        'trans_kode',
        'tdkn_barang',
        'tdkn_harga',
        'tdkn_qty',
        'tdkn_subtot',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'trans_kode', 'trans_kode');
    }
}
