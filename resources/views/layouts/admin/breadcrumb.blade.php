<div class="breadcrumb-holder">
    <div class="container-fluid">
      <ul class="breadcrumb">
        {{-- <li class="breadcrumb-item"><a href="index.html">Home</a></li> --}}
        @if(!empty($breadcrumbs))
          @foreach($breadcrumbs as $breadcrumb)
            @if(empty($breadcrumb['url']))
              <li class="breadcrumb-item active">{{ $breadcrumb['name'] }}</li>
            @else
              <li class="breadcrumb-item active"><a href="{{url($breadcrumb['url'])}}">{{$breadcrumb['name']}}</a></li>
            @endif
          @endforeach
        @endif
      </ul>
    </div>
  </div>