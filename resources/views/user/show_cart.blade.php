@extends('layouts.user')
@section('head')
	<style>
		 .cart_quantity_button form
        {
            display: flex;
        }
        .cart_quantity_button form .input-cart
        {
            margin-right: 5px;
        }
		#do_action .total_area
        {
            padding-right: 40px;
        }
	</style>
@endsection
@section('content')

<section id="cart_items">
    <div class="container" >
		@include('layouts.user.breadcrumb')
		<h1>Giỏ hàng</h1>
        <hr>
		
		<div id="container-cart">
			@if(Session::has('cart') && count(Session::get('cart')) > 0)
				<div class="table-responsive cart_info">	
					@php
						$cart = Session::get('cart');
					@endphp
					<table class="table table-condensed">
						<thead>
							<tr class="cart_menu">
								<td class="image">Ảnh</td>
								<td class="description">Sản phẩm</td>
								<td class="price">Giá</td>
								<td class="qty">Số lượng</td>
								<td class="total">Thành tiền</td>
								<td></td>
							</tr>
						</thead>
						<tbody id="data-table">
							@php $total = 0; @endphp
							@foreach($cart as $item)
							@php $total += $item['price'] * $item['qty']@endphp
								<tr>
									<td class="cart_product">
										<img class="img_cart" @if(empty(getImageProduct($item['product']))) src="{{asset('images/product/no-image-product.png')}}" @else src="{{asset(getImageProduct($item['product']))}}" @endif alt="">
									</td>
									<td class="cart_description">
									<h4><a href="{{route('user.product.detail',['id'=>$item['product']])}}">{{$item['name']}}</a></h4>
										<p>{{('Mã sản phẩm: '.$item['product'])}}</p>
										<p>{{$item['options']['color']['name']}}/{{$item['options']['size']['name']}}</p>
									</td>
									<td class="cart_price">
										<p>{{number_format($item['price'])}}&#8363;<p>
									</td>
									<td class="cart_quantity">
										<div class="cart_quantity_button">
											<form onsubmit="return false;">
												{{-- {{csrf_field()}} --}}
												<?php //$product_qty_limit = Session::get('product_qty_limit');?>
												<input class="quantity input-cart" min="1" max="100" id="cart_{{$item['id']}}_{{$item['options']['color']['id']}}_{{$item['options']['size']['id']}}" type="number" min="1" max="{{$product_qty_limit = 1000}}" name="quantity" value="{{$item['qty']}}" autocomplete="off" size="2">
												<?php //$cart_id_product = Session::get('cart_id_product');?>
												{{-- <input type="hidden" value="{{$cart_id_product}}" name="product_id" class="form-control"> --}}
												{{-- <input type="hidden" value="{{$v_content->rowId}}" name="rowId_cart" class="form-control"> --}}
												<input onclick="clickUpdate({{$item['id']}},{{$item['options']['color']['id']}},{{$item['options']['size']['id']}})" type="button" value="Cập nhật" name="update-qty" class="btn-update btn btn-custom btn-default btn-sm">
											</form>
										</div>
									</td>
									<td class="cart_total">
										<p class="cart_total_price" id="total_{{$item['id']}}_{{$item['options']['color']['id']}}_{{$item['options']['size']['id']}}">{{ number_format($item['qty'] * $item['price']) }}&#8363;</p>
									</td>
									<td class="cart_delete">
										<a onclick="clickDelete({{$item['id']}},{{$item['options']['color']['id']}},{{$item['options']['size']['id']}})" data-size="{{$item['options']['size']['id']}}" class="cart_quantity_delete"><i class="fa fa-times"></i></a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@else
				<div class="container">
					<div class="row">
						<div class="col-12">
							<p class="message-cart">Giỏ hàng của bạn đang trống</p>
						</div>
					</div>
				</div>
			@endif
		</div>
    </div>
</section> 
<section id="do_action">
@if(Session::has('cart') && count(Session::get('cart')) > 0)
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<div class="heading">
					<h3>Chính sách mua hàng<h3>
				</div>
				<ul class="box-term">
					<li class="item-term">Sản phẩm được đổi 1 lần duy nhất, không hỗ trợ trả.</li>
					<li class="item-term">Sản phẩm còn đủ tem mác, chưa qua sử dụng.</li>
					<li class="item-term">Sản phẩm nguyên giá được đổi trong 30 ngày trên toàn hệ thống</li>
					<li class="item-term">Sản phẩm sale chỉ hỗ trợ đổi size (nếu cửa hàng còn) trong 7 ngày trên toàn hệ thống.</li>
				</ul>
			</div>
			<div class="col-sm-6">
				<div class="heading">
					<h3>Bạn có muốn mua hàng hàng tiếp ?<h3>
					<p>Nếu bạn muốn thanh toán vui lòng ấn vào nút thanh toán</p>
				</div>
				<div class="total_area">
					<ul>
						<li class="total_box">Tổng tiền <span id="total_cart">{{ number_format($total)}}&#8363;</span></li>
					</ul>
					<div class="row">
						<div class="col-12">
							<p class="pl-55">Phí vận chuyển sẽ được tính ở trang thanh toán. Bạn cũng có thể nhập mã giảm giá ở trang thanh toán.</p>
							<center><a class="btn btn-default btn-custom update btn-checkout" href="{{ url('/checkout') }}">Thanh toán</a></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endif
</section><!--/#do_action-->
@endsection
@section('script')
	<script src="{{ asset('js/cart.js')}}"></script> 
@endsection