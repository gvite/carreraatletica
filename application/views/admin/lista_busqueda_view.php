<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="panel panel-default" id="lista_alumnos">
        <div class="panel-heading" data-count="<?php echo count($tipo_alumnos) ?>">
            <button type="button" class="btn btn-default btn-sm pull-left">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </button>
            <button type="button" class="btn btn-default btn-sm pull-right">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </button>
            <div>
                <h3 data-actual="0">
                    <?php
                    switch ($tipo_alumnos[0]) {
                        case 2:
                            echo 'Alumnos';
                            break;
                        case 3:
                            echo 'Ex-Alumnos';
                            break;
                        case 4:
                            echo 'Trabajadores';
                            break;
                        case 5:
                            echo 'Externos';
                            break;
                    }
                    ?>
                </h3>
            </div>
        </div>
        <div class="panel-body">
            <?php
            $i = 0;
            foreach ($tipo_alumnos as $tipo) {
                if ($tipo == 2 && is_array($alumnos_data) || $tipo == 3 && is_array($exalumnos_data) || $tipo == 4 && is_array($trabajadores_data) || $tipo == 5 && is_array($externos_data)) {
                    ?>
                    <table class="table table-striped" id="table_talleres_semestre" data-tipo="<?php echo $tipo; ?>" data-name="<?php
                           switch ($tipo) {
                               case 2:
                                   echo 'Alumnos';
                                   break;
                               case 3:
                                   echo 'Ex-Alumnos';
                                   break;
                               case 4:
                                   echo 'Trabajadores';
                                   break;
                               case 5:
                                   echo 'Externos';
                                   break;
                           }
                           ?>">
                        <thead>
                            <tr>
                                <?php
                                switch ($tipo) {
                                    case 2:
                                        foreach ($alumnos_data as $data) {
                                            switch ($data) {
                                                case 'nombre':
                                                    echo '<th>Nombre</th>';
                                                    break;
                                                case 'no_cuenta':
                                                    echo '<th>No. Cta.</th>';
                                                    break;
                                                case 'ingreso':
                                                    echo '<th>Ingreso</th>';
                                                    break;
                                                case 'carrera':
                                                    echo '<th>Carrera</th>';
                                                    break;
                                                case 'materias':
                                                    echo '<th>Materias inscritas (validadas)</th>';
                                                    break;
                                                case 'materias_sin':
                                                    echo '<th>Materias inscritas (no validadas)</th>';
                                                    break;
                                            }
                                        }
                                        break;
                                    case 3:
                                        foreach ($exalumnos_data as $data) {
                                            switch ($data) {
                                                case 'nombre':
                                                    echo '<th>Nombre</th>';
                                                    break;
                                                case 'no_cuenta':
                                                    echo '<th>No. Cta.</th>';
                                                    break;
                                                case 'egreso':
                                                    echo '<th>Egreso</th>';
                                                    break;
                                                case 'carrera':
                                                    echo '<th>Carrera</th>';
                                                    break;
                                                case 'materias':
                                                    echo '<th>Materias inscritas (validadas)</th>';
                                                    break;
                                                case 'materias_sin':
                                                    echo '<th>Materias inscritas (no validadas)</th>';
                                                    break;
                                            }
                                        }
                                        break;
                                    case 4:
                                        foreach ($trabajadores_data as $data) {
                                            switch ($data) {
                                                case 'nombre':
                                                    echo '<th>Nombre</th>';
                                                    break;
                                                case 'no_trabajador':
                                                    echo '<th>No. Trabajador</th>';
                                                    break;
                                                case 'turno':
                                                    echo '<th>Turno</th>';
                                                    break;
                                                case 'area':
                                                    echo '<th>Area</th>';
                                                    break;
                                                case 'materias':
                                                    echo '<th>Materias inscritas (validadas)</th>';
                                                    break;
                                                case 'materias_sin':
                                                    echo '<th>Materias inscritas (no validadas)</th>';
                                                    break;
                                            }
                                        }
                                        break;
                                    case 5:
                                        foreach ($externos_data as $data) {
                                            switch ($data) {
                                                case 'nombre':
                                                    echo '<th>Nombre</th>';
                                                    break;
                                                case 'ocupacion':
                                                    echo '<th>Ocupaci&oacute;n</th>';
                                                    break;
                                                case 'telefono':
                                                    echo '<th>Tel&eacute;fono</th>';
                                                    break;
                                                case 'direccion':
                                                    echo '<th>Direcci&oacute;n</th>';
                                                    break;
                                                case 'materias':
                                                    echo '<th>Materias inscritas (validadas)</th>';
                                                    break;
                                                case 'materias_sin':
                                                    echo '<th>Materias inscritas (no validadas)</th>';
                                                    break;
                                            }
                                        }
                                        break;
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            switch ($tipo) {
                                case 2:
                                    if (is_array($alumnos)) {
                                        foreach ($alumnos as $alumno) {
                                            echo '<tr>';
                                            foreach ($alumnos_data as $data) {
                                                switch ($data) {
                                                    case 'nombre':
                                                        echo '<td>' . $alumno['paterno'] . ' ' . $alumno['materno'] . ' ' . $alumno['nombre'] . '</td>';
                                                        break;
                                                    case 'no_cuenta':
                                                        echo '<td>' . $alumno['no_cuenta'] . '</td>';
                                                        break;
                                                    case 'ingreso':
                                                        echo '<td>' . $alumno['ingreso'] . '</td>';
                                                        break;
                                                    case 'carrera':
                                                        echo '<td>' . $alumno['carrera'] . '</td>';
                                                        break;
                                                    case 'materias':
                                                        echo '<td>' . $alumno['materias'] . '</td>';
                                                        break;
                                                    case 'materias_sin':
                                                        echo '<td>' . $alumno['materias_sin'] . '</td>';
                                                        break;
                                                }
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    break;
                                case 3:
                                    if (is_array($exalumnos)) {
                                        foreach ($exalumnos as $alumno) {
                                            echo '<tr>';
                                            foreach ($exalumnos_data as $data) {
                                                switch ($data) {
                                                    case 'nombre':
                                                        echo '<td>' . $alumno['paterno'] . ' ' . $alumno['materno'] . ' ' . $alumno['nombre'] . '</td>';
                                                        break;
                                                    case 'no_cuenta':
                                                        echo '<td>' . $alumno['no_cuenta'] . '</td>';
                                                        break;
                                                    case 'egreso':
                                                        echo '<td>' . $alumno['egreso'] . '</td>';
                                                        break;
                                                    case 'carrera':
                                                        echo '<td>' . $alumno['carrera'] . '</td>';
                                                        break;
                                                    case 'materias':
                                                        echo '<td>' . $alumno['materias'] . '</td>';
                                                        break;
                                                    case 'materias_sin':
                                                        echo '<td>' . $alumno['materias_sin'] . '</td>';
                                                        break;
                                                }
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    break;
                                case 4:
                                    if (is_array($trabajadores)) {
                                        foreach ($trabajadores as $trabajador) {
                                            echo '<tr>';
                                            foreach ($trabajadores_data as $data) {
                                                switch ($data) {
                                                    case 'nombre':
                                                        echo '<td>' . $trabajador['paterno'] . ' ' . $trabajador['materno'] . ' ' . $trabajador['nombre'] . '</td>';
                                                        break;
                                                    case 'no_trabajador':
                                                        echo '<td>' . $trabajador['no_trabajador'] . '</td>';
                                                        break;
                                                    case 'turno':
                                                        echo '<td>' . (($trabajador['turno'] == '0') ? 'Matutino' : 'Vespertino') . '</td>';
                                                        break;
                                                    case 'area':
                                                        echo '<td>' . $trabajador['area'] . '</td>';
                                                        break;
                                                    case 'materias':
                                                        echo '<td>' . $trabajador['materias'] . '</td>';
                                                        break;
                                                    case 'materias_sin':
                                                        echo '<td>' . $trabajador['materias_sin'] . '</td>';
                                                        break;
                                                }
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    break;
                                case 5:
                                    if (is_array($externos)) {
                                        foreach ($externos as $externo) {
                                            echo '<tr>';
                                            foreach ($externos_data as $data) {
                                                switch ($data) {
                                                    case 'nombre':
                                                        echo '<td>' . $externo['paterno'] . ' ' . $externo['materno'] . ' ' . $externo['nombre'] . '</td>';
                                                        break;
                                                    case 'ocupacion':
                                                        echo '<td>' . $externo['ocupacion'] . '</td>';
                                                        break;
                                                    case 'telefono':
                                                        echo '<td>' . $externo['telefono'] . '</td>';
                                                        break;
                                                    case 'direccion':
                                                        echo '<td>' . $externo['direccion'] . '</td>';
                                                        break;
                                                    case 'materias':
                                                        echo '<td>' . $externo['materias'] . '</td>';
                                                        break;
                                                    case 'materias_sin':
                                                        echo '<td>' . $externo['materias_sin'] . '</td>';
                                                        break;
                                                }
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    break;
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>