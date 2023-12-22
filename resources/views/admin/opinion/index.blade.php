@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Ý kiến</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header" style="display:flex;">
                  {{-- Form delete --}}
                  <form id="delete-opinion" action="{{ route('admin.opinion.delete') }}" method="POST" style=" display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="list_id" id="list_id" value="">
                    <button disabled type="submit" class="btn btn-danger btn-delete">Xóa ý kiến</button>
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
                          <th>Email</th>
                          <th>Tin nhắn</th>
                          <th>Ngày gửi</th>
                          {{-- <th></th> --}}
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          <td><input type="checkbox" name="check" class="check cursor" data-id="{{ $row->id }}"></td>
                          <td>{{ $row->name }}</td>
                          <td>{{ $row->email }}</td>
                          <td>{{ $row->message }}</td>
                          <td>{{ format_datetime($row->created_at) }}</td>
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
      $('#delete-opinion').submit();
    })

    $('#modal-opinion').on('hidden.bs.modal', function (e) {
      $('.error-opinion').css('display','none');
    })
  </script> 
@endsection
