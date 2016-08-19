$(document).on('ready' , function(){
    $('#calendar_horarios').fullCalendar({
        header: {
            left: 'prev,next',
            center: '',
            right: 'agendaWeek,agendaDay'
        },
        defaultView: 'agendaWeek',
        theme: true,
        editable: false,
        minTime: 7,
        maxTime: 20,
        height: 650,
        date: 1,
        month: 6,
        year: 2013,
        columnFormat: {
            month: 'ddd',
            week: 'ddd',
            day: 'dddd'
        },
        allDaySlot: false,
        allDayText: 'Sin asignar',
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'Mayo', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'],
        weekMode: 'variable',
        handleWindowResize: false,
        defaultEventMinutes: 120,
        weekends: false,
        droppable: false,
        weekNumberTitle: 'Sem',
        slotMinutes: 15,
        slotEventOverlap: false,
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Dia'
        },
        viewRender: function(currentView) {
            currentView.start;
            var inicio_date = new Date(2013, 6, 1, 0, 0, 0, 0);
            var fin_date = new Date(2013, 6, 5, 20, 59, 0, 0);
            if (currentView.start < inicio_date) {
                $('#calendar_horarios').fullCalendar('gotoDate', fin_date);
            } else if (currentView.start > fin_date) {
                $('#calendar_horarios').fullCalendar('gotoDate', inicio_date);
            }

        },
        dayClick: function(date, allDay, jsEvent, view) {
            if (view.name === 'agendaWeek') {
                $('#calendar_horarios').fullCalendar('changeView', 'agendaDay');
                $('#calendar_horarios').fullCalendar('gotoDate', date);
            } else {
                $('#calendar_horarios').fullCalendar('changeView', 'agendaWeek');
            }
        }
    });
    $.ajax({
        url: base_url + 'horarios/get_talleres_by_semestre/' + $('#calendar_horarios').data('semestre'),
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            if (data.status === 'OK') {
                var html_select = '';
                $.each(data.talleres, function(key, event) {
                    html_select += '<option value="' + event.id + '">' + event.taller + '</option>';
                });
                $('#calendar_horarios .fc-header-center').html('<select class="multiselect" multiple="multiple">' + html_select + '</select>');
                $('#calendar_horarios .fc-header-center .multiselect').multiselect({
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    maxHeight: 310,
                    filterPlaceholder: 'Buscar',
                    buttonWidth: '230px',
                    nonSelectedText: 'Sin Selecci&oacute;n',
                    onChange: function(element, checked) {
                        get_by_semestre_materia();
                    }
                });
            }
        }
    });
});
function get_by_semestre_materia() {
    var data_html = '';
    $('#calendar_horarios .fc-header-center .multiselect-container input[type="checkbox"]').each(function() {
        if ($(this).is(':checked')) {
            data_html += 'talleres[]=' + $(this).val() + '&';
        }
    });
    if (data_html !== '') {
        $.ajax({
            url: base_url + 'admin/taller_semestre_horario/get_by_semestre/' + $('#calendar_horarios').data('semestre'),
            type: 'POST',
            data: data_html,
            dataType: 'json',
            success: function(data) {
                if (data.status === "OK") {
                    $('#calendar_horarios').fullCalendar('removeEvents');
                    $.each(data.talleres, function(key, event) {
                        var titulo = '<div class="taller_span">Taller: ' + event.taller + '</div>' +
                                '<div class="profesor_span">Profesor: ' + event.nombre + " " + event.paterno + " " + event.materno + '</div>' +
                                '<div class="salon_span">Sal&oacute;n: ' + event.salon + '</div>' +
                                '<div class="salon_span">Grupo: ' + event.grupo + '</div>';
                        var end_array = event.termino.split(':');
                        var end = new Date(2013, 6, event.dia, end_array[0], end_array[1], 0, 0);
                        var start_array = event.inicio.split(':');
                        var start = new Date(2013, 6, event.dia, start_array[0], start_array[1], 0, 0);
                        var _event = {
                            id: event.id,
                            title: titulo,
                            end: end,
                            id_actividad: event.taller_semestre_id,
                            min_duration: 5,
                            duration: 120,
                            durationEditable: false,
                            startEditable: false,
                            editable: false,
                            start: start,
                            allDay: false
                        };
                        $('#calendar_horarios').fullCalendar('renderEvent', _event, true);
                    });
                } else {
                    alerts(data.type, data.message);
                }
            }
        });
    }else{
        $('#calendar_horarios').fullCalendar('removeEvents');
    }
}