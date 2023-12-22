@extends('layouts.user')
@section('content')
<style type="text/css">
    .post ul li {
        padding: 2px;
        font-size: 16px;
        list-style-type: decimal-leading-zero;
    }
    .post ul li a {
        color: #000;
    }
    .post ul li a:hover {
        color: #FE980F;
    }
    .mucluc h1{
        font-size: 2-px;
        color: brown;
    }
    .w-150 {
        width: 150px;
    }
</style>
<section>
<div class="container">
        {{-- @include('layouts.user.breadcrumb') --}}
        <div class="row">
            @include('user.parts.category-post')
            <div class="col-sm-9">
                <div class="blog-post-area">
                    <h2 class="title text-center">{!!$post->name!!}</h2>
                    <div class="single-blog-post">
                        <h3></h3>
                        <div class="post-meta">
                            <ul>
                                <li><i class="fa fa-user"></i>{{$post->author}}</li>
                                <li><i class="fa fa-calendar"></i> {{ format_datetime($post->created_at)}}</li>
                            </ul>
                        </div>
                        <div class="">
                            <img class="w-150" src="{{asset($post->getImagePrimary())}}" alt="">
                        </div>
                        <p>
                            @php
                                echo $post->post_content;
                            @endphp
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="recommended_items"><!--recommended_items-->
                    <h2 class="title text-center">Bài viết liên quan</h2>
                    <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($related_post as $key=>$value)
                            
                                <div class="col-sm-4">
                                    <div class="product-image-wrapper">
                                        <div class="single-products">
                                            <div class="productinfo text-center">
                                                <a href="{{URL::to('post/'.$value->path)}}"><img src="{{asset($value->getImagePrimary())}}" alt="" /></a>
                                                <div class="box-name-product"><a href="{{URL::to('post/'.$value->path)}}" class="name-product cursor">{{$value->name}}</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            @endforeach
                        </div>
                        <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>			
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection