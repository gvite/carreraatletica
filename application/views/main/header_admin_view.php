<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Actividades Deportivas</title>
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>images/favicon.png" />
        <link type="text/css" href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/bootstrap-multiselect.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/prettify.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/default.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/jquery.qtip.min.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/fullcalendar.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/fullcalendar.print.css" rel="stylesheet" media="print"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/jquery-ui-timepicker-addon.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/jquery.dataTables.min.css" rel="stylesheet" media="screen"/>
        <link type="text/css" href="<?php echo base_url(); ?>css/jquery.dataTables_themeroller.min.css" rel="stylesheet" media="screen"/>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/jquery.ui.touch-punch.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/jquery-ui-sliderAccess.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/noty/packaged/jquery.noty.packaged.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/jquery-ui-sliderAccess.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/jquery.md5.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/bootstrap-multiselect.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/moment-with-langs.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/bootstrap-datetimepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/prettify.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/typeahead.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/fullcalendar.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/jquery.qtip.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/jquery.countDown.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/comun.js"></script>
        <?php
        if (isset($js)) {
            if (is_array($js)) {
                foreach ($js as $j) {
                    ?>
                    <script type="text/javascript" src="<?php echo base_url() . $j; ?>"></script>
                    <?php
                }
            } else {
                ?>
                <script type="text/javascript" src="<?php echo base_url() . $js; ?>"></script>
                <?php
            }
        }
        ?>
        <script type="text/javascript">
            var base_url = "<?php echo base_url(); ?>";
            var usuario_data = {
                user: "<?php echo (get_user()) ? get_user() : ''; ?>",
                name: "<?php echo (get_name()) ? get_name() : ''; ?>",
                type: "<?php echo get_type() ?>"
            };
            var date = new Date("<?php echo date("F d, Y H:i:s"); ?>");
            setInterval(function(){ 
                date.setSeconds(date.getSeconds() + 1);
                $('#hora_sistema .hora').text(( '0' + date.getHours()).slice(-2));
                $('#hora_sistema .minutos').text(('0' + date.getMinutes()).slice(-2));
                $('#hora_sistema .segundos').text(( '0' + date.getSeconds()).slice(-2));    
            }, 1000);
        </script>
    </head>
    <body cz-shortcut-listen="true">
        <div class="container" id="content_main">
            <div class="row images-header">
                <img src="<?php echo base_url() ?>images/logo.png"/>
                <img src="<?php echo base_url() ?>images/logo1.jpg"/>
            </div>
            <div class="row">
                <nav class="navbar navbar-default" role="navigation">
                    <!-- El logotipo y el icono que despliega el menú se agrupan
               para mostrarlos mejor en los dispositivos móviles -->
                    <div class="navbar-header">
                        <a class="navbar-brand" href="<?php echo base_url() ?>inicio.jsp">Actividades Deportivas - FES Arag&oacute;n <strong id="hora_sistema"><span class="hora"><?php echo date('H');?></span>:<span class="minutos"><?php echo date('i');?></span>:<span class="segundos"><?php echo date('s');?></span></strong></a>
                    </div>
                    <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                         otro elemento que se pueda ocultar al minimizar la barra -->
                    <div id="nav_bar_div">
                        <?php if (!get_id()) { ?>
                            <ul class="nav navbar-nav navbar-right">
                                <li><a id="logout_link" href="#form_login_modal" data-toggle="modal" class="btn btn-link">Entrar <span class="glyphicon glyphicon-log-in"></span></a></li>
                                <li><a class="btn btn-link" id="registro_link" href="<?php echo base_url() ?>acceso/registro.jsp">Registrar <span class="glyphicon glyphicon-edit"></span></a></li>
                            </ul>
                        <?php } else { ?>
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <a id="user_link" class="btn btn-link btn-small dropdown-toggle" data-toggle="dropdown" href="#" ><?php echo get_name(); ?> <span class="glyphicon glyphicon-user"><span class="caret"></span></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?php echo base_url() ?>acceso/cambia_contra.jsp">Cambiar Contrase&ntilde;a</a></li>
                                        <li><a href="<?php echo base_url() ?>perfil.jsp">Perfil</a></li>
                                    </ul>
                                </li>
                                <li><a id="logout_link" href="<?php echo base_url() ?>acceso/login/logout.jsp" class="btn btn-link btn-small">Salir <span class="glyphicon glyphicon-log-out"></span></a></li>
                            </ul>
                        <?php } ?>
                    </div>
                </nav>
            </div>
            <div class="row">
                <?php if(get_id()){?>
                <div class="col-md-2">
                    <ul class="nav nav-pills nav-stacked nav-divider" id="main_menu">
                        <?php
                        if (!isset($no_menu)) {
                            if (!isset($active)) {
                                $active = '';
                            }
                            ?>
                            <li class="<?php echo ($active === 'inicio') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>inicio.jsp" data-name="inicio"><span class="glyphicon glyphicon-home"></span> Inicio</a></li>
                            <?php
                            if (isset($semestre_actual) && $semestre_actual) {
                                ?>
                                <li class="<?php echo ($active === 'horarios') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>horarios.jsp" data-name="horarios"><span class="glyphicon glyphicon-calendar"></span> Horarios</a></li>
                                <?php
                            }
                            switch (get_type()) {
                                case 1:
                                    ?>
                                <li class="<?php echo ($active === 'actividades') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>admin/talleres.jsp" data-name="actividades"><span class="glyphicon glyphicon-book"></span> Registro de &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;actividades</a></li>
                                    <?php if (isset($puede_inscribir) && !$puede_inscribir) { ?>
                                    <li class="<?php echo ($active === 'inscribir') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>admin/inscribir.jsp" data-name="inscribir"><span class="glyphicon glyphicon-pencil"></span> Inscripci&oacute;n</a></li>
                                    <?php } ?>
                                    <li class="<?php echo ($active === 'validacion') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>admin/validacion.jsp" data-name="validacion"><span class="glyphicon glyphicon-check"></span> Validaci&oacute;n</a></li>
                                    <li class="<?php echo ($active === 'listas') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>admin/listas.jsp" data-name="listas"><span class="glyphicon glyphicon-list"></span> Listas</a></li>
                                    <li class="<?php echo ($active === 'alumnos') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>admin/alumnos.jsp" data-name="alumnos"><span class="glyphicon glyphicon-user"></span> Alumnos</a></li>
                                    <li class="<?php echo ($active === 'reportes') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>admin/reportes/carrera.jsp" data-name="reportes"><span class="glyphicon glyphicon-file"></span> Reportes</a></li>
                                    <?php
                                    if(get_type_user() == 1){
                                    ?>
                                        <!--<li class="<?php echo ($active === 'usuarios') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>admin/usuarios.jsp" data-name="usuarios"><span class="glyphicon glyphicon-user"></span>Usuarios</a></li>-->
                                    <?php
                                        if (isset($puede_inscribir) && $puede_inscribir) {
                                            ?>
                                            <li class="<?php echo ($active === 'inscripcion') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>alumnos/inscripcion.jsp" data-name="inscripcion"><span class="glyphicon glyphicon-pencil"></span> Inscripci&oacute;n Prueba<br /></a></li>
                                            <li class="<?php echo ($active === 'limpiar') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>admin/limpiar.jsp" data-name="inscripcion"><span class="glyphicon glyphicon-pencil"></span> Limpiar Inscripci&oacute;n<br /></a></li>
                                        <?php
                                        }
                                    }
                                    break;
                                case 2:
                                    if (isset($puede_inscribir) && $puede_inscribir) {
                                        ?>
                                        <li class="<?php echo ($active === 'inscripcion') ? 'active' : ''; ?>"><a href="<?php echo base_url() ?>alumnos/inscripcion.jsp" data-name="inscripcion"><span class="glyphicon glyphicon-pencil"></span> Inscripci&oacute;n<br /><span class="glyphicon glyphicon-list-alt"></span> Comprobantes</a></li>
                                    <?php
                                    }
                                    break;
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-md-10">
                    <div id="container">
                <?php }else{ ?>
                <div class="col-md-12">
                    <div id="container">
                    <?php }?>