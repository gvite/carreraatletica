<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
    $mes_name = array(
        1 => "Enero",
        2 => "Febrero",
        3 => "Marzo",
        4 => "Abril",
        5 => "Mayo",
        6 => "Junio",
        7 => "Julio",
        8 => "Agosto",
        9 => "Septiembre",
        10 => "Octubre",
        11 => "Noviembre",
        12 => "Diciembre"
    );
?>
<div class="header">
    <h4>UNIDAD DE EXTENSI&Oacute;N UNIVERSITARIA</h4>
    <h4>DEPARTAMENTO DE ACTIVIDADES CULTURALES</h4>
    <h4>TALLERES CULTURALES</h4>
    <h4><?php echo $semestre['semestre'] ?></h4>
</div>
<table class="table">
    <thead>
        <tr>
            <th>TALLER</th>
            <th>UNIVERSITARIOS</th>
            <th>EGRESADOS</th>
            <th>TRABAJADORES</th>
            <th>EXTERNOS</th>
            <th>TOTAL</th>
            <th>INGRESO</th>
        </tr>
    </thead>
    <tbody>
        <?php
        setlocale(LC_MONETARY, 'en_US');
        if (is_array($talleres)) {
            $total = array(
                'uni' => 0,
                'exuni' => 0,
                'traba' => 0,
                'exter' => 0,
                'suma' => 0
            );
            foreach ($talleres as $taller) {
                ?>
                <tr>
                    <td><?php echo $taller['taller']; ?></td>
                    <td><?php echo $taller['uni']['count_user']; ?></td>
                    <td><?php echo $taller['exuni']['count_user']; ?></td>
                    <td><?php echo $taller['traba']['count_user']; ?></td>
                    <td><?php echo $taller['exter']['count_user']; ?></td>
                    <td><?php echo $taller['uni']['count_user'] + $taller['exuni']['count_user'] + $taller['exter']['count_user'] + $taller['traba']['count_user']; ?></td>
                    <td><?php echo money_format( '%n' , $taller['uni']['suma'] + $taller['exuni']['suma'] + $taller['exter']['suma'] + $taller['traba']['suma']);    ?>
                        <?php //echo $taller['uni']['suma'] + $taller['exuni']['suma'] + $taller['exter']['suma'] + $taller['traba']['suma']; ?></td>
                </tr>
                <?php
                $total['uni'] += $taller['uni']['count_user'];
                $total['exuni'] += $taller['exuni']['count_user'];
                $total['traba'] += $taller['traba']['count_user'];
                $total['exter'] += $taller['exter']['count_user'];
                $total['suma'] += $taller['uni']['suma'] + $taller['exuni']['suma'] + $taller['exter']['suma'] + $taller['traba']['suma'];
            }
            ?>
            <tr>
                <td><strong>TOTAL</strong></td>
                <td><?php echo $total['uni']; ?></td>
                <td><?php echo $total['exuni']; ?></td>
                <td><?php echo $total['traba']; ?></td>
                <td><?php echo $total['exter']; ?></td>
                <td><?php echo $total['uni'] + $total['exuni'] + $total['traba'] + $total['exter']; ?></td>
                <td><?php echo money_format('%n', $total['suma']);  ?>
                    <?php //echo $total['suma']; ?></td>
            </tr>
            
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php
            if(is_array($meses)){
                $total = 0;
                foreach($meses as $mes){
                    ?>
                    <tr>
                        <td><?php echo $mes_name[$mes['mes']];?></td>
                        <td><?php echo money_format('%n', $mes['suma']);?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                    $total += $mes['suma'];
                }
                ?>
                <tr>
                    <td>Total</td>
                    <td><?php echo money_format('%n', $total);?></td>
                    <td></td>
                    <td></td>
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
<div class="header">
    <h4>Nezahualc&oacute;yotl, Estado de M&eacute;xico, a <?php echo date('d');?> de <?php echo $mes_name[date('n')]; ?> de <?php echo date('Y');?>
    </h4>
</div>