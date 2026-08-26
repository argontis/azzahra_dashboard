<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    protected $table = 'arsip';

    protected $primaryKey = 'arsip_id';

    protected $fillable = [
        'tipe', 'nama', 'tanggal', 'no_hp', 'tipe_detail', 'kerusakan', 'alamat',
    ];
}
