<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProductTag;

class Tag extends Model
{
    //
    use SoftDeletes;
    protected $table = 'tags';
    public function product_tag()
    {
        return $this->hasMany(ProductTag::class, 'tag_id', 'id');
    }
}