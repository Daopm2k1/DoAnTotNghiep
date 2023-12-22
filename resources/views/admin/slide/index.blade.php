@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Trình chiếu</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header" style="display:flex;">
                  {{-- Form delete --}}
                  <form id="delete-slide" action="{{ route('admin.slide.delete') }}" method="POST" style=" display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="list_id" id="list_id" value="">
                    <button disabled type="submit" class="btn btn-danger btn-delete">Xóa trình chiếu</button>
                  </form>

                  <button class="btn btn-primary btn-create">Tạo trình chiếu</button>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>
                            <input type="checkbox" name="check_all" class="check_all cursor">
                          </th>
                          {{-- <th>Tài khoản</th> --}}
                          <th>Hình ảnh</th>
                          <th>Tiêu đề</th>
                          <th>Nội dung</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($rows as $row)
                        <tr>
                          <td><input type="checkbox" name="check" class="check cursor" data-id="{{ $row->id }}"></td>
                          {{-- <th>{{$row->user->username}}</th> --}}
                          <td><img style="width: 100px; height: 100px;" src="{{ asset($row->image)}}" alt=""></td>
                          <td>{{$row->title}}</td>
                          <td>{{$row->slide_content}}</td>
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
    <div id="modal-slide" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="exampleModalLabel" class="modal-title">Tạo trình chiếu</h5>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <form id="form-slide" action="{{route('admin.slide.store')}}" method="POST" enctype="multipart/form-data">
              @csrf
              
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label  class="label">Tiêu đề</label>
                    <input type="text" placeholder="Tiêu đề" name="title" id="title" class="form-control">
                    <div class="error error-title">Nhập tiêu đề</div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="label">Nội dung</label>
                    <textarea type="text" placeholder="Nội dung" name="slide_content" id="slide_content" class="form-control"></textarea>
                    <div class="error error-content">Nhập nội dung</div>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="label">Đường dẫn</label>
                    <input id="path" type="text" name="path" class="form-control" placeholder="Đường dẫn">
                    <div class="error error-path" 	@if($errors->has('path')) style="display:block" @endif>Đường dẫn không hợp lệ</div>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-12">
                    <label class="label-check label">Hình ảnh</label>
                    <div class="custom-file">
                        <input type="file" name="image" accept=".jpg, .jpeg, .png" class="custom-file-input" id="file">
                        <label class="custom-file-label" id="label-file" for="customFile">Chọn hình</label>
                        <div class="error error-image" 	@if($errors->has('image')) style="display:block" @endif>{{$errors->has('image') ? $errors->first('image') : "Chọn hình ảnh"}}</div>
                    </div>                                
                </div>
            </div>
            <div class="form-group">
                <img width="200px" height="200px" id="ImgPre" src="{{ asset('images/no-image.png')}}" alt="Alternate Text" class="img-thumbnail" />
            </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-slide btn-secondary">Đóng</button>
            <button type="submit" class="btn btn-slide btn-primary" id="save">Lưu</button>
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
      $('#delete-slide').submit();
    })
    //click create
    $('.btn-create').click(function(){
      let url = $(this).data('url');
      $('#form-slide').attr('action',url);
      $('.modal-title').html('Tạo trình chiếu');
      $('#modal-slide').modal('show');
    });


    function validURL(str) {
      var pattern = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
      return !!pattern.test(str);
    }

    //Form submit
    $('#save').click(function(e){
      let flag = false;
      //Check title
      if($('#title').val() == '')
      {
        $('.error-title').css('display','block');
        flag = true;
      }
      else
      {
        $('.error-title').css('display','none');
      }

      //check content
      if($('#slide_content').val() == '')
      {
        $('.error-content').css('display','block');
        flag = true;
      }
      else
      {
        $('.error-content').css('display','none');
      }

      //check path
      if($('#path').val() == '')
      {
        $('.error-path').html('Nhập đường dẫn');
        $('.error-path').css('display','block');
        flag = true;
      }
      else
      {
        // console.log($('#path').val(), validURL($('#path').val()));
       if(!validURL($('#path').val())) {
        $('.error-path').html('Đường dẫn không hợp lệ');
        $('.error-path').css('display','block');
        flag = true;
       }
       else {
        $('.error-path').html('');
        $('.error-path').css('display','none');
       }
      }

      //Check file
      if($('#file').val() == '')
      {
        $('.error-image').css('display','block');
        flag = true;
      }
      else
      {
        $('.error-image').css('display','none');
      }

      // console.log(1);
      if(!flag)
      {
        $('.btn-slide').attr('disabled','disabled');
        $('#form-slide').submit();
      }
    })
    $('#modal-slide').on('hidden.bs.modal', function (e) {
      $('.error-slide').css('display','none');
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
  </script>
  <script type="text/javascript">
    // function getPathProduct(){
    //   let product_name = document.getElementById("title").value;
    //   if(product_name != '') {
    //     $.ajax({
    //       url:"{{url('/admin/get-path-product')}}",
    //       method:"GET",
    //       dataType:"JSON",
    //       data:{product_name:product_name},
    //       success: function(data){
            
    //         if(data.result == 1){
    //           document.getElementById('path').value = data.path;
    //         }
    //         else{
    //             setPathProduct(data.path);
    //         }
            
    //       }
    //     });
    //   }
    //   else {
    //     document.getElementById('path').value = '';
    //   }
    // }
    
    // function setPathProduct(data){
    //   if(data != null) {
    //     data = data.toLowerCase();
    //     //Đổi ký tự có dấu thành không dấu
    //     data = data.replace(/á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    //     data = data.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    //     data = data.replace(/í|ì|ỉ|ĩ|ị/gi, 'i');
    //     data = data.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    //     data = data.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    //     data = data.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    //     data = data.replace(/đ/gi, 'd');
    //     //Xóa các ký tự đặc biệt
    //     data = data.replace(/\`|\~|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|\‘|\’|\“|\”|\…|\–|_/gi, '');
    //     //Đổi khoảng trắng thành ký tự gạch ngang
    //     data = data.replace(/ /gi, "-");
    //     //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
    //     //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
    //     data = data.replace(/\-\-\-\-\-/gi, '-');
    //     data = data.replace(/\-\-\-\-/gi, '-');
    //     data = data.replace(/\-\-\-/gi, '-');
    //     data = data.replace(/\-\-/gi, '-');
    //     //Xóa các ký tự gạch ngang ở đầu và cuối
    //     data = '@' + data + '@';
    //     data = data.replace(/\@-|\-@|\@/gi, '');
        
    //     document.getElementById('path').value = data;
    //   }
      
    // }

    // function removePath() {
    //   console.log(1);
    // }
  
    </script>
@endsection