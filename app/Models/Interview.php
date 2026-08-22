<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    // Menentukan nama tabel di database secara eksplisit
    protected $table = 'interviews';

    // Mendaftarkan kolom-kolom yang diizinkan untuk diisi data (Mass Assignment)
    protected $fillable = [
        'nama_kandidat',
        'posisi',
        'tanggal_waktu',
        'catatan',
        'status',
    ];
}
