@extends('layouts.login')
@section('head')
    <style>
        .justify-content-center
        {
            height: 100%;
            display: flex;
        }
        .col-md-6
        {
            place-self: center;
        }
        .container
        {
            height:100vh;
        }
        button[type="submit"],
        button[type="submit"]:hover,
        button[type="submit"]:active
        {
            /* background-color: #FE980F !important; */
            border-color: #FE980F !important;
            box-shadow:none;
        }
        .card
        {
            border: 1px solid rgba(0,0,0,.125);
        }
        .error 
		{
			color: red;
			font-size: 12px;
			display: none;
		}
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
        .btn-center{
            text-align-last: center;
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
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <center><a href="{{route('home')}}"><img src="{{ asset('user/images//home/logo.png') }}" alt="" /></a></center>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group-material">
                                    <input id="email" type="email" disabled class="input-material @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                    <label for="login-email" class="label-material">Email</label>
                                     @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group-material">
                                    <input id="password" type="password" class="input-material @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    <label for="login-email" class="label-material">Mật khẩu mới</label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group-material">
                                    <input id="password-confirm" type="password" class="input-material" name="password_confirmation" required autocomplete="new-password">
                                    <label for="login-email" class="label-material">Nhập lại mật khẩu</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12 btn-center" > 
                                <button type="submit" class="btn btn-primary btn-custom">
                                    {{ __('Đổi mật khẩu') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
