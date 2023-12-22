<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageProduct extends Model
{
    //
    use SoftDeletes;
    protected $table = 'image_products';
}
