//Check select list
$('.check_all').click(function(){
    if($('input[name=check]').length == 0 )
    {
      return;
    }
    if($('.check_all')[0].checked == true)
    {
      $('input[name=check]').prop('checked', true);
      $('.btn-delete').prop('disabled', false);
    }
    else
    {
      $('input[name=check]').prop('checked', false);
      $('.btn-delete').prop('disabled', true);
    }
});

$('input[name=check]').click(function(){
  let check = $('input[name=check]');
  let checked = $('input[name=check]:checked');

  if(checked.length == 0)
  {
    $('.btn-delete').prop('disabled', true);
    $('.check_all').prop('checked', false);
    return;
  }

  if(checked.length != 0 && checked.length != check.length)
  {
    $('.check_all').prop('checked', false);
    $('.btn-delete').prop('disabled', false);
    return;
  }
  
  $('.check_all').prop('checked', true);
  $('.btn-delete').prop('disabled', false);
});

$('#modal-confirm').on('hidden.bs.modal', function (e) {
  $('.check_all').prop('checked', false);
  $('.check').prop('checked', false);
  $('.btn-delete').prop('disabled', true);
})

$('#confim').click(function(){
  $('#modal-confirm button').prop('disabled', true);
});

$(document).on("keydown","input[type=text],input[type=email],input[type=password], textarea" ,function(evt){
  var firstChar = $(this).val();
  if(evt.keyCode == 32 && firstChar == ""){
    return false;
  }
});
