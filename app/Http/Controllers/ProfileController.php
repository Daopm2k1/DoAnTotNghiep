<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use DB;
use Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;    
use Spatie\Permission\Models\Role;
use App\Models\Category;
use App\Models\PostCate;
use App\Models\InforContact;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{
    public function index(Request $request){
        $id = Auth::id();

        if(empty($id))
        {
            return redirect('/login');
        }
        $user = User::where('id',$id)->whereNull('deleted_at')->first();

        if(empty($user))
        {
            return redirect('/login');
        }

        $categories = Category::whereNull('deleted_at')->get();
        $category_post = PostCate::where('status',1)->get();
        $infor_contact = InforContact::all();
        $data = [
            'rows' => $user,
            'categories' => $categories,
            'category_post' => $category_post,
            'infor_contact' => $infor_contact,
            'title' => 'Thông tin tài khoản',
            'breadcrumbs' => [
                [
                    'name' => 'Trang chủ',
                    'url'  => '/',
                ],
                [
                    'name' => 'Thông tin tài khoản',
                ],  
            ]
        ];
        return view('user.profile.index',$data);
    }
    public function store(Request $request)
    {
        $id = Auth::id();
        if(empty($id))
        {
            abort(404);
        }
        $rule = [
            'username' => 'required',
            'email' => 'required|email',
            'phone' => 'numeric|digits_between:10,11|nullable',
            'date_of_birth' => 'before:today|nullable',
            'avatar' => 'mimes:jpeg,jpg,png|max:10000|nullable',
            // 'address' => 'required'
        ];
        $messages = [
            'required' => 'Nhập :attribute',
            'email' => 'Email không hợp lệ',
            'phone.numeric' => 'Số điện thoại không hợp lệ',
            'phone.digits_between' => 'Số điện thoại có 10 hoặc 11 số',
            'date_of_birth.before'=>'Ngày sinh phải là ngày trước ngày hôm nay',
            'avatar.mimes'=>'Ảnh đại diện phải có dạng *.png, *.jpg, *jpeg',
        ];
        $customName =[
            'email' => 'email',
            'username' => 'họ và tên',
            'phone' => 'số điện thoại',
            'date_of_birth' => 'ngày sinh',
            'avatar'=>'ảnh dại diện'
        ];
        $validator = Validator::make($request->all(),$rule,$messages,$customName);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator);
        }
        $user = User::where('email',$request->email)->where('id','<>',$id)->whereNull('deleted_at')->first(); 
        if(!empty($user))
        {
            return redirect()->back()->with('error', 'Email đã dược sử dụng');
        }
        
        $user = User::where('id',$id)->whereNull('deleted_at')->first(); 
        if(empty($user))
        {
           abort(404);
        }
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if(!empty($request->date_of_birth))
        {
            $user->date_of_birth = new Carbon($request->date_of_birth);
        }
        $user->address = $request->address;
        $user->gender = $request->gender;

        if(!empty($request->avatar))
        {
            if(File::exists(public_path().$user->avatar)) {
                File::delete(public_path().$user->avatar);
            }

            $imageName=$user->id.".".$request->avatar->getClientOriginalExtension();

            $image_resize = Image::make($request->avatar->getRealPath());              
            $image_resize->resize(400,400);
            $image_resize->save('images/avatar/'.$imageName);
            // $request->avatar->move(public_path('images/avatar'), $imageName);
            $user->avatar = '/images/avatar/'.$imageName;
        }
        $user->save();    
        return redirect()->back()->with('success', 'Cập nhật hồ sơ thành công');
    }
}