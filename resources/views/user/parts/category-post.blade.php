<div class="col-sm-3">
    <div class="left-sidebar">
        <h2 class="title-padding">Danh mục bài viết</h2>
        <div class="panel-group category-products scroll-menu" id="accordian"><!--category-productsr-->
            @foreach($category_post as $key=>$cate)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"><a href="{{URL::to('post-cate/'.$cate->post_path)}}">{{$cate->post_name}}</a></h4>
                </div>
            </div>
           @endforeach
        </div>              
    </div>
</div>