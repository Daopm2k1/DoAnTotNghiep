<div class="col-sm-3">
    <div class="left-sidebar">
        <h2 class="title-padding">Danh mục sản phẩm</h2>
        <div class="panel-group category-products scroll-menu" id="accordian"><!--category-productsr-->
            @foreach($categories as $key => $cate)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"><a href="{{URL::to('/category/'.$cate->id)}}">{{$cate->name}}</a></h4>
                </div>
            </div>
           @endforeach
        </div>      
        @if(isset($is_home) && $is_home)
        <div class="price-range">
            <h2>Khoảng giá</h2>
            <div class="well text-center">
                <form id="form-filter" method="get" action="{{url('/home')}}">
                    <div class="row">
                        <input type="text" name="range_price" class="span2" value="" data-slider-min="{{($min_price)}}" data-slider-max="{{($max_price)}}" data-slider-step="5" data-slider-value="[{{$min ?? $min_price}},{{$max ?? $max_price}}]" id="sl2" ><br />
                        <b class="pull-left">{{format_price($min_price)}}&#8363;</b> <b class="pull-right">{{format_price($max_price)}}&#8363;</b>
                    </div>
                    <div class="row ">
                        <input type="submit" value="Lọc giá" id="btn-filter" class="btn btn-sm btn-default btn-custom">
                    </div>
                </form>   
            </div>
        </div>
        @endif
        <div id="container_viewed">
            <h2 class="title-padding">Sản phẩm đã xem</h2>
            <div class="panel-group category-products scroll-menu">
                <div class="panel panel-default">
                    <div id="row_viewed" class="row">
                    </div>
                </div>                 
            </div>
        </div>
    </div>
</div>