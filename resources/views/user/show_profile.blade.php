@extends('layouts.user')

@section('content')

<section>
    <div class="container">
        <div class="row">
            
            <div class="col-sm-9 padding-right">
                @foreach($user_name as $key=>$profile)
                    <div class="product-details"><!--product-details-->
                        
                        <div class="col-sm-7">
                            <div class="product-information"><!--/product-information-->
                                <img src="images/product-details/new.jpg" class="newarrival" alt="" />
                                <h2>{{$profile->username}}</h2>
                                <p>{{('Email: '.$profile->email)}}</p>
                                <img src="images/product-details/rating.png" alt="" />
                                
                                                  
                            </div><!--/product-information-->
                        </div>
                    </div><!--/product-details-->
                    
                    @endforeach
                    
				</div>
        </div>
    </div>
</section>

@endsection
