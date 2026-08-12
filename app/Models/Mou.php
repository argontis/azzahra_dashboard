<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mou extends Model
{
    use HasFactory;

    protected $table = 'mou';

    protected $primaryKey = 'mou_id';

    protected $fillable = [
        'file_name',
        'lokasi',
        'tanggal',
        'customer',
        'intro_text',
        'grand_total',
        'kry_kode',
    ];

    public function items()
    {
        return $this->hasMany(MouItem::class, 'mou_id', 'mou_id')->orderBy('item_no', 'asc');
    }
}
