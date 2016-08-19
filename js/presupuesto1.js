$(document).on('ready', function () {
    $('#reporte2_form .fecha_inicio').datetimepicker({
        useCurrent: false,
        locale: 'es',
        format: 'DD-MM-YYYY'
    });
    $('#reporte2_form .fecha_fin').datetimepicker({
        locale: 'es',
        useCurrent: false,
        format: 'DD-MM-YYYY'
    });
    $('#mas_opciones_a').on('click', function (event) {
        event.preventDefault();
        if ($(this).data('display') == 0) {
            $(this).data('display', 1);
            $('#mas_opciones').show();
        } else {
            $(this).data('display', 0);
            $('#mas_opciones').hide();
        }
    });
    $("#get_registros").on('click' , function(event){
        event.preventDefault();
        $.ajax({
            url: base_url + 'admin/reportes/get_registros_reportes.jsp',
            data: $('#reporte2_form').serialize(),
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status === "OK") {
                    $('#container #reporte_preview').html(data.container);
                } else {
                    alerts(data.type, data.message);
                }
            }
        });
    })
    $('#semestre_input').on('change', function () {
        if ($(this).val() !== '') {
            var ini = $('#semestre_input option:selected').data('ini');
            var fin = $('#semestre_input option:selected').data('fin');
//            $('#reporte2_form .fecha_inicio').val(ini);
//            $('#reporte2_form .fecha_fin').val(fin);
            $('#reporte2_form .fecha_inicio').data('DateTimePicker').options({
                minDate: ini,
                maxDate: fin
            });
            $('#reporte2_form .fecha_fin').data('DateTimePicker').options({
                minDate: ini,
                maxDate: fin
            });
            $('#reporte2_form .fecha_inicio').data('DateTimePicker').date(ini);
            $('#reporte2_form .fecha_fin').data('DateTimePicker').date(fin);
        } else {
            $('#reporte2_form .fecha_inicio').data('DateTimePicker').date(null);
            $('#reporte2_form .fecha_fin').data('DateTimePicker').date(null);
            $('#reporte2_form .fecha_inicio').data('DateTimePicker').options({
                minDate: false,
                maxDate: false
            });
            $('#reporte2_form .fecha_fin').data('DateTimePicker').options({
                minDate: false,
                maxDate: false
            });
        }
    });
    $('#reporte2_form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status === "OK") {
                    window.open(data.file);
                } else {
                    alerts(data.type, data.message);
                }
            }
        });
    });
});