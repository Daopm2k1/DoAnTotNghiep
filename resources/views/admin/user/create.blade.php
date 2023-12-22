@extends('layouts.admin')
@section('content')
<section class="charts">
  <section>
        {{-- @php if(count($errors)) var_dump($errors->first('username')) @endphp --}}
        <div class="container-fluid">
          <!-- Page Header-->
          <header> 
            <h1 class="h3 display">Tạo tài khoản</h1>
          </header>
          <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                      @include('admin.user.form')
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
  <script>
  </script>
<script src="{{ asset('js/admin_user.js')}}"></script> 
@endsection
