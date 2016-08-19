<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form class="form-signin form-horizontal" id="registro_form" action="<?php echo base_url(); ?>acceso/registro/insert">
            <h3>Datos de acceso</h3>
            <div class="control-group">
                <label class="control-label" for="user_input">*Nickname</label>
                <div class="controls">
                    <input name="user" id="user_input" class="form-control" type="text" placeholder="usuario" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="pass_input_aux">*Contrase&ntilde;a</label>
                <div class="controls">
                    <input type="password" name="" class="form-control" id="pass_input_aux" placeholder="Password">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="repass_input_aux">*Repite Contrase&ntilde;a</label>
                <div class="controls">
                    <input type="password" name="" class="form-control" id="repass_input_aux" placeholder="Password">
                </div>
            </div>
            <h3>Datos personales</h3>
            <div class="control-group">
                <label class="control-label" for="name_user">*Nombre</label>
                <div class="controls">
                    <input type="text" name="name_user" class="form-control" id="name_user" placeholder="Nombre(s)" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="paterno_user">*Apellido Paterno</label>
                <div class="controls">
                    <input type="text" name="paterno_user" class="form-control" id="paterno_user" placeholder="Apellido Paterno" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="materno_user">Apellido Materno</label>
                <div class="controls">
                    <input type="text" name="materno_user" class="form-control" id="materno_user" placeholder="Apellido Materno" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="correo_user">*E-Mail</label>
                <div class="controls">
                    <input type="text" name="correo_user" id="correo_user" placeholder="user@example.com" class="input-xlarge form-control" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="nacimiento_user_input">*Fecha de Nacimiento</label>
                <div class="controls">
                    <div class="input-group date" id="nacimiento_user" data-date-format="DD-MM-YYYY">
                        <input name="nacimiento_user" class="fecha_input form-control" id="nacimiento_user_input" type="text" placeholder="dd-mm-yyyy" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">*Sexo</label>
                <div class="controls">
                    <div class="input-group" id="sexo_user">
                        <label class="radio-inline" for="masculino_user">
                            <input type="radio" name="sexo_user" id="masculino_user" value="1"> Hombre
                        </label>
                        <label class="radio-inline" for="femenino_user">
                            <input type="radio" name="sexo_user" id="femenino_user" value="2"> Mujer
                        </label>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="telefono_user">Teléfono</label>
                <div class="controls">
                    <input type="text" name="telefono_user" id="telefono_user" placeholder="telefono" class="input-xlarge form-control" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="type_user">*Tipo de Usuario</label>
                <div class="controls">
                    <select name="type_user" id="type_user" class="form-control">
                        <option value="0" selected>Selecciona</option>
                        <option value="2">Alumno</option>
                        <option value="3">Ex-Alumno</option>
                        <option value="4">Trabajador</option>
                        <option value="5">Externo</option>
                    </select>
                </div>
            </div>
            <div id="datos_alumnos" class="datos_usuario_tipo">
                <div class="control-group">
                    <label class="control-label" for="num_cuenta">*No. Cuenta</label>
                    <div class="controls">
                        <input type="text" id="num_cuenta" class="form-control" name="num_cuenta" />
                    </div>
                </div>
                <div>
                    <label class="control-label" for="carrera_select">*Carrera</label>
                    <div class="control">
                        <select name="carrera" id="carrera_select" class="form-control">
                            <?php
                            if (is_array($carreras)) {
                                foreach ($carreras as $carrera) {
                                    ?>
                                    <option value="<?php echo $carrera['id'] ?>"><?php echo $carrera['carrera'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label id="label_ingreso" class="control-label" for="ingreso_egreso">*A&ntilde;o de ingreso</label>
                    <div class="controls">
                        <input type="text" id="ingreso_egreso" name="ingreso_egreso" class="input-small form-control" />
                    </div>
                </div>
            </div>
            <div id="datos_trabajador" class="datos_usuario_tipo">
                <div class="control-group">
                    <label class="control-label" for="num_trabajador">*No. Trabajador</label>
                    <div class="controls">
                        <input type="text" id="num_trabajador" class="form-control" name="num_trabajador" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="turno_prof">*Turno</label>
                    <div class="controls">
                        <input type="radio" id="turno_prof" class="form-control" name="turno_prof" value="0" />Matutuno
                        <input type="radio" id="turno_prof" class="form-control" name="turno_prof" value="1" />Vespertino
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="area">*Area</label>
                    <div class="controls">
                        <input type="text" id="area" class="form-control" name="area" />
                    </div>
                </div>
            </div>
            <div id="datos_externo" class="datos_usuario_tipo">
                <div class="control-group">
                    <label class="control-label" for="direccion">*Direcci&oacute;n</label>
                    <div class="controls">
                        <input type="text" id="direccion" name="direccion" placeholder="Direcci&oacute;n" class="input-xxlarge form-control" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="ocupacion">*Ocupaci&oacute;n</label>
                    <div class="controls">
                        <select name="ocupacion" id="carrera_select" class="form-control">
                            <?php
                            if (is_array($ocupaciones)) {
                                foreach ($ocupaciones as $ocupacion) {
                                    ?>
                                    <option value="<?php echo $ocupacion['id'] ?>"><?php echo $ocupacion['ocupacion'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <input type="hidden" name="pass" class="form-control" id="pass_input" />
            <input type="hidden" name="repass" class="form-control" id="repass_input">
            <div class="control-group" style="text-align:center;margin-top:15px">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="modal fade" id="registro_exito">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-signin form-horizontal" id="semestre_form" action="admin/semestres/insert">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h3>Registro Completo</h3>
                    </div>
                    <div class="modal-body">
                        <p>
                            El registro se realiz&oacute; con &eacute;xito
                        </p>
                        <div id="anchor"></div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>