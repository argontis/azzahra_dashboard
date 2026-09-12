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
        'tdkn_ket',
        'tdkn_tanggal',
        'tdkn_jam',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->tdkn_tanggal)) {
                $model->tdkn_tanggal = date('Y-m-d');
            }
            if (empty($model->tdkn_jam)) {
                $model->tdkn_jam = date('H:i:s');
            }
        });
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'trans_kode', 'trans_kode');
    }
}
