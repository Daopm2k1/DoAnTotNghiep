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
                        {{ !empty(Request::get('key_word')) ? 'Sản phẩm tìm kiếm' : ((isset($title) && !empty($title)) ? $title : 'Tất cả sản phẩm')}}
                    </h2>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="amount">Sắp xếp theo</label>
                            <form action="">
                            @csrf
                                <select name="sort" id="sort" class="form-control">
                                    <option value="">--Lọc--</option>
                                    <option value="">--Giá tăng dần--</option>
                                    <option value="">--Giá giảm dần--</option>
                                    <option value="">--Tên từ A đến Z--</option>
                                    <option value="">--Tên từ Z đến A--</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    @if(count($product_wish) > 0)
                        @foreach($product_wish as $key => $pro)
                        <div class="col-sm-4">
                            <div class="product-image-wrapper item-product">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <form>
                                        @csrf
                                            <input type="hidden" value="{{$pro->id}}" class="cart_product_id_{{$pro->id}}">
                                            <input type="hidden" value="{{$pro->name}}" class="cart_product_name_{{$pro->id}}">
                                            <input type="hidden" value="{{$pro->price}}" class="cart_product_price_{{$pro->id}}">
                                            <input type="hidden" value="1" class="cart_product_qty_{{$pro->id}}">

                                            <a href="{{URL::to('/product/'.$pro->id.'/detail')}}">
                                                {{--<img src="{{asset($pro->getImagePrimary())}}" alt="" />--}}
                                                <h2>{{number_format($pro->price)}}&#8363;</h2>
                                                <p>{{$pro->name}}</p>
                                            </a>
                                            
                                            <input type="button" data-toggle="modal" data-target="#xemnhanh" value="Xem nhanh" class="btn btn-default xemnhanh btn-custom" data-id_product="{{$pro->id}}" name="add-to-cart">
                                            
                                            </input>
                                        </form>
                                    </div>
                                </div>
                                <div class="choose">
                                    <!-- <ul class="nav nav-pills nav-justified">
                                        <li><a href=""><i class="fa fa-plus-square"></i>Xóa</a></li>
                                    </ul> -->
                                </div>
                                <div class="container">
                                    <!-- Modal -->
                                    <div class="modal fade" id="sosanh" role="dialog">
                                        <div class="modal-dialog">
                                        
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Modal Header</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Some text in the modal.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                <!-- Modal -->
                <div class="modal fade" id="xemnhanh" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title product_quickview_title" id="">
                                    <span id="product_quickview_title"></span>
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                    @csrf
                                        <h2 class="product_quickview_title"><span id="product_quickview_title"></span></h2>
                                        <label>Mã ID: </label>
                                        <p><span id="product_quickview_id"></span></p>
                                        <label>Giá sản phẩm: </label>
                                        <p><span id="product_quickview_price"></span></p>
                                        <label>Mô tả sản phẩm: </label>
                                        <p><span id="product_quickview_description"></span></p>
                                        <label>Số lượng:</label>
                                        <input name="quty" type="number" min="1" class="cart_product_qty_ style_home" value="1" /> <br />
                                        
                                        <input name="add-to-cart" type="button" class="btn btn-primary btn-sm add-to-cart btn-custom" value="Mua ngay" />   
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary " data-dismiss="modal" style="margin-bottom: -16px;">Đóng</button>
                                <button type="button" class="btn btn-primary btn-custom">Đi tới sản phẩm</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="mt-3 paginate"><center>{{ $product_wish->withQueryString()->links() }}</center></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
