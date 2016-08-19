<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="col-md-10">
    <div class="row">
        <div class="col-md-5" >
            <form class="form-signin form-horizontal" id="taller_semestre_form" action="admin/talleres_semestre/insert">
                <div class="control-group">
                    <label class="control-label" for="semestre_select">Semestre</label>
                    <div class="controls">
                        <select name="semestre" class="form-control" id="semestre_select">
                            <option value="0">---</option>
                            <?php
                            if (is_array($semestres)) {
                                foreach ($semestres as $semestre) {
                                    ?>
                                    <option value="<?php echo $semestre['id'] ?>"><?php echo $semestre['semestre'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="alumno_input">Alumno</label>
                    <div class="controls">
                        <input name="alumno" class="form-control" id="alumno_input" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>