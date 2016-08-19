<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="sub_menu_pasos">
            <li class="active"><a href="<?php echo base_url() ?>admin/talleres.jsp" data-name="talleres">Actividades</a></li>
            <li><a href="<?php echo base_url() ?>admin/semestres.jsp" data-name="semestres">Semestres</a></li>
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
                <a href="#add_talleres_modal" data-toggle="modal" id='btn_add_talleres_modal' class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus-sign">&nbsp;</span>Agregar</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Actividades</h3>
                    </div>
                    <div class="panel-body">
                        <table id="table_talleres" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Actividad</th>
                                    <th>Costo Alumno</th>
                                    <th>Costo Ex-Alumno</th>
                                    <th>Costo Trabajador</th>
                                    <th>Costo Externo</th>
                                    <th style="display:none">Objetivos</th>
                                    <th style="display:none">Requisitos</th>
                                    <th style="display:none">Informaci&oacute;n</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (is_array($talleres)) {
                                    foreach ($talleres as $taller) {
                                        ?>
                                        <tr data-id="<?php echo $taller['id'] ?>" data-events="0">
                                            <td><?php echo $taller['taller'] ?></td>
                                            <td><?php echo $taller['costo_alumno'] ?></td>
                                            <td><?php echo $taller['costo_exalumno'] ?></td>
                                            <td><?php echo $taller['costo_trabajador'] ?></td>
                                            <td><?php echo $taller['costo_externo'] ?></td>
                                            <td style="display:none"><?php echo $taller['objetivo'] ?></td>
                                            <td style="display:none"><?php echo $taller['requisitos'] ?></td>
                                            <td style="display:none"><?php echo $taller['informacion'] ?></td>
                                            <td><button class="btn btn-sm btn-editar">Editar</button><button class="btn btn-sm btn-eliminar">Eliminar</button></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="no_registros">
                                        <td colspan="2">No hay registros</td>
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
        <div class="modal fade" id="add_talleres_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal" id="talleres_form" action="<?php echo base_url() ?>admin/talleres/insert.jsp">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal" type="button">&times;</button>
                            <h3>Agregar Actividad</h3>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label col-lg-2" for="nombre_input">Nombre</label>
                                <div class="col-lg-10">
                                    <input name="taller" id="taller_input" class="form-control" type="text" placeholder="Taller" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-2" for="costo_a_input">Costo Alumno</label>
                                <div class="col-lg-10">
                                    <input name="costo_a" id="costo_a_input" class="form-control" type="text" placeholder="Costo" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-2" for="costo_e_input">Costo Ex-Alumno</label>
                                <div class="col-lg-10">
                                    <input name="costo_e" id="costo_e_input" class="form-control" type="text" placeholder="Costo" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-2" for="costo_t_input">Costo Trabajador</label>
                                <div class="col-lg-10">
                                    <input name="costo_t" id="costo_t_input" class="form-control" type="text" placeholder="Costo" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-2" for="costo_ex_input">Costo Externo</label>
                                <div class="col-lg-10">
                                    <input name="costo_ex" id="costo_ex_input" class="form-control" type="text" placeholder="Costo" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-2" for="objetivo_area">Objetivo</label>
                                <div class="col-lg-10">
                                    <textarea name="objetivo" id="objetivo_area" class="form-control" placeholder="Objetivo"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-2" for="requisitos_area">Requisitos</label>
                                <div class="col-lg-10">
                                    <textarea name="requisitos" id="requisitos_area" class="form-control" placeholder="Requisitos"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-2" for="informacion_area">Informacion Adicional</label>
                                <div class="col-lg-10">
                                    <textarea name="informacion" id="informacion_area" class="form-control" placeholder="Informacion"></textarea>
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