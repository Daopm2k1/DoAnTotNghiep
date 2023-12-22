$('#form-user').submit(function(e){
  let flag = false;
    //check username
    if($('#username').val() == '')
    {
      $('.error-username').html('Nhập tài khoản');
      $('.error-username').css('display','block');
      flag = true;
    }
    else
    {
      $('.error-username').html('');
      $('.error-username').css('display','none');
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

    //Check date of birth
    if($('#date_of_birth').val() != '')
    {
      let date = $('#date_of_birth').val();
      let d1 = new Date(date);
      let d2 = new Date();

      if(!(d1.getTime() < d2.getTime()))
      {
        $('.error-date_of_birth').html('Ngày sinh không phải sau hôm nay');
        $('.error-date_of_birth').css('display','block');
          flag = true;
        }
      else
      {
        $('.error-date_of_birth').html('');
        $('.error-date_of_birth').css('display','none');
      }
    }

    //Check phone
    if(($('#phone').val() != '')) {
      if($('#phone').val().length != 11 && $('#phone').val().length != 10) {
        // console.log( $('#phone').val().length);
        $('.error-phone').html('Số điện thoại phải có 10 hoặc 11 số');
        $('.error-phone').css('display','block');
        flag = true;
      } else {
        $('.error-phone').html('');
        $('.error-phone').css('display','none');
      }
    }
    
    if(flag){
      e.preventDefault()
    }
    else
    {
      $('button[type="submit"]').attr('disabled','disabled');
    }
  });

  // Base 64 image
  function readURL(input, idImg) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(idImg).attr("src", e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
  }

  //Show Image
  $("#file").change(function () {
      readURL(this, "#ImgPre");
  });

  // check Email
 
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