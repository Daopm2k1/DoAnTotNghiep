<?php


function format_price($price)
{
    return number_format($price, 0, '', ',');
}
function format_price_input($price)
{
    return number_format($price, 0, '', '');
}

function format_date($datetime)
{
    return date('d/m/Y', strtotime($datetime));
}

function format_datetime($datetime)
{
    return date_format($datetime,"d/m/Y H:i:s");
}
function format_time($datetime)
{
    return date_format($datetime,"H:i:s");
}

function getImageProduct($id)
{
    $image = \App\Models\ImageProduct::whereNull('deleted_at')->where('product_id',$id)->where('is_primary',1)->first();
    if(empty($image))
    {
        return;
    }
    return $image->path;
}
function getLimitFee($city_id)
{
    $ship = \App\Models\Ship::where('city_id',$city_id)->first();
    return $ship;
}
function getNameProvince($id)
{
    $city = \Vanthao03596\HCVN\Models\Province::where('id',$id)->first();
    return $city->name_with_type;
}
function getNameDistrict($id)
{
    $district = \Vanthao03596\HCVN\Models\District::where('id',$id)->first();
    return $district->name_with_type;
}
function getNameWard($id)
{
    $ward = \Vanthao03596\HCVN\Models\Ward::where('id',$id)->first();
    return $ward->name_with_type;
}
function getMethodPayment($payment = '')
{
    if($payment->method == "delivery")
    {
        return "Thanh toán khi giao hàng";
    }
    return 'Thanh toán qua '.strtoupper($payment->payment_gateway ?? '');
}
function getMethodPaymentDashboard($method = '',$payment_gateway = '') 
{
    if($method == "delivery")
    {
        return "Thanh toán khi giao hàng";
    }
    return 'Thanh toán qua '.strtoupper($payment_gateway ?? '');
}