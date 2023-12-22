<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Cart;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Support\Collection;
use App\Models\Category;
use App\Models\Size;
use App\Models\Color;
use App\Models\Tag;
use App\Models\ProductTag;
use App\Models\Comment;
use App\Models\Wishlist;
use App\Models\Slide;
use App\Models\PostCate;
use App\Models\InforContact;
use App\Models\ImageProduct;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\Authenticatable;
use Auth;
use Illuminate\Contracts\Support\Jsonable;

class ProductController extends Controller
{
    public function checkItemProduct($id)
    {
        if(empty($id))
        {
            abort(404);
        }
        $list_id_product = array_unique(ProductDetail::whereNull('deleted_at')->get()->pluck('product_id')->toArray());
        $product = Product::whereNull('deleted_at')
                    ->whereIn('id',$list_id_product)
                    ->with('category')
                    ->with(array('info' => function($query) {
                        $query->with(['size','color']);
                    }))
                    ->where('id',$id)
                    ->first();
        
        if(empty($product))
        {
            abort(404);
        }
        return $product;
    }
    public function show_detail_product($id){
        $product = $this->checkItemProduct($id);

        $list_size_id = $product->info->pluck('size_id');
        $list_color_id = $product->info->pluck('color_id');
        $categories = Category::whereNull('deleted_at')->get();
        $category_post = PostCate::where('status',1)->get();
        $infor_contact = InforContact::all();

        $related_products = Product::whereNull('deleted_at')
                            ->where('category_id',$product->category_id)
                            ->where('id','<>',$product->id)
                            ->offset(0)->limit(12)->get()->toArray();    

        $related_products = collect($related_products)->chunk(3)->toArray(); 
        // dd(end($related_products));
        if(end($related_products) && count(end($related_products)) < 3)
        {
            $related_products = array_splice($related_products,0,array_key_last($related_products));
        }

        //Get tag product
        $product_tag = ProductTag::where('product_id',$product->id)->whereNull('deleted_at')->with('tag')->get()->pluck('tag');

        //Get Comment
        $comments = Comment::whereNull('deleted_at')
                            ->whereNull('parent_id')
                            // ->where('user_id',Auth::id())
                            ->where('product_id',$product->id)
                            ->with(['user','product'])
                            ->with(array('children' => function($query) {
                                $query->with(['user','product']);
                            }))
                            ->orderBy('created_at','desc')
                            // ->offset(0)->limit(5)
                            // ->get()
                            ->paginate(5)
                            ->getCollection()
                            ->toArray();

        $color = Color::whereNull('deleted_at')->whereIn('id',$list_color_id)->get();

        if(!empty($color))
        {
            $first_color = $color->first();
            if(!empty($first_color))
            {
                $query = ProductDetail::whereNull('deleted_at')
                                        ->where('product_id',$product->id)
                                        ->where('color_id',$first_color->id)
                                        ->with('size');
                $size_first_color = $query->get()->pluck('size.id');
                $first_size = $query->first();
            }
        }
        if(!empty($size_first_color))
        {
            $size = Size::whereNull('deleted_at')->whereIn('id',$size_first_color)->get(); 
        }
        else
        {
            $size = Size::whereNull('deleted_at')->get();
        }
        
        $data = [
            'product' => $product,
            'categories' => $categories,
            'related_products' => $related_products,        
            'sizes' => $size,
            'colors' => $color,  
            'first_color'=>$first_color ?? '',
            'first_size'=>$first_size->size ?? '',
            'tags' => $product_tag,
            'comments'=>$comments,
            'category_post' => $category_post,
            'infor_contact' => $infor_contact,
            'title' => $product->name ?? '',
            'breadcrumbs' => [
                [
                    'name' => 'Trang chủ',
                    'url'  => '/',
                ],
                [
                    'name' => $product->category->name,
                    'url'  => 'category/'.$product->category->id,
                ],
                [
                    'name' => $product->name,
                    // 'url'  => 'admin/user/create',
                ],  
            ]
        ];
        // dd(count($comments));
        return view('user.product.show_detail',$data);
    }

    public function getSize($product = '',$color = '')
    {
        if(empty($product))
        {
            return response()->json([
                'error'=>true,
                'message'=>'Không tìm thấy sản phẩm',
                'data' => null,
            ]);
        }

        if(empty($color))
        {
            return response()->json([
                'error'=>true,
                'message'=>'Không tìm thấy màu sắc',
                'data' => null,
            ]);
        }

        $list_size = ProductDetail::whereNull('deleted_at')
                                ->where('product_id',$product)
                                ->where('color_id',$color)
                                ->get()->pluck('size_id');

        $sizes = Size::whereNull('deleted_at')
                    ->whereIn('id',$list_size)
                    ->get()
                    ->toArray();
                    
        return response()->json([
                'error'=>false,
                'message'=>'Tìm thành công',
                'data' => $sizes,
        ]);
    }
    public function add_wishlist(Request $request){
      
        if(!Auth::check())
        {
            return response()->json([
                'error'=>true,
                'message'=>'Đăng nhập để thêm vào yêu thích',
                'data' => null,
            ]);
        }

        if(empty($request->id_product))
        {
            return response()->json([
                'error'=>true,
                'message'=>'Lỗi khi thêm vào yêu thích',
                'data' => null,
            ]);
        }

        $wishlist = Wishlist::where('user_id',Auth::id())->where('product_id',$request->id_product)->whereNull('deleted_at')->first();

        if(empty($wishlist))
        {
            $new_wishlist = new WishList();
            $new_wishlist->user_id = Auth::id();
            $new_wishlist->product_id = $request->id_product;
            $new_wishlist->save();

            $data = Wishlist::where('user_id',Auth::id())
                ->where('product_id',$request->id_product)
                ->whereNull('deleted_at')
                ->get();

            return response()->json([
                'error'=>false,
                'method'=>'create',
                'message'=>'Thêm yêu thích thành công',
                'data' => $new_wishlist,
                'wishlist' => $data,
            ]);
        }
        else
        {
            if($wishlist->delete())
            {
                $data = Wishlist::where('user_id',Auth::id())
                ->where('product_id',$request->id_product)
                ->whereNull('deleted_at')
                ->get();

                return response()->json([
                    'error'=>false,
                    'method'=>'delete',
                    'message'=>'Xoá yêu thích thành công',
                    'data' => $wishlist,
                    'wishlist' => $data,
                ]);
            }
        }
    }

    public function removeWishlist(Request $request)
    {
        $id = Auth::id();
        if(empty($request->product_id))
        {
            return response()->json([
                'error'=>true,
                'message'=>'Không tìm thấy sản phẩm',
                'data' => null,
            ]);
        }
        $wishlist = Wishlist::whereNull('deleted_at')
                            ->where('user_id',$id)
                            ->where('product_id',$request->product_id)
                            ->first();
        if(!empty($wishlist))
        {
            $wishlist->delete();
        }                    
        $page = $request->page;
        $products = Product::whereNull('deleted_at')->whereHas('wishlist',function($query) use ($id){
            $query->where('user_id',$id);
        })
        ->with('images')->paginate(12, ['*'], 'page', $page);
        $paginate = $products->withQueryString()->links()->render();
        if(empty($products) && $page > 1)
        {
            $page = $page - 1;
            $products = Product::whereNull('deleted_at')->whereHas('wishlist',function($query) use ($id){
                $query->where('user_id',$id);
            })
            ->with('images')->paginate(12, ['*'], 'page', $page);
            $paginate = $products->withQueryString()->links()->render();
        }

        return response()->json([
            'error'=>false,
            'message'=>'Xoá yêu thích thành công',
            'data' => $products,
            'paginate' => $paginate,
            'page'=>$page,
        ]);
    }

    public function show_wishlist(Request $request){
        if(!Auth::check())
        {
            return redirect('/login');
        }
        $id = Auth::id();
        
        $categories = Category::whereNull('deleted_at')->get();
        $category_post = PostCate::where('status',1)->get();
       
        $products = Product::whereNull('deleted_at')->whereHas('wishlist',function($query) use ($id){
                                                        $query->where('user_id',$id);
                                                    })
                                                    ->with('images')->paginate(12);
                                                    // dd($products);
        $paginate = $products->withQueryString()->links()->render();
        
        $slides = Slide::whereNull('deleted_at')->orderBy('created_at','desc')->offset(0)->limit(4)->get();   

        $title = 'Yêu thích';
        $isWishlist = true;
        $infor_contact = InforContact::all();

        return view('user.home.wishlist',  compact('infor_contact','categories','products','slides','title','isWishlist','paginate', 'category_post'));
    }
}