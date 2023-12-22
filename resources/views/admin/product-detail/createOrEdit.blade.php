@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        {{-- @php if(count($errors)) var_dump($errors->first('username')) @endphp --}}
        <div class="container-fluid">
          <header> 
            <h1 class="h3 display">{{($flag = !empty($rows) && $rows->id) ? 'Edit Product' : 'Create Product'}}</h1>
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
                                <label>Name</label>
                                <input id="name" type="text"  @if($flag) value="{{$rows->name}}" @endif name="name" placeholder="Name" class="form-control">
                                <div class="error error-name" 	@if($errors->has('name')) style="display:block" @endif>{{$errors->first('name')}}</div>
                              </div>
                        
                              <div class="col-sm-4">
                                <label>Category</label>
                                <select name="category" id="category" class="form-control">
                                  @foreach($categories as $category)
                                    <option @if($flag && $rows->category->name == $category->name) selected @endif value="{{$category->id}}">{{$category->name}}</option>
                                  @endforeach
                                </select>
                                <div class="error error-category" 	@if($errors->has('category')) style="display:block" @endif>{{$errors->first('category')}}</div>
                              </div>

                              <div class="col-sm-4">
                                <label>Price</label>
                                <div class="custom-file">
                                    <input id="price" type="number" name="price" @if($flag) value="{{$rows->price}}" @endif placeholder="Price" class="form-control ">
                                    <div class="error error-price" 	@if($errors->has('price')) style="display:block" @endif>{{$errors->first('price')}}</div>                            
                                </div>
                              </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                  <label>Description</label>
                                  <input id="description" type="text" name="description" @if($flag) value="{{$rows->description}}" @endif placeholder="Description" class="form-control">
                                  <div class="error error-description" 	@if($errors->has('description')) style="display:block" @endif>{{$errors->first('description')}}</div>
                                </div>
                            </div>
                        
                            {{-- <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="label-check">Image</label>
                                    <div class="custom-file">
                                        <input type="file" name="image" @if($flag) value="{{public_path().$rows->image}}" @endif accept=".jpg, .jpeg, .png" class="custom-file-input" id="file">
                                        <label class="custom-file-label" id="label-file" for="customFile">Choose file</label>
                                        <div class="error error-image" 	@if($errors->has('image')) style="display:block" @endif>{{$errors->first('image')}}</div>
                                     </div>                                
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <img id="ImgPre" @if($flag && !empty($rows->image)) src="{{ asset($rows->image)}}" @else src="{{ asset('images/none-user.png')}}" @endif alt="Alternate Text" class="img-thumbnail" />
                            </div> --}}

                            <div class="form-group row">       
                              <div class="col-sm-12">
                                <span id="create" class="btn btn-primary">Save<span>
                              </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
          </div>
        </div>
  </section>
</section>
@endsection
@section('script')
  <script>
      // function readURL(input, idImg) {
      //   if (input.files && input.files[0]) {
      //       var reader = new FileReader();
      //       reader.onload = function (e) {
      //           $(idImg).attr("src", e.target.result);
      //       }
      //       reader.readAsDataURL(input.files[0]);
      //   }
      // }

      // $("#file").change(function () {
      //     readURL(this, "#ImgPre");
      // });
  </script>
  <script src="{{ asset('js/admin_product.js')}}"></script> 
@endsection
