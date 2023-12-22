@component('mail::message')
# Xin chào !

Chào mừng quý khách đến với EShopper !
Vui lòng chọn kích hoạt để kích hoạt tài khoản của bạn.

@component('mail::button', ['url' => $url])
Kích hoạt
@endcomponent

Cảm ơn quý khách !<br>
{{ config('app.name') }}
@endcomponent

