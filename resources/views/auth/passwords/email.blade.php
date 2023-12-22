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
        .forgot-pass
        {
            color: #66b0ff;
            text-decoration: none;
            font-size: 0.8em;
        }
        .forgot-pass:hover
        {
            color: #66b0ff;
            text-decoration: none;
            font-size: 0.8em;
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
                    @if (session('status'))
                        <div class="alert alert-success mt-3 mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" class="mt-5" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            {{-- <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label> --}}

                            <div class="col-md-12">
                                <div class="form-group-material">
                                    <input id="login-emai" type="email" class="input-material" @error('email') is-invalid @enderror name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    <label for="login-email" class="label-material">Email</label>
                                    <div class="error error-email" 	@if($errors->has('email')) style="display:block" @endif>{{$errors->first('email')}}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12 btn-center" > 
                                <button type="submit" class="btn btn-primary btn-custom">
                                    {{ __('Đổi mật khẩu') }}
                                </button>
                            </div>
                            <div class="col-md-12 btn-center" > 
                                <a href="{{url('/login')}}" class="forgot-pass">Đăng nhập</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
