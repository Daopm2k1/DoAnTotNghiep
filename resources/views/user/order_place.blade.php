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
		<div class="register-req">
			<p>Cảm ơn quý khách đã mua hàng</p>
		</div>
        <div class="table-responsive cart_info">
		<?php
			$content = Cart::content();
		?>
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
				@foreach($content as $v_content)
                    <tr>
                        <td class="cart_product">
                            <a href="#"><img class="img_cart" src="{{asset('images/product/14.jpg')}}" alt=""></a>
                        </td>
                        <td class="cart_description">
                            <h4><a href="#">{{$v_content->name}}</a></h4>
                            <p>{{('Mã sản phẩm: '.$v_content->id)}}</p>
                        </td>
                        <td class="cart_price">
                            <p>{{number_format($v_content->price)}}</p>
                        </td>
                        <td class="cart_quantity">
                            <div class="cart_quantity_button">
                                <form method="post">
                                	<!-- <input class="cart_quantity_input" type="text" min="1" name="cart_quantity" value="{{$v_content->qty}}" autocomplete="off" size="2" readonly> -->
									<p>{{$v_content->qty}}</p>
								</form>
                            </div>
                        </td>
                        <td class="cart_total">
                            <p class="cart_total_price">
								<?php
									$subtotal = $v_content->price * $v_content->qty;
									echo number_format($subtotal);
								?>
							</p>
                        </td>
                    </tr>
					@endforeach
                </tbody>
            </table>
        </div>
    </div>
</section> <!--/#cart_items-->
<section id="do_action">
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<div class="total_area">
					<ul>
						<li>Phí giao hàng:  <span>Miễn phí</span></li>
						<li>Tổng tiền: <span>{{Cart::subtotal()}}</span></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section><!--/#do_action-->

@endsection