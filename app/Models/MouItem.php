<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouItem extends Model
{
    use HasFactory;

    protected $table = 'mou_items';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'mou_id',
        'item_no',
        'spesifikasi',
        'qty',
        'harga',
        'total',
    ];

    public function mou()
    {
        return $this->belongsTo(Mou::class, 'mou_id', 'mou_id');
    }
}
