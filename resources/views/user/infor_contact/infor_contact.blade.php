@extends('layouts.user')
@section('content')
@foreach($infor_contact as $key => $contact)
<div id="contact-page" class="container">
    <div class="bg">
        <!-- <div class="row">    		
            <div class="col-sm-12">    			   			
                
            </div>			 		
        </div>    	 -->
        <div class="row">  	
            <div class="col-sm-8">
                <h2 class="title text-center">Liên hệ với chúng tôi</h2>    			    				    				
                <div id="map" class="contact-map">
                {!!$contact->map!!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="contact-info">
                    <h2 class="title text-center">Thông tin liên hệ</h2>
                    <address>
                        <p>E-Shopper Inc.</p>
                        <p>Địa chỉ: {{$contact->address}}</p>
                        <p>Điện thoại: {{$contact->phone}}</p>
                        <p>Thời gian mở cửa: {{$contact->time}}</p>
                        <p>Email: {{$contact->email}}</p>
                    </address>
                    
                </div>
            </div>    			
        </div>  
    </div>	
</div><!--/#contact-page-->
@endforeach
@endsection