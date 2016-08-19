<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?php echo $taller['taller'] ?></h3>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <h4>Objetivos</h4>
            <p><?php echo $taller['objetivo'] ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4>Requisitos</h4>
            <p><?php echo $taller['requisitos'] ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4>Informaci&oacute;n</h4>
            <p><?php echo $taller['informacion'] ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="4">Aportaci&oacute;n</th>
                    </tr>
                    <tr>
                        <th>Alumno</th>
                        <th>Ex-Alumno</th>
                        <th>Trabajador</th>
                        <th>Externo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>$ <?php echo $taller['costo_alumno'] ?></td>
                        <td>$ <?php echo $taller['costo_exalumno'] ?></td>
                        <td>$ <?php echo $taller['costo_trabajador'] ?></td>
                        <td>$ <?php echo $taller['costo_externo'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="6">Horarios</th>
                    </tr>
                    <tr>
                        <th>Grupo</th>
                        <th>Lun</th>
                        <th>Mar</th>
                        <th>Mie</th>
                        <th>Jue</th>
                        <th>Vie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (is_array($taller['talleres_semestre'])) {
                        foreach ($taller['talleres_semestre'] as $ts) {
                            ?>
                            <tr>
                                <td><?php echo $ts['grupo'] ?></td>
                                <?php
                                if (is_array($ts['horarios'])) {
                                    for ($i = 1; $i <= 5; $i++) {
                                        $esta = false;
                                        for ($j = 0; $j < count($ts['horarios']); $j++) {
                                            if ($ts['horarios'][$j]['dia'] == $i) {
                                                $esta = true;
                                                echo '<td>' . $ts['horarios'][$j]['inicio'] . ' --- ' . $ts['horarios'][$j]['termino'] . '</td>';
                                            }
                                        }
                                        if (!$esta) {
                                            ?>
                                            <td>-</td>
                                            <?php
                                        }
                                    }
                                } else {
                                    ?>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr><td colspan="6"></td></tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
</div>