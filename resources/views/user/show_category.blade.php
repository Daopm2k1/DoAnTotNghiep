@extends('layouts.user')

@section('content')

<section>
    <div class="container">
        <div class="row">
            @include('user.parts.category')
            
            <div class="col-sm-9 padding-right">
                <div class="features_items">
                @foreach($category_name as $key=>$name)
                    <h2 class="title text-center">{{$name->name}}</h2>
                @endforeach
                
                @foreach($category_by_id as $key => $pro)
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                    <div class="productinfo text-center">
                                        <!-- <img src="{{asset('images/product/14.jpg')}}" alt="" />
                                        <h2>{{number_format($pro->price).' VNĐ'}}</h2>
                                        <p>{{$pro->name}}</p> -->
                                        <form>
                                        @csrf
                                            <input type="hidden" value="{{$pro->id}}" class="cart_product_id_{{$pro->id}}">
                                            <input type="hidden" value="{{$pro->name}}" class="cart_product_name_{{$pro->id}}">
                                            <input type="hidden" value="{{$pro->price}}" class="cart_product_price_{{$pro->id}}">
                                            <input type="hidden" value="1" class="cart_product_qty_{{$pro->id}}">

                                            <a href="{{URL::to('/detail-product/'.$pro->id)}}">
                                                <img src="{{asset('images/product/14.jpg')}}" alt="" />
                                                <h2>{{number_format($pro->price).' '.'VNĐ'}}</h2>
                                                <p>{{$pro->name}}</p>
                                            </a>
                                            <button type="button" class="btn btn-default add-to-cart" data-id_product="{{$pro->id}}" name="add-to-cart">
                                            Thêm giỏ hàng
                                            </button>
                                        </form>
                                    </div>
                                    <!-- <div class="product-overlay">
                                        <div class="overlay-content">
                                            <h2>{{number_format($pro->price)}}</h2>
                                            <p>{{$pro->name}}</p>
                                            <a href="{{URL::to('detail-product/'.$pro->id)}}" class="btn btn-default add-to-cart"><i class="fa fa-asterisk" aria-hidden="true"></i>Chi tiết sản phẩm</a>
                                        </div>
                                    </div> -->
                            </div>
                            <!-- <div class="choose">
                                <ul class="nav nav-pills nav-justified">
                                    <li><a href="#"><i class="fa fa-gratipay" aria-hidden="true"></i>Yêu thích</a></li>
                                    <li><a href="#"><i class="fa fa-compress" aria-hidden="true"></i>So sánh</a></li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                @endforeach
                </div><!--/features_items-->
                
                {{--<div class="category-tab"><!--category-tab-->
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tshirt" data-toggle="tab">T-Shirt</a></li>
                            <li><a href="#blazers" data-toggle="tab">Blazers</a></li>
                            <li><a href="#sunglass" data-toggle="tab">Sunglass</a></li>
                            <li><a href="#kids" data-toggle="tab">Kids</a></li>
                            <li><a href="#poloshirt" data-toggle="tab">Polo shirt</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tshirt" >
                            @for ($i=0;$i<4;$i++)
                            <div class="col-sm-3">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <img src="{{ asset('user/images/home/gallery1.jpg') }}" alt="" />
                                            <h2>$56</h2>
                                            <p>Easy Polo Black Edition</p>
                                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        
                        <div class="tab-pane fade" id="blazers" >
                            @for ($i=0;$i<4;$i++)
                            <div class="col-sm-3">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <img src="{{ asset('user/images/home/gallery4.jpg') }}" alt="" />
                                            <h2>$56</h2>
                                            <p>Easy Polo Black Edition</p>
                                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        
                        <div class="tab-pane fade" id="sunglass" >
                            @for ($i=0;$i<4;$i++)
                            <div class="col-sm-3">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <img src="{{ asset('user/images/home/gallery3.jpg') }}" alt="" />
                                            <h2>$56</h2>
                                            <p>Easy Polo Black Edition</p>
                                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        
                        <div class="tab-pane fade" id="kids" >
                            @for ($i=0;$i<4;$i++)
                            <div class="col-sm-3">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <img src="{{ asset('user/images/home/gallery1.jpg') }}" alt="" />
                                            <h2>$56</h2>
                                            <p>Easy Polo Black Edition</p>
                                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        
                        <div class="tab-pane fade" id="poloshirt" >
                            @for ($i=0;$i<4;$i++)
                            <div class="col-sm-3">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <img src="{{ asset('user/images/home/gallery2.jpg') }}" alt="" />
                                            <h2>$56</h2>
                                            <p>Easy Polo Black Edition</p>
                                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div><!--/category-tab-->
                
                <div class="recommended_items"><!--recommended_items-->
                    <h2 class="title text-center">recommended items</h2>
                    
                    <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="item active">
                                @for ($i=0;$i<3;$i++)
                                <div class="col-sm-4">
                                    <div class="product-image-wrapper">
                                        <div class="single-products">
                                            <div class="productinfo text-center">
                                                <img src="{{ asset('user/images/home/recommend1.jpg') }}" alt="" />
                                                <h2>$56</h2>
                                                <p>Easy Polo Black Edition</p>
                                                <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>
                            <div class="item">	
                                @for ($i=0;$i<3;$i++)
                                <div class="col-sm-4">
                                    <div class="product-image-wrapper">
                                        <div class="single-products">
                                            <div class="productinfo text-center">
                                                <img src="{{ asset('user/images/home/recommend1.jpg') }}" alt="" />
                                                <h2>$56</h2>
                                                <p>Easy Polo Black Edition</p>
                                                <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>
                         <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                          </a>
                          <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                          </a>			
                    </div>
                </div><!--/recommended_items-->--}}
                
            </div>
        </div>
    </div>
</section>

@endsection
