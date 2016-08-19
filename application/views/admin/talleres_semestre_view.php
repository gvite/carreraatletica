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
            <li><a href="<?php echo base_url() ?>admin/salones.jsp" data-name="salones">Lugares</a></li>
            <li class="active"><a href="<?php echo base_url() ?>admin/talleres_semestre.jsp" data-name="talleres_semestre">Asignaci&oacute;n</a></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default" id="talleres_semestre_div">
            <div class="panel-heading" data-count="<?php echo count($semestres) ?>">
                <button type="button" class="btn btn-default btn-sm pull-left">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </button>
                <button type="button" class="btn btn-default btn-sm pull-right">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </button>
                <div>
                    <?php
                    $count = 1;
                    if (is_array($semestres)) {
                        foreach ($semestres as $semestre) {
                            if ($semestre_actual === false && $count === 1) {
                                echo '<h3 class="actual title_' . $count . '" data-index="' . $count . '" data-id="' . $semestre['id'] . '">' . $semestre['semestre'] . '</h3>';
                            } else if ($semestre_actual !== false && ($semestre['id'] === $semestre_actual['id'])) {
                                echo '<h3 class="actual title_' . $count . '" data-index="' . $count . '" data-id="' . $semestre['id'] . '">' . $semestre['semestre'] . '</h3>';
                            } else {
                                echo '<h3 style="display:none" class="title_' . $count . '" data-index="' . $count . '" data-id="' . $semestre['id'] . '">' . $semestre['semestre'] . '</h3>';
                            }
                            $count++;
                        }
                    }
                    ?>
                </div>
                <button class="btn btn-default btn-sm btn-agregar-taller-sem">
                    <span class="glyphicon glyphicon-plus"></span>
                    <span class="name_button">Agregar</span>
                </button>
            </div>
            <div class="panel-body">
                
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div id="calendar_actividades"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="modal fade" id="add_talleres_semestre_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-signin form-horizontal" id="taller_semestre_form" action="admin/talleres_semestre/insert">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal" type="button">&times;</button>
                            <h3>Agregar nuevo</h3>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="semestre" id="semestre_input_hidden" value=""/>
                            <div class="control-group">
                                <label class="control-label" for="taller_select">Actividad</label>
                                <div class="controls">
                                    <select name="taller" class="form-control" id="taller_select">
                                        <option value="0">---</option>
                                        <?php
                                        if (is_array($talleres)) {
                                            foreach ($talleres as $taller) {
                                                ?>
                                                <option value="<?php echo $taller['id'] ?>"><?php echo $taller['taller'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="profesores_select">Profesor</label>
                                <div class="controls">
                                    <select name="profesor" class="form-control" id="profesores_select">
                                        <option value="0">---</option>
                                        <?php
                                        if (is_array($profesores)) {
                                            foreach ($profesores as $profesor) {
                                                ?>
                                                <option value="<?php echo $profesor['id'] ?>"><?php echo $profesor['nombre'] . ' ' . $profesor['paterno'] . ' ' . $profesor['materno'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="salones_select">Lugar</label>
                                <div class="controls">
                                    <select name="salon" class="form-control" id="salones_select">
                                        <option value="0" data-cupo="">---</option>
                                        <?php
                                        if (is_array($salones)) {
                                            foreach ($salones as $salon) {
                                                ?>
                                                <option data-cupo="<?php echo $salon['cupo'] ?>" value="<?php echo $salon['id'] ?>"><?php echo $salon['salon'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cupo_input">Cupo</label>
                                <div class="controls">
                                    <input type="number" class="form-control" name="cupo" id="cupo_input" value=""/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="grupo_input">Grupo</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name="grupo" id="grupo_input" value=""/>
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