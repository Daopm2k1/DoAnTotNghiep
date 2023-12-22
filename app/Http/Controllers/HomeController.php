<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\PostCate;
use App\Models\Post;
use App\Models\Slide;
use App\Models\Wishlist;
use App\Models\ProductDetail;
use App\Models\InforContact;
use Session;

class HomeController extends Controller
{
    public function index(Request $request)
    {  
        $list_id_product = array_unique(ProductDetail::whereNull('deleted_at')->get()->pluck('product_id')->toArray());

        $categories = Category::whereNull('deleted_at')->get();
        $category_post = PostCate::where('status',1)->get();
        $infor_contact = InforContact::all();

        $query = Product::whereNull('deleted_at')->whereIn('id',$list_id_product)->with('images');
        $min_price = Product::min('price');
        $max_price = Product::max('price');

        if(!empty($request->key_word))
        {
            $query->where('name','like','%'.$request->key_word.'%')->inRandomOrder();
        }
        else
        {
            $query->inRandomOrder();
        }  

        if($orderby = $request->orderby){
            switch($orderby){
                case 'desc':
                    $query->orderBy('id','DESC');
                    break;
                case 'asc':
                    $query->orderBy('id','ASC');
                    break;
                case 'price_max':
                    $query->orderBy('price','ASC');
                    break;
                case 'price_min':
                    $query->orderBy('price','DESC');
                    break;
                case 'kytu_az':
                    $query->orderBy('name','ASC');
                    break;
                case 'kytu_za':
                    $query->orderBy('name','DESC');
                    break;
                default:
                    $query->orderBy('id','DESC');
            }
        }
        $min = $min_price;
        $max= $max_price;
        if(!empty($request->range_price)){
            $array_range = explode(',',$request->range_price);
            if(count($array_range) >= 2)
            {
                $min = $array_range[0];
                $max = $array_range[1];
                $query->whereBetween('price', [$min, $max])->inRandomOrder();
            }
        }
        $products = $query->paginate(6);
        
        $slides = Slide::whereNull('deleted_at')->orderBy('created_at','desc')->offset(0)->limit(4)->get();   
        $is_home = true;
        $title = 'Trang chủ';
        $tab_products = Category::offset(0)->limit(5)->get(); 

        $product_top_sale = DB::table('order_details')
            ->join('product_details', 'order_details.product_detail_id', '=', 'product_details.id')
            ->join('products','products.id', '=', 'product_details.product_id')
            ->select(DB::raw('count(*) as count_product'),DB::raw('sum(order_details.price) as total_price'),'products.id','products.name','products.price')
            ->groupBy('products.id','products.name','products.price')
            ->orderBy('count_product','desc')
            ->orderBy('total_price','desc')
            ->limit(12)
            ->get();

        $product_top_sale = collect($product_top_sale)->chunk(3)->toArray(); 
       
        if(end($product_top_sale) && count(end($product_top_sale)) < 3)
        {
            $product_top_sale = array_splice($product_top_sale,0,array_key_last($product_top_sale));
        };

        $list_id_product_wishlist = Wishlist::select('product_id', DB::raw('COUNT(product_id) as count_product'))
                                    ->groupBy('product_id')
                                    ->orderBy('count_product', 'desc')
                                    ->offset(0)->limit(12)
                                    ->get()->pluck('product_id')->toArray();
        $list_id_product_wishlist = array_splice($list_id_product_wishlist ,0, (int)floor(count($list_id_product_wishlist) / 3) * 3);
       
        $product_top_wish = Product::whereIn('id', $list_id_product_wishlist)->get()->toArray();

        $product_top_wish = collect($product_top_wish)->chunk(3)->toArray(); 
       
        return view('user.home.index', compact('title', 'min', 'max', 'categories','products','slides', 'max_price', 'min_price', 'category_post','is_home', 'tab_products', 'product_top_sale', 'product_top_wish', 'infor_contact'));
    }

    public function autocomplete_ajax(Request $request){
        $product = Product::where('name','like','%'.$request->key_word.'%')->where('status', 1)->limit(6)->get();

        if(count($product) > 0 ){
            return response()->json([
                'error' => false,
                'message' => 'Lấy dữ liệu thành công',
                'data' => $product,
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'Không có dữ liệu',
            'data' => null,
        ]);
    }
}
