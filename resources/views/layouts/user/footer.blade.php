<footer id="footer"><!--Footer-->
		<div class="footer-widget">
			<div class="container">
				<div class="row">
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Về E-Shopper</h2>
							<ul class="nav nav-pills nav-stacked" style="flex-direction: column-reverse;">
								<li><a href="{{ url('/about') }}">Giới thiệu</a></li>
								<li><a href="{{ url('/exchange') }}">Chính sách đổi trả</a></li>
								<li><a href="{{ url('/privacy') }}">Chính sách bảo mật</a></li>
								<li><a href="{{ url('/terms') }}">Điều khoản dịch vụ</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Danh mục</h2>
							<ul class="nav nav-pills nav-stacked">
							@foreach($categories as $key => $cate)
								<li><a href="{{URL::to('/category/'.$cate->id)}}">{{$cate->name}}</a></li>
							@endforeach
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Tin tức mỗi ngày</h2>
							<ul class="nav nav-pills nav-stacked">
								@foreach($category_post as $key => $cate)
									<li><a href="{{URL::to('/post-cate/'.$cate->post_path)}}">{{$cate->post_name}}</a></li>
								@endforeach
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Liên hệ</h2>
							@foreach($infor_contact as $key => $contact)
								<ul class="nav nav-pills nav-stacked"> 
									<li class="footer-address">{{$contact->address}}</li>
									<li class="footer-address">{{$contact->phone}}</li>
									<li class="footer-address">{{$contact->email}}</li>
								</ul>
							@endforeach
						</div>
					</div>
					
					<div class="col-sm-3 col-sm-offset-1">
						<div class="single-widget">
							<h2>Ý kiến</h2>
							<form id="form-contact" action="" method="POST" class="searchform">
							@csrf
								<input name="contact_name" id="contact-name"  type="text" placeholder="Nhập họ tên"/>
								<div class="error error-name" 	@if(isset($errors) && $errors->has('contact_name')) style="display:block" @endif>{{$errors->first('contact_name')}}</div>
								<input name="contact_email" id="contact-email"  type="text" placeholder="Nhập email"/>
								<div class="error error-email" 	@if(isset($errors) && $errors->has('contact_email')) style="display:block" @endif>{{$errors->first('contact_email')}}</div>
								<textarea  name="content" id="contact-content"  rows="5" placeholder="Nhập nội dung"></textarea>
								<div class="error error-content" @if(isset($errors) && $errors->has('content')) style="display:block" @endif>{{$errors->first('content')}}</div>
								<br>
								<button type="submit" class="btn btn-default btn-send btn-custom">Gửi</button>
							</form>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="footer-bottom">
			<div class="container">
				<div class="row">
					<p class="pull-left">Copyright © 2021 E-SHOPPER</p>
				</div>
			</div>
		</div>
		
	</footer><!--/Footer-->
	