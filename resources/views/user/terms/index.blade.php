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
    </style>
@endsection
@section('content')
<section id="cart_items">
    <div class="container">
        @include('layouts.user.breadcrumb')
        <div class="row">
            @include('user.parts.category-policy')
            <h1>Điều khoản dịch vụ</h1>
            <hr>

            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <ol>
                        <li class="li-item">
                            <span class="fw-bold mb-5-custom">Giới thiệu</span>
                            <ul>
                                <li class="li-item-child">Chào mừng quý khách hàng đến với website chúng tôi.</li>
                                <li class="li-item-child">Khi quý khách hàng truy cập vào trang website của chúng tôi có nghĩa là quý khách đồng ý với các điều khoản này. Trang web có quyền thay đổi, chỉnh sửa, thêm hoặc lược bỏ bất kỳ phần nào trong Điều khoản mua bán hàng hóa này, vào bất cứ lúc nào. Các thay đổi có hiệu lực ngay khi được đăng trên trang web mà không cần thông báo trước. Và khi quý khách tiếp tục sử dụng trang web, sau khi các thay đổi về Điều khoản này được đăng tải, có nghĩa là quý khách chấp nhận với những thay đổi đó.</li>
                                <li class="li-item-child">Quý khách hàng vui lòng kiểm tra thường xuyên để cập nhật những thay đổi của chúng tôi.</li>
                            </ul>
                        </li>
                        <li class="li-item">
                            <span class="fw-bold mb-5-custom">Hướng dẫn sử dụng website</span>
                            <ul>
                                <li class="li-item-child">Khi vào web của chúng tôi, khách hàng phải đảm bảo đủ 18 tuổi, hoặc truy cập dưới sự giám sát của cha mẹ hay người giám hộ hợp pháp. Khách hàng đảm bảo có đầy đủ hành vi dân sự để thực hiện các giao dịch mua bán hàng hóa theo quy định hiện hành của pháp luật Việt Nam.</li>
                                <li class="li-item-child">Trong suốt quá trình đăng ký, quý khách đồng ý nhận email quảng cáo từ website. Nếu không muốn tiếp tục nhận mail, quý khách có thể từ chối bằng cách nhấp vào đường link ở dưới cùng trong mọi email quảng cáo.</li>
                            </ul>
                        </li>
                        <li class="li-item">
                            <span class="fw-bold mb-5-custom">Thanh toán an toàn và tiện lợi</span>
                            <ul>
                               <li class="li-item-child">Người mua có thể tham khảo các phương thức thanh toán sau đây và lựa chọn áp dụng phương thức phù hợp:</li>
                               <li class="li-item-child"><span class="fw-bold">Cách 1:</span> Thanh toán trực tiếp (người mua nhận hàng tại địa chỉ người bán)</li>
                               <li class="li-item-child"><span class="fw-bold">Cách 2:</span> Thanh toán sau (COD – giao hàng và thu tiền tận nơi)</li>
                               <li class="li-item-child"><span class="fw-bold">Cách 3:</span> Thanh toán sau (COD – giao hàng và thu tiền tận nơi)</li>
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