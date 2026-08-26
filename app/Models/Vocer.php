<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocer extends Model
{
    use HasFactory;

    protected $table = 'vocer';

    protected $primaryKey = 'voc_kode';

    protected $fillable = [
        'trans_kode',
        'voc_jumlah',
        'voc_tanggal',
        'voc_jam',
        'voc_status',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'trans_kode', 'trans_kode');
    }
}
