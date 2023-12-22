@extends('layouts.login')
@section('head')
	<style>
		.btn-primary:not([disabled]):not(.disabled):active{
			-webkit-box-shadow: none;
			box-shadow:none;
		}
		input.input-material ~ label.active {
			font-size: 0.8rem;
			top: -10px;
			color: #FE980F;
		}
		input.input-material:focus {
			border-color: #FE980F;
			outline: none;
		}
		.btn-primary:focus, .btn-primary.focus {
			-webkit-box-shadow: unset;
			box-shadow: unset;
		}
		.error 
		{
			color: red;
			font-size: 12px;
			display: none;
		}
		.btn-custom {
			border-radius: 5px !important;
			border: 1px #0a0a0a0f solid !important;
			box-shadow: 0px 4px 14px rgb(11 69 132 / 15%);
			background-color: #FE980F !important;
			color: white !important;
			border: 2px solid #FE980F !important;
			transition: all 0.3s ease 0s;
		}
		.btn-custom:hover{
			/* border: 2px solid white !important;  */
			background-color: white !important;
			color: #FE980F !important;
		}
		.btn
		{
			border-radius:  5px !important;
		}
	</style>
@endsection
@section('content')
{{-- @php if(!empty(Cookie::get('email'))) dd(Cookie::get('email')) @endphp --}}
<div class="page login-page">
	<div class="container">
	  <div class="row">
		  <div class="col-md-6 offset-md-3 col-12 ">
			<div class="form-outer text-center d-flex align-items-center">
				<div class="form-inner" style="width:100%">
				  <form method="post" id="form-login" class="text-left" action="{{route('login')}}">
					@csrf
					<center><a href="{{route('home')}}"><img src="{{ asset('user/images/home/logo.png') }}" alt="" /></a></center>
					<br/>
					@if(isset($_GET['code']) && $_GET['code'] == 401)
						<div class="alert alert-warning alert-dismissible fade show" role="alert">
						{{'Phiên của bạn đã hết. Vui lòng đăng nhập lại.'}}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						</div>
					@endif
					<div class="form-group-material">
					  <input id="login-email" value="{{ $arr->email ?? '' }}" autocomplete="email" autofocus type="text" name="email" class="input-material">
					  <label for="login-email" class="label-material">Email</label>
					  <div class="error error-email" 	@if($errors->has('email')) style="display:block" @endif>{{$errors->first('email')}}</div>
					</div>
					<div class="form-group-material">
					  <input value="{{ $arr->password ?? '' }}" id="login-password" autocomplete="current-password" type="password" name="password" class="input-material">
					  <label for="login-password" class="label-material">Mật khẩu</label>
					  <div class="error error-password" @if($errors->has('password')) style="display:block" @endif>{{$errors->first('password')}}</div>
					</div>
					<div class="form-group-material">
						<input id="remember" type="checkbox" {{ !empty($arr) ? 'checked' : '' }} name="remember" class="" value="0" style="border: #fff 1px solid !important;">
						<label style="color:#aaa;display: inline;padding-left: 10px;" for="option">Nhớ mật khẩu</label>
					</div>
					<div class="form-group text-center">
						  <button style="submit" style="background-color:#FE980F;border-color:#FE980F" id="login" type="submit" class="btn btn-primary btn-custom">Đăng nhập</button>
					<!-- This should be submit button but I replaced it with <a> for demo purposes-->
					</div>
				  </form>
				  <a href="{{route('password.update')}}" class="forgot-pass">Quên mật khẩu?</a>
				  <small>Bạn chưa có tài khoản? </small><a href="{{route('register.get')}}" class="signup ">Đăng ký</a>
				</div>
				<div class="copyrights text-center">
				  {{-- <p>Design by <a href="https://bootstrapious.com/p/bootstrap-4-dashboard" class="external">Bootstrapious</a></p> --}}
				  <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
				</div>
			  </div>
		  </div>
	  </div>
	</div>
  </div>
@endsection
@section('script')
<script>
	$('input').keyup(function(e) {
			if (e.which === 13) {
				e.preventDefault();
				if(!validate())
				{
					$('#form-login').submit();
				}
			}
		});
	function isEmail(email) {
		let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}
	function validate()	{
		let flag = false;
		if($('#login-email').val()=='')
		{
			$('.error-email').html('Nhập email');
			$('.error-email').css('display','block');
			flag = true;
		}
		else
		{
			if(!isEmail($('#login-email').val()))
			{
				$('.error-email').html('Email không hợp lệ');
				$('.error-email').css('display','block');
				flag = true;
			}
			else
			{
				$('.error-email').html('');
				$('.error-email').css('display','none');
			}
		}

		if($('#login-password').val()=='')
		{
			$('.error-password').html('Nhập mật khẩu');
			$('.error-password').css('display','block');
			flag = true;
		}
		else
		{
			if($('#login-password').val().length < 8)
			{
				$('.error-password').html('Mật khẩu phải dài ít nhất 8 kí tự');
				$('.error-password').css('display','block');
				flag = true;
			}
			else
			{
				$('.error-password').html('');
				$('.error-password').css('display','none');
			}
		}
		// console.log(flag);
		return flag;
	}
	$('#form-login').submit(function(e){
		if(validate())
		{
			e.preventDefault();
		}
	});
</script>
@endsection