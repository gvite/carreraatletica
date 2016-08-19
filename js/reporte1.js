$(document).on('ready', function () {
    $('#reporte1_form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status === "OK") {
                    window.open(data.file);
                }else{
                    alerts(data.type, data.message);
                }
            }
        });
    });
});