
@extends('layouts.user')
@section('head')
    <style>
        .error
        {
            padding-top: 2px;
        }
        .p-30
        {
            padding: 30px 0;
        }
    </style>
@endsection
@section('content')

<section id="cart_items" class="section-checkout">
    <div class="container">
        @include('layouts.user.breadcrumb')
        <h1>Đổi mật khẩu</h1>
        <hr>

        <div class="shopper-informations pb-50">
            <div class="row">		
                <div class="col-sm-2">
                </div>
                <div class="col-sm-8">
                    <div class="shopper-info">
                        <form id="form-checkout" action="{{ route('user.change_password.post') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-12 p-30">
                                    <center><a class="" href="{{route('home')}}"><img src="{{ asset('user/images/home/logo.png') }}" alt="" /></a></center>
                                </div>
                            </div>
                    
                            <div class="form-group">
                                <label for="old_password">Mật khẩu cũ </label>
                                <input type="password" id="old_password" name="old_password" placeholder="Nhập mật khẩu cũ" class="form-control input-checkout" >
                                <div class="error error-old_password" 	@if($errors->has('old_password')) style="display:block" @endif>{{$errors->first('old_password')}}</div>
                            </div>
                            <div class="form-group">
                                <label for="new_password">Mật khẩu mới </label>
                                <input type="password" id="new_password" name="new_password"  placeholder="Nhập mật khẩu mới" class="form-control input-checkout" >
                                <div class="error error-new_password" 	@if($errors->has('new_password')) style="display:block" @endif>{{$errors->first('new_password')}}</div>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Nhập lại mật khẩu </label>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" class="form-control input-checkout" >
                                <div class="error error-confirm_password" 	@if($errors->has('confirm_password')) style="display:block" @endif>{{$errors->first('confirm_password')}}</div>
                            </div>
                            <br>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <center><button class="btn btn-custom" type="submit" id="btn-complete">Đổi mật khẩu</button></center>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
              	
            </div>
        </div>
    </div>
</section> <!--/#cart_items-->
@endsection
@section('script')
<script>
    $('#form-checkout').submit(function(e){
        let flag = false;
        let old_password = $('#old_password').val();
        let new_password = $('#new_password').val();
        let confirm_password = $('#confirm_password').val();

        //Check old password
        if(old_password == "")
        {
            $('.error-old_password').html('Nhập mật khẩu cũ');
            $('.error-old_password').css('display','block');
            flag = true;
        }
        else if(old_password.length < 8)
        {
            $('.error-old_password').html('Mật khẩu cũ có ít nhất 8 kí tự');
            $('.error-old_password').css('display','block');
            flag = true;
        }
        else
        {
            $('.error-old_password').html('');
            $('.error-old_password').css('display','none');
        }

        //Check new password
        if(new_password == "")
        {
            $('.error-new_password').html('Nhập mật khẩu mới');
            $('.error-new_password').css('display','block');
            flag = true;
        }
        else if(new_password.length < 8)
        {
            $('.error-new_password').html('Mật khẩu mới có ít nhất 8 kí tự');
            $('.error-new_password').css('display','block');
            flag = true;
        }
        else
        {
            $('.error-new_password').html('');
            $('.error-new_password').css('display','none');
        }

        //Check confirm password
        if(confirm_password == "")
        {
            $('.error-confirm_password').html('Nhập lại mật khẩu');
            $('.error-confirm_password').css('display','block');
            flag = true;
        }
        else if(confirm_password != new_password)
        {
            $('.error-confirm_password').html('Nhập lại mật khẩu không trùng mật khẩu mới');
            $('.error-confirm_password').css('display','block');
            flag = true;
        }
        else
        {
            $('.error-confirm_password').html('');
            $('.error-confirm_password').css('display','none');
        }

        if(flag)
        {
            e.preventDefault();
        }
    })
</script>
@endsection