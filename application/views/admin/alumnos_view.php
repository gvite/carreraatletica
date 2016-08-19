<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="col-md-12">
    <form action="<?php echo base_url(); ?>admin/alumnos/busqueda" class="form-signin form-horizontal" id="busqueda_form">
        <div class="form-group">
            <label class="radio-inline control-label" for="tipo_alumnos_1">Todo
                <input name="alumnos" type="radio" id="tipo_alumnos_1" value="0"/>
            </label>
            <label class="radio-inline control-label" for="tipo_alumnos_2">Especificar
                <input name="alumnos" type="radio" id="tipo_alumnos_2" value="1"/>
            </label>
        </div>
        <div class="form-group" id="semestres_content" style="display: none">
            <?php
            if (is_array($semestres)) {
                foreach ($semestres as $key => $semestre) {
                    ?>
                    <label class="radio-inline control-label" for="semestre_<?php echo $key; ?>">
                        <input name="semestres[]" type="checkbox" id="semestre_<?php echo $key; ?>" value="<?php echo $semestre['id']; ?>" /> <?php echo $semestre['semestre'] ?>
                    </label>
                <?php }
            }
            ?>
        </div>
        <div class="form-group" id="tipos_alumnos_content" style="display: none">
            <label class="radio-inline control-label" for="tipo_alumnos">
                <input name="tipo_alumnos[]" type="checkbox" id="tipo_alumnos" data-id="alumnos_data" value="2" /> Alumnos
            </label>
            <label class="radio-inline control-label" for="tipo_exalumnos">
                <input name="tipo_alumnos[]" type="checkbox" id="tipo_exalumnos" data-id="exalumnos_data" value="3"/> Ex-Alumnos
            </label>
            <label class="radio-inline control-label" for="tipo_trabajadores">
                <input name="tipo_alumnos[]" type="checkbox" id="tipo_trabajadores" data-id="trabajadores_data" value="4"/> Trabajadores
            </label>
            <label class="radio-inline control-label" for="tipo_externos">
                <input name="tipo_alumnos[]" type="checkbox" id="tipo_externos" data-id="externos_data" value="5"/> Externos
            </label>
        </div>
        <div class="form-group row" id="datos_usuarios">            
            <div class="col-md-3" style="display: none" id="alumnos_data">
                <div class="content_data panel panel-info">
                    <div class="panel-heading"><h3><input type="checkbox" class="all_select" />Alumnos</h3></div>
                    <div class="panel-body">
                        <div class="checkbox">
                            <label class="control-label" for="nombre_alumno">
                                <input name="alumnos_data[]" type="checkbox" id="nombre_alumno" value="nombre" />Nombre
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="cta_alumno">
                                <input class="checkbox" name="alumnos_data[]" type="checkbox" id="cta_alumno" value="no_cuenta" />No. Cta
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="ingreso_alumno">
                                <input class="checkbox" name="alumnos_data[]" type="checkbox" id="ingreso_alumno" value="ingreso"/>Ingreso
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="carrera_alumno">
                                <input class="checkbox" name="alumnos_data[]" type="checkbox" id="carrera_alumno" value="carrera"/>Carrera
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="materias_alumno">
                                <input class="checkbox" name="alumnos_data[]" type="checkbox" id="materias_alumno" value="materias"/>Materias inscritas (validadas)
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="materias_sin_alumno">
                                <input class="checkbox" name="alumnos_data[]" type="checkbox" id="materias_sin_alumno" value="materias_sin"/>Materias inscritas (no validadas)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3" style="display: none" id="exalumnos_data">
                <div class="content_data panel panel-info">
                    <div class="panel-heading"><h3><input type="checkbox" class="all_select" />Ex-Alumnos</h3></div>
                    <div class="panel-body">
                        <div class="checkbox">
                            <label class="control-label" for="nombre_exalumno">
                                <input name="exalumnos_data[]" type="checkbox" id="nombre_exalumno" value="nombre"/>Nombre
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="cta_exalumno">
                                <input name="exalumnos_data[]" type="checkbox" id="cta_exalumno" value="no_cuenta"/>No. Cta
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="ingreso_exalumno">
                                <input name="exalumnos_data[]" value="egreso" type="checkbox" id="ingreso_exalumno" />Egreso
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="carrera_exalumno">
                                <input name="exalumnos_data[]" value="carrera" type="checkbox" id="carrera_exalumno" />Carrera
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="materias_exalumno">
                                <input name="exalumnos_data[]" value="materias" type="checkbox" id="materias_exalumno" />Materias inscritas (validadas)
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="materias_sin_exalumno">
                                <input name="exalumnos_data[]" value="materias_sin" type="checkbox" id="materias_sin_exalumno" />Materias inscritas (no validadas)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3" style="display: none" id="trabajadores_data">
                <div class="content_data panel panel-info">
                    <div class="panel-heading"><h3><input type="checkbox" class="all_select" />Trabajadores</h3></div>
                    <div class="panel-body">
                        <div class="checkbox">
                            <label class="control-label" for="nombre_trabajador">
                                <input name="trabajadores_data[]" value="nombre" type="checkbox" id="nombre_trabajador" />Nombre
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="no_trabajador">
                                <input name="trabajadores_data[]" value="no_trabajador" type="checkbox" id="no_trabajador" />No. Trabajador
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="turno_trabajador">
                                <input name="trabajadores_data[]" value="turno" type="checkbox" id="turno_trabajador" />Turno
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="area_trabajador">
                                <input name="trabajadores_data[]" value="area" type="checkbox" id="area_trabajador" />Area
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="materias_trabajador">
                                <input name="trabajadores_data[]" value="materias" type="checkbox" id="materias_trabajador" />Materias inscritas (validadas)
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="materias_sin_trabajador">
                                <input name="trabajadores_data[]" value="materias_sin" type="checkbox" id="materias_sin_trabajador" />Materias inscritas (no validadas)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3" style="display: none" id="externos_data">
                <div class="content_data panel panel-info">
                    <div class="panel-heading"><h3><input type="checkbox" class="all_select" />Externos</h3></div>
                    <div class="panel-body">
                        <div class="checkbox">
                            <label class="control-label" for="nombre_externo">
                                <input name="externos_data[]" value="nombre" type="checkbox" id="nombre_externo" />Nombre
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="ocupacion_externo">
                                <input name="externos_data[]" value="ocupacion" type="checkbox" id="ocupacion_externo" />Ocupaci&oacute;n
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="direccion_externo">
                                <input name="externos_data[]" value="direccion" type="checkbox" id="direccion_externo" />Direcci&oacute;n
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="telefono_externo">
                                <input name="externos_data[]" value="telefono" type="checkbox" id="telefono_externo" />Tel&eacute;fono
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="materias_externo">
                                <input name="externos_data[]" value="materias" type="checkbox" id="materias_externo" />Materias inscritas(validadas)
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="control-label" for="materias_sin_externo">
                                <input name="externos_data[]" value="materias_sin" type="checkbox" id="materias_sin_externo" />Materias inscritas(no validadas)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary pull-right">Consultar</button>
    </form>
</div>