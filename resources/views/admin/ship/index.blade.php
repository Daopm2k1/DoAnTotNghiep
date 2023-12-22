@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Phí vận chuyển</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header" style="display:flex;">
                  {{-- Form delete --}}
                  <form id="delete-ship" action="{{ route('admin.ship.delete') }}" method="POST" style=" display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="list_id" id="list_id" value="">
                    <button disabled type="submit" class="btn btn-danger btn-delete">Xóa phí</button>
                  </form>
                  
                  {{-- Form search desktop --}}
                  <div class="card-body card-form" style="padding:0;">
                    <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.ship.index')}}">
                      <div class="form-group">
                        <label for="inlineFormInputGroup" class="sr-only">Tên</label>
                        <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text" placeholder="Tỉnh/Thành phố" class="mr-3 form-control form-control">
                      </div>
                      <div class="form-group">
                        <input type="submit" value="Tìm kiếm" class="mr-3 btn btn-primary">
                      </div>
                    </form>

                  </div>
                 {{-- Form search desktop --}}
                  <button class="btn btn-primary btn-create">Tạo phí</button>
                </div>

                {{-- Form search Mobile --}}
                <div class="card-body card-form-mob">
                <form class="form-inline " style="float: right;" method="GET" action="{{route('admin.ship.index')}}">
                  <div class="form-group">
                    <label for="inlineFormInputGroup" class="sr-only">Tên</label>
                    <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text"  placeholder="Tỉnh/Thành phố" class="mr-3 form-control form-control">
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
                          <th>Thành phố/Tỉnh</th>
                          <th>Phí vận chuyển</th>
                          {{-- <th></th> --}}
                          </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          <td><input type="checkbox" name="check" class="check cursor" data-id="{{ $row->id }}"></td>
                          <td>{{ $row->city['name']}}</td>
                          <td>{{ format_price($row->fee) }}&#8363</td>
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
    <div id="modal-ship" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="exampleModalLabel" class="modal-title">Tạo phân quyền</h5>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <form id="form-ship" action="{{route('admin.ship.store')}}" method="POST">
              @csrf
              <input type="hidden" name="id_ship" id="id_ship" value="">
              <div class="form-group">
                <label class="label"> Danh mục </label>
                <select name="city" id="city" class="form-control">
                  <option value="">---Chọn thành phố---</option>
                  @foreach($cities as $city)
                    <option value="{{$city->id}}">{{$city->name}}</option>
                  @endforeach
                </select>
                <div class="error error-city" 	@if($errors->has('city')) style="display:block" @endif>{{$errors->first('city')}}</div>
              </div>

              <div class="form-group">
                <label class="label">Phí vận chuyển</label>
                <input type="text"  value="" placeholder="Nhập phí vận chuyển" name="fee" id="fee" class="form-control">
                <div class="error error-fee">Nhập phí vận chuyển</div>
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

    function formatNumber(n)
    {
        return String(parseInt(n)).replace(/(.)(?=(\d{3})+$)/g,'$1');
    }
    
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
      $('#delete-ship').submit();
    })
    
    //click create
    $('.btn-create').click(function(){
      let url = $(this).data('url');
      $('#form-ship').attr('action',url);
      $('.modal-title').html('Tạo phí vận chuyển');
      $('#modal-ship').modal('show');
    });
    $('#save').click(function(){
      let flag =false;

      if($('#city').val() == '')
      {
        $('.error-city').html('Chọn thành phố');
        $('.error-city').css('display','block');
        flag = true;
      }
      else
      {
        $('.error-city').html('');
        $('.error-city').css('display','none');
      }

      if($('#fee').val() == '')
      {
        $('.error-fee').html('Nhập giá tối thiểu');
        $('.error-fee').css('display','block');
        flag = true;
      }
      else if($('#fee').val() > 10000000000|| $('#fee').val() < 1000)
      {
        $('.error-fee').html('Nhập giá từ 1000 đến 10 tỷ');
        $('.error-fee').css('display','block');
        flag = true;
      }
      else
      {
        $('.error-fee').html('');
        $('.error-fee').css('display','none');
      }

      if(!flag)
      {
        $('.btn-disabled').prop('disabled', true);
        $('#form-ship').submit();
      }
    })
    $('#modal-ship').on('hidden.bs.modal', function (e) {
      $('.error-ship').css('display','none');
    })

    $('input').keyup(function(e){
      let value = $(this).val();
      let regex =/^\s*\s*$/;  
      if(regex.test(value))
      {
        e.preventDefault();
      }
    })

    $('#fee').keypress(function(e){
    let char = String.fromCharCode(e.which);
    if(!(/[0-9]/.test(char)))
    {
        e.preventDefault();
    }
    if($('#price').val().length >= 11)
    {
        e.preventDefault();
    }

});
  </script> 
@endsection