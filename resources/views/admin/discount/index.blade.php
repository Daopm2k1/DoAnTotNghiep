@extends('layouts.admin')
@section('head')
   <link rel="stylesheet" href="{{ asset('css/admin/discount.css') }}">
@endsection
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Giảm giá</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header d-flex">

                  {{-- Form delete --}}
                  <form id="delete-discount" action="{{ route('admin.discount.delete') }}" method="POST" style=" display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="list_id" id="list_id" value="">
                    <button disabled type="submit" class="btn btn-danger btn-delete">Xóa giảm giá</button>
                  </form>
                  {{-- Form search desktop --}}
                  <button class="btn btn-primary btn-create">Tạo giảm giá</button>
                </div>

                <div class="card-header card-header-form" >
                  <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.discount.index')}}">
                    <div class="form-group">
                      <label for="start_date_search" class="label">Bắt đầu:</label>
                      <input id="start_date_search" value="{{Request::get('start_date_search')}}" name="start_date_search" type="date" class="mr-3 form-control">
                    </div>

                    <div class="form-group">
                      <label for="end_date_search" class="label">Kết thúc:</label>
                      <input id="end_date_search" value="{{Request::get('end_date_search')}}" name="end_date_search" type="date" class="mr-3 form-control">
                    </div>

                    <div class="form-group">
                      <label for="inlineFormInputGroup" class="label">Email:</label>
                      <input id="inlineFormInputGroup" value="{{Request::get('search')}}" name="search" type="text" placeholder="Email" class="mr-3 form-control">
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
                          <th>Email</th>
                          <th>Mã giảm giá</th>
                          <th style="text-align:center">Loại</th>
                          <th style="text-align:center">Giá giảm</th>
                          <th>Ngày bắt đầu</th>
                          <th>Ngày kết thúc</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          <td><input type="checkbox" name="check" class="check cursor" data-id="{{ $row->id }}"></td>
                          <td>{{$row->userApply->email}}</td>
                          <td>{{ $row->discount_code }}</td>
                          <td class="t-center">{{ ($row->type == "product") ? "Sản phẩm" : "Danh mục"}}</td>
                          <td class="t-center">{{$row->sale_percent}}%</td>
                          <td>{{ format_date($row->start_date) }}</td>
                          <td>{{ format_date($row->end_date) }}</td>
                          <td class="td-edit">
                            <span title="Info" class="edit cursor" data-id="{{ $row->id }}"><i class="fa fa-info"></i></span>
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
</section>
@endsection
@section('script')
  <script >
    //click delete
    $('.btn-create').click(function(e){
      window.location.href= location.origin + '/admin/discount/create';
    });

    //click edit
    $('.edit').click(function(e){
      let id = $(this).data('id');
      window.location.href= location.origin + '/admin/discount/detail/' + id;
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
      $('#delete-discount').submit();
    })
  </script> 
@endsection
