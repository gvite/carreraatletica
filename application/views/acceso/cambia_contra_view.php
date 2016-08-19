<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-6">
        <form class="form-signin form-horizontal" id="cambia_contra_form" action="<?php echo base_url(); ?>acceso/cambia_contra/cambia">
            <h3>Cambio de contrase&ntilde;a</h3>
            <div class="control-group">
                <label class="control-label" for="pass_input_aux">*Contrase&ntilde;a anterior</label>
                <div class="controls">
                    <input type="password" name="" class="form-control" id="pass_ant_input_aux" placeholder="Password">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="pass_input_aux">*Contrase&ntilde;a nueva</label>
                <div class="controls">
                    <input type="password" name="" class="form-control" id="pass_input_aux" placeholder="Password">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="repass_input_aux">*Repite Contrase&ntilde;a nueva</label>
                <div class="controls">
                    <input type="password" name="" class="form-control" id="repass_input_aux" placeholder="Password">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Cambiar</button>
        </form>
    </div>
</div>
