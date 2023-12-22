@component('mail::message')
# Kính gửi anh/chị: {{ $order->fullname }}<br>
Cảm ơn anh/chị đã đặt hàng tại {{ config('app.name') }}. Chúng tôi rất may mắn khi được phục vụ anh/chị. Sau đây là chi tiết hoá đơn mà anh/chị đã đặt:<br><br>

Hoá đơn tạo lúc {{ format_datetime($order->created_at) }}<br>
Mã hoá đơn: {{ $order->id }}<br>
Điện thoại: {{ $order->phone }}<br>
Địa chỉ: {{$order->address}}<br>
Phương thức thanh toán: {{getMethodPayment($order->payment)}}<br>
Trạng thái: {{$order->payment->status ? 'Đã thanh toán' : 'Chưa thanh toán'}}<br>
Ghi chú: {{$order->note}}<br>

@component('mail::table')
| STT        | Sản phẩm                         | SL                  | Giảm giá             | Thành Tiền  |
| ---------- |:--------------------------------:|:-------------------:|:--------------------:|:-----------:|
@foreach ($order->order_detail as $key=>$item)
@php
    $discount = 0;
    if(!empty($item->discount_id))
    {
        $discount = $item->price * $item->discount->sale_percent / (100 - $item->discount->sale_percent);
    }
@endphp
|{{$key + 1}}|  {{$item->product_detail->name}} | {{$item->quantity}} | {{format_price($discount)}}&#8363; | {{format_price($item->price + $discount)}}&#8363;
@endforeach
||||||
||||Tổng tiền:|{{format_price($order->total + $order->sale_price)}}&#8363; |
||||Phí vận chuyển:|{{format_price($order->payment->ship->fee)}}&#8363; |
||||Tiền giảm:|- {{format_price($order->sale_price)}}&#8363; |
||||Phí thanh toán:|{{format_price($order->payment->amount)}}&#8363;|
@endcomponent


Xin cảm ơn Quý khách!<br>
{{ config('app.name') }}
@endcomponent
