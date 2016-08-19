<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row listas">
    <h3 style="text-align: center"><?php echo $taller['taller'] ?></h3>
    <strong>Profesor(a): <?php echo $taller['nombre'] . ' ' . $taller['paterno'] . ' ' . $taller['materno'] ?></strong>
    <strong>Cupo: <?php echo $taller['cupo'] ?></strong>
    <strong>Lugar: <?php echo $taller['salon'] ?></strong>
</div>
<div class="row">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Folio de baucher</th>
                <th>Alumno</th>
                <th>Status</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (is_array($alumnos)) {
                foreach ($alumnos as $key => $alumno) {
                    ?>
                    <tr>
                        <td><?php echo $key + 1 ?></td>
                        <td><?php echo str_pad($alumno['folio'], 11, "0", STR_PAD_LEFT); ?></td>
                        <td><?php echo $alumno['paterno'] . ' ' . $alumno['materno'] . ' ' . $alumno['nombre'] ?></td>
                        <td>
                            <?php
                            if ($alumno['status'] == 0) {
                                echo 'No validado';
                            } else if ($alumno['status'] == 1) {
                                echo 'Validado';
                            } else if ($alumno['status'] == 2) {
                                echo 'Penalizado: A&uacute;n no se puede inscribir';
                            } else {
                                echo 'No validado, pero ya puede inscribir nuevamente los Talleres';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo base_url(); ?>admin/validacion/get_baucher/<?php echo str_pad($alumno['folio'], 11, "0", STR_PAD_LEFT); ?>.jsp">Ver detalle</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="4">No hay registros</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>