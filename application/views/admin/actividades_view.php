<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="sub_menu_pasos">
            <li class="active"><a href="<?php echo base_url() ?>admin/talleres" data-name="talleres">Actividades</a></li>
            <li><a href="<?php echo base_url() ?>admin/semestres" data-name="semestres">Semestres</a></li>
            <li><a href="<?php echo base_url() ?>admin/profesores" data-name="profesores">Profesores</a></li>
            <li><a href="<?php echo base_url() ?>admin/salones" data-name="salones">Lugares</a></li>
            <li><a href="<?php echo base_url() ?>admin/talleres_semestre" data-name="talleres_semestre">Asignaci&oacute;n</a></li>
        </ul>
    </div>
</div>
<div class="row" id="sub_container">
    
</div>