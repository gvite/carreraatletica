<div class="row">
    <div class="col-md-10">
        <div class="row">
            <div>
                <form class="form-signin form-horizontal" id="search_alumno_form" method="POST" action="<?php echo base_url(); ?>admin/inscribir/busca_alumno.jsp">
                    <div class="control-group col-xs-2">
                        <label class="control-label" for="cta_input">Nombre del alumno</label>
                    </div>
                    <div class="control-group col-xs-4">
                        <div class="controls">
                            <input name="cta" class="form-control" id="cta_input" />
                        </div>
                    </div>
                    <div class="control-group col-xs-6">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row hide" id="table_alumnos_content">
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Fecha de nacimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>