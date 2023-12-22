@extends('layouts.user')
@section('head')
    <style>
        .w-100 {
            width: 100px;
            height: auto;
        }
        .box-post {
            display: flex;
        }
        .content-post {
            padding-left: 20px;
        }
        .blog-post-area .single-blog-post:not(:first-child) {
           border-top: 1px solid black;
        }
        .single-blog-post {
             padding-bottom: 10px;
        }
        .blog-post-area {
            padding-bottom: 20px;
        }
    </style>
@endsection
@section('content')

<section>
    <div class="container">
        {{-- @include('layouts.user.breadcrumb') --}}
        <div class="row">
            @include('user.parts.category-post')
            
            <div class="col-sm-9 padding-right">
                
                <div class="features_items">
                    <h2 class="title title-padding text-center">
                        {{ !empty(Request::get('key_word')) ? 'Bài viết tìm kiếm' : ((isset($title) && !empty($title)) ? $title : 'Bài viết mới nhất')}}
                    </h2>
                   @if(count($posts) > 0)
                        <div class="col-sm-12">
                            <div class="blog-post-area">
                                @foreach($posts as $key => $post)
                                <div class="single-blog-post">
                                    <h3>{{$post->name}}</h3>
                                    <div class="post-meta">
                                        <ul>
                                            <li><i class="fa fa-user"></i>{{$post->author}}</li>
                                            <li><i class="fa fa-calendar"></i> {{format_datetime($post->created_at)}}</li>
                                        </ul>
                                    </div>
                                    <div class="box-post">
                                        <div>
                                            <img class="w-100" src="{{asset($post->getImagePrimary())}}" alt="{{$post->path}}">
                                        </div>
                                        <div class="content-post">
                                            <p>{{$post->description}}</p>
                                            <a  class="btn btn-primary btn-custom" href="{{URL::to('post/'.$post->path)}}" style="margin-bottom: 10px;">Đọc tiếp</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                            
                        <div class="clearfix"></div>
                    @else
                      <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <center><p>Không có bài viết</p></center>
                            </div>
                        </div>
                      </div>
                    @endif
                    
                </div>
                {{--<div class="row">
                    <div class="col-12">
                        <div class="mt-3 paginate"><center>{{ $products->withQueryString()->links() }}</center></div>
                    </div>
                </div>--}}
            </div>
        </div>
    </div>
</section>

@endsection



