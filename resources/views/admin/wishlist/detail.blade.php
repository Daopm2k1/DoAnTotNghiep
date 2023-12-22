@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Chi tiết yêu thích</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div  class="card-header" style="display:flex;">

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>Danh mục</th>
                          <th>Tên sản phẩm</th>
                          <th>Mô tả</th>
                          <th>Giá</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          <td>{{ $row->product->category->name }}</td>
                          <td>{{ $row->product->name }}</td>
                          <td>{!! $row->product->description !!}</td>
                          <td>{{ format_price($row->product->price) }}&#8363;</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <div class="mt-3" style="display: flex;justify-content: center;"><center>{{ $rows->links() }}</center></div>
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
    //click info
    $('.info').click(function(e){
      let id = $(this).data('id');
      window.location.href = location.origin + '/wishlist/' + id + '/detail';
    })
    
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
      $('#delete-wishlist').submit();
    })
  </script> 
@endsection