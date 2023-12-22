<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class Slide extends Model
{
    //
    use SoftDeletes;
    protected $table = 'slides';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}