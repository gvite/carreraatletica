$(document).on('ready', function () {
    $.ajax({
        url: base_url + 'admin/listas/get_pdf_lista/' + $('#taller_content').data('id'),
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if (data.status === "OK") {
                window.location.href = data.url;
                //window.open(data.url);
            } else {
                alerts(data.type, data.message);
            }
        }
    });
});