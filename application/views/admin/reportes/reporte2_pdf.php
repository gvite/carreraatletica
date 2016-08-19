<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="header" <?php echo ($font !== false) ? 'style="font-family: ' . $font . '"' : ''; ?>>
    <div>HOJA <?php echo $pagina; ?></div>
    <h4>UNIDAD DE EXTENSI&Oacute;N UNIVERSITARIA</h4>
    <h4>DEPARTAMENTO DE ACTIVIDADES CULTURALES</h4>
    <h4>ALUMNOS INSCRITOS EN LOS TALLERES DE ACTIVIDADES CULTURALES</h4>
    <h4>SEM. <?php echo $semestre['semestre']; ?></h4>
</div>
<table class="table" <?php echo ($font !== false) ? 'style="font-family: ' . $font . '"' : ''; ?>>
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
            $total_taller = 0;
            $taller_ant = null;
            foreach ($alumnos as $alumno) {
                if($taller_ant == null){
                    $taller_ant = $alumno['taller'];
                }
                if($alumno['taller'] !== $taller_ant){
                    ?>
                    <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>Subtotal</td>
                    <td>
                        <?php
                        echo money_format('%n', $total_taller);
                        //echo $total_taller;
                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                    <?php
                    $total_taller = $alumno['aportacion'];
                    $taller_ant = null;
                }else{
                    $total_taller += $alumno['aportacion'];
                }
                ?>
                <tr>
                    <td><?php echo ($alumno['folio_caja'] !== null) ? $alumno['folio_caja'] : 'N/A'; ?></td>
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
            ?>
                    <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>Subtotal</td>
                    <td>
                        <?php
                        echo money_format('%n', $total_taller);
                        //echo $total_taller;
                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                    <?php
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
                    <td>Total</td>
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