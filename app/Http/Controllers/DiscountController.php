<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Order;
use App\Models\Discount;
use App\Models\PostCate;
use App\Models\InforContact;
use Auth;
use Session;
use Carbon\Carbon;

class DiscountController extends Controller
{
    //
    public function index(Request $request)
    {
        // dd(Session::all());
        if(!Auth::check())
        {
            return redirect('/login');
        }
        $categories = Category::whereNull('deleted_at')->get();
        $discounts = Discount::whereNull('deleted_at')
                        ->where('start_date','<=',Carbon::now()->format('Y-m-d') )
                        ->where('end_date','>=',Carbon::now()->format('Y-m-d') )
                        ->where('user_apply',Auth::id())
                        ->where('active',0)
                        ->paginate(10);
        $category_post = PostCate::where('status',1)->get();
        $infor_contact = InforContact::all();
        $data = [
            'categories' => $categories,
            'discounts' => $discounts,
            'category_post' => $category_post,
            'infor_contact' => $infor_contact,
            'title' => 'Giảm giá',
            'breadcrumbs' => [
                [
                    'name' => 'Trang chủ',
                    'url'  => '/',
                ],
                [
                    'name' => 'Giảm giá',
                    
                ]
            ]
        ];
        return view('user.discount.index',$data);
    }
}
