<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\PostCate;
use App\Models\InforContact;

class ExchangeController extends Controller
{
    //
    public function index()
    {
        $category_post = PostCate::where('status',1)->get();
        $categories = Category::whereNull('deleted_at')->get();
        $infor_contact = InforContact::all();
        $data = [
            'categories' => $categories,
            'category_post' => $category_post,
            'infor_contact' => $infor_contact,
            'title' => 'Chính sách đổi trả',
            'breadcrumbs' => [
                [
                    'name' => 'Trang chủ',
                    'url'  => '/',
                ],
                [
                    'name' => 'Chính sách đổi trả',
                ],  
            ]
        ];
 
        return view('user.exchange.index',$data);
    }
}
