<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    //
    use SoftDeletes;
    protected $table = 'order_details';
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function product_detail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id', 'id')->with(['product','color','size']);
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }
}
