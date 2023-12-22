<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\PostCate;
use App\Models\InforContact;

class TermsController extends Controller
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
            'title' => 'Điều khoản dịch vụ',
            'breadcrumbs' => [
                [
                    'name' => 'Trang chủ',
                    'url'  => '/',
                ],
                [
                    'name' => 'Điều khoản dịch vụ',
                ],  
            ]
        ];

        return view('user.terms.index',$data);
    }
}
