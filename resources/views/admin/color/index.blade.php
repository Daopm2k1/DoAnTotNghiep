@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Màu sắc</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header" style="display:flex;">

                  {{-- Form delete --}}
                  <form id="delete-color" action="{{ route('admin.color.delete') }}" method="POST" style=" display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="list_id" id="list_id" value="">
                    <button disabled type="submit" class="btn btn-danger btn-delete">Xóa màu</button>
                  </form>

                  {{-- Form search desktop --}}
                  <div class="card-body card-form" style="padding:0;">
                    <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.color.index')}}">
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
                  <button class="btn btn-primary btn-create">Tạo màu</button>
                </div>

                {{-- Form search Mobile --}}
                <div class="card-body card-form-mob" >
                  <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.color.index')}}">
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
                          <th>Tên</th>
                          <th>Màu</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          <td><input type="checkbox" name="check" class="check cursor" data-id="{{ $row->id }}"></td>
                          <td>{{ $row->name }}</td>
                          <td><div class="color-style" style="background-color
                          :{{$row->color_code}};"></div></td>
                          <td>
                            <span class="edit cursor" data-id="{{ $row->id }}"  data-name="{{ $row->name }}" data-color_code="{{$row->color_code}}"><i class="fa fa-edit"></i></span>
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
    <div id="modal-color" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="exampleModalLabel" class="modal-title">Tạo màu</h5>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <form id="form-color" action="{{route('admin.color.store')}}" method="POST">
              @csrf
              <input type="hidden" name="id_color" id="id_color" value="">
              <div class="form-group">
                <label class="label">Tên</label>
                <input type="text" placeholder="Tên màu" name="name_color" id="name_color" class="form-control">
                <div class="error error-color">Nhập tên màu</div>
              </div>
              <div class="row">
                <div class="col-4">
                  <div class="form-group">
                    <label class="label">Màu</label>
                    <input type="color" name="color_code" class="form-control">
                  </div>
                </div>
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
    //Click edit
    $('.edit').click(function(){
      let id = $(this).data('id');
      let name = $(this).data('name');
      let color_code = $(this).data('color_code');
      $('.modal-title').html('Cập nhật màu');
      $('#id_color').val(id);
      $('#name_color').val(name);
      $('input[name="color_code"').val(color_code);
      $('#modal-color').modal('show');
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
      $('#delete-color').submit();
    })
    //click create
    $('.btn-create').click(function(){
      let url = $(this).data('url');
      $('#form-color').attr('action',url);
      $('#name_color').val('');
      $('input[name="color_code"').val('');
      $('#id_color').val('');
      $('.modal-title').html('Tạo màu');
      $('#modal-color').modal('show');
    });
    $('#save').click(function(){
      if($('#name_color').val() == '')
      {
        $('.error-color').css('display','block');
        return;
      }
     
      $('.btn-disabled').prop('disabled', true);
      $('#form-color').submit();
    })
    $('#modal-color').on('hidden.bs.modal', function (e) {
      $('.error-color').css('display','none');
    })
  </script> 
@endsection