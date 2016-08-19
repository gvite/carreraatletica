$(document).on('ready' , function(){
    $('#registro_form #type_user').on('change' , function(){
        $('.datos_usuario_tipo').hide();
        $('.datos_usuario_tipo input[type="text"]').val('');
        switch($(this).val()){
            case '2':
                $('#registro_form #label_ingreso').html('A&ntilde;o de ingreso');
                $('#datos_alumnos').show();
                break;
            case '3':
                $('#registro_form #label_ingreso').html('A&ntilde;o de egreso');
                $('#datos_alumnos').show();
                break;
            case '4':
                $('#datos_trabajador').show();
                break;
            case '5':
                $('#datos_externo').show();
                break;
        }
    });
    $('#registro_form').on('submit' , function(event){
        event.preventDefault();
        $('#pass_input').val($.md5($('#pass_input_aux').val()));
        $('#repass_input').val($.md5($('#repass_input_aux').val()));
        $.ajax({
            url:$(this).attr('action'),
            data:$(this).serialize(),
            type: 'POST',
            dataType:'json',
            success:function(data){
                if(data.status === "MSG"){
                    if(data.type === 'success'){                        
                        html = '<a data-events="0" class="btn btn-success" data-id="' + data.baucher + '" href="' + base_url + 'alumnos/inscripcion/get_pdf/' + data.baucher + '/' + data.usr + '" target="_blank">Imprimir</a>';
                        $("#registro_exito #anchor").html(html);
                        $('#registro_exito').modal('show');
                    }else{
                        alerts(data.type , data.message);
                    }
                }
            }
        });
    });
    $("#nacimiento_user").datetimepicker({
        useCurrent:false,
        locale:'es',
        format: 'DD-MM-YYYY'
    });
});
function actualiza_pagina(){
    window.location.href = base_url + 'inicio.jsp';
}