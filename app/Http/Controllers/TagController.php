<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\ProductTag;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;
use App\Models\PostCate;
use App\Models\InforContact;

class TagController extends Controller
{
    //

    public function checkTagSlug($slug)
    {
        if(empty($slug))
        {
            abort(404);
        }
        
        $tag = Tag::whereNull('deleted_at')->where('slug',$slug)->with('product_tag')->first();

        if(empty($tag))
        {
            abort(404);
        }
        return $tag;
    }

    public function index($slug)
    {
        $row = $this->checkTagSlug($slug);

        $categories = Category::whereNull('deleted_at')->get();
        
        $list_id = $row->product_tag->pluck('product_id');

        $query = Product::whereNull('deleted_at')->whereIn('id',$list_id);
        if(!empty($request->key_word))
        {
            $products = $query->where('name','like','%'.$request->key_word.'%')->paginate(12);
        }
        else
        {
            $products = $query->paginate(12);
        }   
        $slides = Slide::whereNull('deleted_at')->orderBy('created_at','desc')->offset(0)->limit(4)->get();   
        $title = $row->name;
        $breadcrumbs = [
            [
                'name' => 'Trang chủ',
                'url'  => '/',
            ],
            [
                'name' => 'Danh mục',
                // 'url'  => 'admin/user/create',
            ],
            [
                'name' => $row->name,
                // 'url'  => 'admin/user/create',
            ],
        ];
        $category_post = PostCate::where('status',1)->get();
        $infor_contact = InforContact::all();
       return view('user.home.index', compact('categories','products','slides','title','breadcrumbs', 'category_post', 'infor_contact'));
    }
}
