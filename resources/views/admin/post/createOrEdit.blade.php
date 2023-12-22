@extends('layouts.admin')
@section('head')
  <style>
    .error-input-tag
    {
      display: block;
    }
    .label-image {
      white-space: nowrap;
      overflow: hidden;
    }  
  </style>
@endsection
@section('content')
  <section class="charts">
        {{-- @php if(count($errors)) var_dump($errors->first('username')) @endphp --}}
        <div class="container-fluid">
          <header> 
            <h1 class="h3 display">{{($flag = !empty($rows) && $rows->id) ? 'Cập nhật bài viết' : 'Tạo bài viết'}}</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <form id="form-post" class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{route('admin.post.store')}}">
                            @csrf
                            @if($flag)
                                <input type="hidden" value="{{$rows->id}}" name="id">
                            @endif
                            
                            <div class="form-group row">
                              <div class="col-sm-3">
                                <label class="label">Tên</label>
                                <input id="name" type="text"  @if($flag) value="{{$rows->name}}" @endif name="name" placeholder="Tên" class="form-control" onkeyup="ChangeToSlug();">
                                <div class="error error-name" 	@if($errors->has('name')) style="display:block" @endif>{{$errors->first('name')}}</div>
                              </div>

                              <div class="col-sm-3">
                                <label class="label">Đường dẫn</label>
                                <div class="custom-file">
                                    <input id="path" type="text" name="path" @if($flag) value="{{$rows->path}}" @endif placeholder="Đường dẫn" class="form-control" readonly onkeyup="ChangeToSlug();">
                                    <div class="error error-path" 	@if($errors->has('path')) style="display:block" @endif>{{$errors->first('path')}}</div>                            
                                </div>
                              </div>

                              <div class="col-sm-3">
                                <label class="label">Tác giả</label>
                                <div class="custom-file">
                                    <input id="author" type="text" name="author" @if($flag) value="{{$rows->author}}" @endif placeholder="Tác giả" class="form-control">
                                    <div class="error error-author" 	@if($errors->has('author')) style="display:block" @endif>{{$errors->first('author')}}</div>                            
                                </div>
                              </div>

                              <div class="col-sm-3">
                                <label class="label">Danh mục</label>
                                <select name="post_cate_id" id="post_cate" class="form-control">
                                  @foreach($post_cates as $key=>$post)
                                    <option @if($flag && $rows->post_name == $post->post_name) selected @endif value="{{$post->id}}">{{$post->post_name}}</option>
                                  @endforeach
                                </select>
                                <div class="error error-post" 	@if($errors->has('post')) style="display:block" @endif>{{$errors->first('post')}}</div>
                              </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                  <label class="label">Mô tả</label>
                                  <textarea id="description" rows="5" name="description" placeholder="Nhập mô tả" class="form-control">@if($flag) {{$rows->description}} @endif</textarea>
                                  <div class="error error-description"  @if($errors->has('description')) style="display:block" @endif>{{$errors->first('description')}}</div>
                                </div>
                                <div id="editor"></div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                  <label class="label">Nội dung</label>
                                  <textarea id="content" rows="5" name="content" placeholder="Nội dung" class="form-control">@if($flag) {{$rows->post_content}} @endif</textarea>
                                  <div class="error error-content" 	@if($errors->has('content')) style="display:block" @endif>{{$errors->first('content')}}</div>
                                </div>
                                <div id="editor"></div>
                            </div>
                            
                            <div class="form-group row">
                              <div class="col-sm-4">
                                  <label class="label-check label">Hình ảnh</label>
                                  <div class="custom-file">
                                      <input type="file" name="image" @if($flag) value="{{$primary}}" @endif accept=".jpg, .jpeg, .png" class="custom-file-input img-pro" id="file" data-toggle="tooltip" title="{{!empty($primary->name) ? $primary->name : 'Chọn ảnh'}} " data-placement="bottom">
                                      <label class="custom-file-label label-image" id="label-file" for="customFile">{{!empty($primary->name) ? $primary->name : 'Chọn ảnh'}}  </label>
                                      @if(isset($primary)) <input type="hidden" value="{{$primary->id}}" name="id_img"/> @endif
                                    </div>   
                                  <img width="200px" height="200px" id="ImgPre" @if($flag && !empty($primary)) src="{{ asset($primary->path)}}" @else src="{{ asset('images/post/no-image-post.png')}}" @endif alt="{{!empty($primary->name) ?? ''}}" class="img-thumbnail" />                             
                                  <div class="error error-image" 	@if($errors->has('image')) style="display:block" @endif>{{$errors->first('image')}}</div>
                                </div>
                            </div>
                            
                          
                            <div class="form-group row">       
                              <div class="col-sm-12">
                                <button id="create" type="submit" data-edit="{{isset($rows->id) ? $rows->id : -1}}" class="btn btn-disabled btn-primary">Lưu</button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
          </div>
        </div>

        {{-- Modal bootstrap add tag  --}}
        <div class="modal fade" id="add-tag">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
        
              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">Thêm từ khóa</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
        
              <!-- Modal body -->
              <div class="modal-body">
                <div class="form-group row">
                  <div class="col-sm-12">
                    <input id="input-tag" type="text" name="input-tag" placeholder="Nhập từ khóa" class="form-control">
                    <div class="error error-input-tag"></div>
                    <div class="tagator_tags">
                        @if(!empty($tags)) 
                          @foreach($tags as $item)
                            <div class="tagator_tag">#{{$item->tag->name}}<div class="tagator_tag_remove" onclick="removeTag({{$item->id}})">X</div></div>
                          @endforeach
                        @endif  
                    </div>
                  </div>
                </div>
              </div>        
            </div>
          </div>
        </div>
  </section>
@endsection
@section('script')
  <script src="{{ asset('js/admin_product.js')}}"></script> 
  <script>
    let id = {{ $rows->id ?? -1}};
    CKEDITOR.replace( 'content' );

    $("#add-tag").on('hidden.bs.modal', function () {
      $('.error-input-tag').html('');
      $('#input-tag').val('');
    });

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
        let name = $(this).val().split('\\').pop();
        $('#label-file').html(name);
        $('.display-image').css('display','flex');
    });

		function ChangeToSlug(){
			var name;
			//Lấy text từ thẻ input title
			name = document.getElementById("name").value;
			name = name.toLowerCase();
			//Đổi ký tự có dấu thành không dấu
			name = name.replace(/á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
			name = name.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
			name = name.replace(/í|ì|ỉ|ĩ|ị/gi, 'i');
			name = name.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
			name = name.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
			name = name.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
			name = name.replace(/đ/gi, 'd');
			//Xóa các ký tự đặc biệt
			name = name.replace(/\`|\~|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|\‘|\’|\“|\”|\…|\–|_/gi, '');
			//Đổi khoảng trắng thành ký tự gạch ngang
			name = name.replace(/ /gi, "-");
			//Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
			//Phòng trường hợp người nhập vào quá nhiều ký tự trắng
			name = name.replace(/\-\-\-\-\-/gi, '-');
			name = name.replace(/\-\-\-\-/gi, '-');
			name = name.replace(/\-\-\-/gi, '-');
			name = name.replace(/\-\-/gi, '-');
			//Xóa các ký tự gạch ngang ở đầu và cuối
			name = '@' + name + '@';
			name = name.replace(/\@-|\-@|\@/gi, '');
			document.getElementById('path').value = name;
		}

    $('#form-post').submit(function(e){
        let name = $('#form-post').find('#name').val();
        let author = $('#form-post').find('#author').val();
        let description = $('#form-post').find('#description').val();
        let content = $('#form-post').find('#content').val();
        let flag = false;

        if(name == '') {
          $('.error-name').html('Nhập tên bài viết');
          $('.error-name').css('display','block');

          $('.error-path').html('Nhập tên bài viết để có đường dẫn');
          $('.error-path').css('display','block');
          flag = true;
        } else {
          $('.error-name').html('');
          $('.error-name').css('display','none');

          $('.error-path').html('');
          $('.error-path').css('display','none');
        }

        if(author == '') {
          $('.error-author').html('Nhập tên tác giả');
          $('.error-author').css('display','block');
          flag = true;
        } else {
          $('.error-author').html('');
          $('.error-author').css('display','none');
        }

        if(description == '') {
          $('.error-description').html('Nhập mô tả');
          $('.error-description').css('display','block');
          flag = true;
        } else {
          $('.error-description').html('');
          $('.error-description').css('display','none');
        }

        if(content == '') {
          $('.error-content').html('Nhập nội dung');
          $('.error-content').css('display','block');
          flag = true;
        } else {
          $('.error-content').html('');
          $('.error-content').css('display','none');
        }

        if(flag){
          e.preventDefault();
        }
    });

    </script>
@endsection