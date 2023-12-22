<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\Models\Post;
use App\Models\PostCate;
use App\Models\Slide;
use App\Models\Category;
use App\Models\InforContact;

class PostController extends Controller
{
    public function show_post_cate($path){
        if(empty($path)) {
            abort(404);
        }
        
        $posts = Post::whereHas('post_cate',function($query) use ($path){
            $query->where('post_path',$path);
        })->where('status',1)->with('post_cate')->orderBy('created_at','desc')->paginate(5);
        
        $categories = Category::whereNull('deleted_at')->get();
        $category_post = PostCate::where('status',1)->get();
        $post_cate = PostCate::where('post_path', $path)->first();

        $title = $post_cate->post_name ?? '';
        $infor_contact = InforContact::all();
        return view('user.post.show_post_cate', compact('infor_contact','title','posts','category_post','categories'));
    }
    public function show_post($path, Request $request){
        $post = Post::where('path',$path)->where('status',1)->first();
        
        if(empty($post)) {
            abort(404);    
        }
        $post->view_count += 1;
        $post->save(); 

        $related_post = Post::where('post_cate_id',$post->post_cate_id)->where('id','<>', $post->id)->limit(6)->get();
        $category_post = PostCate::where('status',1)->get();
        $categories = Category::whereNull('deleted_at')->get();
        $infor_contact = InforContact::all();
        return view('user.post.post', compact('infor_contact','post','related_post','category_post','categories'));
    }
}
