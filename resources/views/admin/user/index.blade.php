@extends('layouts.admin')
@section('head')
    <style>
        .change-pass:hover
        {
          color: blue;
          text-decoration: underline;
        }
        span.lock{
          min-width: 40px;
          min-height: 40px;
          margin: auto;
        }
    </style>
@endsection
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Tài khoản</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header" style="display:flex;">
                   {{-- Form delete --}}
                  <form id="delete-user" action="{{ route('admin.user.delete') }}" method="POST" style=" display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="list_id" id="list_id" value="">
                    <button disabled type="submit" class="btn btn-danger btn-delete">Xóa tài khoản</button>
                  </form>

                  {{-- Form search desktop --}}
                  <div class="card-body  card-form" style="padding:0;">
                    <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.user.index')}}">
                      <div class="form-group">
                        <label for="inlineFormInputGroup" class="sr-only">Tài khoản</label>
                        <input id="inlineFormInputGroup" value="{{Request::get('search')}}" value="{{Request::get('search')}}" name="search" type="text" placeholder="Tài khoản" class="mr-3 form-control form-control">
                      </div>
                      <div class="form-group">
                        <input type="submit" value="Tìm kiếm" class="mr-3 btn btn-primary">
                      </div>
                    </form>
                  </div>
                  {{-- Button create --}}
                  <button class="btn btn-primary btn-create" data-url="{{ route('admin.user.create') }}">Tạo tài khoản</button>
                </div>

                 {{-- Form search Mobile --}}
                <div class="card-body  card-form-mob" style="padding:0;">
                    <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.user.index')}}">
                      <div class="form-group">
                        <label for="inlineFormInputGroup" class="sr-only">Tài khoản</label>
                        <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text" placeholder="Tài khoản" class="mr-3 form-control form-control">
                      </div>
                      <div class="form-group">
                        <input type="submit" value="Tìm kiếm" class="mr-3 btn btn-primary">
                      </div>
                    </form>
                  </div>

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>
                            <input type="checkbox" name="check_all" class="check_all cursor">
                          </th>
                          <th>Họ tên</th>
                          <th>Emaill</th>
                          <th>Số điện thoại</th>
                          <th>Phân quyền</th>
                          <th>Trạng thái</th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr> 
                          <td><input type="checkbox" name="check" class="check cursor" data-id="{{ $row->id }}"></td>
                          <td>{{ $row->username }}</td>
                          <td>{{ $row->email }}</td>
                          <td>{{ $row->phone }}</td>
                          <td>{{ $row->roles->first()['name']}}</td>
                          <td id="status_{{$row->id}}">
                           {{$row->status ? "Đang hoạt động" : "Đã khoá"}}
                          </td>
                          <td id="user_{{$row->id}}">
                            @if($row->status)
                              <span class="lock cursor btn btn-danger" data-id="{{ $row->id }}"><i class="fa fa-lock"></i></span> 
                            @else
                              <span class="lock cursor btn btn-primary" data-id="{{ $row->id }}"><i class="fa fa-unlock"></i></span>
                            @endif
                          </td>
                          <td>
                            <span class="edit cursor" data-id="{{ $row->id }}"><i class="fa fa-edit"></i></span>
                          </td>
                          <td>
                            <span class="cursor change-pass" data-id="{{ $row->id }}">Đặt lại mật khẩu</span>
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
        @include('admin.part.modal-confirm')
  </section>
@endsection
@section('script')
  <script >
    //Click edit
    $('.edit').click(function(){
      let id = $(this).data('id');
      window.location.href= location.origin + '/admin/user/edit/' + id;
    });
    //Click change
    $('.change-pass').click(function(){
      let id = $(this).data('id');
      window.location.href= location.origin + '/admin/user/' + id + '/reset-password';
    });
    
    //click delete
    $('.btn-delete').click(function(e){
      e.preventDefault();
      $('#modal-confirm').modal('show');
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
      $('#delete-user').submit();
    })
    //click create
    $('.btn-create').click(function(){
      let url = $(this).data('url');
      window.location.href = url;
    });

    //Click lock
    $('.lock').click(function(){
      let id = $(this).data('id');
      let html = '';

      $.ajax({
          url: location.origin + '/api/user/' + id + '/active',
          method: 'post',
          data: {
            'id': id,
            '_token':"{{ csrf_token() }}",
          },
          success:function(res)
          {
            let user_id = '#user_'+id+  ' span';
            let  status_id  = '#status_'+id;
            if(!res.error)
            {
              if($(user_id).hasClass('btn-danger'))
              {
                $(user_id).removeClass('btn-danger');
                $(user_id).addClass('btn-primary');
                $(user_id).html('<i class="fa fa-unlock"></i>');
                $(status_id).html('Đã khoá');
              }
              else
              {
                $(user_id).removeClass('btn-primary');
                $(user_id).addClass('btn-danger');
                $(user_id).html('<i class="fa fa-lock"></i>');
                $(status_id).html('Đang hoạt động');
              }
            }
            // $('#user_'+id).html(html);
          }
        });
    })
  </script> 
@endsection