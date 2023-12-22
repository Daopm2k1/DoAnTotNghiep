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
            margin:5px 0;
        }
        .li-item-child
        {
            list-style-type: disc;
            margin:5px 0;
        }
    </style>
@endsection
@section('content')
<section id="cart_items">
    <div class="container">
        @include('layouts.user.breadcrumb')
        <div class="row">
            @include('user.parts.category-policy')
            <h1>Giới thiệu</h1>
            <hr>

            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <ul>
                        <li class="li-item">Trang giới thiệu giúp khách hàng hiểu rõ hơn về cửa hàng của bạn. Hãy cung cấp thông tin cụ thể về việc kinh doanh, về cửa hàng, thông tin liên hệ. Điều này sẽ giúp khách hàng cảm thấy tin tưởng khi mua hàng trên website của bạn.</li>
                        <li class="li-item">
                            <span>Một vài gợi ý cho nội dung trang Giới thiệu:</span>
                            <ul>
                                <li class="li-item-child">Bạn là ai</li>
                                <li class="li-item-child">Giá trị kinh doanh của bạn là gì</li>
                                <li class="li-item-child">Địa chỉ cửa hàng</li>
                                <li class="li-item-child">Bạn đã kinh doanh trong ngành hàng này bao lâu rồi</li>
                                <li class="li-item-child">Bạn kinh doanh ngành hàng online được bao lâu</li>
                                <li class="li-item-child">Đội ngũ của bạn gồm những ai</li>
                                <li class="li-item-child">Thông tin liên hệ</li>
                                <li class="li-item-child">Liên kết đến các trang mạng xã hội (Twitter, Facebook)</li>
                            </ul>
                        </li>
                        {{-- <li class="li-item">Bạn có thể chỉnh sửa hoặc xoá bài viết này tại đây hoặc thêm những bài viết mới trong phần quản lý Trang nội dung.</li> --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section> <!--/#cart_items-->
@endsection
@section('script')
	
@endsection