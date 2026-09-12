<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable
{
    use Notifiable;

    protected $table = 'karyawan';

    protected $primaryKey = 'kry_kode';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'kry_kode',
        'kry_nik',
        'kry_username',
        'kry_pswd',
        'kry_nama',
        'kry_level',
        'kry_telp',
        'kry_alamat',
        'kry_join_date',
        'kry_status',
    ];

    protected static function booted()
    {
        static::creating(function ($karyawan) {
            if (empty($karyawan->kry_kode)) {
                $count = static::count() + 1;
                $candidate = 'K'.str_pad($count, 2, '0', STR_PAD_LEFT);
                while (static::where('kry_kode', $candidate)->exists()) {
                    $count++;
                    $candidate = 'K'.str_pad($count, 2, '0', STR_PAD_LEFT);
                }
                $karyawan->kry_kode = $candidate;
            }
        });
    }

    protected $hidden = [
        'kry_pswd',
    ];

    public function getAuthPasswordName()
    {
        return 'kry_pswd';
    }

    public function getAuthPassword()
    {
        return $this->kry_pswd;
    }
}
