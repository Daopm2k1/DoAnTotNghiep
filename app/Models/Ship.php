<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vanthao03596\HCVN\Models\Province;

class Ship extends Model
{
    //
    use SoftDeletes;
    protected $table = 'ships';
    public function city()
    {
        return $this->belongsTo(Province::class, 'city_id', 'id');
    }
    // public function getCityName()
    // {
    //     // dd($this->city_id);
    //     $city = Province::first();
    //     dd($city);
    //     return $city->name;
    // }
}
