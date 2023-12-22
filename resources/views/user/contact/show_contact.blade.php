@extends('layouts.user')
@section('content')

<div id="contact-page" class="container">
    <div class="bg">
        <div class="row">    		
            <div class="col-sm-12">    			   			
                <h2 class="title text-center">Liên hệ với chúng tôi</h2>    			    				    				
                <!-- <div id="gmap" class="contact-map">
                </div> -->
            </div>			 		
        </div>    	
        <div class="row">  	
            <div class="col-sm-8">
                <div class="contact-form">
                    <h2 class="title text-center">Liên lạc</h2>
                    <div class="status alert alert-success" style="display: none"></div>
                    <form id="form-contact" action="" method="POST" class="searchform">
                    @csrf
                        <input name="name" id="contact-name"  type="text" placeholder="Nhập họ tên"/>
                        <div class="error error-name" 	@if($errors->has('name')) style="display:block" @endif>{{$errors->first('name')}}</div>
                        <input name="email" id="contact-email"  type="text" placeholder="Nhập email"/>
                        <div class="error error-email" 	@if($errors->has('email')) style="display:block" @endif>{{$errors->first('email')}}</div>
                        <textarea id="contact-content" name="content"  rows="5" placeholder="Nhập nội dung"></textarea>
                        <div class="error error-content" @if($errors->has('content')) style="display:block" @endif>{{$errors->first('content')}}</div>
                        
                        <button class="btn btn-default btn-send btn-custom">Gửi</button>
                    </form>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="contact-info">
                    <h2 class="title text-center">Thông tin liên hệ</h2>
                    <address>
                        <p>E-Shopper Inc.</p>
                        <p>50 Đường Số 7, Khu Dân Cư City Land, Phường 7, Quận Gò Vấp, Hồ Chí Minh</p>
                        <p>TP Hồ Chí Minh</p>
                        <p>Mobile: +2346 17 38 93 +0988.422.755</p>
                        <p>Email: zuman.officialvn@gmail.comm</p>
                    </address>
                    <div class="social-networks">
                        <h2 class="title text-center">Mạng xã hội</h2>
                        <ul>
                            <li>
                                <a href="#"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-youtube"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>    			
        </div>  
    </div>	
</div><!--/#contact-page-->
@endsection