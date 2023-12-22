<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Mail;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Crypt;
use Session;
use App\Models\Category;
use App\Models\PostCate;
use App\Models\InforContact;
use App\Models\Comment;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail as FacadesMail;

class UserController extends Controller
{
    public function getLogin(Request $request)
    {
        if(Cookie::get('remember_me'))
        {
            $remember_me = Crypt::decryptString(Cookie::get('remember_me'));
            $arr = json_decode($remember_me);
        }

        if(!empty($request->detail))
        {
            Session::put('url_previous',url()->previous());
        }
        if(Auth::check())
        {
            return redirect('/');
        }

        return view('auth.login', [ 'arr' => $arr ?? null] )->withInput($request->except('email'));
    }
    public function postLogin(Request $request)
    {
        $rules = [
            'email'    => 'required|email',
            'password' => 'required'
        ];
        $messages = [
            'email.required'    => 'Nhập email',
            'email.email'       => 'Email không hợp lệ',
            'password.required' => 'Nhập password',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } 

        $arr = [
            'email' =>$request->email,
            'password' =>$request->password
        ]; 
        $remember = $request->has('remember') ? true : false ;
        // dd($remember);

        if(Auth::attempt($arr,$remember))
        { 
            if($remember)
            {
                $expired_time= 1000 * 60 * 60 * 24 * 30;
                $remember_me = Crypt::encryptString(json_encode($arr));
                Cookie::queue('remember_me', $remember_me, $expired_time);
            }
            else{
                if(Cookie::get('remember_me'))
                {
                    Cookie::queue(Cookie::forget('remember_me'));
                }
            }
            
            $request->session()->regenerate();

            if(Auth::user()->status == 0)
            {
                Auth::logout();
                return redirect()->back()->with('error','Tài khoản của bạn đã bị khóa !');
            }
            if(empty(Auth::user()->active))
            {
                Auth::logout();
                return redirect()->back()->with('error','Tài khoản của bạn chưa được kích hoạt !');
            }
            if(Auth::user()->deleted_at != null)
            {
                Auth::logout();
                return redirect()->back()->with('error','Tài khoản hoặc mật khẩu không đúng !');
            }
            
            if(Session::has('url_previous'))
            {
                $url = Session::get('url_previous');
                Session::forget('url_previous');
                return Redirect::to($url);
            }

            if(Auth::user()->hasRole('administrator'))
            {
                return redirect('/admin/dashboard');
            }
           
            return redirect('/');
        }
        return redirect()->back()->with('error','Tài khoản hoặc mật khẩu không đúng !');
    }

    public function logout(Request $request)
    {
        $session_cart = Session::get('cart');
        if($session_cart !=null){
            foreach($session_cart as $key=>$item)
            {
                unset($session_cart[$key]['discount']);
            }
            Session::put('cart', $session_cart);
        }

        if(Session::has('discount_cart'))
        {
            Session::forget('discount_cart');
        }
        
        Auth::logout();
        return redirect()->back();
    }
    public function getRegister(){
        if(Auth::check())
        {
            return redirect('/');
        }
        return view('auth.register');
    }
    public function postRegister(Request $request)
    {
        
        $rule = [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ];
        $messages = [
            'required' => 'Nhập :attribute',
            'confirm_password.required'=>"Nhập lại mật khẩu",
            'email.email' => 'Email không hợp lệ',
            'password.min' => 'Mật khẩu phải dài ít nhất :min kí tự.',
            'confirm_password.same' => 'Nhập lại mật khẩu không trùng khớp'
        ];
        $customName =[
            'email' => 'email',
            'username' => 'họ và tên',
            'password' => 'mật khẩu',
            'confirm_password' => 'Nhập lại mật khẩu'
        ];
        $validator = Validator::make($request->all(),$rule,$messages,$customName);
        if($validator->fails())
        {
            // dd($validator->fails());
            return redirect()->back()->withErrors($validator);
        }

        do {
            $str_random = Str::random(100);  
            $userCheckRandom = User::where('string_verify', $str_random )->first();
        }while(!empty($userCheckRandom));

        $userCheck = User::where('email',$request->email)->first();
        if(!empty($userCheck))
        {
            return redirect()->back()->with('error','Email đã tồn tại');
        }
        $user = new User();

        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->string_verify = $str_random;
        $user->assignRole('customer');
        $user->save();
        // $url = url('active/'.$user->email.'/'.$user->string_verify);
        // FacadesMail::send('emails.account.verifyEmail', compact('url'), function($email) use ($user) {
        //     $email->to($user->email, $user->username);
        //     $email->subject('Kích hoạt tài khoản');
        // });
        FacadesMail::to($user->email)->send(new \App\Mail\ActiveAccount($user->email,$user->string_verify));

    return redirect()->back()->with('success', 'Vui lòng kiếm tra email để kích hoạt');
    }

    public function activeAccount(Request $request, $email = '', $string = '')
    {
        // dd($email, $string);
        if(empty($email) || empty($string))
        {
            abort(404);
        }
        $user = User::where('email', $email)->where('string_verify',$string)->first();
        if(empty($user))
        {
            abort(404);
        }
        $user->active = Carbon::now();
        $user->save();

        Auth::loginUsingId($user->id,true);
        return redirect('/');
    }

    public function getChangePassword(Request $request)
    {
        if(!Auth::check())
        {
            abort(404);
        }

        $categories = Category::whereNull('deleted_at')->get();
        $category_post = PostCate::where('status',1)->get();
        $infor_contact = InforContact::all();

        $data = [
            'categories' => $categories,
            'category_post' => $category_post,
            'infor_contact' => $infor_contact,
            'title' => 'Đổi mật khẩu',
        ];
        return view('user.password.password',$data);
    }

    public function postChangePassword(Request $request)
    {
        if(!Auth::check())
        {
            abort(404);
        }

        $rule = [
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ];
        $messages = [
            'required' => 'Nhập :attribute',
            'confirm_password.required' => 'Nhập lại mật khẩu',
            'old_password.min' => 'Mật khẩu cũ ít nhất 8 kí tự.',
            'new_password.min' => 'Mật khẩu mới ít nhất 8 kí tự.',
            'confirm_password.min' => 'Nhập lại mật khẩu ít nhất 8 kí tự.',
            'confirm_password.same' => 'Nhập lại mật khẩu không trùng khớp.'
        ];
        $customName =[
            'old_password' => 'mật khẩu cũ',
            'new_password' => 'mật khẩu mới',
            'confirm_password' => 'nhập lại mật khẩu'
        ];

        $validator = Validator::make($request->all(),$rule,$messages,$customName);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator);
        }

        $user= Auth::user();

        if(Hash::check($request->new_password,$user->password))
        {
            return redirect()->back()->withErrors(['new_password'=>'Mật khẩu mới trùng với mật khẩu cũ']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return  redirect()->back()->with('success','Đổi mật khẩu thành công');
    }
}
