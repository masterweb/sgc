<?php
$this->widget('application.components.Notificaciones');
setlocale(LC_MONETARY, 'en_US');
?>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/jasny-bootstrap.css" rel="stylesheet"/>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css"/>               
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css"/>        
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jasny-bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<!-- Add fancyBox -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<?php
/* @var $this GestionDiariaController */
/* @var $model GestionDiaria */
/* @var $form CActiveForm */
date_default_timezone_set('America/Guayaquil');
$fechaActual = date("Ymd H00");
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$android = FALSE;
if (stripos($ua, 'android') !== false) { // && stripos($ua,'mobile') !== false) {
    $android = TRUE;
}
$area_id = (int) Yii::app()->user->getState('area_id');
//echo 'id informacion:'.$id;
?>
<style>
    .answers .col-md-8{margin-left: 18px;}.text-danger{font-weight: bold;font-size: 14px;}
    .tl_seccion_rf{margin-left: 0px !important;}
</style>
<script type="text/javascript">
    $(function () {
        $('.fancybox').fancybox();
        $("#GestionDiaria_proximo_seguimiento").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2016'
        });
        $("#GestionDiaria_fecha_venta").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2016'
        });
        $('#gestion-diaria-form').validate({
            rules: {'GestionDiaria[observaciones]': {
                    required: true
                }, 'GestionDiaria[codigo_vehiculo]': {
                    required: true
                }, 'GestionDiaria[test_drive]': {
                    required: true
                }},
            messages: {'GestionDiaria[observaciones]': {
                    required: 'Ingresar observaciónes'
                }, 'GestionDiaria[codigo_vehiculo]': {
                    required: 'Seleccione vehículo'
                }, 'GestionDiaria[test_drive]': {
                    required: 'Seleccione Test Drive'
                }},
            submitHandler: function (form) {
                //alert('enter submit handler');
                var proximoSeguimiento = $('#GestionDiaria_proximo_seguimiento').val();
                var fechaActual = $('#GestionDiaria_time_now').val();
                if (proximoSeguimiento != '') {
                    //console.log('enter proximo seguimiento');
                    if ($('#GestionDiaria_check').val() != 2) {
                        var params = proximoSeguimiento.split("-");
                        var fechaDate = params[0] + params[1] + params[2];
                        var params2 = fechaActual.split(" ");
                        var endTime = parseInt(params2[1]) + 100;
                        endTime = endTime.toString();
                        //console.log(params2[1]);
                        var href = '/intranet/callcenter/index.php/gestionDiaria/calendar?date=' + fechaDate + '&startTime=' + params2[1] + '&endTime=' + endTime + '&subject=Cotizacion&desc=Reunion con el cliente';
                        $('#event-download').attr('href', href);
                        $('#calendar-content').show();
                        $("#event-download").click(function () {
                            $('#GestionDiaria_calendar').val(1);
                            $('#calendar-content').hide();
                            $('#GestionDiaria_check').val(2)
                        });
                        if ($('#GestionDiaria_calendar').val() == 1) {
                            form.submit();
                        } else {
                            alert('Debes descargar agendamiento y luego dar click en Crear');
                        }
                    } else {
                        form.submit();
                    }
                } else {
                    form.submit();
                }
            }
        });
        $('#GestionDiaria_test_drive').change(function () {
            var value = $(this).attr('value');
            if (value == 1) {
                $('.cont-img').show();
            } else {
                $('.cont-img').hide();
            }
        });
    });
</script>
<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <?php if ($_GET['paso'] == '1-2'): ?>
            <li role="presentation" class="active"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion_on.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita_on.png" alt="" /></span> Cita</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
        <?php else: ?>
            <li role="presentation" class="active"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion_on.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita_on.png" alt="" /></span> Cita</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
        <?php endif; ?>
        <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
        <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
        <li role="presentation"><a aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane" id="home">
        </div>
        <div role="tabpanel" class="tab-pane active" id="profile">
            <div class="col-md-9">
                <div class="highlight"><!-- HIGHLIGHT -->
                    <div class="row">
                        <h1 class="tl_seccion_rf">Datos del Cliente</h1>
                        <?php
                        $con = Yii::app()->db;
                        $sql = "SELECT gi.id as id_info, gi. nombres, gi.apellidos, gi.cedula, gi.ruc, gi.pasaporte,    gi.email, gi.direccion,gi.celular, gi.telefono_casa, gi.id_cotizacion, gi.responsable as resp, gd.* FROM gestion_diaria gd 
                                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                                WHERE gi.id = {$_GET['id']} GROUP BY gi.id ORDER BY gd.id_informacion DESC";
                                //die('sql: '.$sql);
                        $request = $con->createCommand($sql);
                        $users = $request->queryAll();
                        /* echo '<pre>';
                          print_r($users);
                          echo '</pre>'; */
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-md-9">

                            <?php                          
                            foreach ($users as $key => $value): ?>
                                <table class="table">
                                    <tr>
                                        <td><strong>Nombres:</strong> <?php echo $value['nombres']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Apellidos:</strong> <?php echo $value['apellidos']; ?></td>
                                    </tr>
                                    <?php if($value['cedula'] != ''): ?>
                                    <tr>
                                        <td><strong>Cédula:</strong> <?php echo $value['cedula']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($value['ruc'] != ''): ?>
                                    <tr>
                                        <td><strong>Ruc:</strong> <?php echo $value['ruc']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($value['pasaporte'] != ''): ?>
                                    <tr>
                                        <td><strong>Pasaporte:</strong> <?php echo $value['pasaporte']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <tr>
                                        <td><strong>Email:</strong> <?php echo $value['email']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dirección:</strong> <?php echo $value['direccion']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Celular:</strong> <?php echo $value['celular']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Teléfono Domicilio:</strong> <?php echo $value['telefono_casa']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fuente de Contacto:</strong> 
                                            <?php if($this->getFuente($value['id_cotizacion']) == 'showroom'){ echo 'Tráfico'; }
                                            else{ echo ucfirst($this->getFuente($value['id_cotizacion'])); } ?> 
                                         </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Responsable:</strong> <?php echo $this->getResponsable($value['resp']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipo:</strong>  
                                            <?php 
                                            $crit = new CDbCriteria();
                                            $crit->select = 'id, tipo';
                                            $crit->condition = 'id = '.$value['id_cotizacion'];
                                            $tipo_nv = GestionNuevaCotizacion::model()->findAll($crit);
                                            foreach ($tipo_nv as $llave => $valor){
                                                echo $valor['tipo'];
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>                       
                            <?php endforeach; ?>
                        </div>

                    </div>
                    <div class="row">
                        <h1 class="tl_seccion_rf">Status</h1>
                    </div>  
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<!--                      <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                          <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                              Prospección
                            </a>
                          </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">
                            
                          </div>
                        </div>
                      </div>-->
                      <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                          <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                              Primera Visita
                            </a>
                          </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                          <div class="panel-body">
                            <?php 
                            $pv = GestionInformacion::model()->findAll(array(
                                'condition' => "id=:match",
                                'params' => array(':match' => $_GET['id']))
                            );
                            
                            ?>
                            <?php
                            foreach ($pv as $vpv) {
                                echo '<div class="col-md-6">Primera Visita el: </div>'
                                . '<div class="col-md-6">'.$vpv['fecha'].'</div>';
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      <?php $cseg = GestionAgendamiento::model()->count(array('condition' => "id_informacion=:match AND paso IN (4,5,6,7)",'params' => array(':match' => $_GET['id'])));?>
                      <?php if($cseg > 0){ ?>
                      <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingThree">
                          <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                              Seguimiento
                            </a>
                          </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                          <div class="panel-body">
                            <?php 
                            $seg = GestionAgendamiento::model()->findAll(array(
                                'condition' => "id_informacion=:match AND paso IN (4,5,6,7)",
                                'params' => array(':match' => $_GET['id']))
                            );
                            ?>
                              <table class="table table-striped">
                                  <thead>
                                      <tr>
                                          <th>Paso</th>
                                          <th>Fecha seguimiento</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                              <?php
                            foreach ($seg as $vseg) {
                                echo '<tr><td>'.$this->getPasoSeguimiento($vseg['paso']).'</td>'
                                . '<td>'.$vseg['agendamiento'].'</td></tr>';
                            }
                            ?>
                            </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                      <?php $cr = GestionFactura::model()->count(array('condition' => "id_informacion=:match",'params' => array(':match' => $_GET['id'])));?>
                      <?php if($cr > 0){ ?>
                        <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingFour">
                          <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseTwo">
                              Cierre
                            </a>
                          </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                          <div class="panel-body">
                            <?php 
                            $cr = GestionFactura::model()->findAll(array(
                                'condition' => "id_informacion=:match",
                                'params' => array(':match' => $_GET['id']))
                            );
                            
                            ?>
                            <div class="col-md-6"><strong>Fecha de Venta</strong></div>
                            <div class="col-md-6"><strong>Observación</strong></div>
                            <br />
                            <?php
                            foreach ($cr as $vcr) {
                                echo '<div class="col-md-6">'.$vcr['fecha'].'</div>'
                                . '<div class="col-md-6">'.$vcr['observaciones'].'</div>';
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                      <?php $cde = GestionAgendamiento::model()->count(array('condition' => "id_informacion=:match AND observaciones = 'Desiste' ",'params' => array(':match' => $_GET['id'])));?>
                      <?php if($cde > 0){ ?>
                        <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingFour">
                          <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseTwo">
                              Desiste
                            </a>
                          </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                          <div class="panel-body">
                            <?php 
                            $de = GestionAgendamiento::model()->findAll(array('condition' => "id_informacion=:match AND observaciones = 'Desiste' ",'params' => array(':match' => $_GET['id'])));
                            
                            ?>
                            <div class="col-md-6"><strong>Fecha</strong></div>
                            <div class="col-md-6"><strong>Observación</strong></div>
                            <br />
                            <?php
                            foreach ($de as $vde) {
                                echo '<div class="col-md-6">'.$vde['fecha'].'</div>'
                                . '<div class="col-md-6">'.$vde['observaciones'].'</div>';
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                        <?php $cent = GestionEntrega::model()->count(array('condition' => "id_informacion=:match ",'params' => array(':match' => $_GET['id'])));?>
                      <?php if($cent > 0){ ?>
                        <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingFive">
                          <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseTwo">
                              Entrega
                            </a>
                          </h4>
                        </div>
                        <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                          <div class="panel-body">
                            <?php 
                            $ent = GestionEntrega::model()->findAll(array('condition' => "id_informacion=:match ",'params' => array(':match' => $_GET['id'])));
                            
                            ?>
                            <div class="col-md-6"><strong>Fecha de Entrega</strong></div>
                            <div class="col-md-6"><strong>Observación</strong></div>
                            <br />
                            <?php
                            foreach ($ent as $vent) {
                                echo '<div class="col-md-6">'.$vent['fecha'].'</div>'
                                . '<div class="col-md-6">'.$vent['observaciones'].'</div>';
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                    <?php $ctc = GestionCategorizacion::model()->count(array('condition' => "id_informacion=:match ",'params' => array(':match' => $_GET['id']))); ?>
                    <?php if($ctc > 0): ?>
                    <div class="row">
                        <h1 class="tl_seccion_rf">Categorización</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Paso</th>
                                        <th>Categorización</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php $ct = GestionCategorizacion::model()->findAll(array('condition' => "id_informacion=:match ",'params' => array(':match' => $_GET['id']))); ?>
                                   <?php foreach ($ct as $vct) { ?>
                                    <tr>
                                        <td><?php echo $this->getPasoSeguimiento($vct['paso']); ?></td>
                                        <td><?php echo $vct['categorizacion']; ?></td>
                                        <td><?php echo $vct['fecha']; ?></td>
                                    </tr>                            
                                   <?php } ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>

                </div><!-- END OF HIGHLIGHT-->
            </div>
            <?php if ($this->getAnswer(1, $id) > 0){?>
                    <div class="col-md-9">
                        <h3 class="tl_seccion_rf" id="prospeccion">
                            <span><img src="/intranet/usuario/images/prospeccion_on.png" alt=""></span> - Paso 1/2 - Prospección/Cita
                        </h3>
                    </div>
                    <!--  ==========================          PASO 1-2   =========================    -->
                    <?php
                    $criteria4 = new CDbCriteria(array(
                        'condition' => "id_informacion={$_GET['id']}"
                    ));
                    $art = GestionProspeccionRp::model()->findAll($criteria4);
                    foreach ($art as $c){
                        if ($c['preg1'] == 1) {
                            ?>
                            <div class="col-md-8"><h3>No estoy interesado</h3></div>
                            <?php
                        }
                        if ($c['preg1'] == 2) {
                            ?>
                            <div class="col-md-8"><h3>Falta de dinero</h3></div>
                            <?php
                        }
                        if ($c['preg1'] == 3) { // COMPRO OTRO VEHICULO
                            $params = explode('@', $c['preg3_sec3']);
                            //print_r($params);
                            $modelo_auto = $params[1] . ' ' . $params[2];
                            ?>
                            <div class="col-md-8"><h3>Compró otro vehículo</h3></div>
                            <div class="col-md-4">
                                <p><strong>Marca:</strong> <?php echo $c['preg3_sec2']; ?> </p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Modelo:</strong> <?php echo $modelo_auto; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Año:</strong> <?php echo $c['preg3_sec4']; ?></p>
                            </div>
                        <?php } if ($c['preg1'] == 4) { // SI ESTOY INTERESADO ?>
                            <div class="col-md-8"><h3>Si estoy interesado</h3></div>
                            <div class="col-md-8">
                                <p><strong>Fecha de agendamiento:</strong> <?php echo $c['preg4_sec1']; ?></p>
                            </div>
                            <div class="col-md-8">
                                <?php if ($c['preg4_sec2'] == 0) { // SI LA CITA ES EN EL CONCESIONARIO  ?>
                                <p><strong>Lugar de encuentro: </strong><?php echo $this->getConcesionario($c['preg4_sec4']); ?></p>
                            </div>
                            <?php } else { // ELSE ES LUGAR DE TRABAJO O DIRECCION ?>
                                <p><strong>Lugar de encuentro: </strong><?php echo $c['preg4_sec5']; ?></p>
                            <?php }
                        }
                        if ($c['preg1'] == 5) { // NO CONTESTA 
                        ?>
                        <div class="col-md-8"><h3>No contesta</h3></div>
                        <div class="col-md-8">
                            <p><strong>Re agendar llamada :</strong> <?php echo $c['preg5_sec1']; ?></p>
                        </div>
                        <?php
                        }
                        if ($c['preg1'] == 6) { // TELEFONO EQUIVOCADO 
                            ?>
                            <div class="col-md-8"><h3>Teléfono equivocado</h3></div>
                            <?php
                        }
                    }// end foreach
                ?>
                <?php } // end if ?>
            <?php if ($this->getAnswer(2, $id) > 0){ ?>
                <div class="col-md-9">
                    <h3 class="tl_seccion_rf" id="consulta"><span><img src="/intranet/ventas/images/consulta_on.png" alt=""></span> - Paso 4 - Consulta</h3>
                </div>
                <!--  =========================          PASO 3-4   =========================    -->
                <?php
                $criteria4 = new CDbCriteria(array(
                    'condition' => "id_informacion={$_GET['id']}"
                ));
                $art = GestionConsulta::model()->findAll($criteria4);
                foreach ($art as $c){
                    if ($c['preg1_sec5'] == 0 && !empty($c['preg1_sec5'])){ // SI TIENE VEHICULO
                        $params = explode('@', $c['preg1_sec2']);
                        //print_r($params);
                        $modelo_auto = $params[1] . ' ' . $params[2];
                        ?>
                        <div class="col-md-8">
                            <h4 class="text-danger">1. ¿Qué clase de vehículo conduce en la actualidad?</h4>
                            <div class="col-md-4">
                                <p><strong>Marca:</strong> <?php echo $c['preg1_sec1']; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Modelo:</strong> <?php echo $modelo_auto; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Año:</strong> <?php echo $c['preg1_sec3']; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Kilometraje:</strong> <?php echo $c['preg1_sec4']; ?></p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-danger">2. ¿Qué tiene pensado hacer con su vehículo actual?</h4>
                            <?php if ($c['preg2'] == 0) { ?>
                                <div class="col-md-4"><p>Vender</p></div>
                                <?php
                            }
                            if ($c['preg2'] == 1) {
                                ?>
                                <div class="col-md-12"><p>Utilizar como parte de pago</p></div>
                                <?php
                                if ($c['preg2_sec1'] != '') {
                                    $stringAutos = $c['preg2_sec1'];
                                    $stringAutos = trim($stringAutos);
                                    $stringAutos = substr($stringAutos, 0, strlen($stringAutos) - 1);
                                    $paramAutos = explode('@', $stringAutos);
                                    ?>        
                                    <div class="row">
                                        <?php foreach ($paramAutos as $value) { ?>
                                            <div class="col-md-3">
                                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $value; ?>" alt="" width="125" class="img-thumbnail">
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-md-5"><a href="<?php $c['link']; ?>" target="_blank">Link de fotos auto usado</a></div>
                                    <?php
                                }
                            }
                            if ($c['preg2'] == 2) {
                                ?>
                                <div class="col-md-4"><p>Mantenerlo</p></div>
                                <?php
                            }
                    } // END SI TIENE VEHICULO 
                        ?>
                    </div>
                    <div class="col-md-8">
                        <h4 class="text-danger">3. ¿Para qué utilizará el nuevo vehículo?</h4>
                        <?php if ($c['preg3'] == 0) { ?>
                            <div class="col-md-4"><p>Primer vehículo del hogar</p></div>
                            <?php if ($c['preg3_sec1'] == 0) { ?>
                                <div class="col-md-4"><p>Familiar</p></div>
                            <?php } else { ?>
                                <div class="col-md-4"><p>Trabajo</p></div>
                                <?php
                            }
                        }
                        if ($c['preg3'] == 1) {
                            ?>
                            <div class="col-md-4"><p>Segundo vehículo del hogar</p></div>
                            <?php if ($c['preg3_sec2'] == 0) { ?>
                                <div class="col-md-4"><p>Familiar</p></div>
                            <?php } else { ?>
                                <div class="col-md-4"><p>Trabajo</p></div>
                                <?php
                            }
                        }
                        if ($c['preg3'] == 2) {
                            ?>
                            <div class="col-md-4"><p>Renovación de vehículo</p></div>
                        <?php } ?>
                    </div>
                    <div class="col-md-8"><h4 class="text-danger">4. ¿Quién más participa en la decisión de compra?</h4>
                        <?php if ($c['preg4'] == 0) { ?>
                            <div class="col-md-4"><p>Esposa/o</p></div>
                            <?php
                        }
                        if ($c['preg4'] == 1) {
                            ?>
                            <div class="col-md-4"><p>Familiar</p></div>
                            <?php
                        }
                        if ($c['preg4'] == 2) {
                            ?>
                            <div class="col-md-4"><p>Departamento de compras</p></div>
                        <?php } ?>
                    </div>
                    <div class="col-md-8"><h4 class="text-danger">5. ¿Qué clase de presupuesto tiene previsto para su nuevo vehículo?</h4>
                        <div class="col-md-4"><p>$ <?php echo number_format($c['preg5'], 2); ?></p></div>
                    </div>
                    <div class="col-md-8"><h4 class="text-danger">6. ¿Cuál sería su forma de pago para su nuevo vehículo?</h4>
                        <?php if ($c['preg6'] == 0) { ?>
                            <div class="col-md-4"><p>Contado</p></div>
                            <?php
                        }
                        if ($c['preg6'] == 1) {
                            ?>
                            <div class="col-md-4"><p>Financiado</p></div>
                        <?php } ?>
                    </div>
                    <div class="col-md-8">
                        <h4 class="text-danger">7. ¿En qué tiempo estima realizar su compra?</h4>
                        <div class="col-md-4"><p><?php echo $c['preg7']; ?></p></div>
                    </div>
                    <div class="col-md-8">
                        <h4 class="text-danger">8. ¿Cuál es su necesidad básica?</h4>
                        <div class="col-md-4"><p><?php echo $this->getArg($c['preg8']); ?></p></div>
                    </div>
                    <div class="col-md-8">
                        <h4 class="text-danger">Vehículos Recomendados</h4>
                        <?php
                        $crit = new CDbCriteria(array(
                            'condition' => "id_informacion={$id}"
                        ));
                        $vh = GestionVehiculo::model()->findAll($crit);
                        foreach ($vh as $val) {
                            ?>
                            <div class="row">
                                <div class="col-md-4"><strong>Modelo: </strong><?php echo $this->getModel($val['modelo']); ?></div>
                                <div class="col-md-4"><strong>Versión: </strong><?php echo $this->getVersion($val['version']); ?></div>
                                <div class="col-md-4"><strong>Precio: </strong>$ <?php echo number_format($val['precio'], 2); ?></div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                    $crit = new CDbCriteria(array('condition' => "id_informacion={$id} AND paso = 4"));
                    $agen = GestionAgendamiento::model()->count($crit);
                    $ag = GestionAgendamiento::model()->findAll($crit);
                    if ($agen > 0) {
                        ?>
                    <div class="col-md-8"><h4 class="tl-agen">Agendamientos</h4>
                        <?php } foreach ($ag as $a) { ?>
                            <div class="row">
                                <div class="col-md-6"><strong>Fecha Agendamiento: </strong><?php echo $a['agendamiento']; ?></div>
                                <div class="col-md-6"><strong>Motivo: </strong><?php echo $a['observaciones']; ?></div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } //endforeach ?> 
            <?php } //End - Paso 4 - Consulta ?>
                
                <?php if ($this->getAnswer(3, $id) > 0){ ?> 
                    <div class="col-md-9" id="presentacion"><h3 class="tl_seccion_rf"><span><img src="/intranet/ventas/images/presentacion_on.png" alt=""></span> - Paso 5 - Presentación</h3></div>
                    <div class="col-md-8">
                        <?php //$modelos = $this->getModelosPr($id); ?>
                        <div class="col-md-2"><a href="https://www.kia.com.ec/images/Fichas_Tecnicas/<?php //echo $this->getPdf($c['modelo']); ?>" class="btn btn-xs btn-success" target="_blank">Catálogo</a></div>
                    </div>
                    <?php
                    $criteria8 = new CDbCriteria(array(
                        'condition' => "id_informacion={$_GET['id']}"
                    ));
                    $art2 = GestionPresentacion::model()->findAll($criteria8);
                    foreach ($art2 as $c){
                        ?>

                        <?php
                        $crit5 = new CDbCriteria(array('condition' => "id_informacion={$id} AND paso = 5"));
                        $agen5 = GestionAgendamiento::model()->count($crit5);

                        $ag5 = GestionAgendamiento::model()->findAll($crit5);
                        if ($agen5 > 0) {
                            ?>
                            <div class="col-md-8"><h4 class="tl-agen">Agendamientos</h4>
                                <?php
                            
                            foreach ($ag5 as $a) {
                                ?>
                                <div class="row">
                                    <div class="col-md-6"><strong>Fecha Agendamiento: </strong><?php echo $a['agendamiento']; ?></div>
                                    <div class="col-md-6"><strong>Motivo: </strong><?php echo $a['observaciones']; ?></div>
                                </div>
                                </div>
                    <?php }} ?>
                        
                        <?php
                    } // endforeach
                } //End - Paso 5 - Presentación
                ?>
            <?php if ($this->getAnswer(4, $id) > 0){ ?>
                    <div class="col-md-9" id="demostracion"><h3 class="tl_seccion_rf"><span><img src="/intranet/ventas/images/demostracion_on.png" alt=""></span> - Paso 6 - Demostración</h3></div>
                <?php     $criteria8 = new CDbCriteria(array(
                        'condition' => "id_informacion={$_GET['id']}"
                    ));
                    $art3 = GestionDemostracion::model()->findAll($criteria8);
                    foreach ($art3 as $c){
                        ?> 
                        <div class="col-md-8">
                            <h4 class="text-danger">1. ¿Desea realizar una prueba de manejo?</h4>
                            <div class="col-md-2"><p><?php echo $c['preg1']; ?></p></div>
                            <?php if ($c['preg1'] == 'Si') { ?>
                                <div class="col-md-2"><a class="fancybox btn btn-success btn-xs" href="#inline1">Licencia</a></div>
                                <div class="col-md-4"><a href="<?php echo Yii::app()->createUrl('site/pdf', array('id_informacion' => $c['id_informacion'], 'id_vehiculo' => $c['id_vehiculo'])); ?>" class="btn btn-warning btn-xs" target="_blank">PDF Prueba Manejo</a></div>
                                <div id="inline1" style="width:auto;display: none;">
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $c['preg1_licencia']; ?>"/> 
                                </div>
                            <?php } else if ($c['preg1'] == 'No') { ?>
                                <div class="col-md-4"><p><?php echo $this->getNoTest($c['preg1_observaciones']); ?></p></div>
                            <?php } ?>
                        </div>
                        
                        <?php } // endforeach?>
                        <?php
                        $crit5 = new CDbCriteria(array('condition' => "id_informacion={$id} AND paso = 6"));
                        $agen5 = GestionAgendamiento::model()->count($crit5);

                        $ag5 = GestionAgendamiento::model()->findAll($crit5);
                        if ($agen5 > 0) {
                            ?>
                            <div class="col-md-8"><h4 class="tl-agen">Agendamientos</h4>
                            <?php foreach ($ag5 as $a) { ?>
                                <div class="row">
                                    <div class="col-md-6"><strong>Fecha Agendamiento: </strong><?php echo $a['agendamiento']; ?></div>
                                    <div class="col-md-6"><strong>Motivo: </strong><?php echo $a['observaciones']; ?></div>
                                </div>
                            </div>    
                        <?php }// end foreach
                        
                            } // endif ?>
                        
                <?php } // End - Paso 6 - Demostración ?>
                <?php if ($this->getAnswer(5, $id) > 0) { ?>
                    <div class="col-md-9" id="negociacion"><h3 class="tl_seccion_rf"><span><img src="/intranet/ventas/images/negociacion_on.png" alt=""></span> - Paso 7 - Negociación</h3></div>
                    <?php
                    $criteria9 = new CDbCriteria(array(
                        'condition' => "id_informacion={$_GET['id']}"
                    ));
                    $art4 = GestionFinanciamiento::model()->findAll($criteria9);
                    foreach ($art4 as $c1) {
                        ?>        
                        <div class="col-md-8">
                            <h4 class="text-danger">Forma de pago</h4>
                            <div class="col-md-2"><p><?php echo $c1['forma_pago']; ?></p></div>
                            <div class="col-md-4">
                                <a class="btn btn-success btn-xs" href="<?php echo Yii::app()->createUrl('site/proformaCliente/', array('id_informacion' => $c1['id_informacion'], 'id_vehiculo' => $c1['id_vehiculo'])); ?>" target="_blank">PDF Proforma</a>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-danger">Precio Total</h4>
                            <div class="col-md-4">
                                <p>$ <?php echo number_format($c1['precio_vehiculo'], 2); ?></p>
                            </div>
                        </div>
                        <?php if ($c1['forma_pago'] == 'Credito'):?>
                        <div class="col-md-8">
                            <h4 class="text-danger">Solicitud de Crédito</h4>
                            <?php
                            $status = $this->getStatusSolicitud($c1['id_informacion'], $c1['id_vehiculo']);
                            switch ($status) {
                                case 'na':
                                    echo '<a class="btn btn-warning btn-xs" target="_blank">No existe</a>';
                                    break;
                                case '1':
                                    echo '<a class="btn btn-warning btn-xs" target="_blank">En Análisis</a>&nbsp;&nbsp;';
                                    $comentario = $this->getComentarioStatus($c['id_informacion'],$c['id_vehiculo']);
                                    echo $comentario;
                                    break;
                                case '2':
                                    echo '<a class="btn btn-success btn-xs" target="_blank">Aprobado</a>';
                                    break;
                                case '4':
                                    echo '<a class="btn btn-danger btn-xs" target="_blank">Negado</a>&nbsp;&nbsp;';
                                    $comentario = $this->getComentarioStatus($c['id_informacion'],$c['id_vehiculo']);
                                    echo $comentario;

                                    break;
                                case '3':
                                    echo '<a class="btn btn-danger btn-xs" target="_blank">Condicionado</a>&nbsp;&nbsp;';
                                    $comentario = $this->getComentarioStatus($c['id_informacion'],$c['id_vehiculo']);
                                    echo $comentario;

                                    break;

                                default:
                                    break;
                            }
                            ?>

                        </div>
                        <?php endif; ?>

                    <?php } // end foreach   ?>
                    <?php
                    $crit5 = new CDbCriteria(array('condition' => "id_informacion={$id} AND paso = 7"));
                    $agen5 = GestionAgendamiento::model()->count($crit5);

                    $ag5 = GestionAgendamiento::model()->findAll($crit5);
                    if ($agen5 > 0) {
                        ?>
                        <div class="col-md-8"><h4 class="tl-agen">Agendamientos</h4>
                            <?php
                        
                        foreach ($ag5 as $a) {
                            ?>
                            <div class="row">
                                <div class="col-md-6"><strong>Fecha Agendamiento: </strong><?php echo $a['agendamiento']; ?></div>
                                <div class="col-md-6"><strong>Motivo: </strong><?php echo $a['observaciones']; ?></div>
                            </div>
                <?php } }?>
                    </div>
                <?php } // end if paso 7 negogociacion  ?>
                <?php if ($this->getAnswer(6, $id) > 0){ ?>
                    <div class="col-md-9" id="cierre"><h3 class="tl_seccion_rf"><span><img src="/intranet/ventas/images/cierre_on.png" alt=""></span> - Paso 8 - Cierre</h3></div>
                    <?php $cr = GestionFactura::model()->findAll(array('condition' => "id_informacion=:match",'params' => array(':match' => $id))); ?>
                    <?php foreach ($cr as $vc) { ?>
                         <div class="col-md-8">
                            <h4 class="text-danger">Cierre</h4>
                            <div class="col-md-3"><p><strong>Fecha de cierre: </strong><?php echo $vc['fecha']; ?></p></div>
                            <div class="col-md-4"><p><strong>Observaciones: </strong><?php echo $vc['observaciones']; ?></p></div>
                        </div> 
                    <?php } ?>
                    
                <?php } // End - Paso 8 - Cierre  ?>
                <?php if ($this->getAnswer(7, $id) > 0): ?>
                    <div class="col-md-9" id="entrega"><h3 class="tl_seccion_rf" id="entrega"><span><img src="/intranet/ventas/images/entrega_on.png" alt=""></span> - Paso 9 - Entrega</h3></div>
                    <?php
                    $criteria10 = new CDbCriteria(array(
                        'condition' => "id_informacion={$_GET['id']}"
                    ));
                    $art5 = GestionEntrega::model()->findAll($criteria10);
                    foreach ($art5 as $c2):
                        ?>        
                        <div class="col-md-8">
                            <h4 class="text-danger">Cita para entrega del Vehículo</h4>
                            <div class="col-md-3"><p><?php echo $c2['agendamiento1']; ?></p></div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-danger">Vehículo Cotizado</h4>
                            <div class="row">
                                <div class="col-md-4"><strong>Modelo: </strong><?php echo $this->getModeloTestDrive($c2['id_vehiculo']); ?></div>
                                <div class="col-md-4"><strong>Versión: </strong><?php echo $this->getVersionTestDrive($c2['id_vehiculo']); ?></div>

                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-danger">Hoja de Entrega</h4>
                            <div class="col-md-2">
                                <a class="btn btn-success btn-xs" href="<?php echo Yii::app()->createUrl('site/hojaentregacliente/', array('id_informacion' => $c2['id_informacion'], 'id_vehiculo' => $c2['id_vehiculo'])); ?>" target="_blank">Hoja de Entrega</a>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-danger">Foto Entrega Vehículo</h4>
                            <div class="col-md-2">
                                <?php
                                $cri = new CDbCriteria(array(
                                    'condition' => "id_informacion={$c2['id_informacion']} AND id_vehiculo = {$c2['id_vehiculo']}"
                                ));
                                $foto = GestionEntrega::model()->find($cri);
                                $imgEntrega = $foto->foto_cliente;
                                ?>
                                <div id="inline2" style="width:auto;display: none;">
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $imgEntrega; ?>"/> 
                                </div>
                                <a class="fancybox btn btn-success btn-xs" href="#inline2">Foto</a>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?> 
                    <div class="row">
                    <div class="col-md-9">
                        <hr style="border-top: 1px solid #AE5858;" />
                    </div>
                </div>
                <div class="row" style="padding-left: 15px;">
                    <?php 
                    $tipo = $this->getExonerado($id);
                    ?>
                    <div class="col-md-2">
                        <?php if($tipo > 0){ ?>
                        <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimientoexonerados'); ?>" type="button" class="btn btn-danger">RGD</a>
                        <?php }else{ ?>
                        <a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>" type="button" class="btn btn-danger">RGD</a>
                        <?php } ?>
                    </div>
                    <?php
                    if($area_id != 4 &&  $area_id != 12 &&  $area_id != 13 &&  $area_id != 14){
                    switch ($_GET['paso']) {
                        case '1-2':
                            $status = $this->getStatusGD($_GET['id_gt']);
                            if ($status == 1) {
                                $url = Yii::app()->createUrl('gestionInformacion/update', array('id' => $_GET['id'], 'tipo' => 'gestion'));
                                echo '<div class="col-md-2"><a href="' . $url . '" type="button" class="btn btn-warning">Continuar</a></div>';
                            } else if ($status == 3 || $status == 4) {
                                $url = Yii::app()->createUrl('gestionInformacion/update', array('id' => $_GET['id'], 'tipo' => 'prospeccion'));
                                echo '<div class="col-md-2"><a href="' . $url . '" type="button" class="btn btn-warning">Continuar</a></div>';
                            }
                            break;
                        case '3':
                            $url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $_GET['id']));
                            echo '<div class="col-md-2"><a href="' . $url . '" type="button" class="btn btn-warning">Continuar</a></div>';
                            break;
                        case '4':
                            $url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $_GET['id']));
                            echo '<div class="col-md-2"><a href="' . $url . '" type="button" class="btn btn-warning">Continuar</a></div>';
                            break;
                        case '5':
                            $url = Yii::app()->createUrl('site/presentacion', array('id' => $_GET['id']));
                            echo '<div class="col-md-2"><a href="' . $url . '" type="button" class="btn btn-warning">Continuar</a></div>';
                            break;
                        case '6':
                            $url = Yii::app()->createUrl('site/demostracion', array('id' => $_GET['id']));
                            echo '<div class="col-md-2"><a href="' . $url . '" type="button" class="btn btn-warning">Continuar</a></div>';
                            break;
                        case '7':
                            $url = Yii::app()->createUrl('site/negociacion', array('id' => $_GET['id']));
                            echo '<div class="col-md-2"><a href="' . $url . '" type="button" class="btn btn-warning">Continuar</a></div>';
                            break;
                        case '9':
                            $url = Yii::app()->createUrl('site/cierre', array('id' => $_GET['id']));
                            echo '<div class="col-md-2"><a href="' . $url . '" type="button" class="btn btn-warning">Continuar</a></div>';
                            break;
                        default:
                            break;
                    }
                    }
                    ?>

                </div>
        </div>

    </div><!-- END OF TAB CONTENT --> 