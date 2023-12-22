$('#price').keypress(function(e){
    let char = String.fromCharCode(e.which);
    if(!(/[0-9]/.test(char)))
    {
        e.preventDefault();
    }
    if($('#price').val().length >= 11)
    {
        e.preventDefault();
    }
});

$('#form-product').submit(function(e){
    let flag = false;
    let edit = $('#form-product').find('#create').data('edit');

    let ck = CKEDITOR.instances.description.getData();
    $('#description').val(ck);

    //check name
    if($('#name').val() == '')
    {
        $('.error-name').html('Nhập tên sản phẩm');
        $('.error-name').css('display','block');
        flag = true;
    }
    else
    {
        $('.error-name').html('');
        $('.error-name').css('display','none');
    }

    //check price
    if($('#price').val()=='')
    {
        $('.error-price').html('Nhập giá sản phẩm');
        $('.error-price').css('display','block');
        flag = true;
    }
    else if($('#price').val().length < 4)
    {
        $('.error-price').html('Giá phải lớn hơn 1000');
        $('.error-price').css('display','block');
        flag = true;
    }
    else
    {
        $('.error-price').html('');
        $('.error-price').css('display','none');
    }

    //Check description
    if($('#description').val() == '')
    {
        $('.error-description').html('Nhập mô tả sản phẩm');
        $('.error-description').css('display','block');
        flag = true;
    }
    else
    {
        $('.error-description').html('');
        $('.error-description').css('display','none');
    }

    //Check file
    // console.log(edit);
    if(edit < 0)
    {
        if($('#file').val() == '')
        {
            $('.error-image').html('Chọn ảnh');
            $('.error-image').css('display','block');
            flag = true;
        }
        else
        {
            $('.error-image').html('');
            $('.error-image').css('display','none');
        }
    }

    if(flag)
    {
        e.preventDefault();
    }
    else
    {
        $('.btn-disabled').prop('disabled', true);
    }
  });

$("#input-tag").keypress(function(event){
    var ew = event.which;
    if(ew == 32)
        return true;
    if(48 <= ew && ew <= 57)
        return true;
    if(65 <= ew && ew <= 90)
        return true;
    if(97 <= ew && ew <= 122)
        return true;
    event.preventDefault();
    return;
});