<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" id="footer_content">
            <footer class="row navbar navbar-default">
                <div class="container text-center" style="font-size: 11px;">
                    Hecho en México, todos los derechos reservados 2012. Esta página puede ser reproducida con fines no lucrativos, siempre y cuando no se mutile, se cite la fuente completa y su dirección electrónica. De otra forma requiere permiso previo por escrito de la institución.
                    
                </div>
                <div class="container text-center" style="font-size: 11px;">
                    Sitio web administrado por: <br />
                    Jefatura de Ingenier&iacute;a en Computación. actividades.culturales.fesa@gmail.com
                </div>
            </footer>
        </div>
        <div id="alerts_message" class="hide"
             data-message="<?php echo (isset($msg) && is_array($msg)) ? $msg['message'] : ''; ?>"
             data-type="<?php echo (isset($msg) && is_array($msg)) ? $msg['type'] : ''; ?>"
             ></div>
        <div class="row">
            <div id="ajax_div"><img src="<?php echo base_url(); ?>images/cargando.gif" width="16" height="16" alt=" " /> Cargando...</div>
        </div>
    </body>
</html>