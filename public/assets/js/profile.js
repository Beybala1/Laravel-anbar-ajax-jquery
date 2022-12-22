$(function(){
    $("#main_form").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url:$(this).attr('action'),
            method:$(this).attr('method'),
            data:new FormData(this),
            processData:false,
            dataType:'json',
            contentType:false,
            beforeSend:function(){
                $(document).find('span.error-text').text('');
            },
            success:function(data){
                if(data.status == 0){
                  $.each(data.error, function (prefix, val) {
                      $('span.' + prefix + '_error').text(val[0]);
                  });
                }else if(data.status == 2){
                  $('.password_error').text(data.msg);
                }else if(data.status == 3){
                  $('.password_new_error').text(data.msg);
                }else if(data.status == 4){
                  $('.password_confirmation_error').text(data.msg);
                }
                else{
                  $('#main_form')[0].reset();
                  Swal.fire({
                    title: data.msg,
                    icon: 'success',
                    confirmButtonText: 'Geri dön',
                    showCloseButton: true
                  })
                }
            }
        });
    });
  });
  