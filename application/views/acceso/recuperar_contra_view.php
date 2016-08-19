<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div style="text-align: center">
    <h4>UNIVERSIDAD NACIONAL AUTONOMA DE MEXICO</h4>
    <h4>FES - ARAGON</h4>
</div>
<div>
    <h4>DEPARTAMENTO DE ACTIVIDADES DEPORTIVAS</h4><br /><br />
    <h4><?php $usuario['nombre'] . ' ' . $usuario['paterno'] . ' ' . $usuario['materno']; ?></h4>
</div>
<div>
    <label>Usuario:</label>
    <span><?php echo $usuario['nickname'] ?></span>
</div>
<div>
    <label>Nueva Contrase&ntilde;a:</label>
    <span><?php echo $contra ?></span>
</div>
<strong>*Recuerda que ya tenemos la opci&oacute;n de cambiar la contrase&ntilde;a</strong>