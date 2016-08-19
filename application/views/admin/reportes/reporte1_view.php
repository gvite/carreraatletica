<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="sub_menu_pasos">
            <li class="active"><a href="<?php echo base_url() ?>admin/reportes/carrera.jsp" data-name="reporte_1">Reporte 1</a></li>
            <li><a href="<?php echo base_url() ?>admin/reportes/presupuesto1.jsp" data-name="presupuesto_1">Reporte 2</a></li>
            <li><a href="<?php echo base_url() ?>admin/reportes/presupuesto2.jsp" data-name="presupuesto_2">Reporte 3</a></li>
        </ul>
    </div>
</div>
<div class="row">
    <form action="<?php echo base_url(); ?>admin/reportes/genera_carrera.jsp" class="form-horizontal" id="reporte1_form">
        <div class="form-group">
            <label class="control-label col-sm-2" for="tipo_alumno">Tipo Alumno</label>
            <div class="col-sm-4">
                <select name="tipo_alumno" class="form-control" id="tipo_alumno" >
                    <option value="2">Alumnos</option>
                    <option value="3">Ex-Alumnos</option>
                    <option value="1">Ambos</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="carrera_input">Carrera</label>
            <div class="col-sm-4">
                <select name="carrera" class="form-control" id="carrera_input" >
                    <option value="0">Todo</option>
                    <?php
                    if (is_array($carreras)) {
                        foreach ($carreras as $carrera) {
                            ?>
                            <option value="<?php echo $carrera['id']; ?>"><?php echo $carrera['carrera'] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="semestre_input">Semestre</label>
            <div class="col-sm-4">
                <select name="semestre" class="form-control" id="semestre_input" >
                    <option value="0">Todo</option>
                    <?php
                    if (is_array($semestres)) {
                        foreach ($semestres as $semestre) {
                            ?>
                            <option value="<?php echo $semestre['id']; ?>"><?php echo $semestre['semestre'] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Generar</button>
            </div>
        </div>
    </form>
</div>