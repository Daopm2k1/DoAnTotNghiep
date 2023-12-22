@extends('layouts.user')
@section('head')
    <style type="text/css">
        .style_home{
            border: 1px solid #DEDEDC;
            color: #696763;
            font-family: 'Roboto', sans-serif;
            font-size: 20px;
            font-weight: 700;
            height: 33px;
            outline: medium none;
            text-align: center;
            width: 50px;
        }
    </style>
@endsection
@section('content')
@include('layouts.user.slider')

<section>
    <div class="container">
        {{-- @include('layouts.user.breadcrumb') --}}
        <div class="row">
        @include('user.parts.category') 
            <div class="col-sm-9 padding-right">
                
                <div class="features_items">
                    <h2 class="title title-padding text-center">
                        {{ (!empty(Request::get('key_word')) || !empty(Request::get('range_price'))) ? 'Sản phẩm tìm kiếm' : ((isset($title) && !empty($title)) ? $title : 'Sản phẩm nổi bật')}}
                    </h2>                                                                           

                    @if(count($products) > 0)                       
                        @foreach($products as $key => $pro)
                            <div class="col-sm-4">
                                <div class="product-image-wrapper item-product">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <a href="{{URL::to('/product/'.$pro->id.'/detail')}}">
                                                <img src="{{asset($pro->getImagePrimary())}}" alt="" />
                                                <h2>{{number_format($pro->price)}}&#8363;</h2>
                                                <p>{{$pro->name}}</p>
                                            </a>
                                            {{-- <input type="button" data-toggle="modal" data-target="#xemnhanh" value="Xem nhanh" class="btn btn-default xemnhanh btn-custom" data-id_product="{{$pro->id}}" name="add-to-cart"> --}}
                                        </div>
                                    </div>
                                    <div class="choose">
                                        <ul class="nav nav-pills nav-justified ">
                                            <li id="li_{{$pro->id}}" @if(Auth::check() && $pro->checkProductWishList(Auth::id())) class="active-custom" @endif>
                                                <a class="cursor" onclick="add_wishlist({{$pro->id}})">
                                                    @if(!$pro->checkProductWishList(Auth::id()))<i class="fa fa-plus-square"></i>Thêm yêu thích @else Đã yêu thích @endif </a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                </div>
                            </div>
                        @endforeach                       
                    @else
                      <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <center><p>Không có sản phẩm</p></center>
                            </div>
                        </div>
                      </div>
                    @endif
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mt-3 paginate"><center>{{ $products->withQueryString()->links() }}</center></div>
                    </div>
                </div>    
                @if(!empty($product_top_sale))
                    <div class="recommended_items"><!--recommended_items-->
                        <h2 class="title text-center">Sản phẩm bán chạy nhất</h2>
                        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($product_top_sale as $key => $items)
                                <div class="item @if($key == 0) active @endif">
                                    @foreach($items as $k => $v)
                                        <div class="col-sm-4">
                                            <div class="product-image-wrapper">
                                                <div class="single-products">
                                                    <div class="productinfo text-center">
                                                        <a href="{{route('user.product.detail',['id'=>$v->id])}}"><img src="{{asset(getImageProduct($v->id))}}" alt="" /></a>
                                                        <h2 class="price-product">{{number_format($v->price)}}&#8363;</h2>
                                                        <div class="box-name-product"><a href="{{route('user.product.detail',['id'=>$v->id])}}" class="name-product cursor">{{$v->name}}</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                            <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                                <i class="fa fa-angle-right"></i>
                            </a>			
                        </div>
                    </div><!--/recommended_items-->
                @endif    
                @if(!empty($product_top_wish))
                {{-- @php dd($product_top_wish) @endphp --}}
                    <div class="recommended_items"><!--recommended_items-->
                        <h2 class="title text-center">Sản phẩm yêu thích nhất</h2>
                        <div id="recommended-item-carousel1" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($product_top_wish as $key => $items)
                                <div class="item @if($key == 0) active @endif">
                                    @foreach($items as $k => $v)
                                        {{-- @php dd($v) @endphp --}}
                                        <div class="col-sm-4">
                                            <div class="product-image-wrapper">
                                                <div class="single-products">
                                                    <div class="productinfo text-center">
                                                        <a href="{{route('user.product.detail',['id'=>$v['id']])}}"><img src="{{asset(getImageProduct($v['id']))}}" alt="" /></a>
                                                        <h2 class="price-product">{{number_format($v['price'])}}&#8363;</h2>
                                                        <div class="box-name-product"><a href="{{route('user.product.detail',['id'=>$v['id']])}}" class="name-product cursor">{{$v['name']}}</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                            <a class="left recommended-item-control" href="#recommended-item-carousel1" data-slide="prev">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="right recommended-item-control" href="#recommended-item-carousel1" data-slide="next">
                                <i class="fa fa-angle-right"></i>
                            </a>			
                        </div>
                    </div><!--/recommended_items-->
                @endif        
            </div>
        </div>
    </div>

</section>
@endsection
@section('script')
<script type="text/javascript">
    function add_wishlist(id){
        var _token = $('input[name="_token"]').val(); 

        $.ajax({
            url:"{{url('add-wishlist')}}",
            method:"POST",
            data:{
                id_product: id,
                _token:_token
            },
            success:function(res){
                if(!res.error)
                {
                    if(res.method == 'create')
                    {
                        let li_id = '#li_' + res.data.product_id;
                        
                        $(li_id).addClass('active-custom');
                        $(li_id).find('a').html('Đã yêu thích');
                    }
                    else
                    {
                        let li_id = '#li_' + res.data.product_id;
                        $(li_id).removeClass('active-custom');
                        $(li_id).find('a').html('<i class="fa fa-plus-square"></i>Thêm yêu thích');
                    }
                }
                else
                {
                    swal({
                        title: "Lỗi!",
                        text: res.message,
                        type: "error",
                        confirmButtonClass: "btn-danger btn",
                    });
                }
            }
        });
    }
</script>
@endsection