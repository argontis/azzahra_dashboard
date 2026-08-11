<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable
{
    use Notifiable;

    protected $table = 'karyawan';
    protected $primaryKey = 'kry_kode';

    protected $fillable = [
        'kry_username',
        'kry_pswd',
        'kry_nama',
        'kry_level',
        'kry_telp',
        'kry_join_date',
        'kry_status',
    ];

    protected $hidden = [
        'kry_pswd',
    ];

    public function getAuthPassword()
    {
        return $this->kry_pswd;
    }
}
