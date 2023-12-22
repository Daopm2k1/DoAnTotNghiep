<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vanthao03596\HCVN\Models\Province;
use Vanthao03596\HCVN\Models\District;
use Vanthao03596\HCVN\Models\Ward;

class Address extends Model
{
    //
    use SoftDeletes;
    protected $table = 'addresses';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id', 'id');
    }
}
