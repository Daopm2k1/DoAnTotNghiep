@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Sản phẩm</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header" style="display:flex;">
                  {{-- Form delete --}}
                  <form id="delete-product" action="{{ route('admin.product.delete') }}" method="POST" style=" display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="list_id" id="list_id" value="">
                    <button disabled type="submit" class="btn btn-danger btn-delete">Xóa sản phẩm</button>
                  </form>

                  {{-- Form search desktop --}}
                  <div class="card-body card-form"  style="padding:0;">
                    <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.product.index')}}">
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
                  <button class="btn btn-primary btn-create" data-url="{{ route('admin.product.create') }}">Tạo sản phẩm</button>
                </div>

                {{-- Form search Mobile --}}
                <div class="card-body card-form-mob" style="padding:0;">
                  <form class="form-inline" style="float: right;" method="GET" action="{{route('admin.product.index')}}">
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
                    <table class="table table-striped table-hover" id="myTable2">
                      <thead>
                        <tr>
                          <th>
                            <input type="checkbox" name="check_all" class="check_all cursor">
                          </th>
                          <th class="cursor" onclick="sortTable(0)">Tên</th>
                          <th class="cursor" onclick="sortTable(1)">Danh mục</th>
                          <th class="cursor" onclick="sortTable(2)">Giá</th>
                          <th class="t-center">Mô tả</th>
                          <th></th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          <td><input type="checkbox" name="check" class="check cursor" data-id="{{ $row->id }}"></td>
                          <td>{{ $row->name }}</td>
                          <td>{{ $row->category->name }}</td>
                          <td>{{ format_price($row->price)}}&#8363;</td>
                          <td>@php echo $row->description; @endphp</td>
                          <td>
                            <span title="Information" class="info cursor" data-id="{{ $row->id }}"><i class="fa fa-info"></i></span>
                          </td>
                          <td class="td-edit">
                            <span title="Edit" class="edit cursor" data-id="{{ $row->id }}"><i class="fa fa-edit"></i></span>
                          </td>
                          <td class="td-comment">
                            {{-- <span title="Comment" class="comment cursor" data-id="{{ $row->id }}"><i class="fa fa-comments"></i></span> --}}
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
  
  </section>
  @include('admin.part.modal-confirm')
@endsection
@section('script')
  <script >
    //Click edit
    $('.edit').click(function(){
      let id = $(this).data('id');
      window.location.href= location.origin + '/admin/product/edit/' + id;
    });

    //Click info
    $('.info').click(function(){
      let id = $(this).data('id');
      window.location.href= location.origin + '/admin/product/' + id + "/detail";
    });

    //click comment
    $('.comment').click(function(){
      let id = $(this).data('id');
      window.location.href= location.origin + '/admin/product/' + id + "/comment";
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
      //  console.log(1);
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
      $('#delete-product').submit();
    })

    //click create
    $('.btn-create').click(function(){
      let url = $(this).data('url');
      window.location.href = url;
    });

    function sortTable(n) {
      var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
      table = document.getElementById("myTable2");
      switching = true;
      dir = "asc";
      while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
          shouldSwitch = false;
          x = rows[i].getElementsByTagName("TD")[n];
          y = rows[i + 1].getElementsByTagName("TD")[n];
          if (dir == "asc") {
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
            }
          } else if (dir == "desc") {
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
            }
          }
        }
        if (shouldSwitch) {
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
          switchcount ++;
        } else {
          if (switchcount == 0 && dir == "asc") {
            dir = "desc";
            switching = true;
          }
        }
      }
    }
  </script> 
@endsection