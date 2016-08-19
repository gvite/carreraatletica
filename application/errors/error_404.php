<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!function_exists('base_url')) {

    function base_url() {
        return 'http://localhost/actividades_culturales_nuevo/';
    }

}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Actividades Culturales</title>
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>images/favicon.png" />
        <link type="text/css" href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/bootstrap-multiselect.css" rel="stylesheet" media="screen"/>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/bootstrap.js"></script>
        <script type="text/javascript">
            var base_url = "<?php echo base_url(); ?>";
        </script>
        <style>
            #no_found span.number{
                font-size: 180px;
            }
            #no_found span.text{
                font-size: 40px;
            }
            body > #content_main .images-header img{
                display: inline;
            }
            body > #content_main .images-header img:first-child{
                width: 36%;
            }
            body > #content_main .images-header img + img{
                width: 63%;
            }
        </style>
    </head>
    <body cz-shortcut-listen="true">
        <div class="container" id="content_main">
            <div class="row images-header">
                <img src="<?php echo base_url() ?>images/logo.png"/>
                <img src="<?php echo base_url() ?>images/logo1.jpg"/>
            </div>
            <div class="row">
                <nav class="navbar navbar-inverse" role="navigation">
                    <!-- El logotipo y el icono que despliega el menú se agrupan
               para mostrarlos mejor en los dispositivos móviles -->
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">Extensi&oacute;n Universitaria</a>
                    </div>
                    <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                         otro elemento que se pueda ocultar al minimizar la barra -->
                    <div class="collapse navbar-collapse navbar-ex1-collapse" id="nav_bar_div">
                    </div>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="container">
                        <div id="no_found">
                            <span class="number">404</span>
                            <img src="<?php echo base_url() ?>images/404_meme.png">
                            <span class="text">P&aacute;gina no encontrada</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>