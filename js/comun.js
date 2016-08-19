$(document).on('ready', function () {
    $(this).ajaxStart(function () {
        $('#ajax_div').show();
    }).ajaxStop(function () {
        $('#ajax_div').hide();
    });
});
function alerts(type, text, timeout, afterShow) {
    if (timeout === undefined || timeout === '') {
        timeout = 2500;
    }
    if (typeof (afterShow) !== 'function') {
        afterShow = function () {
        };
    }
    noty({
        text: text,
        type: type,
        dismissQueue: true,
        layout: 'top',
        theme: 'defaultTheme',
        timeout: timeout,
        callback: {
            afterShow: afterShow
        }
    });
}
function countDownInsc() {
    if ($('#counter').length > 0) {
        $('#counter').html("<div></div>");
        $('#counter').find('div').countdown({
            format: "dd:hh:mm:ss",
            startTime: $('#counter').data('time'),
            digitWidth: 30,
            digitHeight: 43,
            image: "images/digits2.png",
            timerEnd: function () {
                window.location.href = window.location.href;
            }
        });
    }
}