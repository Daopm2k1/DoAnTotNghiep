@extends('layouts.user')
@section('content')

<section id="cart_items">
    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @elseif(session()->has('error'))
                <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
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
                @if(Session::get('cart')==true)
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
                            <a href="#"><img class="img_cart" src="{{asset('images/product/14.jpg')}}" alt="{{$cart['product_name']}}"></a>
                        </td>
                        <td class="cart_description">
                            <h4><a href="#"></a>{{$cart['product_name']}}</h4>
                            <p></p>
                        </td>
                        <td class="cart_price">
                            <style type="text/css">
                                .style_price{
                                    color: #696763;
                                    font-family: 'Roboto', sans-serif;
                                    font-size: 20px;
                                    font-weight: 700;
                                    height: 33px;
                                    outline: medium none;
                                    text-align: center;
                                    width: 50px;
                                    margin-top: 10px;
                                }
                            </style> 
                            <p class="style_price">{{number_format($cart['product_price']).'VNĐ'}}</p>
                        </td>
                        <td class="cart_quantity">
                            <div class="cart_quantity_button">
                                <style type="text/css">
                                    .style_cart{
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
                                <input class="cart_quantity_ style_cart" type="number" min = "1" name="cart_qty[{{$cart['session_id']}}]" value="{{$cart['product_qty']}}" autocomplete="off" size="2">		
                            </div>
                        </td>
                        <td class="cart_total">
                            <p class="cart_total_price">
                                {{number_format($subtotal)}}
							</p>
                        </td>
                        <td class="cart_delete">
                            <a class="cart_quantity_delete btn-custom" href="{{URL('/delete-product/'.$cart['session_id'])}}"><i class="fa fa-times"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    <tr class="tr-display">
                        <td><input type="submit" value="Cập nhật" name="update-qty" class="check_out btn btn-default btn-sm btn-custom"></td>
                        <td><a class="btn btn-default check_out btn-custom" href="{{url('/delete-all-product')}}">Xóa tất cả</a></td>
                        
                        <td>
                            <?php
								$Success = Session::get('Success');
								if($Success == 1 ){
							?>
								<a class="btn btn-default check_out btn-custom" href="{{URL::to('/checkout')}}">Thanh toán</a>
							<?php
								} else{
							?>
								<a class="btn btn-default check_out btn-custom" href="{{URL::to('/login-checkout')}}">Thanh toán</a>
							<?php
								}
							?>
                        </td>

                        <td>
                            <ul>
                                <li>Phí giao hàng: <span>Miễn phí</span></li>
							    <li>Tổng tiền: <span>{{number_format($total).' VNĐ'}}</span></li>
                            </ul>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="2">
                        <center>
                            @php
                                echo 'Hãy thêm sản phẩm vào giỏ hàng';
                            @endphp
                        </center>
                        </td>
                    </tr>
                    @endif
                </tbody>
				</form>
            </table>
        </div>
    </div>
</section> <!--/#cart_items-->
<section id="do_action">
	</section><!--/#do_action-->
@endsection