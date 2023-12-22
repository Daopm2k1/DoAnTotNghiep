@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <div>
              <h1 class="h3 display ">Chi tiết doanh thu {{$month}}/{{$year}}</h1>
            </div>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                {{-- <div class="card-header" style="display:flex;">

                  Form delete
                  <form id="delete-order" action="{{ route('admin.order.delete') }}" method="POST" style=" display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="list_id" id="list_id" value="">
                    <button disabled type="submit" class="btn btn-danger btn-delete">Xóa hóa đơn</button>
                  </form>
                  Form search desktop
                  <div class="card-body card-form" style="padding:0;">
                    <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.dashboard.revenue')}}">
                      <div class="form-group">
                        <label for="inlineFormInputGroup" class="sr-only">Name</label>
                        <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text" placeholder="Nhập họ tên hoặc ID hoá đơn" class="mr-3 form-control form-control">
                      </div>
                      <div class="form-group">
                        <input type="submit" value="Tìm kiếm" class="mr-3 btn btn-primary">
                      </div>
                    </form>
                  </div>
                </div> --}}
                {{-- Form search Mobile --}}
                {{-- <div class="card-body card-form-mob" style="padding:0;">
                  <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.dashboard.revenue')}}">
                    <div class="form-group">
                      <label for="inlineFormInputGroup" class="sr-only">Name</label>
                      <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text" placeholder="Nhập họ tên hoặc ID hoá đơn" class="mr-3 form-control form-control">
                    </div>
                    <div class="form-group">
                      <input type="submit" value="Tìm kiếm" class="mr-3 btn btn-primary">
                    </div>
                  </form>
                </div> --}}
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>Mã đơn hàng</th>
                          <th>Họ và tên</th>
                          <th>Email</th>
                          <th>Địa chỉ</th>
                          <th>Tổng tiền</th>
                          <th>Phương thức</th>
                          <th>Ngày tạo</th>
                          <th>Ghi chú</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          {{-- {{dd($row)}} --}}
                          <td>{{ $row->id}}</td>
                          <td>{{ $row->fullname}}</td>
                          <td>{{ $row->email }}</td>
                          <td>{{ $row->address }}</td>
                          <td>{{ format_price($row->total)}}&#8363;</td>
                          <td>{{ getMethodPaymentDashboard($row->method,$row->payment_gateway)}}</td>
                          <td>{{ $row->date }}</td>
                          <td>{{ $row->note }}</td>
                          <td>
                            <span class="info cursor" data-id="{{ $row->id }}"><i class="fa fa-info"></i></span>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <div class="mt-3" style="display: flex;justify-content: center;"><center>{{ $rows->withQueryString()->links() }}</center></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
  </section>
</section>
@endsection
@section('script')
  <script >
    $('#month').change(function(){
        $('#form-sort-month').submit();
    })
    
  //Click info
  $('.info').click(function(){
      let id = $(this).data('id');
      window.location.href= location.origin + '/admin/order/' + id + "/detail";
    });
  </script> 
@endsection
