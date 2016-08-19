$(document).on('ready', function () {
    get_lista_taller_semestre($('#talleres_semestre_div .panel-heading h3.actual').data('id'));
    $('#calendar_actividades').fullCalendar({
        header: {
            left: 'prev,next',
            center: '',
            right: 'agendaWeek,agendaDay'
        },
        defaultView: 'agendaWeek',
        theme: true,
        editable: true,
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
        droppable: true,
        weekNumberTitle: 'Sem',
        slotMinutes: 15,
        slotEventOverlap: false,
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Dia'
        },
        drop: function (date, allDay, jsEvent, ui) {
            var $this = $(this);
            var copiedEventObject = $.extend({}, $this.data('eventObject'));
            copiedEventObject.start = date;
            copiedEventObject.end = new Date(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes() + copiedEventObject.duration, 0, 0);
            copiedEventObject.allDay = allDay;
            var inicio = '';
            var termino = '';
            if (!allDay) {
                inicio = copiedEventObject.start.getHours() + ':' + copiedEventObject.start.getMinutes() + ':00';
                termino = copiedEventObject.end.getHours() + ':' + copiedEventObject.end.getMinutes() + ':00';
            }
            var data_ajax = 'id=' + copiedEventObject.id_actividad
                    + '&dia=' + date.getDay() +
                    '&termino=' + termino +
                    '&inicio=' + inicio;
            $.ajax({
                url: base_url + 'admin/taller_semestre_horario/insert/',
                data: data_ajax,
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    if (data.status === "MSG") {
                        if (data.type === 'success') {
                            copiedEventObject.id = data.id;
                            $('#calendar_actividades').fullCalendar('renderEvent', copiedEventObject, true);
                        } else {
                            alerts(data.type, data.message);
                        }
                    }
                }
            });
        },
        eventMouseRemove: function (event) {
            $.ajax({
                url: base_url + 'admin/taller_semestre_horario/delete/' + event.id,
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    if (data.status === "MSG") {
                        if (data.type === 'success') {
                            $('#calendar_actividades').fullCalendar('removeEvents', event.id);
                        } else {
                            alerts(data.type, data.message);
                        }
                    }
                }
            });
        },
        eventDrop: function (event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
            update_event(event);
        },
        eventResize: function (event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view) {
            update_event(event);
        },
        viewRender: function (currentView) {
            currentView.start;
            var inicio_date = new Date(2013, 6, 1, 0, 0, 0, 0);
            var fin_date = new Date(2013, 6, 5, 20, 59, 0, 0);
            if (currentView.start < inicio_date) {
                $('#calendar_actividades').fullCalendar('gotoDate', fin_date);
            } else if (currentView.start > fin_date) {
                $('#calendar_actividades').fullCalendar('gotoDate', inicio_date);
            }

        },
        dayClick: function (date, allDay, jsEvent, view) {
            if (view.name === 'agendaWeek') {
                $('#calendar_actividades').fullCalendar('changeView', 'agendaDay');
                $('#calendar_actividades').fullCalendar('gotoDate', date);
            } else {
                $('#calendar_actividades').fullCalendar('changeView', 'agendaWeek');
            }
        }
    });
    $('#table_talleres .btn-editar').on('click', function () {
        var $tr = $(this).closest('tr');
        $('#talleres_form').attr('action', base_url + 'admin/talleres_semestre/update/' + $tr.data('id'));
        $('#add_talleres_semestre_modal').modal('show');
        $('#talleres_form input[name="taller"]').val($tr.find('td').eq(0).text());
    });
    $('#taller_semestre_form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status === "MSG") {
                    if (data.type === 'success') {
                        if (data.tipo == 0) {
                            var html = '<div data-event="0" class="dragg_div" data-id="' + data.id + '">' +
                                    '<div style="display: block">' +
                                    '<button type="button" class="btn btn-link btn-sm pull-right btn-remove"><span class="glyphicon glyphicon-remove"></span></button>' +
                                    '<button type="button" class="btn btn-link btn-sm pull-right btn-editar"><span class="glyphicon glyphicon-pencil"></span></button>' +
                                    '<span class="glyphicon glyphicon-move move_dragg"></span></div>' +
                                    '<div class="content_dragg">' +
                                    '<div class="taller_span">Taller: ' + data.datos.taller + '</div>' +
                                    '<div class="profesor_span">Profesor: ' + data.datos.nombre + ' ' + data.datos.paterno + ' ' + data.datos.materno + '</div>' +
                                    '<div class="salon_span">Sal&oacute;n: ' + data.datos.salon + '</div> ' +
                                    '<div class="grupo_span">Grupo: ' + data.datos.grupo + '</div> ' +
                                    '</div>' +
                                    '</div>';
                            $('#talleres_semestre_div .panel-body').append(html);
                            $('#talleres_semestre_div .panel-body .dragg_div').each(function () {
                                if ($(this).data('event') == 0) {
                                    addEventDragg($(this));
                                }
                            });
                        } else {
                            $('#talleres_semestre_div .panel-body .dragg_div').each(function () {
                                if ($(this).data('id') == data.id) {
                                    $(this).find('.taller_span').data('id', data.datos.taller_id);
                                    $(this).find('.taller_span').text('Taller: ' + data.datos.taller);
                                    $(this).find('.profesor_span').data('id', data.datos.profesor_id);
                                    $(this).find('.profesor_span').text('Profesor: ' + data.datos.nombre + ' ' + data.datos.paterno + ' ' + data.datos.materno);
                                    $(this).find('.salon_span').data('id', data.datos.salon_id);
                                    $(this).find('.salon_span').html('Sal&oacute;n: ' + data.datos.salon);
                                    $(this).find('.cupo_span').data('id', data.datos.cupo);
                                    $(this).find('.cupo_span').text('Cupo: ' + data.datos.cupo);
                                    $(this).find('.grupo_span').data('id', data.datos.grupo);
                                    $(this).find('.grupo_span').text('Grupo: ' + data.datos.grupo);
                                }
                            });
                        }
                        $('#add_talleres_semestre_modal').modal('hide');
                    }
                    alerts(data.type, data.message);
                }
            }
        });
    });
    $('#salones_select').on('change', function () {
        $('#cupo_input').val($("#salones_select option:selected").data('cupo'));
    });
    $('.btn-agregar-taller-sem').on('click', function () {
        $('#taller_semestre_form select').val(0);
        $('#taller_semestre_form .control-group input').val('');
        $('#taller_semestre_form').attr('action', base_url + 'admin/talleres_semestre/insert');
        $('#add_talleres_semestre_modal h3').text('Agregar nuevo');
        $('#add_talleres_semestre_modal').modal('show');
    });
    $('#talleres_semestre_div .panel-heading .pull-left').on('click', function () {
        var index = $('#talleres_semestre_div .panel-heading h3.actual').data('index');
        if (index > 1) {
            $('#talleres_semestre_div .panel-heading h3').removeClass('actual');
            $('#talleres_semestre_div .panel-heading h3').hide();
            $('#talleres_semestre_div .panel-heading h3.title_' + (parseInt(index) - 1)).addClass('actual');
            $('#talleres_semestre_div .panel-heading h3.title_' + (parseInt(index) - 1)).show();
            get_lista_taller_semestre($('#talleres_semestre_div .panel-heading h3.title_' + (parseInt(index) - 1)).data('id'));
        }
    });
    $('#talleres_semestre_div .panel-heading .pull-right').on('click', function () {
        var index = $('#talleres_semestre_div .panel-heading h3.actual').data('index');
        if (index < $('#talleres_semestre_div .panel-heading').data('count')) {
            $('#talleres_semestre_div .panel-heading h3').removeClass('actual');
            $('#talleres_semestre_div .panel-heading h3').hide();
            $('#talleres_semestre_div .panel-heading h3.title_' + (parseInt(index) + 1)).addClass('actual');
            $('#talleres_semestre_div .panel-heading h3.title_' + (parseInt(index) + 1)).show();
            get_lista_taller_semestre($('#talleres_semestre_div .panel-heading h3.title_' + (parseInt(index) + 1)).data('id'));
        }
    });
});
function update_event(event) {
    var inicio = event.start.getHours() + ':' + event.start.getMinutes() + ':00';
    var termino = event.end.getHours() + ':' + event.end.getMinutes() + ':00';
    var data = 'id=' + event.id_actividad
            + '&dia=' + event.start.getDay() +
            '&termino=' + termino +
            '&inicio=' + inicio;
    $.ajax({
        url: base_url + 'admin/taller_semestre_horario/update/' + event.id,
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function (data) {
            if (data.status === "MSG") {
                if (data.type === 'success') {

                } else {
                    alerts(data.type, data.message);
                }
            }
        }
    });
}
function addEventDragg($element) {
    $element.data('event', 1);
    $element.draggable({
        helper: 'clone',
        zIndex: 2000,
        handle: '.move_dragg',
        start: function (event, ui) {
            ui.helper.css("background-color", "#eee");
            ui.helper.css("width", "373px");
            ui.helper.find('.action_dragg').remove();
            ui.helper.find('.salon_span').hide();
            ui.helper.find('.grupo_span').hide();
        }
    });
    var texto_long = $element.find('.content_dragg').html();
    $element.data('eventObject', {
        title: texto_long,
        allDay: true,
        duration: 120,
        min_duration: 5,
        durationEditable: true,
        startEditable: true,
        editable: true,
        end: null,
        id_actividad: $element.data('id')
    });
    $element.find('.btn-editar').on('click', function () {
        var $padre = $(this).closest('.dragg_div');
        $('#taller_semestre_form #taller_select').val($padre.find('.taller_span').data('id'));
        $('#taller_semestre_form #profesores_select').val($padre.find('.profesor_span').data('id'));
        $('#taller_semestre_form #salones_select').val($padre.find('.salon_span').data('id'));
        $('#taller_semestre_form #cupo_input').val($padre.find('.cupo_span').data('id'));
        $('#taller_semestre_form #grupo_input').val($padre.find('.grupo_span').data('id'));
        $('#taller_semestre_form').attr('action', base_url + 'admin/talleres_semestre/update/' + $(this).closest('.dragg_div').data('id'));
        $('#add_talleres_semestre_modal h3').text('Editar');
        $('#add_talleres_semestre_modal').modal('show');
    });
    $element.find('.btn-remove').on('click', function () {
        var $dragg = $(this).closest('.dragg_div');
        $.ajax({
            url: base_url + 'admin/talleres_semestre/delete/' + $dragg.data('id'),
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status === "MSG") {
                    if (data.type === 'success') {
                        $dragg.remove();
                    }
                    alerts(data.type, data.message);
                }
            }
        });
    });
}
function get_lista_taller_semestre(semestre_id) {
    $.ajax({
        url: base_url + 'admin/talleres_semestre/get_talleres/' + semestre_id,
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if (data.status === "OK") {
                $('#talleres_semestre_div .panel-body').html(data.content);
                $.ajax({
                    url: base_url + 'admin/talleres_semestre/get_talleres_group/' + semestre_id,
                    type: 'POST',
                    dataType: 'json',
                    success: function (data1) {
                        if (data1.status === "OK") {
                            var html_select = '';
                            $.each(data1.talleres, function (key, event) {
                                html_select += '<option value="' + event.taller_id + '">' + event.taller + '</option>';
                                $('#calendar_actividades .fc-header-center').html('<select class="multiselect" multiple="multiple">' + html_select + '</select>');
                                $('#calendar_actividades .fc-header-center .multiselect').multiselect({
                                    enableFiltering: true,
                                    enableCaseInsensitiveFiltering: true,
                                    maxHeight: 310,
                                    filterPlaceholder: 'Buscar',
                                    buttonWidth: '230px',
                                    nonSelectedText: 'Sin Selecci&oacute;n',
                                    onChange: function (element, checked) {
                                        get_by_semestre_taller();
                                    }
                                });
                            });
                        }
                    }
                });
                $("#semestre_input_hidden").val(semestre_id);
                $('.dragg_div').each(function () {
                    if ($(this).data('event') == 0) {
                        addEventDragg($(this));
                    }
                });
            } else {
                alerts(data.type, data.message);
            }
        }
    });
}
function get_by_semestre_taller() {
    var data_html = '';
    $('#calendar_actividades .fc-header-center .multiselect-container input[type="checkbox"]').each(function () {
        if ($(this).is(':checked')) {
            data_html += 'talleres[]=' + $(this).val() + '&';
        }
    });
    if (data_html !== '') {
        var semestre_id = $('#talleres_semestre_div .panel-heading h3.actual').data('id');
        $.ajax({
            url: base_url + 'admin/taller_semestre_horario/get_by_semestre/' + semestre_id,
            type: 'POST',
            data: data_html,
            dataType: 'json',
            success: function (data) {
                if (data.status === "OK") {
                    $('#calendar_actividades').fullCalendar('removeEvents');
                    $.each(data.talleres, function (key, event) {
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
                            durationEditable: true,
                            startEditable: true,
                            editable: true,
                            start: start,
                            allDay: false
                        };
                        $('#calendar_actividades').fullCalendar('renderEvent', _event, true);
                    });
                } else {
                    alerts(data.type, data.message);
                }
            }
        });
    } else {
        $('#calendar_actividades').fullCalendar('removeEvents');
    }
}