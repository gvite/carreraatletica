<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="sub_menu_pasos">
            <li><a href="<?php echo base_url() ?>admin/talleres.jsp" data-name="talleres">Actividades</a></li>
            <li><a href="<?php echo base_url() ?>admin/semestres.jsp" data-name="semestres">Semestres</a></li>
            <li><a href="<?php echo base_url() ?>admin/profesores.jsp" data-name="profesores">Profesores</a></li>
            <li class="active"><a href="<?php echo base_url() ?>admin/salones.jsp" data-name="salones">Lugares</a></li>
            <li><a href="<?php echo base_url() ?>admin/talleres_semestre.jsp" data-name="talleres_semestre">Asignaci&oacute;n</a></li>
        </ul>
    </div>
</div>
<div class="col-md-10">
    <div class="row">
        <div class="col-md-1" >
            <a href="#add_salones_modal" id="btn_add_salones_modal" data-toggle="modal" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus-sign">&nbsp;</span>Agregar</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Lugares</h3>
                </div>
                <div class="panel-body">
                    <table id="table_salones" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Lugar</th>
                                <th>Cupo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (is_array($salones)) {
                                foreach ($salones as $salon) {
                                    ?>
                                    <tr data-id="<?php echo $salon['id'] ?>" data-events="0">
                                        <td><?php echo $salon['salon'] ?></td>
                                        <td><?php echo $salon['cupo'] ?></td>
                                        <td><button class="btn btn-sm btn-editar">Editar</button><button class="btn btn-sm btn-eliminar">Eliminar</button></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr class="no_registros">
                                    <td colspan="3">No hay registros</td>
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
        <div class="modal fade" id="add_salones_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-signin form-horizontal" id="salon_form" action="admin/salones/insert">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal" type="button">&times;</button>
                            <h3>Agregar Lugar</h3>
                        </div>
                        <div class="modal-body">
                            <div class="control-group">
                                <label class="control-label" for="nombre_input">Lugar</label>
                                <div class="controls">
                                    <input name="salon" class="form-control" id="nombre_input" type="text" placeholder="Sal&oacute;n" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cupo_input">Cupo</label>
                                <div class="controls">
                                    <input name="cupo" class="form-control" id="cupo_input" type="number" placeholder="Cupo(opcional)" />
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