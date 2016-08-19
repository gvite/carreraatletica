$(document).on('ready' , function(){
    $('.panel-talleres-cambio').on('click' , function(){
        $('.panel-talleres-cambio').removeClass('panel-primary');
        $(this).addClass('panel-primary');
        $('#taller_new_input').val($(this).data('id'));
    });
    $('#form_cambio').on('submit' , function(event){
        event.preventDefault();
        if($('#taller_new_input').val() != ''){
            $.ajax({
                url: base_url + $(this).attr('action'),
                data: $(this).serialize(),
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    if (data.status === "MSG") {
                        alerts(data.type, data.message, '');
                    }else if(data.status === "OK"){
                        window.location = base_url + 'admin/validacion/get_baucher/' + data.folio;
                    }
                }
            });
        }else{
            alerts('warning', 'Selecciona un taller', '');
        }
    });
});