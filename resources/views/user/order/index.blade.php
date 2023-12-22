@extends('layouts.user')
@section('head')
    <style>
        .t-center
        {
            text-align: center;
        }
        .t-right
        {
            text-align: right;
        }
        #customers {
            /* font-family: Arial, Helvetica, sans-serif; */
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: center;
            background-color: #FE980F ;
            color: white;
        }
        .flex-btn {
            display: inline-flex;
            width: 100%;
            justify-content: flex-end;
        }
        .width-262px {
            width: 262px;
        }
        .padding-custom {
            padding: 20px 0 20px 0; 
        }
    </style>
@endsection
@section('content')
<section id="cart_items">
    <div class="container">
        @include('layouts.user.breadcrumb')
        <h1>Danh sách đơn hàng</h1>
        <div class="spacing-top-bottom-30px">
            <div class="container">
                <div class="row box-order">
                    <div class="col-sm-6 header-order active" data-id="unpaid"><center><div class="cursor">Chưa thanh toán</div></center></div>
                    <div class="col-sm-6 header-order" data-id="paid"><center><div class="cursor">Đã thanh toán</div></center></div>
                </div>
            </div>
            <div id="unpaid" class="content-order">
                <div class="container">
                    <div class="row">
                        @if(count($order_unpaid) > 0)
                            <div class="col-sm-12">
                                <div class="row container-item-order">
                                    <table id="customers">
                                        <thead>
                                        <tr >
                                                <th class="t-center">Mã đơn hàng</th>
                                                <th class="t-center">Số tiền thanh toán</th>
                                                <th class="t-center">Phương thức thanh toán</th>
                                                <th class="t-center">Ngày tạo</th>
                                                <th class="t-center"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order_unpaid as $item)
                                            <tr class="t-center">
                                                <td>{{$item->id}}</td>
                                                <td> {{format_price($item->total)}}&#8363</td>
                                                <td>{{getMethodPayment($item->payment)}}</td>
                                                <td> {{format_date($item->created_at)}}</td>
                                                <td class="width-262px">
                                                    <div  class="flex-btn">
                                                        @if($item->payment->method == "online")
                                                            <button style="margin-right: 10px" onclick="payBackOrder({{$item->id}})"  class="btn btn-custom">Thanh toán lại</button>
                                                            <button style="margin-right: 10px" onclick="deleteOrder({{$item->id}})"  class="btn btn-custom btn-danger-custom">Huỷ</button>
                                                            <form id="form-payback-{{$item->id}}" action="{{route('user.order.pay_back',['id'=>$item->id])}}" method="POST" >
                                                                @csrf
                                                            </form>
                                                            <form id="form-delete-{{$item->id}}" action="{{route('user.order.delete',['id'=>$item->id])}}" method="POST" >
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @endif
                                                        <a href="{{route('user.order.detail',['id'=>$item->id])}}" class="btn btn-custom">Chi tiết</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="col-sm-12">
                                <div class="padding-custom"><center>Không có đơn hàng</center></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div id="paid" class="content-order" style="display:none">
                <div class="container">
                    <div class="row">
                        @if(count($order_paid) > 0)
                        <div class="col-sm-12">
                            <div class="row container-item-order">
                                <table id="customers">
                                    <thead>
                                    <tr >
                                            <th class="t-center">Mã đơn hàng</th>
                                            <th class="t-center">Số tiền thanh toán</th>
                                            <th class="t-center">Phương thức thanh toán</th>
                                            <th class="t-center">Ngày tạo</th>
                                            <th class="t-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order_paid as $item)
                                        <tr class="t-center">
                                            <td>{{$item->id}}</td>
                                            <td> {{format_price($item->total)}}&#8363</td>
                                            <td>{{getMethodPayment($item->payment)}}</td>
                                            <td> {{format_date($item->created_at)}}</td>
                                            <td class="width-262px">
                                                <div class="flex-btn" style="padding-left: 20px;">
                                                    <a  href="{{route('user.order.detail',['id'=>$item->id])}}" class="btn btn-custom">Chi tiết</a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="col-sm-12">
                            <div class="padding-custom"><center>Không có đơn hàng</center></div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> <!--/#cart_items-->
@endsection
@section('script')
	<script>
        $('.header-order').click(function(){
            let id = $(this).data('id');

            if($('.header-order').hasClass('active'))
            {
                $('.header-order').removeClass('active');
                $(this).addClass('active');
            }
            $('.content-order').css('display','none');
            $('#'+id).css('display','block');
        })
        function deleteOrder(id){
            console.log(id);
            swal({
                title: "Huỷ đơn hàng",
                text: "Bạn có chắc chắn muốn huỷ đơn hàng ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Xoá đơn hàng",
                cancelButtonText: "Trở lại",
                closeOnConfirm: false,
                confirmButtonClass: "btn-custom",
                closeOnCancel: false
                },
                function(isConfirm){
                    if (isConfirm) {
                        $('#form-delete-'+ id).submit(); 
                    } 
                    else
                    {
                        swal.close();
                    }
                });
        }
        function payBackOrder(id){
            // console.log(1);
            swal({
                title: "Thanh toán",
                text: "Bạn có chắc chắn muốn thanh toán lại ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Thanh toán lại",
                cancelButtonText: "Trở lại",
                closeOnConfirm: false,
                confirmButtonClass: "btn-custom",
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm) {
                    $('#form-payback-' + id).submit(); 
                } 
                else
                {
                    swal.close();
                }
            });
        }
    </script>
@endsection