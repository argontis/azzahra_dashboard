<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $primaryKey = 'voucher_id';

    protected $fillable = [
        'voucher_code',
        'description',
        'discount_percent',
        'start_date',
        'end_date',
        'max_usage',
        'voucher_gambar',
        'status',
    ];
}
