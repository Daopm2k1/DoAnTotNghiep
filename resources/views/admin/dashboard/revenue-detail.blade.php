@extends('layouts.admin')
@section('head')
@endsection
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <div>
              <h1 class="h3 display ">Thống kê hoá đơn</h1>
            </div>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header" style="display:flex; justify-content: flex-end;">
                  <div class="form-group row" style="margin: 0;">
                    <div class="col-sm-12" style="margin: 0;display:flex">
                      <form id="form-sort-month"  action="{{route('admin.dashboard.revenue_detail')}}" method="get">
                        <div class="form-group row d-flex d-flex-custom">
                          <div class="div-date">
                            <label class="label">Từ ngày</label>
                            <input id="from_date" type="date" name="from_date" value="{{--\Carbon\Carbon::parse(Request::get('from_date'))->format('Y-d-m')--}}" class="form-control ">
                            <div class="error error-from_date" ></div>
                          </div>
                          <div class="div-to-date div-date">
                            <label class="label">Đến ngày</label>
                            <input id="to_date" type="date" name="to_date" class="form-control ">
                            <div class="error error-to_date" ></div>
                          </div>
                        </div>
                        <div class="form-group">
                          <button type="submit" class="btn btn-filter btn-report-filter btn-primary">Lọc</button>
                          <a class="btn btn-primary btn-download" style="float:right" href="{{ route('admin.dashboard.export',['from_date'=>$from_date,'to_date'=>$to_date])}}"><i class="fa fa-download"></i></a>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>Mã hoá đơn</th>
                          <th>Họ và tên</th>
                          <th>Email</th>
                          <th>Địa chỉ</th>
                          <th>Phương thức</th>
                          <th>Ngày tạo</th>
                          <th>Ghi chú</th>
                          <th>Tổng tiền</th>
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
                          <td>{{ getMethodPaymentDashboard($row->method,$row->payment_gateway)}}</td>
                          <td>{{ $row->date }}</td>
                          <td>{{ $row->note }}</td>
                          <td>{{ format_price($row->total)}}&#8363;</td>
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
    $(document).ready(function(){
      $('#from_date').val(new Date('{{$from_date}}').toISOString().slice(0,10));
      $('#to_date').val(new Date('{{$to_date}}').toISOString().slice(0,10));
    })

    $('#from_date,#to_date').keypress(function(e){
      e.preventDefault();
    })

    // $('input[type="date"]').change(function(){
    $('#form-sort-month').submit(function(e){
      let fromDate = $('#from_date').val();
      let toDate = $('#to_date').val();
      let now = Date.now();
      let flag = false;

      if(Date.parse(fromDate) > Date.now()) {
        $('.error-from_date').html('Ngày từ phải cùng ngày hoặc sau hôm nay');
        $('.error-from_date').css('display','block');

        flag = true;
      } else {
        $('.error-from_date').html('');
        $('.error-from_date').css('display','none');
      }

      if(Date.parse(toDate) > Date.now()) {
        $('.error-to_date').html('Ngày từ phải cùng ngày hoặc sau hôm nay');
        $('.error-to_date').css('display','block');

        flag = true;
      } else if(Date.parse(fromDate) > Date.parse(toDate)) { 
        $('.error-to_date').html('Ngày đến phải cùng ngày hoặc sau ngày từ');
        $('.error-to_date').css('display','block');
        
        flag = true;
      } else {
        $('.error-to_date').html('');
        $('.error-to_date').css('display','none');
      }

      if(flag) e.preventDefault();
    })

  </script> 
@endsection
