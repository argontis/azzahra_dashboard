<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementRead extends Model
{
    public $timestamps = false;

    protected $table = 'announcement_reads';

    protected $fillable = [
        'announcement_id',
        'kry_kode',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class, 'announcement_id');
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'kry_kode', 'kry_kode');
    }
}
