@extends('layouts.admin')
@section('head')
   <link rel="stylesheet" href="{{ asset('css/admin/order.css') }}">
   <style>
     .btn-paid {
       width: 100px;
     }
     .box-header {
       display: flex;
       align-items: center;
     }
     .box-header h1 {
        flex: 1;
     }
     .box-header a {
      margin-left: 10px;
     }
   </style>
@endsection
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <div class="box-header">
              <h1 class="h3 display">{{$title_page}}</h1>
              @if($isIndex ?? false) <a href="{{ route('admin.order.order_online_unpaid') }}" class="btn  btn-info">Đơn hàng online chưa thanh toán</a>@endif
            </div>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header" style="display:flex;">

                  {{-- Form delete --}}
                  <form id="delete-order" action="{{ route('admin.order.delete') }}" method="POST" style=" display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="list_id" id="list_id" value="">
                    <button disabled type="submit" class="btn btn-danger btn-delete">{{$title_delete}}</button>
                  </form>

                  @if(isset($isOrder) && $isOrder)
                  {{-- Form search desktop --}}
                  <div class="card-body card-form" style="padding:0;">
                    <form class="form-inline" style="float: right;" method="GET" action="{{$url}}">
                      {{-- <div class="form-group">
                        <label for="date" class="sr-only">Name</label>
                        <input id="date" value="{{Request::get('date')}}" name="date" type="date" class="mr-3 form-control form-control">
                      </div> --}}
                      <div class="form-group">
                        <label for="inlineFormInputGroup" class="sr-only">Name</label>
                        <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text" placeholder="Nhập mã đơn hàng" class="mr-3 form-control form-control">
                      </div>
                      <div class="form-group">
                        <input type="submit" value="Tìm kiếm" class="mr-3 btn btn-primary">
                      </div>
                    </form>
                  </div>
                  @endif
                </div>

                @if(isset($isOrder) && $isOrder)
                {{-- Form search Mobile --}}
                <div class="card-body card-body-mob">
                <form class="form-inline f-rights"  method="GET" action="{{$url}}">
                    {{-- <div class="form-group">
                      <label for="date" class="sr-only">Name</label>
                      <input id="date" value="{{Request::get('date')}}" name="date" type="date" class="mr-3 form-control form-control">
                    </div> --}}
                    <div class="form-group">
                      <label for="inlineFormInputGroup" class="sr-only">Name</label>
                      <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text" placeholder="Nhập mã đơn hàng" class="mr-3 form-control form-control">
                    </div>
                    <div class="form-group">
                      <input type="submit" value="Tìm kiếm" class="mr-3 btn btn-primary">
                    </div>
                  </form>
                </div>
                @endif

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>
                            <input type="checkbox" name="check_all" class="check_all cursor">
                          </th>
                          <th>Mã đơn hàng</th>
                          <th>Thông tin</th>
                          <th>Phương thức</th>
                          <th>Tổng cộng</th>
                          <th>Trạng thái</th>
                          <th>Ngày lập</th>
                          <th>Ghi chú</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          <td><input type="checkbox" name="check" class="check cursor" data-id="{{ $row->id }}"></td>
                          <td>{{$row->id}}</td>
                          <td>
                            <div>Họ tên: {{ $row->fullname }}</div>
                            <div>Email: {{ $row->email }}</div>
                            <div>SĐT: {{ $row->phone }}</div>
                            <div>Địa chỉ: {{ $row->address }}</div>
                          </td>
                          <td>{{ getMethodPayment($row->payment)}}</td>
                          <td>{{ format_price($row->total) }}&#8363;</td>
                          <td><button id="btn-paid-{{$row->id}}" class="btn btn-paid {{$row->payment->status ? "btn-success" : "btn-danger"}}" data-id="{{$row->id}}" >{{$row->payment->status ? "Đã thanh toán" : "Chưa thanh toán"}}</button></td>
                          <td>{{ format_date($row->created_at) }}</td>
                          <td>{{ $row->note }}</td>
                          <th>
                            <span class="info cursor" data-id="{{ $row->id }}"  data-name="{{ $row->name }}"><i class="fa fa-info"></i></span>
                          </th>
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

    @include('admin.part.modal-confirm')
  </section>
</section>
@endsection
@section('script')
  <script >
    //click delete
    $('.btn-delete').click(function(e){
      e.preventDefault();
      $('#modal-confirm').modal('show');
    });

  //Click info
  $('.info').click(function(){
      let id = $(this).data('id');
      window.location.href= location.origin + '/admin/order/' + id + "/detail";
    });

    // Click confirm
    $('#confirm').click(function(){
      let list = $('input[name=check]');
      $('#confirm').prop('disabled', true);
      $('.btn-secondary').prop('disabled', true);
      $('#modal-confirm .close').prop('disabled', true);
      let list_id = [];
      $.each( list, function( key, value ) {
         if(value.checked == true)
         {
            list_id.push($(value).data('id'));
         }
      });
      $('#list_id').val(JSON.stringify(list_id));
      $('#delete-order').submit();
    })

    $('#modal-order').on('hidden.bs.modal', function (e) {
      $('.error-order').css('display','none');
    })

    $('.btn-paid').click(function(){
      let id = $(this).data('id');

      $.ajax({
        url: location.origin + '/admin/order/' + id,
        method: 'PUT',
        data: {
            id: id,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success:function(res)
        {
          if(!res.error)
          {
            let btn = $('#btn-paid-' + id);
            if(res.data.status) {
              btn.html('Đã thanh toán');
              if(btn.hasClass('btn-danger'))
              {
                btn.removeClass('btn-danger');
              }
              btn.addClass('btn-success');
            }
            else {
              btn.html('Chưa thanh toán');
              if(btn.hasClass('btn-success'))
              {
                btn.removeClass('btn-success');
              }
              btn.addClass('btn-danger');
            }
          }
        }
      });
    });

  </script> 
@endsection
