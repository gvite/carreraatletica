<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="sub_menu_pasos">
            <li><a href="<?php echo base_url() ?>admin/talleres.jsp" data-name="talleres">Actividades</a></li>
            <li class="active"><a href="<?php echo base_url() ?>admin/semestres.jsp" data-name="semestres">Semestres</a></li>
            <li><a href="<?php echo base_url() ?>admin/profesores.jsp" data-name="profesores">Profesores</a></li>
            <li><a href="<?php echo base_url() ?>admin/salones.jsp" data-name="salones">Lugares</a></li>
            <li><a href="<?php echo base_url() ?>admin/talleres_semestre.jsp" data-name="talleres_semestre">Asignaci&oacute;n</a></li>
        </ul>
    </div>
</div>
<div class="row" id="sub_container">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-1" >
                <a href="#add_semestre_modal" id="btn_add_semestres_modal" data-toggle="modal" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus-sign">&nbsp;</span>Agregar</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Semestres / Intersemestrales</h3>
                    </div>
                    <div class="panel-body">
                        <table id="semestres_table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Semestre / Intersemestral</th>
                                    <th>Inicio</th>
                                    <th>Termino</th>
                                    <th>Inicio de Inscripci&oacute;n</th>
                                    <th>Termino de Inscripci&oacute;n</th>
                                    <th>Termino de Validación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (is_array($semestres)) {
                                    foreach ($semestres as $semestre) {
                                        ?>
                                        <tr data-id="<?php echo $semestre['id'] ?>" data-events="0">
                                            <td><?php echo $semestre['semestre'] ?></td>
                                            <td><?php echo exchange_date($semestre['ini_sem']); ?></td>
                                            <td><?php echo exchange_date($semestre['fin_sem']); ?></td>
                                            <td><?php echo exchange_date_time($semestre['ini_insc']); ?></td>
                                            <td><?php echo exchange_date_time($semestre['fin_insc']); ?></td>
                                            <td><?php echo exchange_date_time($semestre['fin_validacion']); ?></td>
                                            <td><button class="btn btn-sm btn-editar">Editar</button><button class="btn btn-sm btn-eliminar">Eliminar</button></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="no_registros">
                                        <td colspan="4">No hay registros</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div>
            <div class="modal fade" id="add_semestre_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-signin form-horizontal" id="semestre_form" action="admin/semestres/insert">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                <h3>Agregar Semestre / Intersemestral</h3>
                            </div>
                            <div class="modal-body">
                                <div class="control-group">
                                    <label class="control-label" for="nombre_input">Nombre</label>
                                    <div class="controls">
                                        <input name="nombre" class="form-control" id="nombre_input" type="text" placeholder="Nombre" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inicio_input">Fecha de inicio</label>
                                    <div class="controls">
                                        <div class="input-group date fecha_input" id="nacimiento_user" data-date-format="DD-MM-YYYY">
                                            <input name="inicio" class="form-control" id="inicio_input" type="text" placeholder="dd-mm-yyyy" />
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="termino_input">Fecha de Termino</label>
                                    <div class="controls">
                                        <div class="input-group date fecha_input" id="nacimiento_user" data-date-format="DD-MM-YYYY">
                                            <input name="termino" class="form-control" id="termino_input" type="text" placeholder="dd-mm-yyyy" />
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inicio_insc_input">Inicio de inscripci&oacute;n</label>
                                    <div class="controls">
                                        <div class="input-group date fecha_time_input" id="nacimiento_user" data-date-format="DD-MM-YYYY HH:mm:ss">
                                            <input name="ini_insc" class="form-control" id="inicio_insc_input" type="text" placeholder="dd-mm-yyyy hh:mm:ss" />
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="termino_insc_input">Termino de Inscripci&oacute;n</label>
                                    <div class="controls">
                                        <div class="input-group date fecha_time_input" id="nacimiento_user" data-date-format="DD-MM-YYYY HH:mm:ss">
                                            <input name="fin_insc" class="form-control" id="termino_insc_input" type="text" placeholder="dd-mm-yyyy hh:mm:ss" />
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="validacion_input">Termino de la validación</label>
                                    <div class="controls">
                                        <div class="input-group date fecha_time_input" id="nacimiento_user" data-date-format="DD-MM-YYYY HH:mm:ss">
                                            <input name="validacion" class="form-control" id="validacion_input" type="text" placeholder="dd-mm-yyyy hh:mm:ss" />
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>