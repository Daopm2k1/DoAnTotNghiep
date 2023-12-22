//Function active input radio 
function addClassActive(type)
{
    if($('.form-check').hasClass('active'))
    {
        $('.form-check').removeClass('active');
    }
    $('div.' + type).addClass('active');
    return;
}

//Function ready
$(document).ready(function()
{
    let type = ($("input[type='radio']:checked").val());
    if(type === 'product')
    {
        addClassActive(type);
    }
    if(type === 'category')
    {
        addClassActive(type);
    }
})

//Check checked radio input
$('input:radio[name="type_discount"]').change(function(){
    let type = $(this).val();

    $('.error-category').css('display','none');
    $('.error-product').css('display','none');

    if(type === 'category')
    {
        addClassActive(type);
        $('.show-product').html('');
        $('#list-id').val('');
        $('#list-product').val('');
        return;
    }
    addClassActive(type);
    $('.show-category').html('');
    $('#list-id-category').val('');
    $('#list-category').val('');
});

//Check checkbox product
$('input[name="product"').click(function() {
    if ($(this).is(':checked')) {   
        let list_id_product = $('#input-product').val();
        let list_name_product = $('#name-product').val();
        let id = $(this).data('id');
        let name = $(this).data('name');
        let temp = [];
        let temp_name = [];

        if(list_id_product != "")
        {
            temp = temp.concat(list_id_product.split(','));
        }
        if(list_name_product != "")
        {
            temp_name = temp_name.concat(list_name_product.split(','));
        }

        if($('label.product_' + id).hasClass('active-input'))
        {
            $('label.product_' + id).removeClass('active-input');

            if(temp.includes(id+""))
            {
                let index = temp.indexOf(id+"");
                if(index > -1)
                {
                    temp.splice(index,1);
                }
            }
            if(temp_name.includes(name))
            {
                let index = temp_name.indexOf(name);
                if(index > -1)
                {
                    temp_name.splice(index,1);
                }
            }
        }
        else
        {
            $('label.product_' + id).addClass('active-input');
            temp.push(id);
            temp_name.push(name);
        }
        $('#input-product').val(temp);
        $('#name-product').val(temp_name);
    }
});

//Search product in modal
$('#search-product').keyup(function(){
    let list = $('span.item-product label');
    let key_search = $('#search-product').val();
    $.each(list,function(key,value){
        let val = $(this).html();
        let index =  val.toLowerCase().indexOf(key_search.toLowerCase());

        if(index == -1)
        {
            $(this).css('display','none');
        }
        else
        {
            $(this).css('display','block');
        }
    })
});

//Check checkbox modal category
$('input[name="category"').click(function() {
    if ($(this).is(':checked')) {   
        let list_id_category = $('#input-category').val();
        let list_name_category = $('#name-category').val();
        let id = $(this).data('id');
        let name = $(this).data('name');
        let temp = [];
        let temp_name = [];

        if(list_id_category != "")
        {
            temp = temp.concat(list_id_category.split(','));
        }

        if(list_name_category != "")
        {
            temp_name = temp_name.concat(list_name_category.split(','));
        }

        if($('label.category_' + id).hasClass('active-input'))
        {
            $('label.category_' + id).removeClass('active-input');

            if(temp.includes(id+""))
            {
                let index = temp.indexOf(id+"");
                if(index > -1)
                {
                    temp.splice(index,1);
                }
            }

            if(temp_name.includes(name))
            {
                let index = temp_name.indexOf(name);
                if(index > -1)
                {
                    temp_name.splice(index,1);
                }
            }
        }
        else
        {
            $('label.category_' + id).addClass('active-input');
            temp.push(id);
            temp_name.push(name);
        }
        $('#input-category').val(temp);
        $('#name-category').val(temp_name);
    }
});

//Seach category modal
$('#search-category').keyup(function(){
    let list = $('span.item-category label');
    let key_search = $('#search-category').val();
    $.each(list,function(key,value){
        let val = $(this).html();
        let index =  val.toLowerCase().indexOf(key_search.toLowerCase());

        if(index == -1)
        {
            $(this).css('display','none');
        }
        else
        {
            $(this).css('display','block');
        }
    })
});

//Check validate sale price
$('#sale_price').keypress(function(e){
    let char = String.fromCharCode(e.which);
    if(!(/[0-9]/.test(char)))
    {
        e.preventDefault();
        return;
    }
    if($(this).val().length > 2)
    {
       e.preventDefault();
       return;
    }
});

//Click save product
$('#save-product').click(function(){
    let list = $('#name-product').val();
    let temp = [];
    let html = '';
    if(list != "")
    {
        temp = temp.concat(list.split(','));
    }

    if(temp.length > 0)
    {
        $.each(temp,function(key, value){
            html += '<div class="active-input item-product show">' + value + '</div>';
        })
    }
    $('#list-product').val($('#name-product').val());
    $('#list-id').val($('#input-product').val());
    $('.show-product').html(html);
    $('.error-product').css('display','none');
    $('#modal-product').modal('hide');
})

//Click save category
$('#save-category').click(function(){
    let list = $('#name-category').val();
    let temp = [];
    let html = '';
    if(list != "")
    {
        temp = temp.concat(list.split(','));
    }

    if(temp.length > 0)
    {
        $.each(temp,function(key, value){
            html += '<div class="active-input item-category show">' + value + '</div>';
        })
    }
    $('#list-category').val($('#name-category').val());
    $('#list-id-category').val($('#input-category').val());
    $('.show-category').html(html);
    $('.error-category').css('display','none');
    $('#modal-category').modal('hide');
})

//Modal product hide
$('#modal-product').on('hidden.bs.modal', function (e) {
    if($('.item-product').find('label').hasClass('active-input'))
    {
        $('#input-product').val('');
        $('#name-product').val('');
        $('.item-product').find('label').removeClass('active-input');
    }
    $('#search-product').val('');
    $('.item-product').find('label').css('display','block');
})

//Modal product show
$('#modal-product').on('shown.bs.modal', function (e) {
    let list_product = $('#list-product').val();
    let temp = [];
    let list_label = $('.item-product').find('label');

    $('#name-product').val($('#list-product').val());
    $('#input-product').val($('#list-id').val());

    if(list_product != "")
    {
        temp = temp.concat(list_product.split(','));
    }

    if(list_label.length > 0)
    {
        $.each(list_label,function(key,value){
            let value_product = $(this).html();
            if(temp.includes(value_product))
            {
               $(this).addClass('active-input');
            }
        })
    }
})

//Modal category hide
$('#modal-category').on('hidden.bs.modal', function (e) {
    if($('.item-category').find('label').hasClass('active-input'))
    {
        $('#input-category').val('');
        $('#name-category').val('');
        $('.item-category').find('label').removeClass('active-input');
    }
    $('#search-category').val('');
    $('.item-category').find('label').css('display','block');
})

//Modal product show
$('#modal-category').on('shown.bs.modal', function (e) {
    let list_product = $('#list-category').val();
    let temp = [];
    let list_label = $('.item-category').find('label');

    $('#name-category').val($('#list-category').val());
    $('#input-category').val($('#list-id-category').val());

    if(list_product != "")
    {
        temp = temp.concat(list_product.split(','));
    }

    if(list_label.length > 0)
    {
        $.each(list_label,function(key,value){
            let value_product = $(this).html();
            if(temp.includes(value_product))
            {
               $(this).addClass('active-input');
            }
        })
    }
})

//Change state_time
$('#start_date').change(function(){
    if($(this).val() == "")
    {
        $( "#end_date" ).prop( "disabled", true );
    }
    else
    {
        $( "#end_date" ).prop( "disabled", false );
    }
})

function isEmail(email) {
    let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }

// Click save discount
$('#form-discount').submit(function(e){
    let flag = false;

    //Check type discount
    if($('input[name="type_discount"]:checked').val() === "product")
    {
        if($('#list-id').val() == "")
        {
            $('.error-product').css('display','block');
            flag = true;
        }
        else
        {
            $('.error-product').css('display','none');
        }
    }
    else
    {
        if($('#list-id-category').val() == "")
        {
            $('.error-category').css('display','block');
            flag = true;
        }
        else
        {
            $('.error-category').css('display','none');
        }
    }

    //Check sale price
    if($('#sale_price').val() === "")
    {
        $('.error-sale_price').html('Nhập giá giảm');
        $('.error-sale_price').css('display','block');
        flag = true;
    }
    else if($('#sale_price').val() > 100)
    {
        $('.error-sale_price').html('Giá giảm phải nhỏ hơn 100');
        $('.error-sale_price').css('display','block');
        flag = true;
    }
    else
    {
        $('.error-sale_price').html('');
        $('.error-sale_price').css('display','none');
    }
    
    //check user submit
    if($('#user_apply').val() == "")
    {
        $('.error-user_apply').html('Chọn tài khoản giảm giá');
        $('.error-user_apply').css('display','block');
        flag = true;
    }
    else
    {
        $('.error-user_apply').html('');
        $('.error-user_apply').css('display','none');
    }
        

    //Check start date
    let date = new Date($('#start_date').val());
    let now = new Date();
    if($('#start_date').val() === "")
    {
        $('.error-start_date').html('Chọn ngày bắt đầu');
        $('.error-start_date').css('display','block');
        flag = true;
    }
    else if( date < now)
    {
        $('.error-start_date').html('Ngày bắt đầu phải ngày sau hôm nay');
        $('.error-start_date').css('display','block');
        flag = true;
    }
    else
    {
        $('.error-start_date').html('');
        $('.error-start_date').css('display','none');
    }

    //Check start date
    if($('#end_date').val() === "")
    {
        $('.error-end_date').html('Chọn ngày kết thúc');
        $('.error-end_date').css('display','block');
        flag = true;
    }
    else if($('#start_date').val() !== "")
    {
       
        let end_date = new Date($('#end_date').val());
        let date = new Date($('#start_date').val());
        // console.log(end_date == date ,end_date,date);
        if( end_date < now)
        {
            $('.error-end_date').html('Ngày kết thúc phải ngày sau hôm nay');
            $('.error-end_date').css('display','block');
            flag = true;
        }
        else if( end_date <= date)
        {
            $('.error-end_date').html('Ngày kết thúc phải sau ngày bắt đầu');
            $('.error-end_date').css('display','block');
            flag = true;
        }
        else
        {
            $('.error-end_date').html('');
            $('.error-end_date').css('display','none');
        }
    }
    // return;
    // console.log(1);
    if(flag)
    {
        e.preventDefault();
    }
    else
    {
        $('button[type="submit"]').prop('disabled', true);
    }
})

//Not keypress input type date
$('#start_date,#end_date').keypress(function(e){
    e.preventDefault();
})

$('#discount_code').keypress(function(e){
    if(e.which == 32)
    {
        e.preventDefault();
    }
})

$('#discount_code').keyup(function(e){
    if(e.which != 32)
    {
       $(this).val($(this).val().toUpperCase());
    }
});

function clickCheckBoxUser(id)
{
    let id_input =  $('#user_' + id);
    let email = id_input.data('email');

    let list_id = $('#input-user').val();
    let list_email = $('#name-user').val();
    let temp_id = [];
    let temp_email = [];

    if(list_id != "")
    {
        temp_id = temp_id.concat(list_id.split(','));
    }
    if(list_email != "")
    {
        temp_email = temp_email.concat(list_email.split(','));
    }

    if(id_input.is(':checked')) {
        if(!temp_id.includes(id + ""))
        {
            temp_id.push(id);
        }
        if(!temp_email.includes(email))
        {
            temp_email.push(email);
        }
    }
    else
    {
        if(temp_id.includes(id+""))
        {
            let index = temp_id.indexOf(id+"");
            if(index > -1)
            {
                temp_id.splice(index,1);
            }
        }

        if(temp_email.includes(email))
        {
            let index = temp_email.indexOf(email);
            if(index > -1)
            {
                temp_email.splice(index,1);
            }
        }
    }
    $('#input-user').val(temp_id);
    $('#name-user').val(temp_email);

    let checked = $('.item-input-user:checked');
    let unchecked = $('.item-input-user');
    // console.log(checked.length,unchecked.length);
    if(checked.length == unchecked.length)
    {
        $('#user_all').prop('checked',true);
    }
    else
    {
        $('#user_all').prop('checked',false);
    }
}

$('#search-user').keyup(function(){
    let key = $('#search-user').val();
    let list_id = $('#input-user').val();
    // console.log(key);
    $.ajax({
        url: location.origin + '/api/search-user-discount',
        method: "get",
        data:{
            'key':key
        },
        success:function(res){
            // console.log(res);
            if(!res.error)
            {   
                let html = '';
                html += '<li class="item-user">';
                html += '<div class="i-checks">';
                html += '<input id="user_all" type="checkbox" onclick="clickCheckBoxUserAll()" class="form-control-custom">'; 
                html += '<label class="cursor" id="label_all"  for="user_all">Chọn tất cả</label>';
                html += '</div></li>';
                if(res.data.length > 0)
                {
                    $.each(res.data, function(key,value){
                        let email = value.email + '';
                        html += '<li class="item-user">';
                        html += '<div class="i-checks">';
                        if(list_id.includes(value.id+""))
                        {
                            html += '<input checked id="user_' + value.id + '" type="checkbox" data-email="' + value.email + '" onclick="clickCheckBoxUser(' + value.id + ')" class="item-input-user form-control-custom item-user">'; 
                        } 
                        else
                        {
                            html += '<input id="user_' + value.id + '" type="checkbox" data-email="' + value.email + '" onclick="clickCheckBoxUser(' + value.id + ')" class="item-input-user form-control-custom item-user">'; 
                        }
                        html += '<label class="cursor" id="label_"' + value.id + '"  for="user_' + value.id + '">' + value.email + ' - ' + value.username + '</label>';
                        html += '</div></li>';
                    })
                }
                $('#ul-user').html(html);
            }
        }
    });
})

$('#save-user').click(function(){
    let list_id = $('#input-user').val();
    let list_email = $('#name-user').val();
    let temp = [];
    let html = '';
    temp = temp.concat(list_email.split(','));

    $('#user_apply').val(list_id);
    $('#name_apply').val(list_email);
    if(temp.length > 0)
    {
        $.each(temp,function(key,value){
            // console.log(value);
            if(value != '')
            {
                html += '<div class="active-input item-product show">' + value + '</div>';
            }
        })
    }
    
    if(html != '')
    {
        $('.show-user').html(html);
    }
    else
    {
        $('.show-user').html('');
    }

    $('#modal-email').modal('hide');
})

$('#modal-email').on('shown.bs.modal', function (e) {
    $('#input-user').val($('#user_apply').val());
    $('#name-user').val($('#name_apply').val());
    let key = $('#search-user').val();
    let list_id = $('#input-user').val();

    $.ajax({
        url: location.origin + '/api/search-user-discount',
        method: "get",
        success:function(res){
            // console.log(res);
            if(!res.error)
            {   
                let html = '';
                let list_id = $('#input-user').val();
                let temp_id = [];
                let flag = false;
                if(list_id != "")
                {
                    temp_id = temp_id.concat(list_id.split(','));
                }
                

                if(res.data.length > 0)
                {
                    $.each(res.data, function(key,value){
                        if(!temp_id.includes(value.id + ""))
                        {
                            flag = true;
                        }
                    })
                    
                    html += '<li class="item-user">';
                    html += '<div class="i-checks">';
                    if(!flag)
                    {
                        html += '<input id="user_all" checked type="checkbox" onclick="clickCheckBoxUserAll()" class="form-control-custom">';
                    } 
                    else
                    {
                        html += '<input id="user_all" type="checkbox" onclick="clickCheckBoxUserAll()" class="form-control-custom">';
                    }
                    html += '<label class="cursor" id="label_all"  for="user_all">Chọn tất cả</label>';
                    html += '</div></li>';
                    $.each(res.data, function(key,value){
                        let email = value.email + '';
                        html += '<li class="item-user">';
                        html += '<div class="i-checks">';
                        if(temp_id.includes(value.id+""))
                        {
                            html += '<input checked id="user_' + value.id + '" type="checkbox" data-email="' + value.email + '" onclick="clickCheckBoxUser(' + value.id + ')" class="item-input-user form-control-custom item-user">'; 
                        } 
                        else
                        {
                            html += '<input id="user_' + value.id + '" type="checkbox" data-email="' + value.email + '" onclick="clickCheckBoxUser(' + value.id + ')" class="item-input-user form-control-custom item-user">'; 
                        }
                        html += '<label class="cursor" id="label_"' + value.id + '"  for="user_' + value.id + '">' + value.email + ' - ' + value.username + '</label>';
                        html += '</div></li>';
                    })
                }
                $('#ul-user').html(html);
            }
        }
    });
})

$('#modal-email').on('hidden.bs.modal', function (e) {
    $('#input-user').val('');
    $('#name-user').val('');
    // $('.item-user').prop('checked',false);
})

function clickCheckBoxUserAll()
{
    if($('#user_all').is(':checked'))
    {
        $('.item-input-user ').prop('checked',true);
        $.ajax({
            url: location.origin + '/api/search-user-discount',
            method: "get",
            success:function(res){
                if(!res.error)
                {   
                    if(res.data.length > 0)
                    {
                        let list_id = [];
                        let list_name = [];

                        $.each(res.data,function(key,value){
                            list_id.push(value.id);
                            list_name.push(value.email);
                        })
                        $('#input-user').val(list_id);
                        $('#name-user').val(list_name);
                    }
                }
            }
        })
    }
    else
    {
        $('.item-input-user').prop('checked',false);
        $('#input-user').val('');
        $('#name-user').val('');
    }
}