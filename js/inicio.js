$(document).on('ready', function() {
    countDownInsc();
    $('.talleres-class .btn-link').on('click' , function(event){
        event.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if (data.status === "OK") {
                    $('#informacion_modal .modal-content').html(data.content);
                    $('#informacion_modal').modal('show');
                } else {
                    alerts(data.type, data.message);
                }
            }
        });
    });
    var mayorAltura=0;
    $('#requisitos_inscripcion .alert').each(function(){
        var alturaVersiones = $(this).height();
        if(alturaVersiones > mayorAltura){
            mayorAltura = alturaVersiones;
        }
    });
    $('#requisitos_inscripcion .alert').height(mayorAltura + 40);
    $( window ).resize(function() {
        var mayorAltura=0;
        $('#requisitos_inscripcion .alert').each(function(){
            var alturaVersiones = $(this).height();
            if(alturaVersiones > mayorAltura){
                mayorAltura = alturaVersiones;
            }
        });
        $('#requisitos_inscripcion .alert').height(mayorAltura + 40);
    });
});
function logout_events() {
    $('#logout_link').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if (data.status === "OK") {
                    window.location.href = window.location.href;
                } else {
                    alerts(data.type, data.message);
                }
            }
        });
    });
}

function countDownInsc(){
    if ($('#counter').length > 0) {
        $('#counter').html("<div></div>");
        $('#counter').find('div').countdown({
            format: "dd:hh:mm:ss",
            startTime: $('#counter').data('time'),
            digitWidth: 30,
            digitHeight: 43,
            image: "images/digits2.png",
            timerEnd: function(){
                window.location.href = window.location.href;
            }
        });
    }
}