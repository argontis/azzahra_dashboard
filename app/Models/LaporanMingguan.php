<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanMingguan extends Model
{
    protected $table = 'laporan_mingguan';

    protected $primaryKey = 'laporan_id';

    protected $fillable = [
        'id_karyawan', 'nama_karyawan', 'posisi', 'periode',
        'target_mingguan', 'tugas_dilakukan', 'hasil', 'kendala', 'solusi',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'kry_kode');
    }
}
