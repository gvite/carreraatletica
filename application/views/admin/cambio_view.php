<div class="row">
    <div class="col-md-12">
        <h3>Alumno: <?php echo $alumno['nombre'];?> <?php echo $alumno['paterno'];?> <?php echo $alumno['materno'];?></h3>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    <?php
    if (is_array($talleres)) {
        $count = 0;
        foreach ($talleres as $taller) {
            if (($taller['status'] === false || $taller['status']['status'] == 3)) {
                if ($count == 0) {
                ?>
                    <div class="row">
                <?php
                }
                ?>
                <div class="col-md-3">
                    <div class="panel panel-<?php
                    if ($taller['percent'] < 100 && $taller['num_trabajador'] < 2 && $taller['puede_mas']) {
                        echo 'default panel-talleres-cambio';
                    } else {
                        echo 'danger alert-agotado';
                    }
                    ?>" data-id="<?php echo $taller['id'] ?>">
                        <div class="panel-heading">
                            <h4 class="name-taller" data-name="<?php echo $taller['taller'] ?>"><?php echo $taller['taller'] ?></h4>
                        </div>
                        <div class="panel-body">

                            <span class="badge pull-right"><?php echo ($taller['percent'] < 100) ? $taller['insc_count'] . '/' . $taller['cupo'] : 'Agotado'; ?></span>
                            <div>Grupo: <span class="grupo-taller"><?php echo $taller['grupo'] ?></span></div>

                            <div class="horario-taller">Horario: 
                                <span>
                                    <?php
                                    if (is_array($taller['horarios'])) {
                                        for ($i = 1; $i <= 5; $i++) {
                                            for ($j = 0; $j < count($taller['horarios']); $j++) {
                                                if ($taller['horarios'][$j]['dia'] == $i) {
                                                    $dia = '';
                                                    switch ($i) {
                                                        case 1:
                                                            $dia = 'Lu';
                                                            break;
                                                        case 2:
                                                            $dia = 'Ma';
                                                            break;
                                                        case 3:
                                                            $dia = 'Mi';
                                                            break;
                                                        case 4:
                                                            $dia = 'Ju';
                                                            break;
                                                        case 5:
                                                            $dia = 'Vi';
                                                            break;
                                                    }
                                                    $inicio = explode(':', $taller['horarios'][$j]['inicio']);
                                                    $fin = explode(':', $taller['horarios'][$j]['termino']);
                                                    echo '<div>' . $dia . ' ' . $inicio[0] . ':' . $inicio[1] . ' - ' . $fin[0] . ':' . $fin[1] . '</div>';
                                                }
                                            }
                                        }
                                    } else {
                                        ?>
                                        -
                                        <?php
                                    }
                                    ?>
                                </span>
                            </div>
                            <?php
                            if ($taller['num_trabajador'] >= 2) {
                                ?>
                                <span class="label label-warning">* No puede haber mas de 2 trabajadores</span>
                                <?php
                            }
                            if (!$taller['puede_mas']) {
                                ?>
                                <span class="label label-warning">* Ya se inscribió esta actividad</span>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="panel-footer">
                            <div class="costo-taller">Aportaci&oacute;n: $<span class="costo-span"><?php echo $taller['costo'] ?></span></div>
                        </div>
                    </div>
                </div>
                <?php
                $count++;
                if ($count == 4) {
                    $count = 0;
                ?>
                    </div>
                <?php
                }
            }
        }
        if ($count > 0 && $count < 4) {
            echo '</div>';
        }
    }
    ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-md-offset-4">
        <form id="form_cambio" action="admin/cambio/cambiar">
            <input type="hidden" name="taller_ant" value="<?php echo $taller_id;?>" />
            <input id="taller_new_input" type="hidden" name="taller_new" value="" />
            <input type="hidden" name="baucher_id" value="<?php echo $baucher_id;?>" />
            <input type="hidden" name="user_id" value="<?php echo $alumno['id'];?>" />
            <input type="hidden" name="user_type" value="<?php echo $alumno['tipo_usuario_id'];?>" />
            <input type="hidden" name="baucher_folio" value="<?php echo $baucher_folio;?>" />
            <button class="btn btn-primary pull-right" type="submit" id="cambia_taller_button">Cambiar Actividad</button>
        </form>
    </div>
</div>