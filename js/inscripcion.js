$(document).on('ready', function () {
    $('.dragg-taller-div').draggable({
        helper: 'clone',
        zIndex: 2000,
        handle: '.panel-heading',
        start: function (event, ui) {
            ui.helper.css("width", "373px");
            ui.helper.find('.panel-footer').remove();
            ui.helper.find('.horario-taller').remove();
        }
    }).hover(function () {

    }, function () {

    });
    $('.alert-agotado').each(function () {
        $('#materias > .panel-body').append($(this));
    });
    $('.dragg-taller-div .btn-taller-insc').on('click', function () {
        set_event_insc($(this).closest('.panel').data('id'), $(this).closest('.panel'));
    });
    $('#inscribir_panel .panel-body').droppable({
        accept: '.dragg-taller-div',
        drop: function (event, ui) {
            var id = ui.draggable.data("id");
            set_event_insc(id, ui.draggable);
        }
    });

    $('#inscribir_panel .panel-footer .btn-primary').on('click', function () {
        var data = '';
        $('#inscribir_panel .panel-body .alert').each(function () {
            data += 'id[]=' + $(this).data('id') + '&';
        });
        if (data.length > 0) {
            data = data.substring(0, data.length - 1);
        }
        $.ajax({
            url: base_url + 'alumnos/inscripcion/insert',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {
                if (data.status === "MSG") {
                    alerts(data.type, data.message, '', function () {
                        if (data.type == 'success') {
                            var html = '';
                            $.each(data.bauchers, function(key, baucher) {
                                html += '<tr>';
                                html += '<td>' + baucher.folio + '</td>';
                                html += '<td>' + baucher.taller + '</td>';
                                html += '<td>' + baucher.fecha_expedicion + '</td>';
                                html += '<td>No pagado</td>';
                                html += '<td><a data-events="0" class="btn btn-link" data-id="' + baucher.id + '" href="' + base_url + 'alumnos/inscripcion/get_pdf/' + baucher.id + 'id" target="_blank">Imprimir</a></td>';
                                html += '<tr>';
                                $('#materias .panel').each(function(){
                                    if($(this).data('id') == baucher.taller_id){
                                        $(this).remove();
                                    } 
                                });
                            });
                            $('#table_bauchers tbody').append(html);
                            $('#inscribir_panel .panel-body .alert').remove();
                            $('#inscribir_panel .panel-body p').show();
                            add_events_inscripcion();
                            $.each(data.bauchers, function(key, baucher) {
                                get_pdf(baucher.id);
                            });
                        }
                    });
                }
            }
        });
    });
    add_events_inscripcion();
});
function add_events_inscripcion() {
    $('#table_bauchers tbody tr td a').each(function () {
        if ($(this).data('events') == 0) {
            $(this).data('events' , 1);
            $(this).on('click', function (event) {
                event.preventDefault();
                get_pdf($(this).data('id'));
            });
        }
    });
}
function get_pdf(id) {
    $.ajax({
        url: base_url + 'alumnos/inscripcion/get_pdf/' + id,
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if (data.status === "OK") {
                window.open(data.url);
            } else {
                alerts(data.type, data.message);
            }
        }
    });
}
function set_event_insc(id, $dragg) {
    var esta = false;
    var $this = $('#inscribir_panel > .panel-body');
    $this.children('div').each(function () {
        if ($(this).data("id") == id) {
            esta = true;
        }
    });
    if (!esta) {
        var html = '<div data-id="' + id + '" data-events="0" class="alert alert-info col-md-5" style="margin:5px">'
                + '<div>' + $dragg.find('.name-taller').data('name') + '</div>'
                + '<div><strong> ' + $dragg.find('.salon-taller').text() + '</strong></div>'
                + '<div>Grupo: ' + $dragg.find('.grupo-taller').text() + '</div>'
                + '<button class="btn btn-xs btn-link pull-right"><span class="glyphicon glyphicon-remove"></span></button>'
                + '<div>$<span class="costo"> ' + $dragg.find('.costo-span').text() + '</span></div></div>';
        $this.find('p').hide();
        $this.append(html);
        var costo = 0;
        $this.children("div").each(function () {
            if ($(this).data("events") == 0) {
                events_div_insc($(this));
            }
            costo += parseInt($(this).find('.costo').text());
        });
        $('#costo_total').val('$' + costo);
    }
}
function events_div_insc($this) {
    $this.find('.btn-link').on('click', function () {
        var $parent = $('#inscribir_panel .panel-body');
        $(this).closest('.alert').remove();
        var costo = 0;
        $parent.children("div").each(function () {
            costo += parseInt($(this).find('.costo').text());
        });
        if ($parent.children("div").length !== 0) {
            $('#costo_total').val('$' + costo);
        } else {
            $('#costo_total').val('');
            $parent.find('p').show();
        }
    });
}