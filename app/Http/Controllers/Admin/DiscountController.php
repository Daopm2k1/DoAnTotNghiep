<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Category;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Str;
use App\User;
use App\Mail\SendDiscount;
use Carbon\Carbon;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        $key_search = $request->search;
        $start_date_search = $request->start_date_search;
        $end_date_search = $request->end_date_search;

        $query = Discount::whereNull('deleted_at');

        if(!empty($key_search))
        {
            $query->whereHas('userApply',function($query) use ($key_search){
                $query->where('email','LIKE','%'.$key_search.'%');
            });
        }
        if(!empty($start_date_search))
        {
            $query->where('start_date','>=',$start_date_search);
        }
        if(!empty($end_date_search))
        {
            $query->where('end_date','<=',$end_date_search);
        }

        $discount = $query->with(['user'=>function($query){
                        $query->withTrashed();
                    }])
                    ->with(['userApply'=>function($query){
                        $query->withTrashed();
                    }])->orderByDesc('start_date')->paginate(10);

        $data = [
            'rows' => $discount,
            'breadcrumbs'        => [
                [
                    'name' => 'Giảm giá',
                    // 'url'  => 'admin/dashboard',
                ],
            ],
            'isDiscount'=>true,
        ];
        return view('admin.discount.index',$data);
    }

    public function create(Request $request)
    {
        $categories = Category::whereNull('deleted_at')->get();
        $products = Product::whereNull('deleted_at')->get();

        $data = [
            'categories' => $categories,
            'products'=>$products,
            'breadcrumbs'        => [
                [
                    'name' => 'Giảm giá',
                    'url'  => 'admin/discount',
                ],
                [
                    'name' => 'Cập nhật giảm giá',
                ],
            ],
            'isDiscount'=>true,
        ];
        return view('admin.discount.createOrEdit',$data);
    }

    public function edit(Request $request,$id)
    {
        if(empty($id))
        {
            return redrect()->route('admin.discount.index')->with('error','Không tìm thấy giảm giá');
        }
        
        $discount = Discount::whereNull('deleted_at')->where('id',$id)->with(['user','userApply'])->first();

        if(empty($discount))
        {
            return redirect()->route('admin.discount.index')->with('error','Không tìm thấy giảm giá');
        }

        $categories = Category::whereNull('deleted_at')->get();
        $products = Product::whereNull('deleted_at')->get();

        if($discount->type == "product")
        {
            $list_item = Product::whereNull('deleted_at')->whereIn('id',json_decode($discount->apply))->get()->pluck('name','id');
        }
        else
        {
            $list_item = Category::whereNull('deleted_at')->whereIn('id',json_decode($discount->apply))->get()->pluck('name','id');
        }

        $data = [
            'row' => $discount,
            'categories' => $categories,
            'products'=>$products,
            'list_item'=>$list_item,
            'breadcrumbs'        => [
                [
                    'name' => 'Giảm giá',
                    'url'  => 'admin/discount',
                ],
                [
                    'name' => 'Chi tiết giảm giá',
                ],
            ],
            'isDiscount'=>true,
        ];
        return view('admin.discount.createOrEdit',$data);
    }   

    public function store(Request $request)
    {
        $rule = [
            'sale_price' => 'required|numeric|max:100',
            'start_date' => 'required|after:today',
            'end_date' => 'required|after:start_date',
            'user_apply'=>'required',
        ];
        $messages = [
            'required' => 'Nhập :attribute',
            'sale_price.numeric' => 'Giá giảm phải là số',
            'sale_price.max' => 'Giá giảm tối đa là :max',
            'start_date.required' => 'Chọn ngày bắt đầu',
            'end_date.required' => 'Chọn ngày kết thúc',
            'start_date.after' => 'Ngày chọn phải là ngày sau hôm nay',
            'end_date.after' => 'Ngày chọn phải là ngày sau bắt đầu',
            'user_apply.required' => 'Chọn tài khoản giảm giá',
        ];
        $customNames = [
            'sale_price' => 'giá giảm',
            'start_date' => 'ngày bắt đầu',
            'end_date' => 'ngày kết thúc',
        ];
        $validator = Validator::make($request->all(),$rule,$messages,$customNames);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator);
        }

        if(empty($request->user_apply))
        {
            return redirect()->back()->with('error','Cần chọn tài khoản giảm giá');
        }

        if(empty($request->id))
        {
            $list_user = explode(",", $request->user_apply);
            
            $users = User::whereNull('deleted_at')->whereIn('id',$list_user)->get();

            $list = [];
            if($request->type_discount == "product")
            {
                if(empty($request->id_product))
                {
                    return redirect()->back()->with('error','Cần chọn danh mục hoặc sản phẩm cần giảm giá');
                }
                $list = explode(",", $request->id_product);
            }
            else
            {
                if(empty($request->id_category))
                {
                    return redirect()->back()->with('error','Cần chọn danh mục hoặc sản phẩm cần giảm giá');
                }
                $list = explode(",", $request->id_category);
            }
            
            foreach($users as $user)
            {
                $flag = false;
                $str_random = Str::upper(Str::random(20));
                do
                {
                    $discount_random = Discount::whereNull('deleted_at')->where('discount_code',$str_random)->where('active',0)->get();
                    if(empty($discount_random))
                    {
                        $flag = true;
                    }
                }while($flag);
    
                $discount = new Discount();
                $discount->user_id = Auth::id();
                $discount->user_apply =$user->id;
                $discount->discount_code = $str_random;
                $discount->type = $request->type_discount;
                $discount->sale_percent = $request->sale_price;
                $discount->start_date = $request->start_date;
                $discount->end_date = $request->end_date;
                $discount->apply = json_encode($list);
                $discount->save();
    
                $data = Discount::whereNull('deleted_at')->where('id',$discount->id)->with(['user','userApply'])->first();
                
                \Mail::to($user->email)->send(new SendDiscount($data));
            }

            return redirect()->route('admin.discount.index')->with('success','Tạo giảm giá thành công');
        }
    }
    public function delete(Request $request)
    {
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $discount = Discount::find($id);
            if(!empty($discount))
            {
                $discount->delete();
            }
        }
        return redirect()->back()->with('success', 'Xóa giảm giá thành công');
    }
}
