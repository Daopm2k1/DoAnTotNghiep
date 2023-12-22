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
<div class="page register-page">
	<div class="container">
	  <div class="row">
		  <div class="col-md-6 offset-md-3 col-12 ">
			<div class="form-outer text-center d-flex align-items-center">
				<div class="form-inner" style="width:100%">
				  <form method="post" action="{{route('register.post')}}" id="form-register" class="text-left">
					@csrf
					<center> <a href="{{route('home')}}"><img src="{{ ('user/images//home/logo.png') }}" alt="" /></a></center>
					<br/>
					<div class="form-group-material">
					  	<input id="register-username" type="text" name="username" class="input-material">
					  	<label for="register-username" class="label-material">Họ và tên</label>
					  	<div class="error error-username" 	@if($errors->has('username')) style="display:block" @endif>{{$errors->first('username')}}</div>
					</div>
					<div class="form-group-material">
						<input id="register-email" type="text" name="email" class="input-material">
						<label for="register-email" class="label-material">Email</label>
						<div class="error error-email" 	@if($errors->has('email')) style="display:block" @endif>{{$errors->first('email')}}</div>
					  </div>
					<div class="form-group-material">
					  <input id="register-password" type="password" name="password" class="input-material">
					  <label for="register-password" class="label-material">Mật khẩu</label>
					  <div class="error error-password" @if($errors->has('password')) style="display:block" @endif>{{$errors->first('password')}}</div>
					</div>
					<div class="form-group-material">
						<input id="register-confirm_password" type="password"	name="confirm_password" class="input-material">
						<label for="register-confirm_password" class="label-material">Nhập lại mật khẩu</label>
						<div class="error error-confirm_password" @if($errors->has('confirm_password')) style="display:block" @endif>{{$errors->first('confirm_password')}}</div>
					  </div>
					<div class="form-group text-center">
						<span style="background-color:#FE980F;border-color:#FE980F" id="register" class="btn btn-custom btn-primary">Đăng ký</span>
					  <!-- This should be submit button but I replaced it with <a> for demo purposes-->
					</div>
				  </form>
				  {{-- <a href="#" class="forgot-pass">Forgot Password?</a> --}}
				  <small>Bạn đã có tài khoản? </small><a href="{{route('login')}}" class="signup">Đăng nhập</a>
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
	$(document).ready(function(){
		
	})
	$('input').keyup(function(e) {
			if (e.which === 13) {
				e.preventDefault();
				if(!validate())
				{
					$('#form-register').submit();
				}
			}
		});
	function isEmail(email) {
		let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}
	function validate()
	{
		let flag =false;

		//Check username
		if($('#register-username').val()=='')
		{
			$('.error-username').html('Nhập họ và tên');
			$('.error-username').css('display','block');
			flag =true;
		}
		else
		{
			$('.error-username').html('');
			$('.error-username').css('display','none');
		}

		//Check email
		if($('#register-email').val()=='')
		{
			$('.error-email').html('Nhập email');
			$('.error-email').css('display','block');
			flag =true;
		}
		else if(!isEmail($('#register-email').val()))
		{
			
			$('.error-email').html('Email không hợp lệ');
			$('.error-email').css('display','block');
			flag =true;
		}
		else
		{
			$('.error-email').html('');
			$('.error-email').css('display','none');
		}

		//Check password
		if($('#register-password').val()=='')
		{
			$('.error-password').html('Nhập mật khẩu');
			$('.error-password').css('display','block');
			flag =true;
		}
		else if($('#register-password').val().length < 8)
		{
			$('.error-password').html('Mật khẩu phải dài ít nhất 8 kí tự');
			$('.error-password').css('display','block');
			flag =true;
		}

		else
		{
			$('.error-password').html('');
			$('.error-password').css('display','none');
		}

		//Check confirm password
		if($('#register-confirm_password').val()=='')
		{
			$('.error-confirm_password').html('Nhập lại mật khẩu');
			$('.error-confirm_password').css('display','block');
			flag =true;
		}
		else if($('#register-confirm_password').val() != $('#register-password').val())
		{
			$('.error-confirm_password').html('Nhập lại mật khẩu không trùng khớp');
			$('.error-confirm_password').css('display','block');
			flag =true;
		}
		else
		{
			$('.error-confirm_password').html('');
			$('.error-confirm_password').css('display','none');
		}
		
		return flag;
	}

	$('#register').click(function(){
		if(!validate())
		{
			$('#form-register').submit();
		}
	});	
</script>
@endsection