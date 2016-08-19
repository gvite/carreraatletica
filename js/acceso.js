$(document).on('ready' , function(){
    $('#form_login').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: 'pass=' + $.md5($('#password_input').val()) + '&user=' + $('#form_login #user_input').val(),
            dataType: 'json',
            success: function(data) {
                if (data.status === "OK") {
                    window.location.href = base_url;
                } else {
                    alerts(data.type, data.message);
                    $('#password_input').val('');
                }
            }
        });
    });
    $('#recuperar_contrasenia').on('click' , function(event){
        event.preventDefault();
        $('#form_login_modal').modal('hide');
        $('#form_recuperar_contra_modal').modal('show');
    });
    $('#form_recuperar_contra').on('submit' , function(event){
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data) {
                alerts(data.type, data.message);
                if (data.type === "success") {
                    $('#form_recuperar_contra_modal').modal('hide');
                }
            }
        });
    });
});