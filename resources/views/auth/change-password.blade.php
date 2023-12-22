
@extends('layouts.admin')
@section('head')
	<style>
		.change-pass
		{
			margin-top: 50px;
		}
	</style>
@endsection
@section('content')

{{-- <div class="limiter">
	<div class="container-login100">
		<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50">
			<form class="login100-form validate-form" method="POST" action="{{route('admin.resetpassword.post',['id'=>$rows->id])}}">
				@csrf
				<span class="login100-form-title p-b-33">
					Reset password
				</span>

				<div class="wrap-input100 validate-input" data-validate = "Password is required">
					<input class="input100" type="password" name="password" placeholder="Password">
					<span class="focus-input100-1"></span>
					<span class="focus-input100-2"></span>
				</div>
				<div class="wrap-input100 rs1 validate-input" data-validate="confirm password is required">
					<input class="input100" type="password" name="confirm_password" placeholder="Confirm password">
					<span class="focus-input100-1"></span>
					<span class="focus-input100-2"></span>
				</div>
				<div class="container-login100-form-btn m-t-20">
					<button class="login100-form-btn" type="submit">
						Reset password
					</button>
				</div>
			</form>
		</div>
	</div>
</div> --}}

<section class="change-pass">
	<section>
		  <div class="container-fluid">
			<div class="row">
			  <div class="col-sm-8 offset-sm-2">
				  <div class="card">
					  <div class="card-body">
  
						  <form id="form-product" class="form-horizontal" method="POST" action="{{route('admin.resetpassword.post',['id'=>$rows->id])}}">
							  @csrf

							  <div class="form-group">
							  	<center><h1 class="title-change-pass">{{'Đặt lại mật khẩu'}}</h1></center>
							  </div>

							  <div class="form-group row">
									<div class="col-sm-12">
									<label>Mật khẩu mới</label>
									<input class="form-control" type="password" name="password" placeholder="Mật khẩu mới">
									<div class="error error-password" 	@if($errors->has('password')) style="display:block" @endif>{{$errors->first('password')}}</div>
									</div>
								</div>

							  <div class="form-group row">
								  <div class="col-sm-12">
									<label>Nhập lại mật khẩu</label>
									<input class="form-control" type="password" name="confirm_password" placeholder="Nhập lại mật khẩu">
									<div class="error error-confirm_password" 	@if($errors->has('confirm_password')) style="display:block" @endif>{{$errors->first('confirm_password')}}</div>
								  </div>
							  </div>

							  <div class="form-group row">       
								<div class="col-sm-12">
								  	<center><button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button></center>
								</div>
							  </div>
						  </form>
					  </div>
				  </div>
			  </div>
			</div>
		  </div>
	</section>
  </section>
@endsection
@section('footer')
<script>
	$('.close').click(function(){
		window.location.href = location.origin;
	});
</script>
@endsection