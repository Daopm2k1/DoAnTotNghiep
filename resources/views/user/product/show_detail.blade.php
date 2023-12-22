@extends('layouts.user')
@section('head')
    <style>
        #reviews textarea {
            height: auto;
        }
        .error
        {
            display: none;
        }
        .d-none
        {
            display: none;
        }
        .active
        {
            display: block;
        }
        .title-reply
        {
            font-weight: Bold;
            color: blue;
        }
        .pl-10px
        {
            padding-left:10px;
        }
    </style>
@endsection
@section('content')
    <input type="hidden" id="product_viewed_id" value="{{$product->id}}">
    <input type="hidden" id="viewed_product_name{{$product->id}}" value="{{$product->name}}">
    <input type="hidden" id="viewed_product_url{{$product->id}}" value="{{URL::to('/product/'.$product->id.'/detail')}}">
    <input type="hidden" id="viewed_product_image{{$product->id}}" value="{{asset($product->getImagePrimary())}}">
    <input type="hidden" id="viewed_product_price{{$product->id}}" value="{{number_format($product->price)}}">
<section>
    <div class="container">
        @include('layouts.user.breadcrumb')
        <div class="row">
             @include('user.parts.category')
            <div class="col-sm-9 padding-right">
                    <div class="product-details">
                        <div class="col-sm-5">
							<div class="view-product">
								<img src="{{asset($product->getImagePrimary())}}" alt="" />
							</div>
                            @if(!empty($product->getExtraImage()))
                            <div id="similar-product" class="carousel slide" data-ride="carousel">
								
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                <div class="item active d-flex">
                                    @foreach ($product->getExtraImage() as $image)
                                    <div class="box-img"><img src="{{ asset($image->path) }}" alt=""></div>
                                    @endforeach
                                </div>
                            </div>
                          </div>
                            @endif
						</div>
                        <div class="col-sm-7">
                            <div class="product-information"><!--/product-information-->
                                <h2>{{$product->name}}</h2>
                                <p>{{('Mã sản phẩm: '.$product->id)}}</p>
                              
                                <form action="{{URL::to('save-cart')}}" method="post" id="form-cart">
                                    {{csrf_field()}}
                                    <span>
                                        <div>
                                            <div class="price-display">{{number_format($product->price)}}&#8363;</div>
                                        
                                            <div class="full-width pt-20-custom pb-20-custom">
                                                <p class="font-16">Màu sắc</p>
                                                @if(count($colors) > 0)
                                                    @php 
                                                        $color_white = '#ffffff';
                                                        list($rf, $gf, $bf) = sscanf($color_white, "#%02x%02x%02x");
                                                    @endphp
                                                    @foreach ($colors as $color)
                                                        @php 
                                                            if(!empty($color))
                                                            {
                                                                list($r, $g, $b) = sscanf($color->color_code, "#%02x%02x%02x");
                                                            }
                                                           
                                                            $flag_color = ($rf == $r) && ($gf == $g) && ($bf == $b); 
                                                            // dd($rf, $gf, $bf,$r, $g, $b,$flag_color);
                                                        @endphp
                                                        <span class="size-item item-color cursor @if(!empty($first_color) && $color->id == $first_color->id) active @endif" style="background-color:{{$color->color_code}}; @if(!$flag_color) color:white @else color:black @endif" data-product="{{$product->id}}" data-color={{$color->id}}>{{$color->name}}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        
                                            <div class="full-width pt-20-custom pb-20-custom"">
                                                <p  class="font-16">Kích thước</p>
                                                <div id="box-size">
                                                    @if(count($sizes) > 0)
                                                        @foreach ($sizes as $size)
                                                                <span class="size-item item-size cursor @if(!empty($first_size) && $size->id == $first_size->id) active @endif size_{{$size->id}}" onclick="clickSize({{$size->id}})" >{{$size->name}}</span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <span>
                                                <div class="price-display">
                                                    <label>Số lượng:</label>
                                                    <input id="quantity" name="quantity" type="number" min="1" max="100" value="1"/>
                                                    <input id="product_id" name="product_id" type="hidden" value="{{$product->id}}" />
                                                    <input id="color" type="hidden" name="color" value="{{ !empty($first_color) ? $first_color->id : '' }}">
                                                    <input id="size" type="hidden" name="size" value="{{ !empty($first_size) ? $first_size->id : '' }}">
                                                    <div class="full-width">
                                                        <button type="submit" id="add-cart" class="btn btn-fefault cart btn-custom ml-custom">
                                                            <i class="fa fa-shopping-cart"></i>
                                                            Thêm vào giỏ hàng
                                                        </button>
                                                    </div>
                                            </div>
                                            </span>
                                        </div>
                                    </span>
                                </form>
                            </div><!--/product-information-->
                            @if(count($tags) > 0)
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4>Xem thêm:</h4>
                                        <ul class="box-tag">
                                            @foreach ($tags as $tag)
                                                <li class="box-tag-medium"><a href="{{route('user.tag.index',['slug'=>$tag->slug])}}" class="tag-product cursor">#{{$tag->name}}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div><!--/product-details-->   

                    <div class="category-tab shop-details-tab"><!--category-tab-->
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#content" data-toggle="tab">Mô tả</a></li>
                                <li><a href="#reviews" data-toggle="tab">Bình Luận</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="content" >
                                <div class="col-sm-12 ul-box">
                                    @php echo $product->description; @endphp
                                </div>
                            </div>
                            <div class="tab-pane fade" id="reviews" >
                                <div class="response-area">
                                    <ul class="media-list">
                                        @php 
                                            $isAdmin = Auth::check() && Auth::user()->checkAdmin();
                                            $isBought = $product->checkItemComment();
                                        @endphp
                                        @if(!$isAdmin && !$isBought)
                                            <li class="active">
                                                <div>
                                                    @if(Auth::check())
                                                        <span style="color: #FE980F !important;">Mua hàng</span>
                                                    @else
                                                        <a class="review-login" href="{{url('/login?detail=true')}}">Đăng nhập</a> và <span style="color: #FE980F !important;">mua hàng</span> 
                                                    @endif
                                                    để bình luận sản phẩm
                                                </div>
                                            </li>
                                            <hr>
                                        @endif
                                        @if((Auth::check() && $isBought) || $isAdmin)
                                        <li class="media">
                                            <div class="form-group pl-10-custom">
                                                <textarea type="text" id="comment" rows="2" name="note" placeholder="Nhập bình luận" class="form-control"></textarea>
                                                <div class="error error-comment error-comment_text">Nhập nội dung bình luận</div>
                                                <button class="btn btn-custom" @click="comment()">Bình luận</button>
                                            </div>
                                        </li>
                                        @endif
                                        <li class="media"  v-if="comments.length > 0" v-for="(comment, index) in comments">
                                            <a class="pull-left" href="#">
                                                <img class="media-object img-avatar" :src="getAvatar(comment.user.avatar)" alt="">
                                            </a>
                                            <div class="media-body">
                                                <ul class="sinlge-post-meta">
                                                    <li><i class="fa fa-user"></i>@{{ comment.user.username}}</li>
                                                    <li><i class="fa fa-calendar"></i> @{{ format_date(comment.created_at) }}</li>
                                                    {{-- <li><i class="fa fa-clock-o"></i>@{{ format_time(comment.created_at) }}</li> --}}
                                                </ul>
                                                <p>@{{comment.comment_text}}</p>
                                                @if(Auth::check() && $isBought || $isAdmin)<span class="cursor title-reply" @click="showReply(comment.id)">Trả lời</span>@endif
                                                @if(Auth::check() && $isBought)<span  v-if="checkPermission(comment.user_id)" class="cursor title-reply pl-10px" @click="deleteComment(comment.id)">Xoá</span>@endif
                                                @if($isAdmin)<span class="cursor title-reply pl-10px" @click="deleteComment(comment.id)">Xoá</span>@endif
                                                @if(Auth::check() && $isBought || $isAdmin)
                                                <ul class="form-child d-none" :id="getClassForm(comment.id)">
                                                    <li class="media">
                                                        <div class="form-group pl-10-custom">
                                                            <textarea type="text" :class="getNameReply(comment.id)" rows="2" name="note" placeholder="Nhập bình luận" class="form-control"></textarea>
                                                            <div class="error error-comment" :class="getErrorName(comment.id)">Nhập nội dung bình luận</div>
                                                            <button class="btn btn-custom" @click="replyComment(comment.id)">Bình luận</button>
                                                        </div>
                                                    </li>
                                                </ul>
                                                @endif  
                                            </div>
                                            <ul v-if="comment.children.length > 0">
                                                <li class="media second-media" v-for="(item, index) in comment.children">
                                                    <a class="pull-left" href="#">
                                                        <img class="media-object img-avatar" :src="getAvatar(item.user.avatar)"  alt="">
                                                    </a>
                                                    <div class="media-body">
                                                        <ul class="sinlge-post-meta">
                                                            <li><i class="fa fa-user"></i>@{{ item.user.username}}</li>
                                                            <li><i class="fa fa-calendar"></i> @{{ format_date(item.created_at) }}</li>
                                                            {{-- <li><i class="fa fa-clock-o"></i>@{{ format_time(item.created_at) }}</li> --}}
                                                        </ul>
                                                        <p>@{{item.comment_text}}</p>
                                                        @if(Auth::check() && $isBought)<span  v-if="checkPermission(item.user_id)" class="cursor title-reply pl-10px" @click="deleteComment(item.id,item.parent_id)">Xoá</span>@endif
                                                        @if($isAdmin)<span class="cursor title-reply pl-10px" @click="deleteComment(item.id,item.parent_id)">Xoá</span>@endif
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>	
                                    @if(count($comments) >= 5)
                                    <div class="form-group pl-10-custom">
                                        <center><button class="btn btn-custom" v-if="!all" @click="showMore()">Xem thêm</button></center>
                                    </div>
                                    @endif
                                </div><!--/Response-area-->
                            </div>
                        </div>
                    </div><!--/category-tab-->
                    @if(!empty($related_products))
                        <div class="recommended_items"><!--recommended_items-->
                            <h2 class="title text-center">Sản phẩm liên quan</h2>
                            <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($related_products as $key => $items)
                                    <div class="item @if($key == 0) active @endif">
                                        @foreach($items as $k => $v)
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
                                <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                                    <i class="fa fa-angle-left"></i>
                                </a>
                                <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
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
   
    <script>
        var auth = {{ Auth::id() ?? 0 }};
        var page = 1;
        var product_id ={{ $product->id}};
        var comments = {!! json_encode($comments) !!};
        
        $('.item-color').click(function(){
            let color = $(this).data('color');
            let product = $(this).data('product');
            let html = '';

            $('.item-color').removeClass('active');
            $(this).addClass('active');
            $('#color').val(color);
            $('#size').val('');
            $('#add-cart').prop('disabled', true);
            $.ajax({
                url: location.origin + '/api/product/' + product + '/detail/' + color,
                method: 'get',
                data: {
                    '_token':"{{ csrf_token() }}",
                },
                success:function(res)
                {
                    if(!res.error)
                    {
                        if(res.data.length > 0)
                        {
                            $.each(res.data,function(key,value){
                                html +=  '<span class="size-item item-size cursor size_' + value.id + '" onclick="clickSize(' + value.id + ')" >'+ value.name +'</span>';
                            });
                        }
                        $('#box-size').html(html);
                    }
                }
            });
        })

       function clickSize(id)
       {
            $('.item-size').removeClass('active');
            $('.size_' + id).addClass('active');
            $('#size').val(id);
            $('#add-cart').prop('disabled', false);
       }

       $('#form-cart').submit(function(e){
            e.preventDefault();
            let color = $('#color').val();
            let size = $('#size').val();

            if( color == '' || typeof color == 'undefined')
            {    
                alert('Vui lòng chọn màu sắc');
                return;
            }
            if( size == '' || typeof size == 'undefined')
            {
                alert('Vui lòng chọn kích thước');
                return;
            }

            let data = {
               'quantity': $('#quantity').val(),
               'product_id': $('#product_id').val(),
               'size': size,
               'color': color,
               '_token':"{{ csrf_token() }}",
            }

            $.ajax({
                url: location.origin + '/save-cart',
                method: 'post',
                data: data,
                success:function(res)
                {
                    if(!res.error)
                    {
                        if(res.data.length == 1)
                        {
                            let html = '<span class="count-cart">' + res.data.length + '</span>';
                            $('.box-count-cart').html(html);
                        }
                        else
                        {
                            $('.count-cart').html(res.data.length);
                        }
                        swal({
                            title: "Đã thêm sản phẩm vào giỏ hàng",
                            text: "Bạn có thể mua hàng tiếp hoặc tới giỏ hàng để tiến hành thanh toán",
                            type:'success',
                            showCancelButton: true,
                            cancelButtonText: "Xem tiếp",
                            confirmButtonClass: "btn-success",
                            confirmButtonText: "Đi đến giỏ hàng",
                            closeOnConfirm: false
                        },
                        function() {
                            window.location.href = "{{url('/cart')}}";
                        });
                    }
                    else
                    {
                        swal({
                            title: "Thông báo",
                            text: res.message,
                            type: 'warning',
                            confirmButtonClass: "btn-warning",
                        },
                        function() {

                        });
                    }
                }
            });
       })

       $('#quantity').keypress(function(e){
            let char = String.fromCharCode(e.which);
            if(!(/[0-9]/.test(char)) || $(this).val().length > 10)
            {
                e.preventDefault();
            }

            var firstChar = $(this).val();
            if(e.keyCode == 48 && firstChar == ""){
                e.preventDefault();
            }
        });
    </script>
    <script src="{{asset('js/comment.js')}}"></script>
@endsection
