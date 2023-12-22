@extends('layouts.user')
@section('content')

<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="{{URL::to('home')}}">Trang chủ</a></li>
                <li class="active">Giỏ hàng</li>
            </ol>
        </div>
        <div class="table-responsive cart_info">
		<form method="post" action="{{URL::to('/update-cart')}}">
        @csrf
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="image">Ảnh</td>
                        <td class="description">Tên sản phẩm</td>
                        <td class="price">Giá</td>
                        <td class="qty">Số lượng</td>
                        <td class="total">Thành tiền</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                
                    @php
                        $total = 0;
                    @endphp
				    @foreach(Session::get('cart') as $key => $cart)
                    @php 
                        $subtotal = $cart['product_price']*$cart['product_qty'];
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td class="cart_product">
                            <a href="#"><img class="img_cart" src="{{asset('images/product/14.jpg')}}" alt="$cart['product_name']}}"></a>
                        </td>
                        <td class="cart_description">
                            <h4><a href="#"></a>{{$cart['product_name']}}</h4>
                            <p></p>
                        </td>
                        <td class="cart_price">
                            <p>{{number_format($cart['product_price'])}}</p>
                        </td>
                        <td class="cart_quantity">
                            <div class="cart_quantity_button">                                
                                	<!-- <input class="cart_quantity_" type="number" min = "1" name="cart_qty[{{$cart['session_id']}}]" value="{{$cart['product_qty']}}" readonly>		 -->
                                    <p>{{$cart['product_qty']}}</p>
                            </div>
                        </td>
                        <td class="cart_total">
                            <p class="cart_total_price">
                                {{number_format($subtotal)}}
							</p>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>
                            <li>Phí giao hàng:  <span>Miễn phí</span></li>
							<li>Tổng tiền: <span>{{number_format($total)}}</span></li>
                        </td>
                        
                    </tr>
                </tbody>
				</form>
            </table>
        </div>
    </div>
</section> <!--/#cart_items-->
<section id="do_action">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<div class="total_area">	
					</div>
				</div>
			</div>
		</div>
	</section><!--/#do_action-->
@endsection