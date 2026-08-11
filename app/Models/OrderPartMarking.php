<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPartMarking extends Model
{
    protected $fillable = [
        'trans_kode',
        'is_ordered',
        'rma_number',
        'end_warranty_date',
    ];

    public function order()
    {
        return $this->belongsTo(OrderList::class, 'trans_kode', 'trans_kode');
    }
}
