<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pencatatan extends Model
{
    protected $table = 'pencatatan';

    protected $primaryKey = 'pencatatan_id';

    protected $fillable = [
        'batch_id', 'nama_barang', 'qty', 'harga_satuan',
        'total', 'tanggal', 'gambar', 'kategori_global',
    ];
}
