@if(count($slides) > 0)
<section id="slider"><!--slider-->
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div id="slider-carousel" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            @foreach ($slides as $key=>$item)
              <li data-target="#slider-carousel" data-slide-to="{{$key}}" @if($key == 0) class="active" @endif></li>
            @endforeach
          </ol>
          
          <div class="carousel-inner">
            @foreach ($slides as $key=>$item)
              <div class="item @if($key == 0) active @endif">
                <div class="col-sm-6">
                  <h1><span>E</span>-SHOPPER</h1>
                  <h2>{{$item->title}}</h2>
                  <p>{{$item->slide_content}}</p>
                  <a style="margin-bottom:20px;" target="_blank" class="shop-now" href="{{URL::to($item->path)}}">Xem ngay</a>
                  <!-- <button type="button" class="btn btn-default btn-custom" >Xem ngay</button> -->
                </div>
                <div class="col-sm-6">
                  <img src="{{ asset($item->image) }}" class="girl img-responsive" alt="" />
                </div>
              </div>
            @endforeach
          </div>
          
          <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
            <i class="fa fa-angle-left"></i>
          </a>
          <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
            <i class="fa fa-angle-right"></i>
          </a>
        </div>
        
      </div>
    </div>
  </div>
</section>
@endif
