$(document).on('ready' , function(){
    $('#actualiza_form').on('submit' , function(event){
        event.preventDefault();
        $.ajax({
            url:$(this).attr('action'),
            data:$(this).serialize(),
            type: 'POST',
            dataType:'json',
            success:function(data){
                if(data.status === "MSG"){
                    alerts(data.type , data.message);
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