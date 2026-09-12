<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Costomer extends Model
{
    protected $table = 'costomer';

    protected $primaryKey = 'id_costomer';

    public $incrementing = false;

    protected $keyType = 'string';
}
