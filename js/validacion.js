$(document).on('ready', function () {
    $("#search_folio_form").on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status === "OK") {
                    location.href = base_url + 'admin/validacion/get_baucher/' + data.folio;
                } else {
                    alerts(data.type, data.message);
                }
            }
        });
    });
});
function search_folio_function() {

}