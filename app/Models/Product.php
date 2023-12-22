<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ImageProduct;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Wishlist;
use Auth;

class Product extends Model
{
    //
    use SoftDeletes;
    protected $table = 'products';

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function info()
    {
        return $this->hasMany(ProductDetail::class, 'product_id', 'id');
    }
    public function images()
    {
        return $this->hasMany(ImageProduct::class, 'product_id', 'id');
    }
    public function wishlist()
    {
        return $this->hasMany(wishlist::class, 'product_id', 'id');
    }
    public function getImagePrimary()
    {
        $id = $this->id;
        $image = ImageProduct::where('product_id',$id)->where('is_primary',1)->first();
        
        if(empty($image))
        {
            return '/images/product/no-image-product.png';
        }

        return $image->path;
    }

    public function getExtraImage()
    {
        $id = $this->id;
        $image = ImageProduct::where('product_id',$id)->where('is_primary',0)->get();
        
        if(empty($image))
        {
            return;
        }

        return $image;
    }
    
    public function checkProductWishList($user_id = '')
    {
        if(empty($user_id))
        {
            return false;
        }

        $wishlist = Wishlist::where('user_id',$user_id)->where('product_id',$this->id)->whereNull('deleted_at')->first();
        if(empty($wishlist))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function checkItemComment()
    {
        if(!Auth::check())
        {
            return false;
        }

        $product_id = $this->id;

        $order = Order::where('email',Auth::user()->email)
                        ->whereNull('deleted_at')
                        ->whereHas('order_detail',function($query) use ($product_id) {
                            $query->whereHas('product_detail',function($query) use ($product_id)
                            {
                                $query->where('product_id',$product_id);
                            });
                        })
                        ->whereHas('payment',function($query) {
                            $query->where('status', 1);
                        })
                        ->first();
                        // dd($order);
        if(!empty($order))
        {
            return true;
        }
        return false;
        
    }
}
