function isEmail(email) {
    let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

//check input phone
$('#phone').keypress(function(e){
    let char = String.fromCharCode(e.which);
    if(!(/[0-9]/.test(char)))
    {
        e.preventDefault();
    }
    });

$('#form-checkout').submit(function(e){
    let flag = false;

    //Check username
    if($('#fullname').val() == '')
    {
      $('.error-fullname').html('Nhập họ và tên');
      $('.error-fullname').css('display','block');
      flag = true;
    }
    else
    {
      $('.error-fullname').html('');
      $('.error-fullname').css('display','none');
    }

    //check email
    if($('#email').val()=='')
    {
        $('.error-email').html('Nhập email');
        $('.error-email').css('display','block');
        flag = true;
    }
    else
    {
        if(!isEmail($('#email').val()))
        {
            $('.error-email').html('Email không hợp lệ');
            $('.error-email').css('display','block');
            flag = true;
        }
        else
        {
            $('.error-email').html('');
            $('.error-email').css('display','none');
        }
    }

    //Check phone
    if($('#phone').val() == '')
    {
        $('.error-phone').html('Nhập số điện thoại');
        $('.error-phone').css('display','block');
        flag = true;
    }
    else
    {
        $('.error-phone').html('');
        $('.error-phone').css('display','none');
    }

     //Check province
     if($('#province').val() == '')
     {
         $('.error-province').html('Chọn tỉnh/thành phố');
         $('.error-province').css('display','block');
         flag = true;
     }
     else
     {
         $('.error-province').html('');
         $('.error-province').css('display','none');
     }

      //Check district
      if($('#district').val() == '')
      {
          $('.error-district').html('Chọn quận/huyện');
          $('.error-district').css('display','block');
          flag = true;
      }
      else
      {
          $('.error-district').html('');
          $('.error-district').css('display','none');
      }
      
      //Check ward
      if($('#ward').val() == '')
      {
          $('.error-ward').html('Chọn xã/phường/thị trấn');
          $('.error-ward').css('display','block');
          flag = true;
      }
      else
      {
          $('.error-ward').html('');
          $('.error-ward').css('display','none');
      }

    //Check address
    if($('#address').val() == '')
    {
        $('.error-address').html('Nhập địa chỉ');
        $('.error-address').css('display','block');
        flag = true;
    }
    else
    {
        $('.error-address').html('');
        $('.error-address').css('display','none');
    }
    
    // $('#btn-cart').prop('disabled',true);
    
    if(flag)
    {
        e.preventDefault();
    }
    else
    {
        $("#btn-cart").attr("disabled", "disabled");
        $('button').prop('disabled',true);
    }
})

function formatNumber(n)
{
    return String(parseInt(n)).replace(/(.)(?=(\d{3})+$)/g,'$1,');
}
function formatNumber2(n,m)
{
    return String(parseInt(n) * parseInt(m)).replace(/(.)(?=(\d{3})+$)/g,'$1,');
}


$('#form-discount').submit(function(e){
    let discount = $('#input-discount-checkout').val();
    e.preventDefault();
    $.ajax({
        url: location.origin + '/check-discount',
        method: 'get',
        data: {
            'discount': discount,
        },
        success:function(res)
        {
            // console.log((res));
            if(!res.error)
            {
                // console.log(res.data.data);
                let html = '';
                $('.success-discount').html(res.message);
                $('.success-discount').css('display','block');
                $('.error-discount').html('');
                $('.error-discount').css('display','none');
                $('#discount').val(res.data.id);
                
                html += '<div class="box_discount_code">';
                html += '<div class="discount_code">' + res.data.discount_code + '<span class="close_discount cursor" onclick="removeDiscount()" aria-hidden="true">&times;</span></div>';
                html += '</div>';
                $('.container-discount').html(html);
                $('#input-discount-checkout').val('');

                //    return;
                let cart = res.cart;
                let total_sale = 0;
                let total = 0;
                let amount = 0;
                // console.log(res);
                if(cart.length > 0 )
                {
                    $.each(cart,function(key,value)
                    {
                        total = 0;
                        if(typeof value.discount !== "undefined")
                        {
                            let sale_price = value.price * value.qty *value.discount.sale_percent /100 ;
                            total_sale += sale_price;
                            total += value.price * value.qty - sale_price;
                           
                            $('#checkout_price_' + value.id + '_' + value.options.color.id + '_' +  value.options.size.id ).html(formatNumber(value.price * value.qty - sale_price) + '&#8363;');
                        }
                        else
                        {
                            total += value.price * value.qty;
                        }
                        // console.log(total);
                        amount += total;
                    })

                    //Get ship
                    let ship = 0;
                    // console.log();
                    $.ajax({
                        url: location.origin + '/api/check-ship',
                        method: 'get',
                        data: {
                            'id_province': $('#province').val()
                        },
                        success:function(data)
                        {
                            if(!data.error)
                            {
                                ship = data.data.fee;
                                $('#total_price').html(formatNumber(amount) + '&#8363;');
                                $('#total_sale_price').html('- ' + formatNumber(total_sale) + '&#8363;');
                                $('#ship_price').html(formatNumber(ship) + '&#8363;');
                                $('#payment_price').find('h4').html(formatNumber(parseInt(ship) + parseInt(amount)) + '&#8363;');
                            }
                            else
                            {
                                $('#total_sale_price').html('-' + formatNumber(total_sale) + '&#8363;');
                                $('#total_price').html(formatNumber(amount) + '&#8363;');
                                $('#payment_price').find('h4').html(formatNumber(parseInt(ship) + parseInt(amount)) + '&#8363;');
                            }
                        }
                    })                    
                }
            }
            else
            {
                $('#input-discount-checkout').val('');
                $('.error-discount').html(res.message);
                $('.error-discount').css('display','block');
                $('.success-discount').html('');
                $('.success-discount').css('display','none');
                $('#discount').val('');
            }
        }
    });
})
function removeDiscount()
{
    $('.container-discount').html('');
    $('.success-discount').html('');
    $('.success-discount').css('display','none');
    $('#discount').val('');
    // console.log(1);
    $.ajax({
        url: location.origin + '/api/remove-discount',
        method: 'get',
        success:function(res)
        {
            if(!res.error)
            {
                let total = 0;
                if(res.data.length > 0)
                {
                    $.each(res.data,function(key,value){
                        total += value.price * value.qty;
                        // console.log(value);
                        let checkout_id = 'checkout_price_' + value.id + '_' + value.options.color.id + '_' + value.options.size.id;
                        
                        $('#' + checkout_id).html(formatNumber2(value.price,value.qty) + '&#8363;');
                    });
                }

                //Get ship
                let ship = 0;
                $.ajax({
                    url: location.origin + '/api/check-ship',
                    method: 'get',
                    data: {
                        'id_province': $('#province').val()
                    },
                    success:function(data)
                    {
                        if(!data.error)
                        {
                            ship = data.data.fee;
                            $('#total_price').html(formatNumber(total) + '&#8363;');
                            $('#total_sale_price').html('- 0&#8363;');
                            $('#ship_price').html(formatNumber(ship) + '&#8363;');
                            $('#payment_price').find('h4').html(formatNumber(parseInt(ship) + parseInt(total)) + '&#8363;');
                        }
                        else
                        {
                            $('#total_price').html(formatNumber(total) + '&#8363;');
                            $('#total_sale_price').html('- 0&#8363;');
                            $('#ship_price').html(formatNumber(ship) + '&#8363;');
                            $('#payment_price').find('h4').html(formatNumber(parseInt(ship) + parseInt(total)) + '&#8363;');
                        }
                    }
                })   
            }
        }
    });
}

$('#province').change(function(){
    let id = $(this).val();

    $.ajax({
        url: location.origin + '/api/get-district',
        method: 'get',
        data: {
            'id': id,
        },
        success:function(res)
        {
            // console.log(res);
            if(!res.error)
            {
                let html = '<option value="">---Chọn quận/huyện---</option>';
                let total = 0;
                if(res.data != null && res.data.length > 0) 
                {
                    $.each(res.data,function(key,value){
                        html += '<option value="' + value.id + '">' + value.name_with_type + '</option>';
                    })
                }
                if(res.cart != null && res.cart.length > 0) 
                {
                    // console.log(res.cart);
                    $.each(res.cart,function(key,value){
                       
                        if(typeof value.discount != 'undefined')
                        {
                            total += parseInt(value.qty) * parseInt(value.price) - (parseInt(value.qty) * parseInt(value.price) * parseInt(value.discount.sale_percent) / 100);
                        }
                        else
                        {
                            total += parseInt(value.qty) * parseInt(value.price);
                        }
                        // console.log(key,value,total);
                    })
                }
                
                $('#ship_price').html(formatNumber(res.fee) + '&#8363;');
                $('#district').html(html); 
                $('#payment_price').find('h4').html(formatNumber(parseInt(res.fee) + parseInt(total)) + '&#8363;');
            }
            else
            {
                let total = 0;
                // console.log(res);
                if(res.cart != null) 
                {
                    $.each(res.cart,function(key,value){
                        // console.log(value);
                        total += parseInt(value.qty) * parseInt(value.price);
                    })
                }
                // console.log(total);
                $('#ship_price').html('0&#8363;');
                $('#payment_price').find('h4').html(formatNumber(total) + '&#8363;');
            }
        }
    })   
})

$('#district').change(function(){
    let id = $(this).val();
    // console.log(1);

    $.ajax({
        url: location.origin + '/api/get-ward',
        method: 'get',
        data: {
            'id': id,
        },
        success:function(res)
        {
            // console.log(res);
            let html = '<option value="">---Chọn xã/phường/thị trấn---</option>';
            if(res.data != null && res.data.length > 0)
            {
                $.each(res.data,function(key,value){
                    html += '<option value="' + value.id + '">' + value.name_with_type + '</option>';
                })
            }
            $('#ward').html(html);   
        }
    })   
})

function addDiscount(code)
{
    $('#input-discount-checkout').val(code);
}
function addAddress(street,ward,district,province)
{
    // console.log(street,ward,district,province);
    $('#province option').each(function() {
        // console.log($(this).val(),province);
        if($(this).val() == province) {
            $(this).prop("selected", true);
        }
    });
    $('#address').val(street);

    $.ajax({
        url: location.origin + '/api/get-address',
        method: 'get',
        data: {
            'province_id': province,
            'district_id': district,
        },
        success:function(res)
        {
            // console.log(res);
            if(res.data.district != null &&  res.data.district.length > 0)
            {
                let html = '<option value="">---Chọn quận/huyện---</option>';
                $.each(res.data.district,function(key,value)
                {
                    if(value.id == district)
                    {
                        html += '<option selected value="' + value.id + '">' + value.name_with_type + '</option>';
                    }
                    else
                    {
                        html += '<option value="' + value.id + '">' + value.name_with_type + '</option>';
                    }
                })
                $('#district').html(html);   
            }
           
            if(res.data.ward != null && res.data.ward.length > 0)
            {
                let html = '<option value="">---Chọn xã/phường/thị trấn---</option>';
                $.each(res.data.ward,function(key,value)
                {
                    if(value.id == ward)
                    {
                        html += '<option selected value="' + value.id + '">' + value.name_with_type + '</option>';
                    }
                    else
                    {
                        html += '<option value="' + value.id + '">' + value.name_with_type + '</option>';
                    }
                })
                $('#ward').html(html);  
            }

            let total = 0;
            if(res.cart != null && res.cart.length > 0) 
            {
                // console.log(res.cart);
                $.each(res.cart,function(key,value){
                    
                    if(typeof value.discount != 'undefined')
                    {
                        total += parseInt(value.qty) * parseInt(value.price) - (parseInt(value.qty) * parseInt(value.price) * parseInt(value.discount.sale_percent) / 100);
                    }
                    else
                    {
                        total += parseInt(value.qty) * parseInt(value.price);
                    }
                })
            }
            console.log(total);
            $('#ship_price').html(formatNumber(res.fee) + '&#8363;');
            $('#payment_price').find('h4').html(formatNumber(parseInt(res.fee) + parseInt(total)) + '&#8363;');
        }
    }) 

}

function deleteAddress(street,ward,district,province)
{
    // console.log(street,ward,district,province);
    $.ajax({
        url: location.origin + '/api/delete-address',
        method: 'post',
        data: {
            'province_id': province,
            'district_id': district,
            'ward_id': ward,
            'street':street,
            '_token':$('meta[name=csrf-token]').attr('content'),
        },
        success:function(res)
        {
            if(!res.error)
            {
                // console.log(res.data);
                if(res.data != null)
                {
                    let html = "";
                    if(res.data.length > 0)
                    {
                        $.each(res.data, function(key,value){
                            // console.log(value);
                            let street = value.street;
                            // console.log(String(value.street));
                            html += '<li class="li-discount">';
                            html += '<div class="box-li-discount">';    
                            html += '<span>';
                            html += '<input type="radio" name="check_discount" id="discount_'+value.id+'" class="item-discount">';
                            html += '<label for="discount_'+value.id+'">Địa chỉ '+(key+1)+'</label>';
                            html += '</span>';
                            html += '<button type="button" class="btn-close close discount-plus" aria-label="Close">';
                            html += '<span aria-hidden="true" onclick="deleteAddress(\''+ value.street + '\',' + value.ward.id + ',' + value.district.id + ',' + value.province.id + ')">&times;</span>';
                            html += '</button>';
                            html += '<span class="discount-plus" data-dismiss="modal" onclick="addAddress(\''+ value.street + '\',' + value.ward.id + ',' + value.district.id + ',' + value.province.id + ')"><i class="fa fa-plus-square"></i></span>';
                            html += '</div>';
                            html += '<div class="content-li-discount">';
                            html += '<div>' + value.street + ', '+ value.ward.name_with_type + ', ' + value.district.name_with_type + ', ' + value.province.name_with_type + '</div>';
                            html += '</div>';
                            html += '</li>';
                        })
                    }
                    else
                    {
                        html += '<li class="li-discount">Không có địa chỉ</li>';
                    }
                    $('#ul-address').html(html); 
                }
            }
        }
    }) 
}
