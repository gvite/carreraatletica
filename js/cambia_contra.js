$(document).on('ready', function () {
    $('#cambia_contra_form').on('submit', function (event) {
        event.preventDefault();
        var data = 'pass=' + $.md5($('#pass_input_aux').val()) + '&';
        data += 'repass=' + $.md5($('#repass_input_aux').val()) + '&';
        data += 'pass_ant=' + $.md5($('#pass_ant_input_aux').val()) + '&';
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {
                if(data.status === 'OK'){
                    window.location.href = base_url + 'acceso/cambia_contra/contrasenia_exito.jsp';
                }else{
                    alerts(data.type , data.message);
                }
            }
        });
    });
});