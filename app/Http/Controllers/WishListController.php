<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class WishListController extends Controller
{
    public function save_wishlist(){
        $product_id = $request->productid_hidden;
        $product = DB::table('products')
        ->join('wishlists','products.id','wishlists.product_id')
        ->where('products.id',$product_id)->first();

        return Redirect::to('/show-wishlist');
    }
    public function show_wishlist(){
        $cate = DB::table('categories')->get(); 
        return view('user.show_wishlist')->with('categories',$cate);
    }
}
