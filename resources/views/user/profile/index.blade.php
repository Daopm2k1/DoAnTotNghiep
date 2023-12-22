@extends('layouts.user')

    <!-- jQuery Circle-->
    <link rel="stylesheet" href="{{ asset('admin/css/grasp_mobile_progress_circle-1.0.0.min.css')}}">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="{{ asset('admin/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css')}}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{ asset('admin/css/style.default.css')}}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{ asset('admin/css/custom.css')}}">
    <!-- Favicon-->
    {{-- <link rel="shortcut icon" href="{{ asset('admin/img/favicon.ico')}}"> --}}

    <link href="{{ asset('css/admin/custom.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="{{ asset('admin/vendor/font-awesome/css/font-awesome.min.css')}}">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="{{ asset('admin/css/fontastic.css')}}">
    <style>
        .error   
        {
            color: red;
            font-size: 12px;
            display: none;
        }
        input.form-control-custom:checked + label::before {
            background: #FE980F !important;
        }
        input.form-control-custom + label {
            font-weight: 400;
            padding-left: 20px;
            padding-top: 1px;
            font-size: 14px;
            color: #333;
        }
        label {
            font-size: 14px;
        }
        #slider
        {
            display: none;
        }
        .shop-menu .navbar-nav li ul.sub-menu li {
            padding: 0 15px;
        }
    </style>
@section('content')
<section id="cart_items" class="">
    <div class="container">
        @include('layouts.user.breadcrumb')
        <h1>Thông tin tài khoản</h1>
        <hr>
        <div class="shopper-informations pb-50">
            <form id="form-user" class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{route('user.profile.store')}}">
                @csrf
                @if($flag = !empty($rows) && $rows->id)
                    <input type="hidden" value="{{$rows->id}}" name="id">
                @endif
                <div class="row form-group">
                    <div class="col-sm-6">
                        <label>Họ và tên</label>
                        <input id="username" type="text"  @if($flag) value="{{$rows->username}}" @endif name="username" placeholder="Họ và tên" class="form-control">
                        <div class="error error-username" 	@if($errors->has('username')) style="display:block" @endif>{{$errors->first('username')}}</div>
                      </div>

                      <div class="col-sm-6">
                        <label>Email</label>
                        <input id="email" type="Email" name="email" @if($flag) value="{{$rows->email}}" @endif placeholder="Email" class="form-control">
                        <div class="error error-email" 	@if($errors->has('email')) style="display:block" @endif>{{$errors->first('email')}}</div>
                    </div>	
                </div>		

                <div class="row form-group">
                    <div class="col-sm-6">
                        <label>Số điện thoại</label>
                        <input id="phone" type="text" name="phone" @if($flag) value="{{$rows->phone}}" @endif placeholder="Số điện thoại" class="form-control "">
                        <div class="error error-phone" 	@if($errors->has('phone')) style="display:block" @endif>{{$errors->first('phone')}}</div>
                    </div>	

                    <div class="col-sm-6">
                        <label>Ngày sinh</label>
                        <input id="date_of_birth" type="date" name="date_of_birth" @if($flag && !empty($rows->date_of_birth)) value="{{\Carbon\Carbon::parse($rows->date_of_birth)->format('Y-m-d')}}" @endif placeholder="" class="form-control ">
                        <div class="error error-date_of_birth" 	@if($errors->has('date_of_birth')) style="display:block" @endif>{{$errors->first('date_of_birth')}}</div>
                      </div>	
                </div>		

                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="label-check">Giới tính</label>
                        <div class="i-checks">
                            @php 
                                if($flag)
                                {
                                    $gender = $rows->gender ? true : false;
                                }
                            @endphp
                            <input id="radioCustom1" type="radio" value="1" @if((!empty($rows->gender) && $gender) || empty($rows->gender)) checked @endif name="gender" class="form-control-custom radio-custom">
                            <label for="radioCustom1">Nam</label>
                        </div>
                        <div class="i-checks">
                            <input id="radioCustom2" type="radio" value="0" @if(!empty( $rows->gender) && !$gender)  checked @endif name="gender" class="form-control-custom radio-custom">
                            <label for="radioCustom2">Nữ</label>
                        </div>
                        <div class="error error-gender" 	@if($errors->has('gender')) style="display:block" @endif>{{$errors->first('gender')}}</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label class="label-check">Ảnh đại diện</label>
                        <div class="custom-file">
                            <input type="file" name="avatar" @if($flag) value="{{public_path().$rows->avatar}}" @endif accept=".jpg, .jpeg, .png" class="custom-file-input" id="file">
                            <label style="    border-radius: 5px !important;border: none;background-color: #F0F0E9;" class="custom-file-label" id="label-file" for="customFile">Chọn ảnh</label>
                            <div class="error error-avatar" 	@if($errors->has('avatar')) style="display:block" @endif>{{$errors->first('avatar')}}</div>
                        </div>                                
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <img id="ImgPre" @if($flag && !empty($rows->avatar)) src="{{ asset($rows->avatar)}}" @else src="{{ asset('images/none-user.png')}}" @endif alt="Alternate Text" class="img-thumbnail" />
                    </div>
                </div>

                <div class="form-group row">       
                    <div class="col-sm-12"> 
                        <button type="submit" id="create" class="btn btn-primary btn-custom">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section> <!--/#cart_items-->
@endsection
@section('script')
  <script>
    function readURL(input, idImg) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(idImg).attr("src", e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#file").change(function () {
        readURL(this, "#ImgPre");
    });
    function isEmail(email) {
        let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
    
  </script>
  <script src="{{ asset('js/admin_user.js')}}"></script> 
@endsection
