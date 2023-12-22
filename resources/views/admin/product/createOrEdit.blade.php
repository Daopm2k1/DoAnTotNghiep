@extends('layouts.admin')
@section('head')
  <link rel="stylesheet" href="{{ asset('tagator/fm.tagator.jquery.min.css')}}">
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
            <h1 class="h3 display">{{($flag = !empty($rows) && $rows->id) ? 'Cập nhật sản phẩm' : 'Tạo sản phẩm'}}</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <form id="form-product" class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{route('admin.product.store')}}">
                            @csrf
                            @if($flag)
                                <input type="hidden" value="{{$rows->id}}" name="id">
                            @endif
                            
                            <div class="form-group row">
                              <div class="col-sm-4">
                                <label class="label">Tên</label>
                                <input id="name" type="text"  @if($flag) value="{{$rows->name}}" @endif name="name" placeholder="Tên" class="form-control">
                                <div class="error error-name" 	@if($errors->has('name')) style="display:block" @endif>{{$errors->first('name')}}</div>
                              </div>
                        
                              <div class="col-sm-4">
                                <label class="label">
                                  Danh mục
                                </label>
                                <select name="category" id="category" class="form-control">
                                  @foreach($categories as $category)
                                    <option @if($flag && $rows->category->name == $category->name) selected @endif value="{{$category->id}}">{{$category->name}}</option>
                                  @endforeach
                                </select>
                                <div class="error error-category" 	@if($errors->has('category')) style="display:block" @endif>{{$errors->first('category')}}</div>
                              </div>

                              <div class="col-sm-4">
                                <label class="label">Giá</label>
                                <div class="custom-file">
                                    <input id="price" type="text" name="price" @if($flag) value="{{format_price_input($rows->price)}}" @endif placeholder="Giá" class="form-control ">
                                    <div class="error error-price" 	@if($errors->has('price')) style="display:block" @endif>{{$errors->first('price')}}</div>                            
                                </div>
                              </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                  <label class="label">Mô tả</label>
                                  <textarea id="description" rows="5" name="description" placeholder="Mô tả" class="form-control">@if($flag) {{$rows->description}} @endif</textarea>
                                  <div class="error error-description" 	@if($errors->has('description')) style="display:block" @endif>{{$errors->first('description')}}</div>
                                </div>
                                <div id="editor"></div>
                            </div>
                            
                            <div class="form-group row">
                              <div class="col-sm-4">
                                  <label class="label-check label">Ảnh sản phẩm</label>
                                  <div class="custom-file">
                                      <input type="file" name="image" @if($flag) value="{{$primary}}" @endif accept=".jpg, .jpeg, .png" class="custom-file-input img-pro" id="file" data-toggle="tooltip" title="{{!empty($primary->name) ? $primary->name : 'Chọn ảnh'}} " data-placement="bottom">
                                      <label class="custom-file-label label-image" id="label-file" for="customFile">{{!empty($primary->name) ? $primary->name : 'Chọn ảnh'}}  </label>
                                      @if(isset($primary)) <input type="hidden" value="{{$primary->id}}" name="id_img"/> @endif
                                    </div>   
                                  <img width="200px" height="200px" id="ImgPre" @if($flag && !empty($primary)) src="{{ asset($primary->path)}}" @else src="{{ asset('images/product/no-image-product.png')}}" @endif alt="{{!empty($primary->name) ?? ''}}" class="img-thumbnail" />                             
                                  <div class="error error-image" 	@if($errors->has('image')) style="display:block" @endif>{{$errors->first('image')}}</div>
                                </div>
                            </div>
                            
                            <div class="form-group row display-image" @if(!isset($rows->id))   style="display:none;" @endif> 
                              <div class="col-sm-4">
                                <div class="custom-file">
                                  @php 
                                      // if(isset($rows->images))
                                      // {
                                      //  $images = $rows->images;  
                                      // }
                                  @endphp
                                  <input type="file" name="image1" @if(isset($list_image[0])) value="{{$list_image[0]->path}}" @endif accept=".jpg, .jpeg, .png" class="custom-file-input img-pro" id="file1" data-toggle="tooltip" title="{{!empty($list_image[0]->name) ? $list_image[0]->name : 'Chọn ảnh'}} " data-placement="bottom">
                                  <label class="custom-file-label label-image" id="label-file-1" for="customFile">{{isset($list_image[0]->name) ? $list_image[0]->name : 'Chọn ảnh'}}</label>
                                  <div class="error error-image1" 	@if($errors->has('image1')) style="display:block" @endif>{{$errors->first('image1')}}</div>
                                  @if(isset($list_image[0])) <input type="hidden" value="{{$list_image[0]->id}}" name="id_img_0"/> @endif
                                </div> 
                                  <img width="200px" height="200px" id="ImgPre1" @if(isset($list_image[0])) src="{{ asset($list_image[0]->path)}}" @else src="{{ asset('images/product/no-image-product.png')}}" @endif alt="{{ isset($list_image[0]) ?? ''}}" class="img-thumbnail" />
                              </div>
                              <div class="col-sm-4">
                                <div class="custom-file">
                                  <input type="file" name="image2" @if(isset($list_image[1])) value="{{$list_image[1]->path}}" @endif accept=".jpg, .jpeg, .png" class="custom-file-input img-pro" id="file2" data-toggle="tooltip" title="{{!empty($list_image[1]->name) ? $list_image[1]->name : 'Chọn ảnh'}} " data-placement="bottom">
                                  <label class="custom-file-label label-image" id="label-file-2" for="customFile">{{isset($list_image[1]->name) ? $list_image[1]->name : 'Chọn ảnh'}}</label>
                                  <div class="error error-image2" 	@if($errors->has('image2')) style="display:block" @endif>{{$errors->first('image2')}}</div>
                                  @if(isset($list_image[1])) <input type="hidden" value="{{$list_image[1]->id}}" name="id_img_1"/> @endif
                                </div> 
                                <img width="200px" height="200px" id="ImgPre2" @if(isset($list_image[1])) src="{{ asset($list_image[1]->path)}}" @else src="{{ asset('images/product/no-image-product.png')}}" @endif alt="{{ isset($list_image[1]) ?? ''}}" class="img-thumbnail" />
                              </div>
                              <div class="col-sm-4">
                                <div class="custom-file">
                                  <input type="file" name="image3" @if(isset($list_image[2])) value="{{$list_image[2]->path}}" @endif accept=".jpg, .jpeg, .png" class="custom-file-input img-pro" id="file3" data-toggle="tooltip" title="{{!empty($list_image[2]->name) ? $list_image[2]->name : 'Chọn ảnh'}} " data-placement="bottom">
                                  <label class="custom-file-label label-image" id="label-file-3" for="customFile">{{isset($list_image[2]->name) ? $list_image[2]->name : 'Chọn ảnh'}}</label>
                                  <div class="error error-image3" 	@if($errors->has('image3')) style="display:block" @endif>{{$errors->first('image3')}}</div>
                                  @if(isset($list_image[2])) <input type="hidden" value="{{$list_image[2]->id}}" name="id_img_2"/> @endif
                                </div> 
                                <img width="200px" height="200px" id="ImgPre3" @if(isset($list_image[2])) src="{{ asset($list_image[2]->path)}}" @else src="{{ asset('images/product/no-image-product.png')}}" @endif alt="{{ isset($list_image[2]) ?? ''}}" class="img-thumbnail" />
                              </div>
                            </div>
                        
                            @if($flag)
                            <div class="form-group row">
                              <div class="col-sm-12">
                                <label class="label-tag label">Từ khóa</label>
                                <div class="tagator_tags mb-2">
                                  @if(!empty($tags)) 
                                    @foreach($tags as $item)
                                      <div class="tagator_tag">#{{$item->tag->name}}<div class="tagator_tag_remove" onclick="removeTag({{$item->id}})">X</div></div>
                                    @endforeach
                                  @endif  
                                </div>
                                <span data-target="#add-tag"  data-toggle="modal" class="btn btn-primary">Thêm từ khóa<span>
                              </div>
                          </div>
                          @endif

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
  <script src="{{ asset('tagator/fm.tagator.jquery.js')}}"></script> 
  <script>
    let id = {{ $rows->id ?? -1}};

    CKEDITOR.replace( 'description' );

    $("#add-tag").on('hidden.bs.modal', function () {
      $('.error-input-tag').html('');
      $('#input-tag').val('');
    });
  
    $('#input-tag').keypress(function(e){
      if(e.which == 32)
      {
        e.preventDefault();
      }
    });

    // Add tag
    $('#input-tag').keyup(function(e){
      if(e.keyCode == 13)
      {
        let value = $(this).val();

        if(value == '')
        {
            $('.error-input-tag').html('Nhập từ khóa');
            return;
        }

        $.ajax({
          url: location.origin + '/api/product/' + id + '/add-tag',
          method: 'post',
          data: {
            'value': value,
            '_token':"{{ csrf_token() }}",
          },
          success:function(res)
          {
            if(res.error)
            {
              $('.error-input-tag').html(res.message);
              return;
            }

            let html = '';
            if(res.data != null)
            {
              $.each( res.data, function( key, value ) {
                html +=  '<div class="tagator_tag">#' + value.tag.name + '<div class="tagator_tag_remove" onclick="removeTag(' +  value.id + ')">X</div></div>';
              });
              $('.tagator_tags').html(html);
            }
          }
        });
        
        $('#input-tag').val('');
      }
      else
      {
        $('.error-input-tag').html('');
      }
    })

    //Remove function
    function removeTag(idTag)
    {
      $.ajax({
          url: location.origin + '/api/product/' + id + '/remove-tag',
          method: 'post',
          data: {
            'idTag': idTag,
            '_token':"{{ csrf_token() }}",
          },
          success:function(res)
          {
            let html = '';
            if(res.data != null)
            {
              $.each( res.data, function( key, value ) {
                html +=  '<div class="tagator_tag">#' + value.tag.name + '<div class="tagator_tag_remove" onclick="removeTag(' +  value.id + ')">X</div></div>';
              });
              $('.tagator_tags').html(html);
            }
          }
        });
    }

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
      $("#file1").change(function () {
          readURL(this, "#ImgPre1");
          let name = $(this).val().split('\\').pop();
          $('#label-file-1').html(name);
      });
      $("#file2").change(function () {
          readURL(this, "#ImgPre2");
          let name = $(this).val().split('\\').pop();
          $('#label-file-2').html(name);
      });
      $("#file3").change(function () {
          readURL(this, "#ImgPre3");
          let name = $(this).val().split('\\').pop();
          $('#label-file-3').html(name);
      });
// description

</script>
@endsection
