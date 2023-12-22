@extends('layouts.user')
@section('head')
    <style>
        #cart_items
        {
            padding-bottom: 60px;
        }
        .li-item
        {
            list-style-type: disclosure-closed;
            padding-bottom: 5px;
        }
    </style>
@endsection
@section('content')
<section id="cart_items">
    <div class="container">
        @include('layouts.user.breadcrumb')
        <div class="row">
            @include('user.parts.category-policy')
            <h1>Chính sách bảo mật</h1>
            <hr>

            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <ul>
                        <li class="li-item">Chính sách bảo mật này nhằm giúp Quý khách hiểu về cách website thu thập và sử dụng thông tin cá nhân của mình thông qua việc sử dụng trang web, bao gồm mọi thông tin có thể cung cấp thông qua trang web khi Quý khách đăng ký tài khoản, đăng ký nhận thông tin liên lạc từ chúng tôi, hoặc khi Quý khách mua sản phẩm, dịch vụ, yêu cầu thêm thông tin dịch vụ từ chúng tôi.</li>
                        <li class="li-item">Chúng tôi sử dụng thông tin cá nhân của Quý khách để liên lạc khi cần thiết liên quan đến việc Quý khách sử dụng website của chúng tôi, để trả lời các câu hỏi hoặc gửi tài liệu và thông tin Quý khách yêu cầu.</li>
                        <li class="li-item">Trang web của chúng tôi coi trọng việc bảo mật thông tin và sử dụng các biện pháp tốt nhất để bảo vệ thông tin cũng như việc thanh toán của khách hàng. </li>
                        <li class="li-item">Mọi thông tin giao dịch sẽ được bảo mật ngoại trừ trong trường hợp cơ quan pháp luật yêu cầu.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section> <!--/#cart_items-->
@endsection
@section('script')
	
@endsection