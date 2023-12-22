<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetail extends Model
{
    //
    use SoftDeletes;
    protected $table = 'product_details';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }
}
