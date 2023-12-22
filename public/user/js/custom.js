
 // check Email
function isEmail(email) {
    let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

$( "#form-contact" ).submit(function( e ) {
    // console.log(1);
    e.preventDefault();
    $('.error-name').css('display','none');
    $('.error-email').css('display','none');
    $('.error-content').css('display','none');
    let flag = false;
    let name = $('#contact-name').val();
    let email = $('#contact-email').val();
    let content = $('#contact-content').val();
    let _token = $('meta[name="csrf-token"]').attr('content');

    //check name
    if(name == "")
    {
        $('.error-name').html('Vui lòng nhập họ và tên');
        $('.error-name').css('display','block');
        flag = true;
    }
    else
    {
        $('.error-name').html('');
        $('.error-name').css('display','none');
    }

    //check email
    if(email == "")
    {
        $('.error-email').html('Vui lòng nhập email');
        $('.error-email').css('display','block');
        flag = true;
    }
    else if(!isEmail($('#contact-email').val()))
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

    //check nội dung
    if(content == "")
    {
        $('.error-content').html('Vui lòng nhập nội dung');
        $('.error-content').css('display','block');
        flag = true;
    }
    else
    {
        $('.error-content').html('');
        $('.error-content').css('display','none');
    }

    $.ajax({
        url: location.origin + '/opinion/add',
        method: 'POST',
        data:{
            name:name,
            email:email,
            content:content,
            _token:_token
        },
        success:function(data){
            console.log(data);
            if(!data.error)
            {
                $('#contact-name').val('');
                $('#contact-email').val('');
                $('#contact-content').val('');
            swal({
                    title: "Đóng góp ý kiến thành công",
                    text: "Cảm ơn bạn đã đóng góp ý kiến",
                    type:'success',
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Đóng",
                }),
                function(){
                }
            }
            else
            {
                $.each(data.message,function(key,value){
                    $('.error-'+key).html(value);
                    $('.error-'+key).css('display','block');
                })
            }
        }
    })
        
});

$(document).on("keydown","input[type=text],input[type=email],input[type=password],textarea" ,function(evt){
    var firstChar = $(this).val();
    if(evt.keyCode == 32 && firstChar == ""){
      return false;
    }
  });