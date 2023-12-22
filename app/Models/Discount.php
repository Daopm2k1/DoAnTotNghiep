<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;
use App\Models\Category;
use App\User;

class Discount extends Model
{
    //
    use SoftDeletes;
    protected $table = 'discounts';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function userApply()
    {
        return $this->belongsTo(User::class, 'user_apply', 'id');
    }
    
    public function display_name_type()
    {
        if($this->type == "product")
        {
            $product = Product::whereNull('deleted_at')->where('id',$this->type_id)->first();
            return $product->name ?? ''; 
        }
        $category = Category::whereNull('deleted_at')->where('id',$this->type_id)->first();
        return $category->name ?? '';
    }
    public function getItemDiscount()
    {
        $list_id = json_decode($this->apply);
        if($this->type == "product")
        {
            $product = Product::whereNull('deleted_at')->whereIn('id',$list_id)->get();
            return $product;
        }
        $category = Category::whereNull('deleted_at')->whereIn('id',$list_id)->get();
        return $category;
    }
}
