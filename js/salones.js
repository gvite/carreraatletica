$(document).on('ready', function () {
    $('#btn_add_salones_modal').on('click', function () {
        $('#salon_form input').val('');
        $('#salon_form').attr('action', base_url + 'admin/salones/insert');
    });
    $('#salon_form').on('submit', function (event) {
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
                            $('#table_salones tbody tr').each(function () {
                                if ($(this).data('id') == data.salon.id) {
                                    $(this).children('td').eq(0).text(data.salon.salon);
                                    $(this).children('td').eq(1).text(data.salon.cupo);
                                }
                            });
                        } else {
                            $('.no_registros').remove();
                            var html = '<tr data-id="' + data.salon.id + '" data-events="0">';
                            html += '<td>' + data.salon.salon + '</td>';
                            html += '<td>' + data.salon.cupo + '</td>';
                            html += '<td><button class="btn btn-sm btn-editar">Editar</button><button class="btn btn-sm btn-eliminar">Eliminar</button></td>';
                            html += '</tr>';
                            $('#table_salones tbody').append(html);
                            add_events_salones();
                        }
                        $('#add_salones_modal').modal('hide');
                    }
                    alerts(data.type, data.message);
                }
            }
        });
    });
    add_events_salones();
});
function add_events_salones() {
    $('#table_salones tbody tr').each(function () {
        if ($(this).data('events') == 0) {
            $(this).data('events', 1);
            $(this).find('.btn-editar').on('click', function () {
                var $tr = $(this).closest('tr');
                $('#salon_form').attr('action', base_url + 'admin/salones/update/' + $tr.data('id'));
                $('#salon_form #nombre_input').val($tr.find('td').eq(0).text());
                $('#salon_form #cupo_input').val($tr.find('td').eq(1).text());
                $('#add_salones_modal').modal('show');
            });
            $(this).find('.btn-eliminar').on('click', function () {
                var $tr = $(this).closest('tr');
                noty({
                    text: '¿Est&aacute;s seguro que quieres borrar este sal&oacute;n: ' + $tr.find('td').eq(0).text() + '?',
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
                                    url: base_url + 'admin/salones/delete/' + $tr.data('id'),
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