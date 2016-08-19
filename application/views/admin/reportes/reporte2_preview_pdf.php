<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<table class="table">
    <thead>
        <tr>
            <th>NO. RECIBO</th>
            <th>FECHA</th>
            <th>IMPORTE</th>
            <th>NOMBRE</th>
            <th>TALLER</th>
            <th>CARRERA</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (is_array($alumnos)) {
            setlocale(LC_MONETARY, 'en_US');
            foreach ($alumnos as $alumno) {
                ?>
                <tr>
                    <td>
                        <?php if ($alumno['folio_caja'] !== null) { ?>
                            <?php echo $alumno['folio_caja'] ?>
                        <?php } else { ?>
                        <a href="<?php echo base_url();?>admin/validacion/get_baucher/<?php echo $alumno['folio']; ?>">N/A</a>
                        <?php } ?>
                    </td>
                    <td><?php echo exchange_date($alumno['fecha_caja']); ?></td>
                    <td>
                        <?php
                        echo money_format('%n', $alumno['aportacion']);
                        //echo $alumno['aportacion'];
                        ?>
                    </td>
                    <td><?php echo ucfirst(strtolower($alumno['paterno'])) . ' ' . ucfirst(strtolower($alumno['materno'])) . ' ' . ucwords(strtolower($alumno['nombre'])); ?></td>
                    <td><?php echo $alumno['taller']; ?></td>
                    <td>
                        <?php
                        switch ($alumno['tipo_usuario_id']) {
                            case '2':
                                echo $alumno['carrera'];
                                break;
                            case '3':
                                echo 'Exalumno';
                                break;
                            case '4':
                                echo 'Trabajador';
                                break;
                            case '5':
                                echo 'Externo';
                                break;
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            if (isset($total)) {
                ?>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <?php
                        echo money_format('%n', $total);
                        //echo $total;
                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="2">No hay registros.</td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>