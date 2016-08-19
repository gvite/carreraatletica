<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-10">
        <div class="row">
            <div>
                <form class="form-signin form-horizontal" id="search_folio_form" action="<?php echo base_url(); ?>admin/validacion/verifica_folio.jsp">
                    <div class="control-group col-xs-1">
                        <label class="control-label" for="folio_input">Folio</label>
                    </div>
                    <div class="control-group col-xs-4">
                        <div class="controls">
                            <input name="folio" class="form-control" id="folio_input" />
                        </div>
                    </div>
                    <div class="control-group col-xs-7">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row" id="content_validacion">
</div>