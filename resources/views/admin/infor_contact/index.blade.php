@extends('layouts.admin')
@section('content')
<section class="charts">
    <section>
        <div class="container-fluid">
            <!-- Page Header-->
            <header> 
                <h1 class="h3 display">Thông tin liên hệ</h1>
            </header>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div  class="card-header" style="display:flex;">
                            {{-- Form delete --}}
                            <form id="delete-inforcontact" action="{{ route('admin.infor_contact.delete') }}" method="POST" style=" display: inline;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="list_id" id="list_id" value="">
                                <button disabled type="submit" class="btn btn-danger btn-delete">Xóa thông tin liên hệ</button>
                            </form>

                            

                            {{-- Button create --}}
                            <button class="btn btn-primary btn-create">Tạo thông tin</button>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                        <th>
                                            <input type="checkbox" name="check_all" class="check_all cursor">
                                        </th>
                                        <th>Địa chỉ</th>
                                        <th>Thời gian hoạt động</th>
                                        <th>Điện thoại</th>
                                        <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rows as $row)
                                        <tr>
                                        <td><input type="checkbox" name="check" class="check cursor" data-id="{{ $row->id }}"></td>
                                        <td>{{ $row->address }}</td>
                                        <td>{{ $row->time }}</td>
                                        <td>{{ $row->phone }}</td>
                                        <td>{{ $row->email }}</td>
                                        <!-- <td>
                                            <span class="edit cursor" data-id="{{ $row->id }}"  data-address="{{ $row->address }}" data-time="{{ $row->time }}" data-phone="{{ $row->phone }}" data-email="{{ $row->email }}" data-map="{{ $row->map }}"><i class="fa fa-edit"></i></span>
                                        </td> -->
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
        <div id="modal-inforcontact" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">Tạo danh mục</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-inforcontact" action="{{route('admin.infor_contact.store')}}" method="POST">
                            @csrf
                            <input type="hidden" name="id_infor_contact" id="id_infor_contact" value="">
                            <div class="form-group">
                                <label class="label">Địa chỉ</label>
                                <input type="text" placeholder="Địa chỉ" name="address" id="address" class="form-control">
                                <div class="error error-inforcontact">Nhập địa chỉ</div>
                                <label class="label">Thời gian hoạt động</label>
                                <input type="text" placeholder="Thời gian" name="time" id="time" class="form-control">
                                <div class="error error-inforcontact">Nhập thời gian</div>
                                <label class="label">Điện thoại</label>
                                <input type="text" placeholder="Điện thoại" name="phone" id="phone" class="form-control">
                                <div class="error error-inforcontact">Nhập số điện thoại</div>
                                <label class="label">Email</label>
                                <input type="text" placeholder="Email" name="email" id="email" class="form-control">
                                <div class="error error-inforcontact">Nhập Email</div>
                                <label class="label">Bản đồ</label>
                                <input type="text" placeholder="Bản đồ" name="map" id="map" class="form-control">
                                <div class="error error-inforcontact">Bản đồ</div>
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
      let address = $(this).data('address');
      let time = $(this).data('time');
      let phone = $(this).data('phone');
      let email = $(this).data('email');
      let map = $(this).data('map');
      $('.modal-title').html('Cập nhật thông tin');
      $('#id_inforcontact').val(id);
      $('#address').val(address);
      $('#time').val(time);
      $('#phone').val(phone);
      $('#email').val(email);
      $('#map').val(map);
      $('#modal-inforcontact').modal('show');
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
      $('#delete-inforcontact').submit();
    })
    //click create
    $('.btn-create').click(function(){
      let url = $(this).data('url');
      $('#form-inforcontact').attr('action',url);
      $('.modal-title').html('Tạo thông tin liên hệ');
      $('#modal-inforcontact').modal('show');
    });
    $('#save').click(function(){
      if($('#address').val() == '')
      {
        $('.error-inforcontact').css('display','block');
        return;
      }
      $('.btn-disabled').prop('disabled', true);
      $('#form-inforcontact').submit();
    })
    $('#modal-inforcontact').on('hidden.bs.modal', function (e) {
      $('.error-inforcontact').css('display','none');
    })
  </script> 
@endsection