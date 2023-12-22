@extends('layouts.admin')
@section('head')
  <style>
      .text-uppercase
      {
        align-self: center;
        width: 100%;
        padding-left:10px;
      }
      .container-icon
      {
        display: flex;
        position: relative;
      }
      .dashboard-counts .count-number {
          font-size: 2rem;
      }
      .name
      {
        width: 100%;
      }
      .btn-download:hover
      {
        text-decoration: none;
      }
      .btn-download
      {
        margin-left: 10px;
      }
  </style>
@endsection
@section('content')
<section class="charts">
    <div class="container-fluid">
      <!-- Page Header-->
      <header> 
        <h1 class="h3 display" style="flex:0%">Bảng điều khiển </h1>
      </header>
      <div class="dashboard-counts">
        <div class="card line-chart-example">
          <div class="card-header d-flex d-flex-dashboard align-items-center">
            <h4 style="flex: 0%;">Thông tin tháng {{$month}}/{{$year}}</h4>
            <div class="form-group row" style="margin: 0;">
              <div class="col-sm-12 box-info-dashboard">
                <form id="form-sort-month" style="display:flex;" action="{{route('admin.dashboard.index')}}" method="get">
                  <label for="sort-month" class="label-select label">Tháng</label>
                  <select id="sort-month" type="tel" name="sort_month" class="dashboard-input-select form-control form-control-sm" >
                      @for($m = 1;$m < 13 ;$m++)
                      {
                        <option @if(Request::get('sort_month') == $m || (empty(Request::get('sort_month')) && \Carbon\Carbon::now()->month == $m)) selected @endif value="{{$m}}">{{$m}}</option>
                      }
                      @endfor
                  </select>
                <label for="sort-year" class="label-select label">Năm</label>
                <select id="sort-year" type="tel" name="sort_year" class="dashboard-input-select form-control form-control-sm" >
                    {{-- <option value="">--Chọn năm--</option> --}}
                      @for($y = 1970;$y<=\Carbon\Carbon::now()->format('Y');$y++)
                      {
                        <option @if(Request::get('sort_year') == $y || (empty(Request::get('sort_year')) && \Carbon\Carbon::now()->year == $y)) selected @endif value="{{$y}}">{{$y}}</option>
                      }
                      @endfor
                  </select>
                  {{-- <button type="submit" class="btn-download btn btn-primary btn-filter">Lọc</button> --}}
                </form>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="container">
              <div class="row">
                <!-- Count item widget-->
                <div class="col-md-3 col-6">
                  <div class="wrapper count-title d-flex">
                    <div class="name">
                      <a id="revenue" data-month="{{$month}}" data-year="{{$year}}" href="#" style="text-decoration: none">
                        <div class="container-icon">
                          <i class="icon-check"></i>
                            <strong class="text-uppercase">Doanh thu</strong>
                        </div>
                        <div class="count-number">{{format_price($revenue)}}&#8363;</div>
                      </a>
                    </div>
                  </div>
                </div>
                <!-- Count item widget-->
                <div class="col-md-3 col-6">
                  <div class="wrapper count-title d-flex">
                    <div class="name">
                      <a id="order-month" data-month="{{$month}}" data-year="{{$year}}" href="#" style="text-decoration: none">
                      <div class="container-icon">
                        <i class="icon-bill"></i>
                          <strong class="text-uppercase">Hóa đơn</strong>
                      </div>
                      <div class="count-number">{{$bill}}</div>
                      </a>
                    </div>
                  </div>
                </div>
                <!-- Count item widget-->
                <div class="col-md-3 col-6">
                  <div class="wrapper count-title d-flex">
                    <div class="name">
                      <a href="{{route('admin.dashboard.new_order')}}" style="text-decoration: none">
                        <div class="container-icon">
                          <i class="icon-padnote"></i>
                            <strong class="text-uppercase">Đơn hàng mới</strong>
                        </div>
                        <div class="count-number">{{$new_order}}</div>
                      </a>
                    </div>
                  </div>
                </div>
                <!-- Count item widget-->
                <div class="col-md-3 col-6">
                  <div class="wrapper count-title d-flex">
                    <div class="name">
                      <a href="{{route('admin.dashboard.product-almost-over')}}" style="text-decoration: none">
                        <div class="container-icon">
                          <i class="icon-interface-windows"></i>
                            <strong class="text-uppercase">Hàng sắp hết</strong>
                        </div>
                        <div class="count-number">{{$product}}</div>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="card line-chart-example">
            <div class="card-header d-flex  d-flex-dashboard align-items-center">
              <h4 style="flex: 0%;">Biểu đồ doanh thu hằng tháng</h4>
              <div class="form-group row" style="margin: 0;">
                <div class="col-sm-12 box-info-dashboard">
                  <form id="form-sort" action="{{route('admin.dashboard.index')}}" method="get">
                    <select style="height: 100%;" id="sort" type="tel" name="sort" class="form-control form-control-sm" >
                      {{-- <option value="">--Chọn năm--</option> --}}
                        @for($y = 1970;$y<=\Carbon\Carbon::now()->format('Y');$y++)
                        {
                          <option @if(Request::get('sort') == $y || (empty(Request::get('sort')) && \Carbon\Carbon::now()->year == $y)) selected @endif value="{{$y}}">{{$y}}</option>
                        }
                        @endfor
                    </select>
                  </form>
                  @php $data_year = !empty(Request::get('sort')) ? Request::get('sort') : \Carbon\Carbon::now()->year; @endphp
                  <button class="btn-download btn btn-primary" id="btn-detail" data-year="{{$data_year}}">Thống kê</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <canvas id="lineChartExample"></canvas>
            </div>
          </div>
        </div>

        <div class="col-lg-12">
          <div class="card line-chart-example">
            <div class="card-header d-flex align-items-center">
              <h4>Sản phẩm bán chạy nhất</h4>
            </div>
            <div class="card-body">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                </thead>
                <tbody>
                    @if(!empty($rows))
                      @foreach ($rows as $key=>$value)
                      <tr>
                        <td>#{{$key+1}}</td>
                        <td>{{$value->name}}</td>
                        <td>{{$value->count_product}}</td>
                        <td>{{format_price($value->total_price)}}&#8363;</td>
                      </tr>
                      @endforeach
                    @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
@endsection

@section('script')
   <script>
      $('#sort').change(function(){
          $('#form-sort').submit();
      });
      $('#btn-detail').click(function(){
        let year = $(this).data('year');
        // console.log(year);
        window.location.href = location.origin + '/admin/dashboard/revenue-detail';
      })
      $('#revenue').click(function(e){
        // e.preventdefault();
        let month = $(this).data('month');
        let year = $(this).data('year');
        window.location.href = location.origin + '/admin/dashboard/revenue?year=' + year + '&month=' + month;
      })

      $('#order-month').click(function(e){
        // e.preventdefault();
        let month = $(this).data('month');
        let year = $(this).data('year');
        window.location.href = location.origin + '/admin/dashboard/order-month?year=' + year + '&month=' + month;
      })

      $('#sort-month').change(function(){
        $('#form-sort-month').submit();
      })
      $('#sort-year').change(function(){
        $('#form-sort-month').submit();
      })

      let amount = {{$data_total}};
   </script>
   <script src="{{asset('admin/js/charts-custom.js')}}"></script>
@endsection