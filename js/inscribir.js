$(document).on('ready' , function(){
    $('#search_alumno_form').on('submit' , function(event){
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status === "MSG") {
                    alerts(data.type, data.message, '');
                }else if(data.status === "OK"){
                    var html = "";
                    for(var i = 0 ; i < data.alumnos.length ; i++){
                        html += '<tr>';
                        html += '<td>' + data.alumnos[i].nombre + '</td>';
                        html += '<td>' + data.alumnos[i].paterno + '</td>';
                        html += '<td>' + data.alumnos[i].materno + '</td>';
                        html += '<td>' + data.alumnos[i].nacimiento + '</td>';
                        html += '<td><a href="'+ base_url +'admin/inscribir/get_alumno/' + data.alumnos[i].id + '">Ir</a></td>';
                        html += '</tr>';
                    }
                    $('#table_alumnos_content table tbody').html(html);
                    $('#table_alumnos_content').removeClass('hide');
                }
            }
        });
    });
});