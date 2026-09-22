<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    use HasFactory;

    protected $table = 'tindakan';

    protected $primaryKey = 'tdkn_kode';

    public $timestamps = true;

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
            if (! isset($model->tdkn_ket) || $model->tdkn_ket === null) {
                $model->tdkn_ket = '-';
            }
            if (! isset($model->tdkn_harga) || $model->tdkn_harga === null) {
                $model->tdkn_harga = 0;
            }
            if (! isset($model->tdkn_subtot) || $model->tdkn_subtot === null) {
                $model->tdkn_subtot = 0;
            }
            if (! isset($model->tdkn_qty) || $model->tdkn_qty === null) {
                $model->tdkn_qty = 1;
            }
        });
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'trans_kode', 'trans_kode');
    }
}
