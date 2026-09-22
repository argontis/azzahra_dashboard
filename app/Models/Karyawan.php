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

    public $timestamps = true;

    protected $guarded = [];

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
            if (empty($karyawan->kry_nik)) {
                $karyawan->kry_nik = 'NIK-'.($karyawan->kry_kode ?? uniqid());
            }
            if (empty($karyawan->kry_telp) && ! empty($karyawan->kry_tlp)) {
                $karyawan->kry_telp = $karyawan->kry_tlp;
            }
            if (empty($karyawan->kry_join_date) && ! empty($karyawan->kry_tgl_masuk)) {
                $karyawan->kry_join_date = $karyawan->kry_tgl_masuk;
            } elseif (empty($karyawan->kry_join_date)) {
                $karyawan->kry_join_date = date('Y-m-d');
            }
            if (! isset($karyawan->kry_status)) {
                $karyawan->kry_status = 1;
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

    public function getKryTelpAttribute(): ?string
    {
        return $this->attributes['kry_telp'] ?? $this->attributes['kry_tlp'] ?? null;
    }

    public function getKryJoinDateAttribute(): ?string
    {
        return $this->attributes['kry_join_date'] ?? $this->attributes['kry_tgl_masuk'] ?? null;
    }

    public function getKryStatusAttribute(): int
    {
        return isset($this->attributes['kry_status']) ? (int) $this->attributes['kry_status'] : 1;
    }
}
