<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="modal fade active" id="form_login_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" action="<?php echo base_url();?>acceso/login/valida" role="form" id="form_login">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3>LOGIN</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-lg-2" for="user_input">Usuario</label>
                            <div class="col-lg-7">
                                <input class="form-control" name="user" type="text" placeholder="Usuario" id="user_input">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2" for="password_input">Contrase&ntilde;a</label>
                            <div class="col-lg-7">
                                <input class="form-control" name="pass" type="password" placeholder="Contrase&ntilde;a" id="password_input">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo base_url();?>acceso/login/recuperar_contra_form" id="recuperar_contrasenia" class="btn-link">Recuperar Contrase&ntilde;a</a>
                        <button type="button" data-dismiss="modal" class="btn">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>

<div class="row">
    <div class="modal fade active" id="form_recuperar_contra_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" action="<?php echo base_url() ?>acceso/login/recuperar_contra" role="form" id="form_recuperar_contra">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3>Recuperar Contrase&ntilde;a</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-lg-2" for="user_input">Email registrado</label>
                            <div class="col-lg-7">
                                <input class="form-control" name="email" type="text" placeholder="usuario@xxx.com" id="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>