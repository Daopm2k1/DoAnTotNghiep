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
    <div class="container" id="wishlist">
        {{-- @include('layouts.user.breadcrumb') --}}
        <div class="row">
            @include('user.parts.category')          
            <div class="col-sm-9 padding-right">
                
                <div class="features_items">
                    <h2 class="title title-padding text-center">
                        {{ !empty(Request::get('key_word')) ? 'Sản phẩm tìm kiếm' : ((isset($title) && !empty($title)) ? $title : 'Tất cả sản phẩm')}}
                    </h2>
                    <div v-if="wishlist.data.length > 0">
                        <div class="col-sm-4" v-for="(item, index) in wishlist.data">
                            <div class="product-image-wrapper item-product">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <form>
                                        @csrf
                                            <input type="hidden" :value="item.id" :class="cart_product_id(item.id)">
                                            <input type="hidden" :value="item.name" :class="cart_product_name(item.id)">
                                            <input type="hidden" :value="item.price" :class="cart_product_price(item.id)">
                                            <input type="hidden" :value="1" :class="cart_product_qty(item.id)">

                                            <a :href="linkDetail(item.id)">
                                                {{-- <p>@{{item.images}}</p> --}}
                                                <img :src="get_image(item.images)" alt="" />
                                                <h2>@{{ format_price(item.price) }}&#8363;</h2>
                                                <p>@{{item.name}}</p>
                                            </a>
                                            <input type="button" data-toggle="modal" data-target="#xemnhanh" value="Xem nhanh" class="btn btn-default xemnhanh btn-custom" :data-id_product="item.id" name="add-to-cart">
                                        </form>
                                    </div>
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified ">
                                        <li :id="li_item(item.id)" class="active-custom">
                                            <a class="cursor" @click="removeWishlist(item.id)">Đã yêu thích </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                         <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <center><p>Không có sản phẩm</p></center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="mt-3 paginate"><center v-html="paginate_custom"></center></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script type="text/javascript">
    var page = {!!json_encode(Request::get('page') ?? 1)!!};
    var paginate_custom = <?php echo json_encode($paginate) ?> + '';
    var wishlist = {!!json_encode($products)!!};
</script>
<script src="{{asset('js/wishlist.js')}}"></script>
@endsection
