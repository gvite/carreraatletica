<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if ($tiempo !== false) {
    ?>
    <div class="row pull-right" id="content_count">
        <div><p>Inscripci&oacute;n inicia en:</p></div>
        <div id="counter" data-time="<?php echo $tiempo ?>"></div>
    </div>
<?php } ?>
<!--    <div class="col-md-12">
        <h3>Candidatos a inscribirse al Taller de Piano:</h3>
        <p>Inmediatamente después de haber obtenido el voucher que genera el sistema, favor de presentarlo en el Departamento de Actividades Culturales, como parte indispensable de su procedimiento de inscripción. Las inscripciones que no atiendan a este requerimiento serán canceladas.</p>
    </div>-->
    
        <?php
        if (is_array($talleres)) {
            $count = 0;
            foreach ($talleres as $taller) {
                ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="thumbnail">
                            <img src="<?php echo base_url();?>images/talleres/<?php echo $taller['id'] . '_image' ?>.jpg" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        
                        <h2><?php echo $taller['taller'] ?></h2>
                        
                        <div class="bs-callout bs-callout-primary">
                            <h4>Objetivos</h4>
                            <p><?php echo $taller['objetivo'] ?></p>
                        </div>
                        <div class="bs-callout bs-callout-primary">
                            <h4>Requisitos</h4>
                            <p><?php echo $taller['requisitos'] ?></p>
                        </div>
                        <div class="bs-callout bs-callout-primary">
                            <h4>Información</h4>
                            <p><?php echo $taller['informacion'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th colspan="4">Aportaci&oacute;n</th>
                                </tr>
                                <tr>
                                    <th>Alumno</th>
                                    <th>Ex-Alumno</th>
                                    <th>Trabajador</th>
                                    <th>Externo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4">Hasta el 21 de Octubre del 2016</td>
                                </tr>
                                <tr>
                                    <td>$ <?php echo $taller['costo_alumno'] ?></td>
                                    <td>$ <?php echo $taller['costo_exalumno'] ?></td>
                                    <td>$ <?php echo $taller['costo_trabajador'] ?></td>
                                    <td>$ <?php echo $taller['costo_externo'] ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4">Del 22 de Octubre al 04 de Noviembre del 2016</td>
                                </tr>
                                <td>$ 180</td>
                                <td>$ 180</td>
                                <td>$ 180</td>
                                <td>$ 200</td>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" style="text-align:center">
                    <a class="btn btn-primary" id="registro_link" href="<?php echo base_url() ?>acceso/registro.jsp">Registrar <span class="glyphicon glyphicon-edit"></span></a>
                </div>
        <?php
            }
        }else{
            ?>
            <p style="font-size:20px;padding: 30px 0 30px 0;text-align:center;">Por el momento no se encuentran actividades para inscribir. <br />Sigue al pendiente de las inscripciones.<br />¡Gracias por tu atención!</p>
            <?php
        }
        ?>