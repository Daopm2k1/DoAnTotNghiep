<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCate extends Model
{
    //
    use SoftDeletes;
    protected $table = 'post_cate';
}
