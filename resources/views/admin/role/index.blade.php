@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Phân quyền</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header" style="display:flex;">
                  {{-- Form delete --}}
                  <form id="delete-role" action="{{ route('admin.role.delete') }}" method="POST" style=" display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="list_id" id="list_id" value="">
                    <button disabled type="submit" class="btn btn-danger btn-delete">Xóa phân quyền</button>
                  </form>
                  
                  {{-- Form search desktop --}}
                  <div class="card-body card-form" style="padding:0;">
                    <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.role.index')}}">
                      <div class="form-group">
                        <label for="inlineFormInputGroup" class="sr-only">Tên</label>
                        <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text" placeholder="Tên" class="mr-3 form-control form-control">
                      </div>
                      <div class="form-group">
                        <input type="submit" value="Tìm kiếm" class="mr-3 btn btn-primary">
                      </div>
                    </form>

                  </div>
                 {{-- Form search desktop --}}
                  <button class="btn btn-primary btn-create">Tạo phân quyền</button>
                </div>

                {{-- Form search Mobile --}}
                <div class="card-body card-form-mob">
                <form class="form-inline " style="float: right;" method="GET" action="{{route('admin.role.index')}}">
                  <div class="form-group">
                    <label for="inlineFormInputGroup" class="sr-only">Tên</label>
                    <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text" placeholder="Tên" class="mr-3 form-control form-control">
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
                          <th>Tên phân quyền</th>
                          {{-- <th></th> --}}
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          <td><input type="checkbox" name="check" class="check cursor" data-id="{{ $row->id }}"></td>
                          <td>{{ $row->name }}</td>
                          {{-- <td>
                            <span class="edit cursor" data-id="{{ $row->id }}"  data-name="{{ $row->name }}"><i class="fa fa-edit"></i></span>
                          </td> --}}
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
    <div id="modal-role" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="exampleModalLabel" class="modal-title">Tạo phân quyền</h5>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <form id="form-role" action="{{route('admin.role.store')}}" method="POST">
              @csrf
              <input type="hidden" name="id_role" id="id_role" value="">
              <div class="form-group">
                <label class="label">Tên</label>
                <input type="text"  value="{{Request::get('search')}}"is placeholder="Tên phân quyền" name="name_role" id="name_role" class="form-control">
                <div class="error error-role">Nhập tên phân quyền</div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-disabled btn-secondary">Đóng</button>
            <button type="button" class="btn btn-disabled btn-primary" id="save">Lưu</button>
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
      $('#delete-role').submit();
    })
    
    //click create
    $('.btn-create').click(function(){
      let url = $(this).data('url');
      $('#form-role').attr('action',url);
      $('.modal-title').html('Tạo phân quyền');
      $('#modal-role').modal('show');
    });
    $('#save').click(function(){
      if($('#name_role').val() == '')
      {
        $('.error-role').css('display','block');
        return;
      }
      $('.btn-disabled').prop('disabled', true);
      $('#form-role').submit();
    })
    $('#modal-role').on('hidden.bs.modal', function (e) {
      $('.error-role').css('display','none');
    })

    $('input').keyup(function(e){
      let value = $(this).val();
      let regex =/^\s*\s*$/;  
      if(regex.test(value))
      {
        e.preventDefault();
      }
    })
  </script> 
@endsection