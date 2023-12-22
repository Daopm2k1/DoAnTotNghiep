
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
       <!-- Bootstrap CSS-->
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
	   
     <link href="{{ asset('user/css/sweetalert.css')}}" rel="stylesheet">
     <link rel="stylesheet" href="{{ asset('admin/css/custom.css')}}">
	   <!-- Favicon-->
	   {{-- <link rel="shortcut icon" href="{{ asset('admin/img/favicon.ico')}}"> --}}
   
	   <link href="{{ asset('css/admin/custom.css')}}" rel="stylesheet">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
		@yield('head')
  </head>
  <body>
   @yield('content')
   <!-- JavaScript files-->
   <script src="{{ asset('admin/vendor/jquery/jquery.min.js')}}"></script>
   <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
   <script src="{{ asset('admin/js/grasp_mobile_progress_circle-1.0.0.min.js')}}"></script>
   <script src="{{ asset('admin/vendor/jquery.cookie/jquery.cookie.js')}}"> </script>
   <script src="{{ asset('admin/vendor/chart.js/Chart.min.js')}}"></script>
   <script src="{{ asset('admin/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
   <script src="{{ asset('admin/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')}}"></script>
   <script src="{{ asset('admin/js/charts-home.js')}}"></script>
   <script src="{{ asset('user/js/sweetalert.min.js')}}"></script>
   <!-- Main File-->
   <script src="{{ asset('admin/js/front.js')}}"></script>
   <script>
     $(document).on("keydown","input[type=text],input[type=email],input[type=password],textarea" ,function(evt){
      var firstChar = $(this).val();
      if(evt.keyCode == 32 && firstChar == ""){
        return false;
      }
    });
   </script>
   @yield('script')
   @include('layouts.message.alert-message')
  </body>
</html>