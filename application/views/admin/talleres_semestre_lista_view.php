<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php
if (is_array($talleres)) {
    foreach ($talleres as $sem_talleres) {
        ?>
        <div data-event="0" class="dragg_div" data-id="<?php echo $sem_talleres['id'] ?>">
            <div style="display: block">
                <button type="button" class="btn btn-link btn-sm pull-right btn-remove"><span class="glyphicon glyphicon-remove"></span></button>
                <button type="button" class="btn btn-link btn-sm pull-right btn-editar"><span class="glyphicon glyphicon-pencil"></span></button>
                <span class="glyphicon glyphicon-move move_dragg"></span>
            </div>
            <div class="content_dragg">
                <div class="taller_span" data-id="<?php echo $sem_talleres['taller_id']; ?>">Actividad: <?php echo $sem_talleres['taller']; ?></div>
                <div class="profesor_span" data-id="<?php echo $sem_talleres['profesor_id'] ?>">Profesor: <?php echo $sem_talleres['nombre'] . ' ' . $sem_talleres['paterno'] . ' ' . $sem_talleres['materno'] ?> </div>
                <div class="salon_span" data-id="<?php echo $sem_talleres['salon_id'] ?>">Lugar: <?php echo $sem_talleres['salon'] ?></div>
                <div class="cupo_span" data-id="<?php echo $sem_talleres['cupo'] ?>">Cupo: <?php echo $sem_talleres['cupo'] ?></div>
                <div class="grupo_span" data-id="<?php echo $sem_talleres['grupo'] ?>">Grupo: <?php echo $sem_talleres['grupo'] ?></div>
            </div>
        </div>
        <?php
        
    }
}
?>