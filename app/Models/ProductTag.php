<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTag extends Model
{
    //
    use SoftDeletes;
    protected $table = 'product_tags';
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }
}