<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(Auth::id())
        <meta name="user_id" content="{{ Auth::id() }}">
    @endif
    <title>{{ config('app.name','EShopper') }}{{ !empty($title) ? ' - '.$title : ''}}</title>
    <link href="{{ asset('user/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('user/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{ asset('user/css/prettyPhoto.css')}}" rel="stylesheet">
    <link href="{{ asset('user/css/price-range.css')}}" rel="stylesheet">
    <link href="{{ asset('user/css/animate.css')}}" rel="stylesheet">
    <link href="{{ asset('user/css/main.css')}}" rel="stylesheet">
    <link href="{{ asset('user/css/responsive.css')}}" rel="stylesheet">
    <link href="{{ asset('user/css/sweetalert.css')}}" rel="stylesheet">
    <link href="{{ asset('user/css/style.css')}}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('vendor/images/icons/favicon.ico') }}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('images/ico/apple-touch-icon-144-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('images/ico/apple-touch-icon-114-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('images/ico/apple-touch-icon-72-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('images/ico/apple-touch-icon-57-precomposed.png')}}">
	
    <link href="{{ asset('css/user/custom.css')}}" rel="stylesheet">

    <div class="zalo-chat-widget" data-oaid="832119572324877926" data-welcome-message="Rất vui khi được hỗ trợ bạn!" data-autopopup="0" data-width="" data-height=""></div>
    <script src="https://sp.zalo.me/plugins/sdk.js"></script>
    <style>
        #container_viewed
        {
            display: none;
        }
        #container_viewed.active
        {
            display: block;
        }
    </style>
     @yield('head')
</head><!--/head-->

<body>
	@include('layouts.user.header')

	@yield('content')
	
	@include('layouts.user.footer')
  
    <script src="{{ asset('user/js/jquery.js')}}"></script>
    <script src="{{ asset('user/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('user/js/jquery.scrollUp.min.js')}}"></script>
    <script src="{{ asset('user/js/price-range.js')}}"></script>
    <script src="{{ asset('user/js/jquery.prettyPhoto.js')}}"></script>
    <script src="{{ asset('user/js/main.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('vendor/vendor/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ asset('js/moment.min.js')}}"></script>
    <script type='text/javascript'  src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js' ></script>
    <script src="{{asset('user/js/sweetalert.min.js')}}"></script>
    <script src="{{asset('user/js/custom.js')}}"></script>
    <script type="text/javascript">
        function viewed(){
            if(localStorage.getItem('viewed') != null){
                var data = JSON.parse(localStorage.getItem('viewed'));
                var row_viewed = $('#row_viewed');
                data.reverse();
                if(row_viewed)
                {
                    row_viewed.css('overflow','scroll');
                    row_viewed.css('height','500px');

                    for(i=0; i<data.length; i++){
                        if(data[i].id != undefined){
                            $('#container_viewed').addClass('active');
                            var name = data[i].name;
                            var price = data[i].price;
                            var url = data[i].url;
                            var image = data[i].image;
                            row_viewed.append('<div class="row" style="margin:10px 0"><div class="col-md-4"><img width="100%" src="'+image+'"></div><div class="col-md-8"><p>'+name+'</p><p style="color:#FE980F">'+price+'&#8363;</p><a href="'+url+'">Xem ngay</a></div>')
                        }
                    }
                }
            }
        }
        viewed();
        product_viewed();
        function product_viewed(){ 
            var id_pro = $('#product_viewed_id').val();
            if(id_pro != undefined){
                var id = id_pro;
                var name = document.getElementById('viewed_product_name'+id).value;
                var url = document.getElementById('viewed_product_url'+id).value;
                var price = document.getElementById('viewed_product_price'+id).value;
                var image = document.getElementById('viewed_product_image'+id).value;
            }
            var newItem = {
                'url': url,
                'id':id_pro,
                'name':name,
                'price':price,
                'image':image
            }
            if(localStorage.getItem('viewed')==null){
                localStorage.setItem('viewed', '[]')
            }
     
            var old_data = JSON.parse(localStorage.getItem('viewed'));
            var matches = $.grep(old_data, function(obj) { 
                return obj.id == id_pro;
            })

            if(matches.length){

            }else{
                old_data.push(newItem);
                $('#row_viewed').append('<div class="row" style="margin:10px 0"><div class="col-md-4"><img width="100%" src="'+newItem.image+'"></div><div class="col-md-8"><p>'+newItem.name+'</p><p style="color:"#FE980F">'+newItem.price+'</p><a href="'+newItem.url+'">Xem ngay</a></div>')
            }
            localStorage.setItem('viewed', JSON.stringify(old_data));
        }
    </script>
    <script type="text/javascript">
		$('#key_word').keyup(function(){ 
			var key_word = $(this).val(); 
            let searchInput = $('#search_ajax');
            
			if(key_word.length>0){
				$.ajax({
					url:"{{url('autocomplete-ajax')}}",
					method:"GET",
					data:{
                        key_word:key_word
                    },
					success:function(res) {
                        if(!res.error) {
                            if(res.data.length > 0)
                            {
                                let html = '<ul class="show">';
                                    
                                $.each(res.data, function(key, value) {
                                    let url = location.origin + '/product/' + value.id + '/detail';
                                    html +='<li class="li_cursor_mouse" style="padding: 5px 10px;"><a href="' + url + '" class="a_cursor_mouse">' + value.name + '</a></li>';
                                });

                                html += '</ul>';
                                
                                searchInput.fadeIn();
                                searchInput.html(html);
                            }
                        
                        } else {
                            searchInput.fadeOut();
                            searchInput.html('');
                        }
					}
				});
			}else{
				searchInput.fadeOut();
                searchInput.html('');
			}
		});

	</script>
    @yield('script')
    @include('layouts.message.alert-message')
</body>
</html>