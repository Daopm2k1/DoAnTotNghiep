<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use TeamPickr\DistanceMatrix\DistanceMatrix;
use TeamPickr\DistanceMatrix\Response\DistanceMatrixResponse;
use TeamPickr\DistanceMatrix\Response\Element;
use TeamPickr\DistanceMatrix\TravelMode;
use TeamPickr\DistanceMatrix\Licenses\StandardLicense;
use App\Models\PostCate;
use App\Models\InforContact;

class AboutController extends Controller
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
            'title' => 'Giới thiệu',
            'breadcrumbs' => [
                [
                    'name' => 'Trang chủ',
                    'url'  => '/',
                ],
                [
                    'name' => 'Giới thiệu',
                ],  
            ]
        ];
 
        return view('user.about.index',$data);
    }
}
