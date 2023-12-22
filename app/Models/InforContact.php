<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InforContact extends Model
{
    //
    use SoftDeletes;
    protected $table = 'infor_contact';
}
