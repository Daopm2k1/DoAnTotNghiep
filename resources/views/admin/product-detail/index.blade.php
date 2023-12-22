@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Chi tiết sản phẩm</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header" style="display:flex;">
                  <form id="delete-product-detail" action="{{ route('admin.product_detail.delete',['id'=>$product->id]) }}" method="POST" style=" display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="list_id" id="list_id" value="">
                    <button disabled type="submit" class="btn btn-danger btn-delete">Xóa chi tiết</button>
                  </form>
                  <div class="card-body" style="padding:0;">
                    <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.product_detail.index',['id'=>$product->id])}}">
                      <div class="form-group pr-2">
                        <select name="search_size" id="search_size" class="form-control">
                          <option value="">--Chọn kích thước--</option>
                          @foreach($sizes as $size)
                            <option @if(Request::get('search_size') == $size->id) selected @endif value="{{$size->id}}">{{$size->name}}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group pr-2" >
                        <select name="search_color" id="search_color" class="form-control">
                          <option value="">--Chọn màu--</option>
                          @foreach($colors as $color)
                            <option @if(Request::get('search_color') == $color->id) selected @endif value="{{$color->id}}">{{$color->name}}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <input type="submit" value="Tìm kiếm" class="mr-3 btn btn-primary">
                      </div>
                    </form>
                  </div>
                  <button class="btn btn-primary btn-create">Tạo chi tiết</button>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>
                            <input type="checkbox" name="check_all" class="check_all cursor">
                          </th>
                          {{-- <th>Hình ảnh</th> --}}
                          <th>Tên sản phẩm</th>
                          <th>Kích thước</th>
                          <th>Màu</th>
                          <th>Số lượng</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          <td><input type="checkbox" name="check" class="check cursorc" data-id="{{ $row->id }}"></td>
                          {{-- <td><img class="zoom img-thumbnail" src="{{ asset($row->image)}}" alt=""></td> --}}
                          <th>{{ $row->name}}</th>
                          <td>{{ $row->size->name }}</td>
                          <td>{{ $row->color->name }}</td>
                          <td>{{ $row->quantity }}</td>
                          <td>
                            <span class="edit cursor" data-size="{{$row->size->id}}" data-color="{{$row->color->id}}" data-quantity="{{$row->quantity}}" data-id="{{ $row->id }}"><i class="fa fa-edit"></i></span>
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

        <div id="modal-product-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
          <div role="document" class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">Tạo chi tiết</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body">
                <form id="form-product-detail" action="{{route('admin.product_detail.store',['id'=>$product->id])}}" method="POST"  enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="id_product_detail" id="id_product_detail" value="">
                  <div class="row">
                    <div class="col-4">
                      <div class="form-group">
                        <label>Kích thước</label>
                        <select name="size" id="size" class="form-control">
                          @foreach($sizes as $size)
                            <option value="{{$size->id}}">{{$size->name}}</option>
                          @endforeach
                        </select>
                        {{-- <div class="error error-role" 	@if($errors->has('role')) style="display:block" @endif>{{$errors->first('role')}}</div> --}}
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label>Màu</label>
                        <select name="color" id="color" class="form-control">
                          @foreach($colors as $color)
                            <option value="{{$color->id}}">{{$color->name}}</option>
                          @endforeach
                        </select>
                        {{-- <div class="error error-role" 	@if($errors->has('role')) style="display:block" @endif>{{$errors->first('role')}}</div> --}}
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label>Số lượng</label>
                        <input type="text" placeholder="Số lượng" name="quantity" id="quantity" class="form-control">
                        <div class="error error-quantity">Nhập số lượng</div>
                      </div>
                    </div>
                  </div>

                    <div class="form-group row">
                      {{-- <div class="col-sm-6">
                          <label class="label-check">Ảnh sản phẩm</label>
                          <div class="custom-file">
                              <input type="file" name="image" @if($flag) value="{{public_path().$rows->avatar}}" @endif accept=".jpg, .jpeg, .png" class="custom-file-input" id="file">
                              <label class="custom-file-label" id="label-file" for="customFile">Chọn ảnh</label>
                              <div class="error error-avatar" 	@if($errors->has('avatar')) style="display:block" @endif>{{$errors->first('avatar')}}</div>
                          </div>                                
                      </div> --}}
                  </div>
                  {{-- <div class="form-group">
                      <img width="200px" height="200px" id="ImgPre" @if($flag && !empty($rows->avatar)) src="{{ asset($rows->avatar)}}" @elsesrc="{{ asset('images/none-user.png')}}" alt="Alternate Text" class="img-thumbnail" />
                  </div> --}}
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
@endsection
@section('script')
  <script >
    //Click edit
    $('.edit').click(function(){
      let id = $(this).data('id');
      let quantity = $(this).data('quantity');
      let color = $(this).data('color');
      let size = $(this).data('size');
      $('#size').val(size).change();
      $('#color').val(color).change();
      $('.modal-title').html('Cập nhật chi tiết');
      $('#id_product_detail').val(id);
      $('#quantity').val(quantity);
      $('#modal-product-detail').modal('show');
    });

    //Check select list
    $('.check_all').click(function(){
      if($('.check_all')[0].checked == true)
        $('input[name=check]').prop('checked', true);
      else
        $('input[name=check]').prop('checked', false);
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
      $('#delete-product-detail').submit();
    })

      //click create
      $('.btn-create').click(function(){
        let url = $(this).data('url');
        $('#id_product_detail').val('');
        $('#quantity').val('');
        $("#size option:first").attr('selected','selected');
        $("#color option:first").attr('selected','selected');
        $('#form-product-detail').attr('action',url);
        $('.modal-title').html('Tạo chi tiết');
        $('#modal-product-detail').modal('show');
      });

      $('#save').click(function(){
      if($('#quantity').val() == '')
      {
        $('.error-quantity').css('display','block');
        return;
      }
      
      $('.btn-disabled').prop('disabled', true);
      $('#form-product-detail').submit();
    })

    $('#modal-product-detail').on('hidden.bs.modal', function (e) {
      $('.error-product-detail').css('display','none');
    })

    function readURL(input, idImg) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(idImg).attr("src", e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
      }

      $("#file").change(function () {
          readURL(this, "#ImgPre");
      });

      $('#quantity').keypress(function(e){
        let char = String.fromCharCode(e.which);
        if(!(/[0-9]/.test(char)) || $(this).val().length > 10)
        {
          e.preventDefault();
        }
      });
  </script> 
@endsection
