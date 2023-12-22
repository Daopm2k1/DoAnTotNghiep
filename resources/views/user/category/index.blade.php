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
        .product-list .choose ul li a:hover 
        {
            color: white;
        }
    </style>
@endsection
@section('content')

<section>
    <div class="container">
        {{-- @include('layouts.user.breadcrumb') --}}
        <div class="row">
            @include('user.parts.category')          
            <div class="col-sm-9 padding-right">
                
                <div class="features_items">
                    <h2 class="title title-padding text-center">
                        {{ !empty(Request::get('key_word')) ? 'Sản phẩm tìm kiếm' : ((isset($title) && !empty($title)) ? $title : 'Tất cả sản phẩm')}}
                    </h2>
                    <div class="col-xs-6 nopadding-left">
                        <form id="form-sort" action="{{url('category/'.$id)}}">
                            <div class="orderby-wrapper pull-left">
                                <label>Sắp xếp theo:</label>
                                @if(!empty(Request::get('display')))
                                    <input type="hidden" name="display" value="{{ Request::get('display')}}">
                                @endif
                                <select name="sort" id="sort">
                                    <option value="">Mặc định</option>
                                    <option value="az" @if(Request::get('sort') == 'az') selected @endif>Tên: A-Z</option>
                                    <option value="za" @if(Request::get('sort') == 'za') selected @endif>Tên: Z-A</option>
                                    <option value="asc" @if(Request::get('sort') == 'asc') selected @endif>Giá: Tăng dần</option>
                                    <option value="desc"@if(Request::get('sort') == 'desc') selected @endif>Giá: Giảm dần</option>
                                    <option value="new" @if(Request::get('sort') == 'new') selected @endif>Mới nhất</option>
                                    <option value="old" @if(Request::get('sort') == 'old') selected  @endif>Cũ Nhất</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-6 nopadding-left">
                        <form class="tree-most" id="form-change-layout" method="get" action="{{url('category/'.$id)}}" method="get">
                            <div class="orderby-wrapper pull-right">
                                @if(!empty(Request::get('sort')))
                                    <input type="hidden" name="sort" value="{{ Request::get('sort')}}">
                                @endif
                                <label>Hiển thị theo:</label>
                                <select id="display" name="display" class="display">
                                    <option value="grid" @if($display == 'grid') selected @endif>Lưới</option>
                                    <option value="list"  @if($display == 'list') selected @endif>Danh sách</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    @if(count($products) > 0)
                        @if($display == 'grid')
                            @foreach($products as $key => $pro)
                                <div class="col-sm-4">
                                    <div class="product-image-wrapper item-product">
                                        <div class="single-products">
                                            <div class="productinfo text-center">
                                                <form>
                                                @csrf
                                                    <input type="hidden" value="{{$pro->id}}" class="cart_product_id_{{$pro->id}}">
                                                    <input type="hidden" value="{{$pro->name}}" class="cart_product_name_{{$pro->id}}">
                                                    <input type="hidden" value="{{$pro->price}}" class="cart_product_price_{{$pro->id}}">
                                                    <input type="hidden" value="{{asset($pro->getImagePrimary())}}" class="cart_product_image_{{$pro->id}}">
                                                    <input type="hidden" value="1" class="cart_product_qty_{{$pro->id}}">
                                                    <a href="{{URL::to('/product/'.$pro->id.'/detail')}}">
                                                        <img src="{{asset($pro->getImagePrimary())}}" alt="" />
                                                        <h2>{{number_format($pro->price)}}&#8363;</h2>
                                                        <p>{{$pro->name}}</p>
                                                    </a>
                                                    {{-- <input type="button" data-toggle="modal" data-target="#xemnhanh" value="Xem nhanh" class="btn btn-default xemnhanh btn-custom" data-id_product="{{$pro->id}}" name="add-to-cart"> --}}
                                                </form>
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
                                <!-- Modal -->
                                <div class="modal fade" id="xemnhanh" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title product_quickview_title" id="">
                                                    <span id="product_quickview_title"></span>
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -26px; font-size: 30px; color: #FE980F">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <span id="product_quickview_image"></span>
                                                    </div>
                                                    <form>
                                                    <div class="col-md-7">
                                                        <h2 class="product_quickview_title"><span id="product_quickview_title"></span></h2>
                                                        <label>Giá sản phẩm: </label>
                                                        <p><span id="product_quickview_price"></span></p>
                                                        <label>Mô tả sản phẩm: </label>
                                                        <p><span id="product_quickview_description"></span></p>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a title="Thêm sản phẩm vào giỏ hàng" name="add_to_cart_quick_view" type="button" class="btn btn-primary btn-custom">Mua ngay</a>
                                                <a type="submit" href="{{URL::to('cart')}}" class="btn btn-primary btn-custom">Đi tới giỏ hàng</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @elseif($display == 'list')
                            <div class="products-list">
                                @foreach($products as $key => $pro)
                                <div class="item item-custom">
                                    <div class="inner-item">
                                        <div class="item-image">
                                            <div class="inner"> 
                                                <a class="product-image no-touch" href="{{URL::to('/product/'.$pro->id.'/detail')}}">  
                                                    <img class="first_image" src="{{asset($pro->getImagePrimary())}}" alt=""/> 
                                                </a>               
                                            </div>
                                        </div>
                                        <div class="product-shop">
                                            <div class="inner">
                                                <h2 class="product-name"><a class="product-image" href="{{URL::to('/product/'.$pro->id.'/detail')}}" title="">{{$pro->name}}</a></h2>
                                                <div class="price-box">
                                                    <span class="regular-price">
                                                        <h2 class="price">{{number_format($pro->price)}}&#8363;</h2>                                    
                                                    </span>
                                                </div>
                                                
                                                <div class="desc std box-description">
                                                    {!!$pro->description!!}
                                                </div>
                                                <div class="wrap-btn-prolist">
                                                    <div class="item-btn">
                                                        <div class="box-inner">
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
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
                // id_user: id_user,
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
<script>
    $(function(){
        $('.orderby').change(function(){
            $('#form_order').submit();
        })
    })

    $('#display').on('change',function(){
        $('#form-change-layout').submit();
    })
    
    $('#sort').on('change', function(){
        $('#form-sort').submit();
    });
</script>
@endsection
