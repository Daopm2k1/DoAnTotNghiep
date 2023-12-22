<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'EShopper') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS-->
    {{-- <link href="{{ asset('user/css/bootstrap.min.css')}}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('admin/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="{{ asset('admin/vendor/font-awesome/css/font-awesome.min.css')}}">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="{{ asset('admin/css/fontastic.css')}}">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="{{ asset('admin/css/grasp_mobile_progress_circle-1.0.0.min.css')}}">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="{{ asset('admin/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css')}}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{ asset('admin/css/style.default.css')}}" id="theme-stylesheet">
    
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{ asset('admin/css/custom.css')}}">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ asset('vendor/images/icons/favicon.ico') }}">

    <link href="{{ asset('css/admin/custom.css')}}" rel="stylesheet">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
        <style>
          .error 
          {
            color: red;
            font-size: 12px;
            display: none;
          }
          .table td, .table th {
              vertical-align: middle;
          }
        </style>
    @yield('head')
  </head>
  <body>
    <!-- Side Navbar -->
    @include('layouts.admin.sidebar')
    <div class="page">
      <!-- navbar-->
      @include('layouts.admin.header')
      @include('layouts.admin.breadcrumb')
      @include('layouts.message.message_admin')
      @yield('content')
      @include('layouts.admin.footer')
    </div>
    <!-- JavaScript files-->
    <script src="{{ asset('admin/vendor/jquery/jquery.min.js')}}"></script>
    {{-- <script src="{{ asset('user/js/bootstrap.min.js')}}"></script> --}}
    <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('admin/js/grasp_mobile_progress_circle-1.0.0.min.js')}}"></script>
    <script src="{{ asset('admin/vendor/jquery.cookie/jquery.cookie.js')}}"> </script>
    <script src="{{ asset('admin/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{ asset('admin/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('admin/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <script src="{{ asset('admin/js/charts-home.js')}}"></script>
    {{-- <script src="{{ asset('ckeditor5/ckeditor.js')}}"></script> --}}
    <script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
    <!-- Main File-->
    <script src="{{ asset('admin/js/front.js')}}"></script>
    <script src="{{ asset('js/custom.js')}}"></script>
    @yield('script')
  </body>
</html>