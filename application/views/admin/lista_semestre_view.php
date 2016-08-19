<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div>
    <table class="table table-striped" id="table_talleres_semestre">
        <thead>
            <tr>
                <th>Grupo</th>
                <th>Actividad</th>
                <th>Profesor</th>
                <th>Lugar</th>
                <th>Cupo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (is_array($talleres)) {
                foreach ($talleres as $taller) {
                    ?>
                    <tr>
                        <td><?php echo $taller['grupo']?></td>
                        <td><?php echo $taller['taller']?></td>
                        <td><?php echo $taller['nombre'] . ' ' . $taller['paterno'] . ' ' . $taller['materno']?></td>
                        <td><?php echo $taller['salon']?></td>
                        <td><?php echo $taller['count'] . '/' .$taller['cupo']?></td>
                        <td>
                            <a href="<?php echo base_url(); ?>admin/listas/get_lista_taller/<?php echo $taller['id']; ?>" class="btn btn-default" title="Ver lista">
                                <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                            </a>
                            <a href="<?php echo base_url(); ?>admin/listas/get_lista_asistencia/<?php echo $taller['id']; ?>" target="_blank" class="btn btn-default" title="Lista de asistencia">
                                <span class="glyphicon glyphicon glyphicon-file" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="5">No hay actividades</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>