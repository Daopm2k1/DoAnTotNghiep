@extends('layouts.user')
@section('content')
<section>
    <div class="container">
        <div class="row">
            @include('user.parts.category')
            
            <div class="col-sm-9 padding-right">
            <div class="features_items"><!--features_items-->
                <h2 class="title text-center">Sản phẩm</h2>
                @foreach($products as $key => $pro)
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="{{asset('images/product/14.jpg')}}" alt="" />
                                        <h2>{{number_format($pro->price)}}</h2> <!--number_format chỉnh sửa số-->
                                        <p>{{$pro->name}}</p>
                                    <!--     <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Thêm vào giỏ hàng</a> -->
                                    </div>
                                    <div class="product-overlay">
                                        <div class="overlay-content">
                                            <h2>{{number_format($pro->price)}}</h2>
                                            <p>{{$pro->name}}</p>
                                            <a href="{{URL::to('detail-product/'.$pro->id)}}" class="btn btn-default add-to-cart"><i class="fa fa-asterisk" aria-hidden="true"></i>Chi tiết sản phẩm</a>
                                        <!--       <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Thêm vào giỏ hàng</a> -->
                                        </div>
                                    </div>
                            </div>
                            <div class="choose">
                                <ul class="nav nav-pills nav-justified">
                                    <li><a href="#"><i class="fa fa-gratipay" aria-hidden="true"></i>Yêu thích</a></li>
                                    <li><a href="#"><i class="fa fa-compress" aria-hidden="true"></i>So sánh</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div><!--/features_items-->
        </div>
    </div>
</section>
@endsection
@section('menu')
<div class="mainmenu pull-left">
    <ul class="nav navbar-nav collapse navbar-collapse">
        <li><a href="{{URL::to('home')}}" class="active">Trang chủ</a></li>
        <li class="dropdown"><a href="{{URL::to('/')}}">Sản phẩm<i class="fa fa-angle-down"></i></a>
            <ul role="menu" class="sub-menu">
            @foreach($categories as $key=>$cate)
                <li><a href="{{URL::to('category-product/'.$cate->id)}}">{{$cate->name}}</a></li> 
            @endforeach
            </ul>
        </li>
        <li><a href="#">Giỏ hàng</a></li>
    </ul>
</div>
@endsection