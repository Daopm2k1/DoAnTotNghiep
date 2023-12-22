@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Hóa đơn tháng {{$month}}/{{$year}}</h1>
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
                    <button disabled type="submit" class="btn btn-danger btn-delete">Xóa hóa đơn</button>
                  </form>

                  {{-- Form search desktop --}}
                  {{-- <div class="card-body card-form" style="padding:0;">
                    <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.order.index')}}">
                      <div class="form-group">
                        <label for="inlineFormInputGroup" class="sr-only">Name</label>
                        <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text" placeholder="Name" class="mr-3 form-control form-control">
                      </div>
                      <div class="form-group">
                        <input type="submit" value="Search" class="mr-3 btn btn-primary">
                      </div>
                    </form>
                  </div> --}}
                </div>

                {{-- Form search Mobile --}}
                {{-- <div class="card-body card-form-mob" style="padding:0;">
                  <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.order.index')}}">
                    <div class="form-group">
                      <label for="inlineFormInputGroup" class="sr-only">Name</label>
                      <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text" placeholder="Name" class="mr-3 form-control form-control">
                    </div>
                    <div class="form-group">
                      <input type="submit" value="Search" class="mr-3 btn btn-primary">
                    </div>
                  </form>
                </div> --}}

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>
                            <input type="checkbox" name="check_all" class="check_all">
                          </th>
                          <th>Thông ti
                            n</th>
                          <th>Phương thức</th>
                          <th>Tổng cộng</th>
                          <th>Ghi chú</th>
                          <th>Ngày tạo</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          <td><input type="checkbox" name="check" class="check" data-id="{{ $row->id }}"></td>
                          <td>
                            <div>Họ tên: {{ $row->fullname }}</div>
                            <div>Email: {{ $row->email }}</div>
                            <div>SĐT: {{ $row->phone }}</div>
                            <div>Địa chỉ: {{ $row->address }}</div>
                          </td>
                          <td>{{$row->payment->method}}</td>
                          <td>{{ $row->total }}</td>
                          <td>{{ $row->note }}</td>
                          <td>{{format_date($row->created_at)}}</td>
                          <td>
                            <span class="info cursor" data-id="{{ $row->id }}"><i class="fa fa-info"></i></span>
                          </td>
                          {{-- <th>
                            <span class="edit cursor" data-id="{{ $row->id }}"  data-name="{{ $row->name }}"><i class="fa fa-edit"></i></span>
                          </th> --}}
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
  </script> 
@endsection
