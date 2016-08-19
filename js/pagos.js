function init_pagos(){
    $('#sub_menu_pasos a').on('click' , function(event){
        event.preventDefault();
        $(this).closest('.nav-tabs').find('li').removeClass();
        $(this).parent().addClass('active');
        var $this = $(this);
        $.ajax({
            url:$(this).attr('href'),
            type: 'POST',
            dataType:'json',
            success:function(data){
                if(data.status === "OK"){
                    $('#sub_container').html(data.content);
                    $.getScript(base_url + 'js/'+$this.data('name')+'.js' , function(){
                        window['init_' + $this.data('name')].call(this);
                    });
                }
            }
        });
    });
    $('#sub_menu_pasos li.active a').trigger('click');
}