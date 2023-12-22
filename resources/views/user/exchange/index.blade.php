@extends('layouts.user')
@section('head')
    <style>
        #cart_items
        {
            padding-bottom: 60px;
        }
        .li-item
        {
            margin-bottom: 5px;
        }
        .li-item::marker 
        {
            font-weight: bold; 
        }
        .li-item-child
        {
            list-style-type: disclosure-closed;
            padding-bottom: 5px;
            padding-top: 5px;
        }
        .li-item-small
        {
            list-style-type: disc;
            padding-bottom: 5px;
            padding-top: 5px;
        }
    </style>
@endsection
@section('content')
<section id="cart_items">
    <div class="container">
        @include('layouts.user.breadcrumb')
        <div class="row">
            @include('user.parts.category-policy')
            <h1>Chính sách đổi trả</h1>
            <hr>

            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <ol>
                        <li class="li-item">
                            <span class="fw-bold mb-5-custom">Điều kiện đổi trả</span>
                            <ul>
                                <li class="li-item-child">
                                    <span>Quý Khách hàng cần kiểm tra tình trạng hàng hóa và có thể đổi hàng/ trả lại hàng ngay tại thời điểm giao/nhận hàng trong những trường hợp sau:</span>
                                    <ul>
                                        <li class="li-item-small">Hàng không đúng chủng loại, mẫu mã trong đơn hàng đã đặt hoặc như trên website tại thời điểm đặt hàng.</li>
                                        <li class="li-item-small">Không đủ số lượng, không đủ bộ như trong đơn hàng.</li>
                                        <li class="li-item-small">Tình trạng bên ngoài bị ảnh hưởng như rách bao bì, bong tróc, bể vỡ…</li>
                                    </ul>
                                </li>
                                <li class="li-item-child"> Khách hàng có trách nhiệm trình giấy tờ liên quan chứng minh sự thiếu sót trên để hoàn thành việc hoàn trả/đổi trả hàng hóa. </li>
                            </ul>
                        </li>
                        <li class="li-item">
                            <span class="fw-bold mb-5-custom">Quy định về thời gian thông báo và gửi sản phẩm đổi trả</span>
                            <ul>
                                <li class="">
                                    <ul>
                                        <li class="li-item-small">Thời gian thông báo đổi trả: trong vòng 48h kể từ khi nhận sản phẩm đối với trường hợp sản phẩm thiếu phụ kiện, quà tặng hoặc bể vỡ.</li>
                                        <li class="li-item-small">Thời gian gửi chuyển trả sản phẩm: trong vòng 14 ngày kể từ khi nhận sản phẩm.</li>
                                        <li class="li-item-small">Địa điểm đổi trả sản phẩm: Khách hàng có thể mang hàng trực tiếp đến văn phòng/ cửa hàng của chúng tôi hoặc chuyển qua đường bưu điện.</li>
                                    </ul>
                                </li>
                               <li class="li-item-child">Trong trường hợp Quý Khách hàng có ý kiến đóng góp/khiếu nại liên quan đến chất lượng sản phẩm, Quý Khách hàng vui lòng liên hệ đường dây chăm sóc khách hàng của chúng tôi.</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section> <!--/#cart_items-->
@endsection
@section('script')
	
@endsection