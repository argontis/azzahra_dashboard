<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Announcement extends Model
{
    protected $table = 'announcements';

    protected $fillable = [
        'judul',
        'isi',
        'tipe',
        'mulai_pada',
        'selesai_pada',
        'created_by',
        'status',
    ];

    protected $casts = [
        'mulai_pada' => 'datetime',
        'selesai_pada' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'created_by', 'kry_kode');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(AnnouncementRead::class, 'announcement_id');
    }

    public function isReadBy(int|string $kryKode): bool
    {
        return $this->reads()->where('kry_kode', $kryKode)->exists();
    }
}
