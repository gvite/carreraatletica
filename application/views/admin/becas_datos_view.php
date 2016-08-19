<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div id="datos_baucher" class="row">
    <div class="datos_alumno">
        <div><span class="nombre-span">Folio: </span><span class="content-span"><?php echo str_pad($baucher['folio'], 11, "0", STR_PAD_LEFT); ?></span></div>
        <div>
            <span class="nombre-span">No. Recibo: </span>
            <span class="content-span">
                <?php
                if ($baucher['folio_caja'] !== null) {
                    echo $baucher['folio_caja'];
                } else {
                    if ($baucher['status'] == 1) {
                        ?>
                        <button class="btn btn-primary" href="#ingresa_folio_dialog" data-toggle="modal">Ingresar</button>
                        <?php
                    }else{
                        echo '---';
                    }
                }
                ?>
            </span>
        </div>
        <div><span class="nombre-span">Fecha Expedici&oacute;n: </span><span class="content-span"><?php echo exchange_date_time($baucher['fecha_expedicion']) ?></span></div>
        <div><span class="nombre-span">Alumno: </span><span class="content-span"><?php echo $usuario['paterno'] . ' ' . $usuario['materno'] . ' ' . $usuario['nombre'] ?></span></div>
        <div>
            <span class="nombre-span">Estado: </span><span class="content-span">
                <?php
                if ($baucher['status'] == 0) {
                    echo 'No Validado';
                } else if ($baucher['status'] == 1) {
                    echo 'Validado';
                } else if ($baucher['status'] == 2) {
                    echo 'Penalizado: A&uacute;n no se puede inscribir';
                } else {
                    echo 'No validado, pero ya puede inscribir nuevamente los Talleres';
                }
                ?>
            </span>
        </div>
    </div>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Grupo</th>
                    <th>Taller</th>
                    <th>Profesor</th>
                    <th>Sal&oacute;n</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $costo_total = 0;
                foreach ($talleres as $taller) {
                    ?>
                    <tr>
                        <td><?php echo $taller['grupo'] ?></td>
                        <td><?php echo $taller['taller'] ?></td>
                        <td><?php echo $taller['nombre'] . ' ' . $taller['paterno'] . ' ' . $taller['materno'] ?></td>
                        <td><?php echo $taller['salon'] ?></td>
                        <td><?php
                            echo '$ ';
                            switch ($baucher['tipo']) {
                                case 2:
                                    $costo_total += $taller['costo_alumno'];
                                    echo $taller['costo_alumno'];
                                    break;
                                case 3:
                                    $costo_total += $taller['costo_exalumno'];
                                    echo $taller['costo_exalumno'];
                                    break;
                                case 4:
                                    $costo_total += $taller['costo_trabajador'];
                                    echo $taller['costo_trabajador'];
                                    break;
                                case 5:
                                    $costo_total += $taller['costo_externo'];
                                    echo $taller['costo_externo'];
                                    break;
                            }
                            ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr class="tr_total">
                    <td colspan="4" class="td_total">Total</td>
                    <td><?php echo '$ ' . $costo_total ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="btn_valida_content">
        <?php
        if ($baucher['status'] == 0) {
            ?>
            <button class="btn btn-danger pull-right" id="btn_baja_baucher" data-id="<?php echo $baucher['id'] ?>">Dar de baja</button>
            <button class="btn btn-success pull-right" href="#valida_folio_dialog" data-toggle="modal">Validar</button>
            <?php
        }
        ?>
    </div>
</div>
<?php
if ($baucher['status'] == 0) {
    ?>
    <div class="row">
        <div class="modal fade active" id="valida_folio_dialog" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal" action="<?php echo base_url(); ?>admin/validacion/valida_insc/<?php echo $baucher['id'] ?>" role="form" id="valida_folio_form">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3>N&uacute;mero de recibo</h3>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label col-lg-4" for="user_input">No. Recibo</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="numero_caja" type="text" placeholder="Caja" id="caja_input">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Validar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </div>
    <?php
}
if ($baucher['folio_caja'] === null && $baucher['status'] == 1) {
    ?>
    <div class="row">
        <div class="modal fade active" id="ingresa_folio_dialog" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal" action="<?php echo base_url(); ?>admin/validacion/ingresa_folio_caja/<?php echo $baucher['id'] ?>" role="form" id="ingresa_folio_form">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3>N&uacute;mero de recibo de caja</h3>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label col-lg-4" for="user_input">Numero de caja</label>
                                <div class="col-lg-8">
                                    <input class="form-control" name="numero_caja" type="text" placeholder="Caja" id="caja_input">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Validar</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </div>
    <?php
}
?>