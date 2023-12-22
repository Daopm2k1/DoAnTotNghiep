@component('mail::message')
# Thông tin giảm giá

Thời gian áp dụng: {{format_date($discount->start_date)}} - {{format_date($discount->end_date)}}<br>
Khi quý khách sử dụng tài khoản đăng kí bằng email {{$discount->userApply->email}}<br>
Quý khách sẽ được giảm giá {{$discount->sale_percent}}%<br>
Đối với các {{$discount->type == 'product' ? 'sản phẩm' : 'danh mục'}}:

@if(!empty($discount->getItemDiscount()))
    @foreach($discount->getItemDiscount() as $key=>$value)
        - {{$value->name}}
    @endforeach
@endif


Cảm ơn quý khách !<br>
{{ config('app.name') }}
@endcomponent
