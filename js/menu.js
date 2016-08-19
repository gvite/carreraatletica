function init_menu(){
    $("#main_menu a").on('click' , function(event){
        $("#main_menu li").removeClass('active');
        $(this).parent().addClass('active');
        event.preventDefault();
        var $this = $(this);
        if($this.data('name') !== 'inicio'){
            $.ajax({
                url:$this.attr('href'),
                type: 'POST',
                dataType:'json',
                success:function(data){
                    if(data.status === "OK"){
                        $("#container").html(data.content);
                        $.getScript(base_url + 'js/'+$this.data('name')+'.js' , function(){
                            window['init_' + $this.data('name')].call(this);
                        });
                    }else{
                        alerts(data.type , data.message);
                    }
                }
            });
        }else{
            window.location.href = base_url;
        }
    });
}