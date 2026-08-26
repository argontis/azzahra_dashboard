<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPartApproval extends Model
{
    protected $primaryKey = 'approval_id';

    protected $fillable = [
        'trans_kode',
        'type',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'rejected_at',
        'supplier_id',
        'lead_time',
        'note',
    ];

    public function order()
    {
        return $this->belongsTo(OrderList::class, 'trans_kode', 'trans_kode');
    }

    public function approver()
    {
        return $this->belongsTo(Karyawan::class, 'approved_by', 'kry_id'); // Assuming Karyawan primary key is kry_id or similar
    }
}
