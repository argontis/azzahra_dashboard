<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'sender_nama',
        'sender_level',
        'target_role',
        'judul',
        'isi',
    ];

    /**
     * Cek apakah pesan ini sudah dibaca oleh karyawan tertentu.
     */
    public function isReadBy(int $kryKode): bool
    {
        return MessageRead::where('message_id', $this->id)
            ->where('reader_id', $kryKode)
            ->exists();
    }
}
