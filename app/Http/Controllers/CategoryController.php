<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\Models\Category;
use App\Models\Postcate;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Slide;
use App\Models\InforContact;

class CategoryController extends Controller
{
    public function checkCategory($id)
    {
        if(empty($id))
        {
            abort(404);
        }
        $category = Category::whereNull('deleted_at')->where('id',$id)->first();
        if(empty($category))
        {
            abort(404);
        }
        return $category;
    }
    public function show_category_product(Request $request, $id){
        $row = $this->checkCategory($id);

        $list_id_product = array_unique(ProductDetail::whereNull('deleted_at')->get()->pluck('product_id')->toArray());
        $categories = Category::whereNull('deleted_at')->get();
        $category_post = PostCate::where('status',1)->get();
        $infor_contact = InforContact::all();

        $query = Product::whereNull('deleted_at')->whereIn('id',$list_id_product)->where('category_id',$id);
        $min_price = Product::min('price');
        $max_price = Product::max('price');

        if(!empty($sort = $request->sort))
        {
            switch($sort) {
                case 'az': 
                    $query->orderBy('name', 'asc');
                    break;
                case 'za': 
                    $query->orderBy('name', 'desc');
                    break;
                case 'asc': 
                    $query->orderBy('price', 'asc');
                    break;
                case 'desc': 
                    $query->orderBy('price', 'desc');
                    break;
                case 'new': 
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'old': 
                    $query->orderBy('created_at', 'asc');
                    break;
            };            
        }   
        $products = $query->paginate(12);
        
        $slides = Slide::whereNull('deleted_at')->orderBy('created_at','desc')->offset(0)->limit(4)->get();   
        $title = $row->name;
        $breadcrumbs = [
            [
                'name' => 'Trang chủ',
                'url'  => '/',
            ],
            [
                'name' => 'Danh mục',
            ],
            [
                'name' => $row->name,
            ],
        ];

        $display = 'grid';
        if(!empty($request->display))
        {   
           $display = $request->display; 
        }
        
        return view('user.category.index', compact('display','categories','products','slides','title','breadcrumbs', 'category_post', 'id', 'infor_contact'));
    }
}
