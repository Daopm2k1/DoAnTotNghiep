<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InforContact;
use App\Models\Category;
use App\Models\Postcate;

class InforContactController extends Controller
{ 
    public function infor_contact(){
        $categories = Category::whereNull('deleted_at')->get();
        $category_post = PostCate::where('status',1)->get();
        $infor_contact = InforContact::all();
        $title = 'Liên hệ';
        return view('user.infor_contact.infor_contact', compact('title', 'infor_contact', 'categories', 'category_post'));
    }
}