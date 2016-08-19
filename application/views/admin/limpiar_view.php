<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-10">
        <table class="table" id="table_bauchers">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Actividad</th>
                    <th>Fecha expedici&oacute;n</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (is_array($bauchers)) {
                    $this->load->helper('date');
                    foreach ($bauchers as $baucher) {
                        if ($baucher['status'] != 3) {
                            ?>
                            <tr>
                                <td><?php echo str_pad($baucher['folio'], 11, "0", STR_PAD_LEFT); ?></td>
                                <td><ul><?php
                                    if (is_array($baucher['talleres'])) {
                                        foreach ($baucher['talleres'] as $key2 => $taller_semestre) {
                                        ?>
                                        <li><?php echo $taller_semestre['taller'];?></li>
                                        <?php
                                        }
                                    }
                                ?></ul></td>
                                <td><?php echo exchange_date_time($baucher['fecha_expedicion']) ?></td>
                                <td><?php
                                    if ($baucher['status'] == 0) {
                                        echo 'No pagado';
                                    } else if ($baucher['status'] == 1) {
                                        echo 'Pagado';
                                    } else {
                                        echo 'Penalizado';
                                    }
                                    ?></td>
                                <td>
                                    <?php
                                    if ($baucher['status'] == 0) {
                                        
                                    }
                                    ?>
                                    <a class="btn btn-link delete" data-id="<?php echo $baucher['id'] ?>" href="<?php echo base_url(); ?>admin/limpiar/delete/">Eliminar</a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>