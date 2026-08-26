<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $table = 'kpi';

    protected $primaryKey = 'kpi_id';

    protected $fillable = [
        'id_karyawan', 'nama_karyawan', 'posisi', 'status_kerja',
        'siklus', 'periode', 'kedisiplinan', 'kualitas_kerja',
        'produktivitas', 'kerja_tim', 'total', 'rata_rata', 'kategori', 'catatan',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'kry_kode');
    }
}
