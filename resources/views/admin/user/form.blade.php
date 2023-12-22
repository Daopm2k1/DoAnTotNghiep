<form id="form-user" class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{route('admin.user.store')}}">
    @csrf
    @if($flag = !empty($rows) && $rows->id)
        <input type="hidden" value="{{$rows->id}}" name="id">
    @endif
    <div class="form-group row">
      <div class="col-sm-6">
        <label class="label">Họ tên</label>
        <input id="username" type="text"  @if($flag) value="{{$rows->username}}" @endif name="username" placeholder="Họ tên" class="form-control">
        <div class="error error-username" 	@if($errors->has('username')) style="display:block" @endif>{{$errors->first('username')}}</div>
      </div>

      <div class="col-sm-6">
        <label class="label">Email</label>
        <input id="email" type="email" name="email" @if($flag) value="{{$rows->email}}" @endif placeholder="Email" class="form-control">
        <div class="error error-email" 	@if($errors->has('email')) style="display:block" @endif>{{$errors->first('email')}}</div>
      </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-4">
          <label class="label">Số điện thoại</label>
          <input id="phone" type="text" name="phone" @if($flag) value="{{$rows->phone}}" @endif placeholder="Số điện thoại" class="form-control "">
          <div class="error error-phone" 	@if($errors->has('phone')) style="display:block" @endif>{{$errors->first('phone')}}</div>
        </div>
        
        <div class="col-sm-4">
          <label class="label">Phân quyền</label>
          <select name="role" id="role" class="form-control">
            @foreach($roles as $role)
              <option @if($flag && $rows->roles->first()['name'] == $role->name) selected @endif value="{{$role->name}}">{{$role->name}}</option>
            @endforeach
          </select>
          <div class="error error-role" 	@if($errors->has('role')) style="display:block" @endif>{{$errors->first('role')}}</div>
        </div>

        <div class="col-sm-4">
          <label class="label">Ngày sinh</label>
          <input id="date_of_birth" type="date" name="date_of_birth" @if($flag) value="{{$rows->date_of_birth}}" @endif placeholder="" class="form-control ">
          <div class="error error-date_of_birth" 	@if($errors->has('date_of_birth')) style="display:block" @endif>{{$errors->first('date_of_birth')}}</div>
        </div>
    </div>

    {{-- <div class="form-group row">
        <div class="col-sm-12">
            <label class="label">Địa chỉ</label>
          <input id="address" type="text" name="address" @if($flag) value="{{$rows->address}}" @endif placeholder="Địa chỉ" class="form-control">
          <div class="error error-address" 	@if($errors->has('address')) style="display:block" @endif>{{$errors->first('address')}}</div>
        </div>
    </div> --}}

    <div class="form-group row">
        <div class="col-sm-12">
            <label class="label-check label" >Giới tính</label>
            <div class="i-checks">
                @php 
                    if($flag)
                    {
                        $gender = $rows->gender ? true : false;
                    }
                @endphp
                <input id="radioCustom1" type="radio" value="1" checked @if((!empty($rows->gender) && $gender) || empty($rows->gender)) checked @endif name="gender" class="form-control-custom radio-custom">
                <label for="radioCustom1">Nam</label>
              </div>
              <div class="i-checks">
                <input id="radioCustom2" type="radio" value="0" @if(!empty( $rows->gender) && !$gender) checked @endif name="gender" class="form-control-custom radio-custom">
                <label for="radioCustom2">Nữ</label>
              </div>
              <div class="error error-gender" 	@if($errors->has('gender')) style="display:block" @endif>{{$errors->first('gender')}}</div>
        </div>
    </div>

    @php 
      $name_file = '';
      if($rows && $rows->avatar)
      {
        $file = public_path().$rows->avatar;
        $filename = pathinfo($file, PATHINFO_FILENAME);
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $name_file = $filename . '.' . $extension;
      }
    @endphp
    <div class="form-group row">
        <div class="col-sm-6">
            <label class="label-check label" >Ảnh đại diện</label>
            <div class="custom-file">
                <input type="file" name="avatar" @if($flag) value="{{public_path().$rows->avatar}}" @endif accept=".jpg, .jpeg, .png" class="custom-file-input" id="file" data-toggle="tooltip" title="{{!empty($name_file) ? $name_file : 'Chọn ảnh'}}" data-placement="bottom">
                <label class="custom-file-label" id="label-file" for="customFile">{{!empty($name_file) ? $name_file : 'Chọn ảnh'}}</label>
                <div class="error error-avatar" 	@if($errors->has('avatar')) style="display:block" @endif>{{$errors->first('avatar')}}</div>
             </div>                                
        </div>
    </div>
    <div class="form-group">
        <img id="ImgPre" @if($flag && !empty($rows->avatar)) src="{{ asset($rows->avatar)}}" @else src="{{ asset('images/none-user.png')}}" @endif alt="" class="img-thumbnail" />
    </div>
    <div class="form-group row">       
      <div class="col-sm-12">
        <button type="submit" id="create" class="btn btn-primary">Lưu</button>
      </div>
    </div>
  </form>