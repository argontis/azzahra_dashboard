<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'absensi_id';
    
    protected $fillable = [
        'tanggal', 'id_karyawan', 'nama_karyawan', 'posisi', 
        'status', 'jam_masuk', 'jam_pulang', 'keterangan'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'kry_kode');
    }
}
