<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-6">
        <form class="form-signin form-horizontal" id="actualiza_form" action="<?php echo base_url(); ?>perfil/update/<?php echo $alumno['id']?>">
            <h3>Datos de acceso</h3>
            <div class="control-group">
                <label class="control-label" for="user_input">*Nickname</label>
                <div class="controls">
                    <input name="user" id="user_input" value="<?php echo $alumno['nickname']; ?>" class="form-control" type="text" placeholder="usuario" disabled="disabled" />
                </div>
            </div>
            <h3>Datos personales</h3>
            <div class="control-group">
                <label class="control-label" for="name_user">*Nombre</label>
                <div class="controls">
                    <input type="text" name="name_user" value="<?php echo $alumno['nombre']; ?>" class="form-control" id="name_user" placeholder="Nombre(s)" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="paterno_user">*Apellido Paterno</label>
                <div class="controls">
                    <input type="text" name="paterno_user" value="<?php echo $alumno['paterno']; ?>" class="form-control" id="paterno_user" placeholder="Apellido Paterno" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="materno_user">Apellido Materno</label>
                <div class="controls">
                    <input type="text" name="materno_user" value="<?php echo $alumno['materno']; ?>" class="form-control" id="materno_user" placeholder="Apellido Materno" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="correo_user">*E-Mail</label>
                <div class="controls">
                    <input type="text" name="correo_user" value="<?php echo $alumno['email']; ?>" id="correo_user" placeholder="user@example.com" class="input-xlarge form-control" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="nacimiento_user_input">*Fecha de Nacimiento</label>
                <div class="controls">
                    <div class="input-group date" id="nacimiento_user" data-date-format="DD-MM-YYYY">
                        <input name="nacimiento_user" value="<?php echo exchange_date($alumno['nacimiento']); ?>" class="fecha_input form-control" id="nacimiento_user_input" type="text" placeholder="dd-mm-yyyy" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</div>
<div class="row">
    <div class="modal fade" id="registro_exito">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h3>Registro Completo</h3>
                </div>
                <div class="modal-body">
                    <p>
                        El registro se realiz&oacute; con &eacute;xito
                    </p>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
</div>