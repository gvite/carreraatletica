<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div>
    <h4>UNIDAD DE EXTENSI&Oacute;N UNIVERSITARIA</h4>
    <h4>DEPARTAMENTO DE ACTIVIDADES CULTURALES</h4>
    <h4><?php echo strtoupper(($semestre) ? $semestre['semestre'] : "Todo");?></h4>
</div>
<table class="table">
    <thead>
        <tr>
            <th colspan="2"><?php echo $carrera['carrera']?></th>
        </tr>
        <tr>
            <th>Taller</th>
            <th>No. Alumnos</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (is_array($talleres)) {
            $total = 0;
            foreach ($talleres as $taller) {
                ?>
                <tr>
                    <td><?php echo $taller['taller']; ?></td>
                    <td><?php echo $taller['num_alumnos']; ?></td>
                </tr>
                <?php
                $total += $taller['num_alumnos'];
            }
            ?>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td><?php echo $total; ?></td>
                </tr>
            <?php
        }else{
            ?>
                <tr>
                    <td colspan="2">No hay registros.</td>
                </tr>
            <?php
        }
        ?>
    </tbody>
</table>