$(document).on('ready' , function(){
    get_lista_talleres($('#lista_semestre .panel-heading h3.actual').data('id'));
    $('#lista_semestre .panel-heading .pull-left').on('click' , function(){
        var index = $('#lista_semestre .panel-heading h3.actual').data('index');
        if(index > 1){
            $('#lista_semestre .panel-heading h3').removeClass('actual');
            $('#lista_semestre .panel-heading h3').hide();
            $('#lista_semestre .panel-heading h3.title_' + (parseInt(index) -1 )).addClass('actual');
            $('#lista_semestre .panel-heading h3.title_' + (parseInt(index) -1 )).show();
            get_lista_talleres($('#lista_semestre .panel-heading h3.title_' + (parseInt(index) -1 )).data('id'));
        }
    });
    $('#lista_semestre .panel-heading .pull-right').on('click' , function(){
        var index = $('#lista_semestre .panel-heading h3.actual').data('index');
        if(index < $('#lista_semestre .panel-heading').data('count')){
            $('#lista_semestre .panel-heading h3').removeClass('actual');
            $('#lista_semestre .panel-heading h3').hide();
            $('#lista_semestre .panel-heading h3.title_' + (parseInt(index) + 1 )).addClass('actual');
            $('#lista_semestre .panel-heading h3.title_' + (parseInt(index) + 1 )).show();
            get_lista_talleres($('#lista_semestre .panel-heading h3.title_' + (parseInt(index) + 1 )).data('id'));
        }
    });
});
function get_lista_talleres(semestre_id){
    if(semestre_id !== null){
        $.ajax({
            url: base_url + 'admin/listas/get_lista_talleres/' + semestre_id,
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if (data.status === "OK") {
                    $('#lista_semestre .panel-body').html(data.content);
                }else{
                    alerts(data.type , data.message);
                }
            }
        });
    }
}