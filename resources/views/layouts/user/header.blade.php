<header id="header"><!--header-->
  <div class="header_top"><!--header_top-->
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <div class="contactinfo">
            <ul class="nav nav-pills">
              <li><a href="#"><i class="fa fa-phone"></i> 0989275330</a></li>
              <li><a href="#"><i class="fa fa-phone"></i> 0999999999</a></li>
              <li><a href="#"><i class="fa fa-envelope"></i> admin@eshop.com</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div><!--/header_top-->
  <!-- oke oong -->
  <div class="header-middle"><!--header-middle-->
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <div class="logo pull-left">
            <a href="{{route('home')}}"><img src="{{ asset('user/images/home/logo.png') }}" alt="" /></a>
          </div>

        </div>
        <div class="col-sm-8">
          <div class="shop-menu pull-right">
            <ul class="nav navbar-nav" style="display:inline">
              <li ><a style="color: #FE980F;" href="{{URL::to('wishlist')}}"><i class="fa fa-star"></i> Yêu thích</a></li>
              <li><a href="{{url('/checkout')}}"><i class="fa fa-crosshairs"></i> Thanh toán</a></li>
              <li class="box-cart">
                <a href="{{URL::to('cart')}}"><i class="fa fa-shopping-cart"></i> Giỏ hàng</a>
                <span class="box-count-cart">
                  @if(Session::has('cart') && (count(Session::get('cart')) > 0))
                      <span class="count-cart">{{count(Session::get('cart'))}}</span>
                  @else
                      {{''}}
                  @endif
                </span>
              </li>
              @if(!Auth::check())
                <li><a href="{{route('login')}}"><i class="fa fa-lock"></i> Đăng nhập</a></li>
              @else
                {{-- <li><a href="#"><i class="fa fa-user"></i> Account</a></li> --}}
                <li class="dropdown"><a href="#"><i class="fa fa-user"></i>{{ Auth::user()->username }}</a>
                  <ul role="menu" class="sub-menu sub-menu-custom">
                      @if(Auth::user()->checkAdmin())
                        <li><a target="_blank" href="{{route('admin.dashboard.index')}}">Quản lí</a></li>
                      @endif
                      <li><a href="{{route('user.profile.index')}}">Thông tin tài khoản</a></li>
                      <li><a href="{{route('user.change_password.get')}}">Đổi mật khẩu</a></li>
                      <li><a  href="{{route('user.order.index')}}">Đơn hàng</a></li>
                      <li><a  href="{{route('user.discount.index')}}">Giảm giá</a></li>
                      <li><a href="{{ route('logout') }}">Đăng xuất</a></li>
                  </ul>
              </li> 
              @endif
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div><!--/header-middle-->

  <div class="header-bottom"><!--header-bottom-->
    <div class="container">
      <div class="row">
        <div class="col-sm-9">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle toggle-custom" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="mainmenu pull-left">
            <ul class="nav navbar-nav collapse navbar-collapse">
              <li><a href="{{URL::to('home')}}" {{--class="active"--}}>Trang chủ</a></li>
              <li class="dropdown"><a class="cursor">Danh mục<i class="fa fa-angle-down"></i></a>
                <ul role="menu" class="sub-menu">
                @foreach($categories as $key => $cate)
                  <li><a href="{{URL::to('/category/'.$cate->id)}}">{{$cate->name}}</a></li>
                @endforeach
                </ul>
              </li>
              <li class="dropdown"><a class="cursor">Tin tức mỗi ngày<i class="fa fa-angle-down"></i></a>
                <ul role="menu" class="sub-menu">
                  @foreach($category_post as $key => $cate)
                    <li><a href="{{URL::to('/post-cate/'.$cate->post_path)}}">{{$cate->post_name}}</a></li>
                  @endforeach
                </ul>
              </li>
              <li><a href="{{URL::to('infor-contact')}}">Liên hệ</a></li>
            </ul>
          </div>
        </div>
        <div class="col-sm-3">
          <form action="{{URL::to('/home')}}" method="get" autocomplete="off">
							<div class="search_box pull-right" style="display: flex;">
								<input type="text" id="key_word" name="key_word" placeholder="Nhập tên sản phẩm" style="width: 302px;"/>
								<input type="submit" style="margin-top:0, color:#000000; display: flex;" class="btn btn-custom btn-default search" value="Tìm kiếm"/>
							</div>
              <div id="search_ajax"></div>
					</form>
        </div>
      </div>
    </div>
  </div><!--/header-bottom-->
</header><!--/header-->
