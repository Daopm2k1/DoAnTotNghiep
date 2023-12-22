<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function checkout(){
        $category_product = DB::table('categories')->orderBy('category_id','desc')->get();
        return view('pages.checkout.show_checkout')->with('category_product',$category_product);
    }
    public function save_checkout(Request $request){
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_address'] = $request->shipping_address;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['shipping_note'] = $request->shipping_note;
        $shipping_id = DB::table('shipping')->insertGetId($data); //Lấy id của user vừa insert
        Session::put('shipping_id',$shipping_id);
        return Redirect::to('payment');
    }   
}
