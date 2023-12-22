<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use Cart;
use App\Models\ProductDetail;
use App\Models\Category;
use App\Models\PostCate;
use App\Models\InforContact;

class CartController extends Controller
{
    public function checkSaveCart($value,$message)
    {
        if(empty($value))
        {
            return response()->json([
                'error'=> true,
                'message'=>"Không tìm thấy ".$message,
                'data'=>null,
            ]);
        }
    }

    public function save_cart(Request $request){
        //Validate
        $product_id = $request->product_id;
        $color_id = $request->color;
        $size_id = $request->size;
        $quantity = $request->quantity;
        
        $this->checkSaveCart($product_id,'sản phẩm');
        $this->checkSaveCart($color_id,'màu sắc');
        $this->checkSaveCart($size_id,'kích thước');

        if(empty($quantity))
        {
            return response()->json([
                'error'=> true,
                'message'=>"Vui lòng nhập số lượng",
                'data'=>null,
            ]);
        }

        //Get item product
        $product = ProductDetail::where('deleted_at')
                                ->where('size_id',$size_id)
                                ->where('color_id',$color_id)
                                ->where('product_id',$product_id)
                                ->with(['product','color','size'])
                                ->first();
        //Check product
        if(empty($product))
        {
            return response()->json([
                'error'=> true,
                'message'=>"Không tìm thấy sản phẩm",
                'data'=>null,
            ]);
        }

        $data = [
            'id' => $product->id,
            'product'=>$product->product_id,
            'name' => $product->product->name,
            'qty' => $quantity,
            'price' => $product->product->price, 
            // 'weight' => 0,
            'options' => [ 
                'size' => [
                    'id' => $product->size->id,
                    'name'=>$product->size->name,
                ],
                'color' => [
                    'id' => $product->color->id,
                    'name'=>$product->color->name,
                ],
            ]    
        ];
        // Session::forget('cart');
        $session_cart = Session::get('cart');
        $array_product = [];
        $flag = false;

        if(!Session::has('cart') || count(Session::get('cart')) == 0)
        {
            $product_detail = ProductDetail::find($data['id']);

            if(!empty($product_detail))
            {
                if($product_detail->quantity < $data['qty'])
                {
                    return response()->json([
                        'error'=> true,
                        'message'=>"Còn ".$product_detail->quantity." sản phẩm",
                        'data'=> Session::get('cart'),
                    ]);
                }
            }
            
            array_push($array_product,$data);
            Session::put('cart',$array_product);

            return response()->json([
                'error'=> false,
                'message'=>"Thêm vào giỏ hàng thành công",
                'data'=> Session::get('cart'),
            ]);
        }
        
        if(count($session_cart) > 0 )
        {
            foreach( $session_cart as $key=>$cart)
            {
                if($cart["id"] == $data["id"] && $cart['options']['size']['id'] == $data['options']['size']['id'] && $cart['options']['color']['id'] == $data['options']['color']['id'])
                {
                    $session_cart[$key]['qty'] = $data['qty'];

                    $product_detail = ProductDetail::find($data['id']);

                    if(!empty($product_detail))
                    {
                        if($product_detail->quantity < $session_cart[$key]['qty'])
                        {
                            return response()->json([
                                'error'=> true,
                                'message'=>"Còn ".$product_detail->quantity." sản phẩm",
                                'data'=> Session::get('cart'),
                            ]);
                        }
                    }
                    $flag = true;
                }
            }
            if(!$flag)
            {
                $product_detail = ProductDetail::find($data['id']);

                if(!empty($product_detail))
                {
                    if($product_detail->quantity < $data['qty'])
                    {
                        return response()->json([
                            'error'=> true,
                            'message'=>"Còn ".$product_detail->quantity." sản phẩm",
                            'data'=> Session::get('cart'),
                        ]);
                    }
                }

                $product_detail = ProductDetail::find($data['id']);

                if(!empty($product_detail))
                {
                    if($product_detail->quantity < $data['qty'])
                    {
                        return response()->json([
                            'error'=> true,
                            'message'=>"Còn ".$product_detail->quantity." sản phẩm",
                            'data'=> Session::get('cart'),
                        ]);
                    }
                }
                array_push($session_cart,$data);
            }

            Session::put('cart',$session_cart);
        }
        
        return response()->json([
            'error'=> false,
            'message'=>"Thêm vào giỏ hàng thành công",
            'data'=> Session::get('cart'),
        ]);
    }
    
    public function show_cart() {
        $categories = Category::whereNull('deleted_at')->get();
        $category_post = PostCate::where('status',1)->get();
        $infor_contact = InforContact::all();
        $data = [
            'categories' => $categories,
            'category_post' => $category_post,
            'infor_contact' => $infor_contact,
            'title' => 'Giỏ hàng',
            'breadcrumbs' => [
                [
                    'name' => 'Trang chủ',
                    'url'  => '/',
                ],
                [
                    'name' => 'Giỏ hàng',
                ],  
            ]
        ];
        $show_cate = DB::table('categories')->get();
        return view('user.show_cart',$data);
    }

    public function deleteCart(Request $request)
    {
        $product_id = $request->product_id;
        $color_id = $request->color_id;
        $size_id = $request->size_id;

        $session_cart = Session::get('cart');

        foreach ($session_cart as $key => $item) {
            if($item['id'] == $product_id && $item['options']['size']['id'] == $size_id && $item['options']['color']['id'] == $color_id) {
                unset($session_cart[$key]);
            }
            else
            {
                $session_cart[$key]['path'] = getImageProduct($item['product']);
            }
        }
        
        Session::put('cart',$session_cart);
        
        return response()->json([
            'error'=> false,
            'message'=>"Xoá sản phẩm khỏi giỏ hàng",
            'data'=> Session::get('cart'),
        ]);
    }

    public function updateCart(Request $request)
    {
        $product_detail_id = $request->product_id;
        $color_id = $request->color_id;
        $size_id = $request->size_id;
        $quantity = $request->quantity;

        $session_cart = Session::get('cart');

        foreach ($session_cart as $key => $item) {
            if($item["id"] == $product_detail_id && $item['options']['size']['id'] == $size_id && $item['options']['color']['id'] == $color_id)
            {
                $product_detail = ProductDetail::find($product_detail_id);

                if(!empty($product_detail))
                {
                    if($product_detail->quantity < $quantity)
                    {
                        return response()->json([
                            'error'=> true,
                            'message'=>"Còn ".$product_detail->quantity." sản phẩm",
                            'data'=> Session::get('cart'),
                        ]);
                    }
                }
                $session_cart[$key]['qty'] = $quantity;
                $data = $session_cart[$key];
            }
        }

        Session::put('cart',$session_cart);

        return response()->json([
            'error'=> false,
            'message'=>"Cập nhật giỏ hàng thành công",
            'data'=> Session::get('cart'),
            'value'=>$data,
        ]);
    }
}
