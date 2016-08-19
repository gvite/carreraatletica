<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="footer">
        <strong>Requisitos para terminar inscripci&oacute;n:</strong>
        <ul>
            <li>2 fotograf&iacute;as tama&ntilde;o infantil</li>
            <?php
            switch (get_type_user()) {
                case 2:
                    ?>
                    <li>Copia de credencial de alumno</li>
                    <li>Historial Acad&eacute;mico</li>
                    <?php
                    break;
                case 3:
                    ?>
                    <li>Copia de credencial de exalumno</li>
                    <?php
                    break;
                case 4:
                    ?>
                    <li>Copia de credencial de empleado</li>
                    <li>Copia de tal&oacute;n de cheque</li>
                    <?php
                    break;
                case 5:
            }
            ?>
                    <li>Presentar &eacute;ste voucher</li>
                    <li>Ticket de caja en original y 2 copia</li>
                    <li>2 Fotos tamaño infantil en blanco y negro o a color</li>
                    <li>Copia de carnet (seguro social / seguro m&eacute;dico)</li>
        </ul>
    <p>El presente pago se debe realizar antes de esta fecha: <strong><?php echo $date_fin['mday'] . '/' . $date_fin['mon'] . '/' . $date_fin['year'] ?></strong>. Y debe ser presentado en <strong>Extensi&oacute;n Universitaria</strong> antes de las <?php echo $termina_hora ?>:00 hrs. para que se concluya su inscripci&oacute;n.</p>
</div>