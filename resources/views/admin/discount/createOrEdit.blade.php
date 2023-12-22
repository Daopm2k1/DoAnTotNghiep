@extends('layouts.admin')
@section('head')
 <style>
     .form-check
     {
         display: none;
     }
     .active
     {
         display: block;
     }
     .d-none
     {
         display: none;
     }
     .item-product,
     .item-category
     {
         float:left;
         margin:0 5px;
     }
     .item-product label,
     .item-category label,
     .item-product.show,
     .item-category.show
     {
        background: #6868f342;
        padding: 10px;
        border-radius: 20px;
        margin: 2px;
     }
     .active-input
     {
         background-color: #6868f3bf !important;
     }
     .height-400
     {
        height: 400px;
     }
     .height-300
     {
        height: 300px;
     }
     .list-item
     {    
      overflow: auto;
      padding-bottom: 10px; 
      margin: 20px 0 !important;
     }
     .button-save 
     {
        position: absolute; 
        bottom: 0;
        margin: 0 !important;
        padding: 5px;
     }
     .error
     {
       padding-top: 5px;
     }
     .item-user
     {
      list-style-type: none;
     }
 </style>
@endsection
@section('content')
<section class="charts">
    <div class="container-fluid">
        <!-- Page Header-->
        <header> 
            <h1 class="h3 display">{{ (isset($row->id) && $row->id) ? 'Chi tiết giảm giá' : 'Tạo giảm giá'}}</h1>
        </header>
        <div class="row">
            <div class="col-lg-12">
                <form id="form-discount" method="POST" action="{{route('admin.discount.store')}}">
                  @csrf
                    @php
                      $flag =isset($row->id) && $row->id;
                    @endphp
                      @if($flag)
                        <input type="hidden" name="id" id="id_hidden" value="{{$row->id}}">
                    @endif
                    <div class="form-group row">
                        <div class="col-sm-12">     
                          <label class="label">{{ (isset($row->id) && $row->id) ? 'Sản phẩm hoặc danh mục được giảm giá' : 'Chọn sản phẩm hoặc danh mục được giảm giá'}}</label>
                        </div>
                        <div class="col-sm-12">                           
                            <div class="i-checks">
                                <input id="product" @if($flag) disabled @endif type="radio" value="product" @if($flag && $row->type == "product" || !isset($row)) checked @endif name="type_discount" class="product form-control-custom radio-custom">
                                <label for="product">Sản phẩm</label>
                             </div>
                             <div class="form-group product row mt-3 form-check">
                                <div class="col-sm-12">
                                    @php
                                    $list_id = [];
                                    $list_name = [];
                                      if(!empty($list_item))
                                      {
                                        foreach($list_item as $key=>$value) 
                                        {
                                           array_push($list_id,$key);
                                           array_push($list_name,$value);
                                        }
                                      }       
                                    @endphp
                                    <input type="hidden" id="list-product" @if(!empty($list_name) && $row->type == 'product') value="{{ implode(",", $list_name)}}" @endif>
                                    <input type="hidden" name="id_product" id="list-id" @if(!empty($list_id) && $row->type == 'product') value="{{ implode(",", $list_id) }}" @endif>
                                    <div class="row">
                                      <div class="col-12">
                                        <div class="show-product">
                                          @if($flag && $row->type == 'product')
                                            @foreach ($list_item as $key=>$value)
                                              <div class="active-input item-product show">{{$value}}</div>
                                            @endforeach
                                          @endif
                                        </div>
                                      </div>
                                    </div>
                                    @if(!$flag)<span data-target="#modal-product"  data-toggle="modal" class="btn btn-primary">{{ $flag ? 'Cập nhật sản phẩm giảm giá' : 'Thêm sản phẩm giảm giá'}}</span>@endif
                                  <div class="error error-product">Chọn sản phẩm cần giảm giá</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="i-checks">
                                <input id="category" @if($flag) disabled @endif type="radio" @if($flag && $row->type == "category") checked @endif value="category" name="type_discount" class="category form-control-custom radio-custom">
                                <label for="category">Danh mục</label>
                             </div>
                             <div class="form-group category row mt-3 form-check">
                                <div class="col-sm-12">
                                    <input type="hidden" id="list-category" @if(!empty($list_name) && $row->type == 'category') value="{{ implode(",", $list_name)}}" @endif>
                                    <input type="hidden" name="id_category" id="list-id-category"  @if(!empty($list_id) && $row->type == 'category') value="{{ implode(",", $list_id) }}" @endif>
                                    <div class="row">
                                      <div class="col-12">
                                        <div class="show-category">
                                          @if($flag && $row->type == 'category')
                                            @foreach ($list_item as $key=>$value)
                                              <div class="active-input item-product show">{{$value}}</div>
                                            @endforeach
                                          @endif
                                        </div>
                                      </div>
                                    </div>
                                    @if(!$flag)<span data-target="#modal-category"  data-toggle="modal" class="btn btn-primary">{{$flag ? 'Cập nhật danh mục giảm giá' : 'Thêm danh mục giảm giá'}}</span>@endif
                                  <div class="error error-category">Chọn danh mục cần giảm giá</div>
                                </div>
                            </div>
                        </div>
                    </div>
                   <div class="form-group row">
                        <div class="col-sm-6">
                          <label class="label">Giá giảm (%)</label>
                          <input id="sale_price" @if($flag) disabled @endif  @if($flag) value="{{$row->sale_percent}}" @endif type="text" name="sale_price" placeholder="Giá giảm" class="form-control ">
                          <div class="error error-sale_price" @if($errors->has('sale_price')) style="display:block" @endif>{{$errors->first('sale_price')}}</div>
                        </div>
                        @if($flag)
                        <div class="col-sm-6">
                          <label class="label">Mã giảm giá</label>
                          <input id="discount_code" disabled type="text" value="{{$row->discount_code}}" name="discount_code" placeholder="Mã giảm giá" class="form-control ">
                          <div class="error error-discount_code"  @if($errors->has('discount_code')) style="display:block" @endif>{{$errors->first('discount_code')}}</div>
                        </div>
                    @endif
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-12">
                        <div class="label">Tài khoản</div>
                        <input id="user_apply" type="hidden" name="user_apply" class="form-control ">
                        <input id="name_apply" type="hidden" class="form-control ">
                        @if(!$flag)<button style="margin-top:5px;" type="button" data-target="#modal-email"  data-toggle="modal" class="btn btn-primary">{{ $flag ? 'Cập nhật danh sách tài khoản' : 'Thêm danh sách tài khoản'}}</button>@endif
                        <div class="show-user">
                          @if($flag)<div class="active-input item-product show">{{$row->userApply->email}}</div>@endif
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="error error-user_apply" @if($errors->has('user_apply')) style="display:block" @endif>{{$errors->first('user_apply')}}</div>
                      </div>
                    </div>
    
                    <div class="form-group row">
                        <div class="col-sm-6">
                          <label class="label">Ngày bắt đầu</label>
                          <input id="start_date" type="date"  @if($flag) disabled value="{{$row->start_date}}" @endif name="start_date" class="form-control ">
                          <div class="error error-start_date" @if($errors->has('start_date')) style="display:block" @endif>{{$errors->first('start_date')}}</div>
                        </div>
                    {{-- </div> --}}
    
                    {{-- <div class="form-group row"> --}}
                        <div class="col-sm-6">
                          <label class="label">Ngày kết thúc</label>
                          <input id="end_date" @if(empty($row->end_date)) disabled @endif @if($flag) disabled value="{{$row->end_date}}" @endif type="date" name="end_date" class="form-control ">
                          <div class="error error-end_date" @if($errors->has('end_date')) style="display:block" @endif>{{$errors->first('end_date')}}</div>
                        </div>
                    </div>

                    @if(!$flag)
                    <div class="form-group row">       
                        <div class="col-sm-12">
                          <button type="submit" id="create" class="btn btn-primary">Lưu</button>
                        </div>
                      </div>
                      @endif
                </form>
            </div>
        </div>
    </div>

 {{-- Modal bootstrap product --}}
<div class="modal fade" id="modal-product">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
  
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">{{ $flag ? 'Cập nhật sản phẩm giảm giá' : 'Thêm sản phẩm giảm giá'}}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
  
        <!-- Modal body -->
        <div class="modal-body">
          <div class="form-group row">

            <div class="col-sm-12">
                <input id="search-product" type="text" placeholder="Tìm sản phẩm" class="form-control">
            </div>

            <div class="col-sm-12 height-400 list-item">
              <input id="input-product" type="hidden" name="input-product" class="form-control">
              <input id="name-product" type="hidden" name="name-product" class="form-control">
              <div class="error error-input-tag"></div>
              <div class="tagator_tags">
                  @if(!empty($products))
                    @foreach ($products as $product)
                        <span class="item-product">
                            <input type="checkbox" name="product" class="d-none" data-name="{{$product->name}}" data-id="{{$product->id}}" id="product_{{$product->id}}">
                            <label class="cursor product_{{$product->id}}" for="product_{{$product->id}}">{{$product->name}}</label>
                        </span>
                    @endforeach
                  @endif
              </div>
            </div>
            <div class="col-sm-12 button-save">
                <center><span id="save-product" class="btn btn-primary">Lưu</span></center>
            </div>
          </div>
        </div>
  
      </div>
    </div>
</div>

  {{-- Modal bootstrap add tag  --}}
<div class="modal fade" id="modal-category">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">{{ $flag ? 'Cập nhật danh mục giảm giá' : 'Thêm danh mục giảm giá'}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group row">
          <div class="col-sm-12">
              <input id="search-category" type="text" placeholder="Tìm danh mục" class="form-control">
          </div>

          <div class="col-sm-12 height-400 list-item">
            <input id="input-category" type="hidden" name="input-category" class="form-control">
            <input id="name-category" type="hidden" name="name-category" class="form-control">
            <div class="error error-input-tag"></div>
            <div class="tagator_tags">
                @if(!empty($categories))
                  @foreach ($categories as $category)
                      <span class="item-category">
                          <input type="checkbox" name="category" class="d-none" data-name="{{$category->name}}" data-id="{{$category->id}}" id="category_{{$category->id}}">
                          <label class="cursor category_{{$category->id}}" for="category_{{$category->id}}">{{$category->name}}</label>
                      </span>
                  @endforeach
                @endif
            </div>
          </div>
          <div class="col-sm-12 button-save">
              <center><span id="save-category" class="btn btn-primary">Lưu</span></center>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

  <!-- Model Email -->
<div class="modal fade" id="modal-email">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">{{ $flag ? 'Cập nhật tài khoản giảm giá' : 'Thêm tài khoản giảm giá'}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

        <div class="form-group row">
          <div class="col-sm-12">
              <input id="search-user" type="text" placeholder="Tìm email" class="form-control">
          </div>

          <div class="col-sm-12 height-300 list-item">
            <input id="input-user" type="hidden" name="input-user" class="form-control">
            <input id="name-user" type="hidden" name="name-user" class="form-control">
            <div class="error error-input-tag"></div>
            <div class="tagator_tags">
                <ul id="ul-user">

                </ul>
            </div>
          </div>
        </div>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
         <div class="row">
           <div class="col-sm-12">
            <center><button id="save-user" class="btn btn-primary">Lưu</button></center>
           </div>
         </div>
      </div>

    </div>
  </div>
</div>

    @include('admin.part.modal-confirm')
</section>
@endsection
@section('script')
  <script src="{{ asset('js/admin_discount.js')}}"></script> 
  <script src="{{ asset('tagator/fm.tagator.jquery.js')}}"></script> 
    <script>

    </script>
@endsection
