@extends('errors::minimal')
@section('head')
    <link href="{{ asset('user/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('user/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('user/css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('user/css/price-range.css') }}" rel="stylesheet">
    <link href="{{ asset('user/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('user/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('user/css/responsive.css') }}" rel="stylesheet">
    <style>
        .code {
             border-right: none;
        }
        .logo-404 {
            margin-bottom: 20px;
            margin-top: 30px;
        }
    </style>
@endsection
@section('title', __('Not Found'))
@section('code')
<div class="container text-center">
    <div class="logo-404">
        <a href="{{url('/')}}"><img src="{{ asset('user/images/home/logo.png ') }}" alt="" /></a>
    </div>
    <div class="content-404">
        <img width="300px" src="{{ asset('user/images/404/404.png')}}" class="img-responsive" alt="" />
        <h1><b>Oh no!</b> Trang web không tìm thấy</h1>
        {{-- <p>Uh... So it looks like you brock something. The page you are looking for has up and Vanished.</p> --}}
        <h2><a href="{{url('/')}}">Về trang chủ</a></h2>
    </div>
</div>
@endsection
@section('script')
    <script src="{{ asset('user/js/jquery.js') }}"></script>
    <script src="{{ asset('user/js/price-range.js') }}"></script>
    <script src="{{ asset('user/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('user/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('user/js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('user/js/main.js') }}"></script>
@endsection
{{-- @section('message', __('Not Found')) --}}
