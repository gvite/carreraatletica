$(document).on('ready', function () {
    $('#btn_add_talleres_modal').on('click', function () {
        $('#talleres_form input').val('');
        $('#talleres_form').attr('action', base_url + 'admin/talleres/insert');
    });

    $('#talleres_form').on('submit', function (event) {
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
                            $('#table_talleres tbody tr').each(function () {
                                if ($(this).data('id') == data.taller.id) {
                                    $(this).children('td').eq(0).text(data.taller.taller);
                                    $(this).children('td').eq(1).text(data.taller.costo_alumno);
                                    $(this).children('td').eq(2).text(data.taller.costo_exalumno);
                                    $(this).children('td').eq(3).text(data.taller.costo_trabajador);
                                    $(this).children('td').eq(4).text(data.taller.costo_externo);
                                    $(this).children('td').eq(5).text(data.taller.objetivo);
                                    $(this).children('td').eq(6).text(data.taller.requisitos);
                                    $(this).children('td').eq(7).text(data.taller.informacion);
                                }
                            });
                        } else {
                            $('.no_registros').remove();
                            var html = '<tr data-id="' + data.taller.id + '" data-events="0">';
                            html += '<td>' + data.taller.taller + '</td>';
                            html += '<td>' + data.taller.costo_alumno + '</td>';
                            html += '<td>' + data.taller.costo_exalumno + '</td>';
                            html += '<td>' + data.taller.costo_trabajador + '</td>';
                            html += '<td>' + data.taller.costo_externo + '</td>';
                            html += '<td style="display:none">' + data.taller.objetivo + '</td>';
                            html += '<td style="display:none">' + data.taller.requisitos + '</td>';
                            html += '<td style="display:none">' + data.taller.informacion + '</td>';
                            html += '<td><button class="btn btn-sm btn-editar">Editar</button><button class="btn btn-sm btn-eliminar">Eliminar</button></td>';
                            html += '</tr>';
                            $('#table_talleres tbody').append(html);
                            add_events_talleres();
                        }
                        $('#add_talleres_modal').modal('hide');
                    }
                    alerts(data.type, data.message);
                }
            }
        });
    });
    add_events_talleres();
});
function add_events_talleres() {
    $('#table_talleres tbody tr').each(function () {
        if ($(this).data('events') == 0) {
            $(this).data('events', 1);
            $(this).find('.btn-editar').on('click', function () {
                var $tr = $(this).closest('tr');
                $('#talleres_form').attr('action', base_url + 'admin/talleres/update/' + $tr.data('id'));
                $('#talleres_form #taller_input').val($tr.find('td').eq(0).text());
                $('#talleres_form #costo_a_input').val($tr.find('td').eq(1).text());
                $('#talleres_form #costo_e_input').val($tr.find('td').eq(2).text());
                $('#talleres_form #costo_t_input').val($tr.find('td').eq(3).text());
                $('#talleres_form #costo_ex_input').val($tr.find('td').eq(4).text());
                $('#talleres_form #objetivo_area').val($tr.find('td').eq(5).text());
                $('#talleres_form #requisitos_area').val($tr.find('td').eq(6).text());
                $('#talleres_form #informacion_area').val($tr.find('td').eq(7).text());
                $('#add_talleres_modal').modal('show');
            });
            $(this).find('.btn-eliminar').on('click', function () {
                var $tr = $(this).closest('tr');
                noty({
                    text: '¿Est&aacute;s seguro que quieres borrar este taller: ' + $tr.find('td').eq(0).text() + '?',
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
                                    url: base_url + 'admin/talleres/delete/' + $tr.data('id'),
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