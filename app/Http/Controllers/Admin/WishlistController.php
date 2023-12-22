<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\User;
use App\Models\Wishlist;
use Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request,$id ='')
    {
        $query = Wishlist::whereNull('deleted_at');
        $search = $request->search;
        if(!empty($search))
        {
            $query->whereHas('user',function($query) use ($search){
                $query->where('username','LIKE','%'.$search.'%');
            });
        }
        $wishlist = $query->with(['product','user'])->get();
        $list_id = array_unique($wishlist->pluck('user.id')->toArray());
        
        
        $user = User::whereNull('deleted_at')->whereIn('id',$list_id)->paginate(10);
        
        $data = [
            'rows' => $user,
            'breadcrumbs'        => [
                [
                    'name' => 'Yêu thích',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isWishlist'=>true,
        ];
        // dd($wishlist);
        return view('admin.wishlist.index',$data);
    }

    public function detail(Request $request,$id = '')
    {
        if(empty($id))
        {
            return redirect()->route('admin.wishlist.index')->with('error', 'Không tìm thấy tài khoản');
        }
        $wishlist =  Wishlist::whereNull('deleted_at')->where('user_id',$id)
                        ->with(['product' => function($query) { 
                            $query->with('category');
                        }])
                    ->paginate(10);
        $data = [
            'rows' => $wishlist,
            'breadcrumbs'        => [
                [
                    'name' => 'Yêu thích',
                    'url'  => 'admin/wishlist',
                ],
                [
                    'name' => $id,
                    // 'url'  => 'admin/dashboard',
                ],
                [
                    'name' => 'Chi tiết',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isWishlist'=>true,
        ];
        // dd($wishlist);
        return view('admin.wishlist.detail',$data);
    }

    public function delete(Request $request,$id = '')
    {
        // dd(json_decode($request->list_id));
        $list_id = json_decode($request->list_id);
        foreach($list_id as $wishlist_id)
        {
            $wishlists = Wishlist::where('user_id',$wishlist_id)->get();
            if(count($wishlists) > 0)
            {
                foreach($wishlists as $wishlist)
                {
                    $wishlist->forceDelete();
                }
            }
        }
        return redirect()->route('admin.wishlist.index')->with('success', 'Xóa yêu thích thành công');
    }
}
