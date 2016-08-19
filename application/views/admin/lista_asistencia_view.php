<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row header">
    <h3 style="text-align: center">LISTA DE ASISTENCIA SEMESTRE <?php echo $taller['semestre'] ?></h3>
</div>
<div class="row listas">
    <strong>ACTIVIDAD: <?php echo $taller['taller'] ?></strong><br />
    <strong>PROFESOR: <?php echo $taller['nombre'] . ' ' . $taller['paterno'] . ' ' . $taller['materno'] ?></strong><br />
    <strong>HORARIO: <?php
        if (is_array($taller['horarios'])) {
            $dias_aux = array('Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado');
            $arrayDias = array();
            foreach ($taller['horarios'] as $horario) {
                $arrayDias[] = $dias_aux[$horario['dia']];
            }
            echo implode(', ', $arrayDias) . ' ' . substr($taller['horarios'][0]['inicio'] , 0 , -3) . ' - ' . substr($taller['horarios'][0]['termino'] , 0 , -3);
        }
        ?></strong><br />
    <strong>Alumnos inscritos: <?php echo $taller['inscritos']; ?></strong><br />
</div>
<div id="encabezado_right">
    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Firma de profesor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
</div>
<div class="row">
    <table class="table table-asistencia">
        <thead>
            <tr>
                <th rowspan="3">#</th>
                <th rowspan="3">NOMBRE</th>
                <th colspan="<?php echo (is_array($dias)) ? count($dias) : 1; ?>"><?php echo $mes; ?></th>
            </tr>
            <tr>
                <?php
                if (is_array($dias)) {
                    foreach ($dias as $dia) {
                        ?>
                        <th><?php 
                        switch($dia['d']){
                            case '0':
                                echo 'D';
                                break;
                            case '1':
                                echo 'L';
                                break;
                            case '2':
                                echo 'Ma';
                                break;
                            case '3':
                                echo 'Mi';
                                break;
                            case '4':
                                echo 'J';
                                break;
                            case '5':
                                echo 'V';
                                break;
                            case '6':
                                echo 'S';
                                break;
                        } 
                        ?></th>
                        <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <?php
                if (is_array($dias)) {
                    foreach ($dias as $dia) {
                        ?>
                        <th><?php echo $dia['n']; ?></th>
                        <?php
                    }
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if (is_array($alumnos)) {
                foreach ($alumnos as $key => $alumno) {
                    ?>
                    <tr>
                        <td><?php echo ($key + 1); ?></td>
                        <td><?php echo $alumno['paterno'] . ' ' . $alumno['materno'] . ' ' . $alumno['nombre'] ?></td>
                        <?php
                        if (is_array($dias)) {
                            foreach ($dias as $dia) {
                                ?>
                                <th></th>
                                <?php
                            }
                        }
                        ?>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>