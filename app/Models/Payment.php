<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    //
    use SoftDeletes;
    protected $table = 'payments';
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function ship()
    {
        return $this->belongsTo(Ship::class, 'ship_id', 'id');
    }
}
