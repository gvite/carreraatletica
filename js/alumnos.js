$(document).on('ready', function () {
    $('input[name="alumnos"]').on('click', function () {
        if ($(this).val() === "0") {
            $('input[type="checkbox"]').attr('checked', false);
            $('#tipos_alumnos_content').hide();
            $('#semestres_content').hide();
            $('#datos_usuarios .col-md-3').hide();
        } else {
            $('#tipos_alumnos_content').show();
            $('#semestres_content').show();
        }
    });
    $('input[name="tipo_alumnos[]"]').on('click', function () {
        if ($(this).is(':checked')) {
            $('#' + $(this).data('id')).show();
        } else {
            $('#' + $(this).data('id')).hide();
            $('#' + $(this).data('id') + ' input').attr('checked', false);
        }
    });
    $('#datos_usuarios .all_select').on('click', function () {
        if ($(this).is(':checked')) {
            //alert($(this).closest('div').siblings('.panel-body').find('input').length)
            $(this).closest('div').siblings('.panel-body').find('input').prop("checked", true);
        } else {
            $(this).closest('div').siblings('.panel-body').find('input').attr('checked', false);
        }
    });
    $('#busqueda_form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (data) {
                if (data.status === "OK") {
                    $('#container').html(data.content);
                    $('#container table').DataTable({
                        language: {
                            emptyTable: "No hay registros",
                            info: "Mostrando _START_ - _END_ de _TOTAL_ registros",
                            infoEmpty: "Mostrando 0 - 0 de 0 registros",
                            infoFiltered: "(Filtrado de _MAX_ registros totales)",
                            infoPostFix: "",
                            thousands: ",",
                            lengthMenu: "Mostrar _MENU_ registros",
                            loadingRecords: "Cargando...",
                            processing: "Procesando...",
                            search: "Buscar:",
                            zeroRecords: "Ningún resultado",
                            paginate: {
                                "first": "Primera",
                                "last": "Ultima",
                                "next": "Siguiente",
                                "previous": "Anterior"
                            },
                            aria: {
                                "sortAscending": ": activate to sort column ascending",
                                "sortDescending": ": activate to sort column descending"
                            }
                        }
                    });
                    $('.dataTables_wrapper').hide();
                    $('.dataTables_wrapper').eq(0).show();
                    $('#lista_alumnos .panel-heading .pull-left').on('click', function () {
                        var index = $('#lista_alumnos .panel-heading h3').data('actual');
                        if (index > 0) {
                            $('.dataTables_wrapper').hide();
                            $('.dataTables_wrapper').eq(index - 1).show();
                            $('#lista_alumnos .panel-heading h3').html($('.dataTables_wrapper').eq(index - 1).find('table').data('name'));
                            $('#lista_alumnos .panel-heading h3').data('actual', index - 1);
                        }
                    });
                    $('#lista_alumnos .panel-heading .pull-right').on('click', function () {
                        var index = $('#lista_alumnos .panel-heading h3').data('actual');
                        if (index < $('#lista_alumnos .panel-heading').data('count') - 1) {
                            $('.dataTables_wrapper').hide();
                            $('.dataTables_wrapper').eq(index + 1).show();
                            $('#lista_alumnos .panel-heading h3').html($('.dataTables_wrapper').eq(index + 1).find('table').data('name'));
                            $('#lista_alumnos .panel-heading h3').data('actual', index + 1);
                        }
                    });
                } else {
                    alerts(data.type, data.message);
                }
            }
        });
    });
});