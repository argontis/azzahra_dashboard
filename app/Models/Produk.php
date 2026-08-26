<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $primaryKey = 'kode_barang';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'kode_barang',
        'nama_produk',
        'deskripsi',
        'harga',
        'gambar',
    ];
}
