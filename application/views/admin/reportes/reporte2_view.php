<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="sub_menu_pasos">
            <li><a href="<?php echo base_url() ?>admin/reportes/carrera.jsp" data-name="reporte_1">Reporte 1</a></li>
            <li class="active"><a href="<?php echo base_url() ?>admin/reportes/presupuesto1.jsp" data-name="presupuesto_1">Reporte 2</a></li>
            <li><a href="<?php echo base_url() ?>admin/reportes/presupuesto2.jsp" data-name="presupuesto_2">Reporte 3</a></li>
        </ul>
    </div>
</div>
<div class="row">
    <form action="<?php echo base_url(); ?>admin/reportes/genera_presupuesto1.jsp" class="form-horizontal" id="reporte2_form">
        <div class="form-group">
            <label class="control-label col-sm-2" for="semestre_input">Semestre</label>
            <div class="col-sm-4">
                <select name="semestre" class="form-control" id="semestre_input" >
                    <option value="">Elige una opci&oacute;n</option>
                    <?php
                    if (is_array($semestres)) {
                        foreach ($semestres as $semestre) {
                            ?>
                            <option data-ini="<?php echo exchange_date($semestre['ini_sem']); ?>" data-fin="<?php echo exchange_date($semestre['fin_sem']); ?>" value="<?php echo $semestre['id']; ?>"><?php echo $semestre['semestre'] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="fecha_inicio">De</label>
            <div class="col-sm-2">
                <input type="text" name="fecha_inicio" id="fecha_inicio" class="form-control fecha_inicio" value=""/>
            </div>
            <label class="control-label col-sm-1" for="fecha_termino">a</label>
            <div class="col-sm-2">
                <input type="text" name="fecha_termino" id="fecha_termino" class="form-control fecha_fin" value=""/>
            </div>
        </div>
        <a href="#" class="btn btn-link" id="mas_opciones_a" data-display="0">Mas opciones</a>
        <div id="mas_opciones" style="display: none;">
            <div class="form-group">
                <label class="control-label col-sm-2" for="tamanio_fuente">Tama&ntilde;o de fuente</label>
                <div class="col-sm-2">
                    <input type="text" name="tamanio_fuente" id="tamanio_fuente" class="form-control" value=""/>
                </div>
                <label class="control-label col-sm-2" for="no_reg">No. Registros por hoja</label>
                <div class="col-sm-2">
                    <input type="text" name="no_reg" id="no_reg" class="form-control" value=""/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="fuente">Fuente</label>
                <div class="col-sm-4">
                    <select name="fuente" class="form-control" id="fuente" >
                        <option value="">Elige una opci&oacute;n</option>
                        <option value="0">Arial</option>
                        <option value="1">Courier New</option>
                        <option value="2">Times New Roman</option>
                        <option value="3">Franklin</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Generar</button>
                <a class="btn btn-default" href="#" id="get_registros">Registros</a>
            </div>
        </div>
    </form>
</div>
<div class="row" id="reporte_preview"></div>