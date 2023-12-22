<!-- Side Navbar -->
<nav class="side-navbar">
  <div class="side-navbar-wrapper">
    <!-- Sidebar Header    -->
    <div class="sidenav-header d-flex align-items-center justify-content-center">
      <!-- User Info-->
      <div class="sidenav-header-inner text-center cursor">
        <a target="_blank" href="{{route('user.profile.index')}}" class="nav-link logout"> 
          <img src="{{ (Auth::check() && !empty(Auth::user()->avatar)) ? asset(Auth::user()->avatar) : asset('images/none-user.png')}}" alt="person" class="img-fluid rounded-circle">
          <h2 class="h5">{{Auth::user()->username}}
        </a>
        {{-- <span>Web Developer</span> --}}
      </div>
      <!-- Small Brand information, appears on minimized sidebar-->
    <div class="sidenav-header-logo"><a href="{{route('home')}}" class="brand-small text-center"><strong class="text-primary">E</strong></a></div>
    </div>
    <!-- Sidebar Navigation Menus-->
    <div class="main-menu">
      {{-- <h5 class="sidenav-heading">Main</h5> --}}
      <ul id="side-main-menu" class="side-menu list-unstyled">                  
        <li class=@if(isset($isDashboard) && $isDashboard) {{'active'}} @endif><a href="{{route('admin.dashboard.index')}}"> <i class="icon-home"></i>Bảng điều khiển</a></li>
        {{-- <li><a href="{{route('admin.profile.index')}}"> <i class="fa fa-user"></i>Profile</a></li> --}}
        <li class=@if(isset($isSlide) && $isSlide) {{'active'}} @endif><a href="{{route('admin.slide.index')}}"> <i class="fa fa-sliders"></i></i>Trình chiếu</a></li>
        <li class=@if(isset($isUser) && $isUser) {{'active'}} @endif><a href="#exampledropdownDropdown" id="manager-user" aria-expanded="false" data-toggle="collapse"> <i class="fa fa-users"></i>Quản lí tài khoản</a>
          <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
            <li><a href="{{route('admin.user.index')}}">Tài khoản</a></li>
            <li><a href="{{route('admin.role.index')}}">Phân quyền</a></li>
          </ul>
        </li>
        <li class=@if(isset($isProduct) && $isProduct) {{'active'}} @endif><a href="#exampledropdownDropdown1" id="manager-product" aria-expanded="false" data-toggle="collapse"> <i class="icon-interface-windows"></i>Quản lí sản phẩm</a>
          <ul id="exampledropdownDropdown1" class="collapse list-unstyled ">
            <li><a href="{{route('admin.category.index')}}">Danh mục</a></li>
            <li><a href="{{route('admin.product.index')}}">Sản phẩm</a></li>
            <li><a href="{{route('admin.color.index')}}">Màu sắc</a></li>
            <li><a href="{{route('admin.size.index')}}">Kích thước</a></li>
            <li><a href="{{route('admin.tag.index')}}">Từ khóa</a></li>
          </ul>
        </li>
        <li class=@if(isset($isPost) && $isPost) {{'active'}} @endif><a href="#exampledropdownDropdown2" id="manager-post" aria-expanded="false" data-toggle="collapse"> <i class="fa fa-list"></i>Quản lí bài viết</a>
          <ul id="exampledropdownDropdown2" class="collapse list-unstyled ">
            <li><a href="{{route('admin.post_cate.index')}}">Danh mục</a></li>
            <li><a href="{{route('admin.post.index')}}">Bài viết</a></li>
          </ul>
        </li>
        <li class=@if(isset($isInforContact) && $isInforContact) {{'active'}} @endif><a href="{{route('admin.infor_contact.index')}}"> <i class="fa fa-address-book"></i></i>Thông tin liên hệ</a></li>
        <li class=@if(isset($isOrder) && $isOrder) {{'active'}} @endif><a href="{{route('admin.order.index')}}"> <i class="fa fa-cart-plus"></i>Đơn hàng</a></li>
        {{-- <li class=@if(isset($isBill) && $isBill) {{'active'}} @endif><a href="{{route('admin.bill.index')}}"><i class="icon-bill"></i></i>Hoá đơn</a></li> --}}
        <li class=@if(isset($isOpinion) && $isOpinion) {{'active'}} @endif><a href="{{route('admin.opinion.index')}}"> <i class="fa fa-comments"></i>Ý kiến</a></li>
        <li class=@if(isset($isWishlist) && $isWishlist) {{'active'}} @endif><a href="{{route('admin.wishlist.index')}}"> <i class="fa fa-heart"></i>Yêu thích</a></li>
        <li class=@if(isset($isDiscount) && $isDiscount) {{'active'}} @endif><a href="{{route('admin.discount.index')}}"> <i class="fa fa-percent"></i>Giảm giá</a></li>
        <li class=@if(isset($isShip) && $isShip) {{'active'}} @endif><a href="{{route('admin.ship.index')}}"><i class="fa fa-money"></i>Phí vận chuyển</a></li>
      </ul>
    </div>
  </div>
</nav>
