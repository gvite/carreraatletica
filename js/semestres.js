$(document).on('ready', function () {
    $('#btn_add_semestres_modal').on('click', function () {
        $('#semestre_form input').val('');
        $('#semestre_form').attr('action', base_url + 'admin/semestres/insert');
    });
    $('#semestre_form .fecha_input').datetimepicker({
        useCurrent:false,
        locale:'es',
        format: 'DD-MM-YYYY'
    });
    $('#semestre_form .fecha_time_input').datetimepicker({
        locale:'es',
        format: 'DD-MM-YYYY HH:mm:ss'
    });
    $('#semestre_form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status === "MSG") {
                    if (data.type === 'success') {
                        if (data.tipo == 1) {
                            $('#semestres_table tbody tr').each(function () {
                                if ($(this).data('id') == data.semestre.id) {
                                    $(this).children('td').eq(0).text(data.semestre.semestre);
                                    $(this).children('td').eq(1).text(data.semestre.ini_sem);
                                    $(this).children('td').eq(2).text(data.semestre.fin_sem);
                                    $(this).children('td').eq(3).text(data.semestre.ini_insc);
                                    $(this).children('td').eq(4).text(data.semestre.fin_insc);
                                    $(this).children('td').eq(5).text(data.semestre.fin_validacion);
                                }
                            });
                        } else {
                            $('.no_registros').remove();
                            var html = '<tr data-id="' + data.semestre.id + '" data-events="0">';
                            html += '<td>' + data.semestre.semestre + '</td>';
                            html += '<td>' + data.semestre.ini_sem + '</td>';
                            html += '<td>' + data.semestre.fin_sem + '</td>';
                            html += '<td>' + data.semestre.ini_insc + '</td>';
                            html += '<td>' + data.semestre.fin_insc + '</td>';
                            html += '<td>' + data.semestre.fin_validacion + '</td>';
                            html += '<td><button class="btn btn-sm btn-editar">Editar</button><button class="btn btn-sm btn-eliminar">Eliminar</button></td>';
                            html += '</tr>';
                            $('#semestres_table tbody').append(html);
                            add_events_semestres();
                        }
                        $('#add_semestre_modal').modal('hide');
                    }
                    alerts(data.type, data.message, '');
                }
            }
        });
    });
    add_events_semestres();
});
function add_events_semestres() {
    $('#semestres_table tbody tr').each(function () {
        if ($(this).data('events') == 0) {
            $(this).data('events', 1);
            $(this).find('.btn-editar').on('click', function () {
                var $tr = $(this).closest('tr');
                $('#semestre_form').attr('action', base_url + 'admin/semestres/update/' + $tr.data('id'));
                $('#semestre_form #nombre_input').val($tr.find('td').eq(0).text());
                $('#semestre_form #inicio_input').val($tr.find('td').eq(1).text());
                $('#semestre_form #termino_input').val($tr.find('td').eq(2).text());
                $('#semestre_form #inicio_insc_input').val($tr.find('td').eq(3).text());
                $('#semestre_form #termino_insc_input').val($tr.find('td').eq(4).text());
                $('#semestre_form #validacion_input').val($tr.find('td').eq(5).text());
                $('#add_semestre_modal').modal('show');
            });
            $(this).find('.btn-eliminar').on('click', function () {
                var $tr = $(this).closest('tr');
                noty({
                    text: '¿Est&aacute;s seguro que quieres borrar este semestre: ' + $tr.find('td').eq(0).text() + '?',
                    type: 'warning',
                    modal: true,
                    buttons: [
                        {
                            addClass: 'btn btn-primary',
                            text: 'Si',
                            onClick: function ($noty) {
                                $noty.close();
                                $.ajax({
                                    type: 'POST',
                                    dataType: 'json',
                                    url: base_url + 'admin/semestres/delete/' + $tr.data('id'),
                                    success: function (data) {
                                        if (data.status === "MSG") {
                                            if (data.type === 'success') {
                                                $tr.remove();
                                            }
                                            alerts(data.type, data.message);
                                        }
                                    }
                                });
                            }
                        },
                        {
                            addClass: 'btn btn-danger',
                            text: 'No',
                            onClick: function ($noty) {
                                $noty.close();
                            }
                        }
                    ],
                    closable: false,
                    timeout: false
                });
            });
        }
    });
}