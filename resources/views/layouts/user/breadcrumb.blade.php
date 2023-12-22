<div class="breadcrumbs">
  <ol class="breadcrumb">
    @if(!empty($breadcrumbs))
      @foreach($breadcrumbs as $breadcrumb)
          @if(empty($breadcrumb['url']))
            <li class="breadcrumb-item">{{ $breadcrumb['name'] }}</li>
          @else
            <li class="breadcrumb-item active"><a href="{{url($breadcrumb['url'])}}">{{$breadcrumb['name']}}</a></li>
          @endif
      @endforeach
    @endif
  </ol>
</div>