$(document).on('ready', function () {
    $('#btn_add_profesores_modal').on('click', function () {
        $('#profesor_form input').val('');
        $('#profesor_form').attr('action', base_url + 'admin/profesores/insert');
    });
    $('#profesor_form').on('submit', function (event) {
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
                            $('#table_profesores tbody tr').each(function () {
                                if ($(this).data('id') == data.profesor.id) {
                                    var html = '<span class="nombre-span">' + data.profesor.nombre + '</span><span class="paterno-span">' + data.profesor.paterno + '</span><span class="materno-span">' + data.profesor.materno + '</span>';
                                    $(this).children('td').eq(0).html(html);
                                }
                            });
                        } else {
                            $('.no_registros').remove();
                            var html = '<tr data-id="' + data.profesor.id + '" data-events="0">';
                            html += '<td><span class="nombre-span">' + data.profesor.nombre + '</span><span class="paterno-span">' + data.profesor.paterno + '</span><span class="materno-span">' + data.profesor.materno + '</span></td>';
                            html += '<td><button class="btn btn-sm btn-editar">Editar</button><button class="btn btn-sm btn-eliminar">Eliminar</button></td>';
                            html += '</tr>';
                            $('#table_profesores tbody').append(html);
                            add_events_profesores();
                        }
                        $('#add_profesores_modal').modal('hide');
                    }
                    alerts(data.type, data.message);
                }
            }
        });
    });
    add_events_profesores();
});
function add_events_profesores() {
    $('#table_profesores tbody tr').each(function () {
        if ($(this).data('events') == 0) {
            $(this).data('events', 1);
            $(this).find('.btn-editar').on('click', function () {
                var $tr = $(this).closest('tr');
                $('#profesor_form').attr('action', base_url + 'admin/profesores/update/' + $tr.data('id'));
                $('#profesor_form #nombre_input').val($tr.find('td').eq(0).children('span.nombre-span').text());
                $('#profesor_form #paterno_input').val($tr.find('td').eq(0).children('span.paterno-span').text());
                $('#profesor_form #materno_input').val($tr.find('td').eq(0).children('span.materno-span').text());
                $('#add_profesores_modal').modal('show');
            });
            $(this).find('.btn-eliminar').on('click', function () {
                var $tr = $(this).closest('tr');
                noty({
                    text: '¿Est&aacute;s seguro que quieres borrar este profesor: ' + $tr.find('td').eq(0).text() + '?',
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
                                    url: base_url + 'admin/profesores/delete/' + $tr.data('id'),
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