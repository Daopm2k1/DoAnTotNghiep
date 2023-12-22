$(document).ready(function() {
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
        event.preventDefault();
        return false;
        }
    });
});

function formatNumber(n)
{
    return String(parseInt(n)).replace(/(.)(?=(\d{3})+$)/g,'$1,');
}
function formatNumber2(n,m)
{
    return String(parseInt(n) * parseInt(m)).replace(/(.)(?=(\d{3})+$)/g,'$1,');
}

function clickDelete(id,color,size)
{
    let data = {
        'product_id': id,
        'color_id': color,
        'size_id': size,
        '_token': $('meta[name=csrf-token]').attr('content'),
    };
    let html = '';

    $.ajax({
        url: location.origin + '/delete-cart',
        method: 'post',
        data: data,
        success:function(res)
        {
            // console.log(res);
            // return;
            if(!res.error)
            {
                if(res.data.length == 0)
                {
                    html += '<div class="container">';
                    html += '<div class="row">';
                    html += '<div class="col-12">';
                    html += '<p class="message-cart">Giỏ hàng của bạn đang trống</p>';
                    html += '</div></div></div>';
                    $('#container-cart').html(html);
                    $('#do_action').html('');
                    $('.box-count-cart').html('');
                }
                else
                {
                    let href = location.origin;
                    let total = 0;
                    let count = 0;
                    $.each(res.data,function(key,value)
                    {
                        ++count;
                        total += parseInt(value.price) * parseInt(value.qty);
                        html += '<tr><td class="cart_product">';
                        html += '<img class="img_cart" src="' + href + value.path +'" alt="">';
                        html += '</td><td class="cart_description">';
                        html += '<h4><a href="#">' + value.name + '</a></h4>';
                        html += '<p>Mã sản phẩm: '+ value.product + '</p>';
                        html += '<p>' + value.options.color.name + '/' + value.options.size.name + '</p></td>';
                        html += '<td class="cart_price">';
                        html += '<p>' + formatNumber(value.price) + '&#8363;</p></td>';
                        html += '<td class="cart_quantity"><div class="cart_quantity_button"><form>';
                        html += '<input style="margin-right: 3px;" class="quantity input-cart" id="cart_' + value.id + '_' + value.options.color.id + '_' +  value.options.color.id + '" type="number" min="1" max="1000" name="quantity" value="' + value.qty + '" autocomplete="off" size="2">';
                        html += '<input onclick="clickUpdate(' + value.id + ',' + value.options.color.id + ',' + value.options.size.id + ')" type="button" value="Cập nhật" name="update-qty" class="btn-update btn btn-custom btn-default btn-sm">';
                        html += '</form onsubmit="return false;"></div></td><td class="cart_total">';
                        html += '<p class="cart_total_price" id="total_' + value.id + '_' + value.options.color.id + '_' +  value.options.color.id + '">' + formatNumber2(value.price,value.qty) + '&#8363;</p>';
                        html += '</td><td class="cart_delete">';
                        html += '<a onclick="clickDelete(' + value.id + ',' + value.options.color.id + ',' + value.options.size.id + ')" class="cart_quantity_delete"><i class="fa fa-times"></i></a>';
                        html += '</td></tr>';
                    });
                    if(count > 0)
                    {
                        $('.count-cart').html(count);
                    }                    
                    $('#data-table').html(html);
                    $('#total_cart').html(formatNumber(total)+'&#8363;');
                    // location.reload();
                }
            }
            else
            {
                swal({
                    title: "Thông báo",
                    text:  res.message,
                    type: 'error',
                    confirmButtonClass: "btn-danger",
                },
                function() {

                });
            }
        }
    });
}

$('.input-cart').keypress(function(e){
    let char = String.fromCharCode(e.which);
    if(!(/[0-9]/.test(char)) || $(this).val().length > 10)
    {
      e.preventDefault();
    }
    
    var firstChar = $(this).val();
    if(e.keyCode == 48 && firstChar == ""){
        e.preventDefault();
    }
  });

function clickUpdate(id,color,size)
{
    let product_id =  id;
    let color_id = color;
    let size_id = size;
    let _token = $('meta[name=csrf-token]').attr('content');
    let quantity_id = 'cart_' + product_id + "_" + color_id + "_" + size_id;
    let quantity = parseInt($('#' + quantity_id).val());

    let total_id = 'total_' + product_id + "_" + color_id + "_" + size_id;
    if(quantity == "")
    {
        alert('Vui lòng nhập số lượng');
        return;
    }

    $.ajax({
        url: location.origin + '/update-cart',
        method: 'post',
        data: {
            'product_id': product_id,
            'color_id': color_id,
            'size_id': size_id,
            'quantity': quantity,
            '_token': _token,
        },
        success:function(res)
        {
            // console.log(res);
            if(!res.error)
            {
                swal({
                    title: "Thông báo",
                    text:  res.message,
                    type: 'success',
                    confirmButtonClass: "btn-success",
                },
                function() {

                });
                let total = 0;
                // console.log(res.data);
                $.each(res.data,function(key,value)
                {
                    total += parseInt(value.price) * parseInt(value.qty);
                });
                // console.log(formatNumber(total));
                $('#total_cart').html(formatNumber(total) + '&#8363;');
                $('#'+quantity_id).val(res.value.qty);
                $('#'+total_id).html(formatNumber2(res.value.qty,res.value.price) + '&#8363;');
            }
            else
            {
                swal({
                    title: "Thông báo",
                    text:  res.message,
                    type: 'warning',
                    confirmButtonClass: "btn-warning",
                },
                function() {

                });
            }
        }
    });
}
