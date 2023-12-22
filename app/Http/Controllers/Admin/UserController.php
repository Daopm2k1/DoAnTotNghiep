<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Comment;
use App\Models\Wishlist;
use App\Models\Address;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;    
use Spatie\Permission\Models\Role;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }
    public function index(Request $request)
    {
        $query = User::whereNull('deleted_at')->where('id','<>',Auth::id());
        if(!empty($request->search))
        {
            $query->where('username','like','%'.$request->search.'%');
        }
        $user = $query->paginate(10);
        $data = [
            'rows' => $user,
            'breadcrumbs'        => [
                [
                    'name' => 'Tài khoản',
                    // 'url'  => 'admin/user',
                ],
            ],
            'isUser'=>true,
        ];
        return view('admin.user.index',$data);
    }
    public function create()
    {
        $roles = Role::all();
        $data = [
            'roles' => $roles,
            'rows' => null,
            'breadcrumbs'        => [
                [
                    'name' => 'Tài khoản',
                    'url'  => 'admin/user',
                ],
                [
                    'name' => 'Tạo tài khoản',
                    // 'url'  => 'admin/user/create',
                ],
            ],
            'isUser'=>true,
        ];
        return view('admin.user.create',$data);
    }

    public function edit(Request $request,$id)
    {
        $user = User::where('id',$id)->whereNull('deleted_at')->first();
        $roles = Role::all();
        if(empty($user))
        {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản');
        }
        // $url = 'admin/user/edit/'.$id;
        $data = [
            'roles' => $roles,
            'rows' => $user,
            'breadcrumbs'        => [
                [
                    'name' => 'Tài khoản',
                    'url'  => 'admin/user',
                ],
                [
                    'name' => 'Cập nhật tài khoản',
                    // 'url'  => $url,
                ],
            ],
            'isUser'=>true,
        ];
        return view('admin.user.edit',$data);
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
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
        if(empty($id))
        {
            $userCheck = User::where('email',$request->email)->withTrashed()->first();
            if(!empty($userCheck))
            {
                return redirect()->back()->with('error','Email đã tồn tại');
            }
            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone = $request->phone;
            if(!empty($request->date_of_birth))
            {
                $user->date_of_birth = new Carbon($request->date_of_birth);
            }
            // $user->address = $request->address;
            $user->gender = $request->gender;
            $user->syncRoles($request->role);
            $user->active= Carbon::now();
            $user->save();

            if(!empty($request->avatar))
            {
                $imageName=$user->id.".".$request->avatar->getClientOriginalExtension();

                $image_resize = Image::make($request->avatar->getRealPath());              
                $image_resize->resize(400,400);
                $image_resize->save('images/avatar/'.$imageName);
                // $request->avatar->move(public_path('images/avatar'), $imageName);
                $user->avatar = '/images/avatar/'.$imageName;
            }
            $user->save();

            return redirect()->route('admin.user.index')->with('success', 'Tạo tài khoản thành công');
        }  
       
        $query = User::where('email',$request->email)->where('id','<>',$id);
        $userCheck = $query->withTrashed()->first();
        if(!empty($userCheck))
        {
            return redirect()->back()->with('error','Email đã tồn tại');
        }

        $user = $query->whereNull('deleted_at')->first(); 
        if(!empty($user))
        {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản');
        }

        $user = User::where('id',$id)->whereNull('deleted_at')->first(); 
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if(!empty($request->date_of_birth))
        {
            $user->date_of_birth = new Carbon($request->date_of_birth);
        }
        // $user->address = $request->address;
        $user->gender = $request->gender;

        if(!empty($request->avatar))
        {
            if(File::exists(public_path().$user->avatar)) {
                File::delete(public_path().$user->avatar);
            }

            $imageName=$user->id.".".$request->avatar->getClientOriginalExtension();
            
            $image_resize = Image::make($request->avatar->getRealPath());        
            // dd($image_resize);      
            $image_resize->resize(400,400);
            $image_resize->save('images/avatar/'.$imageName);
            // $request->avatar->move(public_path('images/avatar'), $imageName);
            $user->avatar = '/images/avatar/'.$imageName;
        }
        $user->syncRoles($request->role);
        $user->save();    
        return redirect()->route('admin.user.index')->with('success', 'Cập nhật tài khoản thành công');
    }
    public function delete(Request $request)
    {
        // dd(json_decode($request->list_id));
        $list_id = json_decode($request->list_id);
        foreach($list_id as $id)
        {
            $user = User::find($id);
            if(!empty($user))
            {
                // return redirect()->back()->with('error', 'User not found');

                $wishlist = Wishlist::whereNull('deleted_at')->where('user_id',$user->id)->get();
                if(!empty($wishlist))
                {
                    foreach($wishlist as $key=>$item)
                    {
                        $item->delete();
                    }
                }

                $comment = Comment::whereNull('deleted_at')->where('user_id',$user->id)->get();
                if(!empty($comment))
                {
                    foreach($comment as $key=>$item)
                    {
                        $item->deleteChild();
                        $item->delete();
                    }
                }
                
                $addresses = Address::whereNull('deleted_at')->where('user_id',$user->id)->get();
                if(!empty($addresses))
                {
                    foreach($addresses as $key=>$address)
                    {
                        $address->delete();
                    }
                }

                if($flag = $user->id != Auth::id())
                {
                    if(File::exists(public_path().$user->avatar)) {
                        File::delete(public_path().$user->avatar);
                    }
                    $user->delete();
                }
            }
        }
        if(!$flag) return redirect()->back()->with('error', "Không thể xóa tài khoản người dùng đang đăng nhập");
        return redirect()->back()->with('success', 'Xóa tài khoản thành công');
    }
    public function getResetPassword(Request $request,$id)
    {
        $user = User::where('id',$id)->whereNull('deleted_at')->first();
        if(empty($user))
        {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản');
        }
        $data = [
            'rows' => $user,
            'breadcrumbs'        => [
                [
                    'name' => 'Tài khoản',
                    'url'  => 'admin/user',
                ],
                [
                    'name' => 'Đặt lại mật khẩu',
                    // 'url'  => 'admin/user/'.$id.'/change-password',
                ],
            ],
            'isUser'=>true,
        ];
        return view('auth.change-password',$data);
    }

    public function postResetPassword(Request $request,$id)
    {
        $rule = [
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password|min:8',
        ];
        $messages = [
            'password.required' => 'Nhập mật khẩu',
            'confirm_password.required' => 'Nhập lại mật khẩu',
            'min' => ':attribute có ít nhất :min kí tự.',
            'same' => ':attribute và :other phải trùng nhau.'
        ];
        $customName =[
            'password' => 'Mật khẩu',
            'confirm_password' => 'Nhập lại mật khẩu'
        ];
        $validator = Validator::make($request->all(),$rule,$messages,$customName);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator);
        }
        $user = User::where('id',$id)->whereNull('deleted_at')->first();
        if(empty($user))
        {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản');
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('admin.user.index')->with('success', 'Thay đổi mật khẩu thành công');
    }
    
    public function lockAccount(Request $request,$id)
    {
        $user = User::where('id',$id)->whereNull('deleted_at')->first();

        if(empty($user))
        {
            return response()->json([
                'error' =>true,
                'data'=> '',
                'message' => 'Error',
            ]);
        }

        if($user->status)
        {
            $user->status = 0;
            $user->save();
            return response()->json([
                'error' =>false,
                'data'=> $user,
                'message' => 'Success',
            ]);
        }
        else
        {
            $user->status = 1;
            $user->save();
            
            return response()->json([
                'error' =>false,
                'data'=> $user,
                'message' => 'Success',
            ]);
        }
    }

    public function searchUserDiscount(Request $request)
    {
        $key = $request->key;

        $list_user = User::whereNull('deleted_at')
                            ->where('email','LIKE','%'.$key.'%')
                            ->whereNotNull('active')
                            ->where('status',1)
                            ->offset(0)->limit(100)
                            ->get()->toArray();

        if(count($list_user) <= 0)
        {
            return response()->json([
                'error' =>true,
                'data'=> null,
                'message' => 'Không tìm thấy tài khoản',
            ]);
        }
        
        return response()->json([
            'error' =>false,
            'data'=> $list_user,
            'message' => 'Tìm tài khoản thành công',
        ]);
    }
}
