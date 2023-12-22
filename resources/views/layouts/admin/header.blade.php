<header class="header">
    <nav class="navbar">
      <div class="container-fluid">
        <div class="navbar-holder d-flex align-items-center justify-content-between">
          <div class="navbar-header"><a id="toggle-btn" href="#" class="menu-btn"><i class="icon-bars"> </i></a><a target="_blank" href="{{url('/')}}" class="navbar-brand">
              <div class="brand-text d-none d-md-inline-block"><span>ESHOP </span>
                {{-- <strong class="text-primary">Eshop</strong> --}}
              </div></a></div>
          <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
            <!-- Log out-->
            <li class="nav-item">
              <a target="_blank" href="{{route('user.change_password.get')}}" class="nav-link logout"> <span class="d-none d-sm-inline-block">Đổi mật khẩu</span><i class="fa fa-key"></i></a>
            </li>
            <li class="nav-item">
              <a target="_blank" href="{{route('user.profile.index')}}" class="nav-link logout"> <span class="d-none d-sm-inline-block">{{ Auth::user()->username }}</span><i class="fa fa-user"></i></a>
            </li>
            <li class="nav-item">
              <a href="{{route('logout')}}" class="nav-link logout"> <span class="d-none d-sm-inline-block">Đăng xuất</span><i class="fa fa-sign-out"></i></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>