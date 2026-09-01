<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageRead extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'message_id',
        'reader_id',
        'read_at',
    ];
}
