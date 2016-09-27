<?php $this->widget('application.components.Notificaciones'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<?php
$id_asesor = Yii::app()->user->getId();
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
if($cargo_id != 46){
$concesionarioid = $this->getConcesionarioDealerId($id_asesor);
$nombreConcesionario = urlencode($this->getNameConcesionarioById($concesionarioid));
$nombre_cliente = urlencode($this->getNombresInfo($id).' '.$this->getApellidosInfo($id));
$direccion_concesionario = urlencode($this->getConcesionarioDireccionById($concesionarioid));
}
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$android = FALSE;
if (stripos($ua, 'android') !== false) { // && stripos($ua,'mobile') !== false) {
    $android = TRUE;
}
?>
<?php
// si la solicitud es al contado entonces el boton continuar var por defecto
$crit5 = new CDbCriteria(array(
    'condition' => "id_informacion={$id} AND (forma_pago = 'Contado' OR forma_pago = 'Crédito')"
        ));
$gf = GestionFinanciamiento::model()->count($crit5);
?>
<script type="text/javascript">
    $(function () {
        $('#GestionAgendamiento_agendamiento').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                //$(this).find('.xdsoft_date.xdsoft_weekend')
                //        .addClass('xdsoft_disabled');
            },
            weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
        });
        
        $('#Demostracion_test_drive').change(function () {
            var value = $(this).attr('value');
            if (value == 1) {
                $('.cont-img').show();
            } else {
                $('.cont-img').hide();
            }
        });
    });

</script>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/jasny-bootstrap.css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jasny-bootstrap.js"></script>
<div class="container">
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">            
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita.png" alt="" /></span> Cita</a></li>
            <?php
            $criteria = new CDbCriteria(array(
                'condition' => "id={$id}"
            ));
            $info = GestionInformacion::model()->count($criteria);
            ?>
            <?php if ($info > 0): ?>
                <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/update/', array('id' => $id, 'tipo' => 'gestion')); ?>"  aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <?php else: ?>
                <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <?php endif; ?>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionVehiculo/create/' . $id); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/demostracion/' . $id); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
            <li role="presentation" class="active"><a aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion_on.png" alt="" /></span> Negociación</a></li>
            <li role="presentation">
                <?php if($gf > 0){ ?>
                <a href="<?php echo Yii::app()->createUrl('site/cierre/' . $id); ?>" aria-controls="messages" role="tab">
                <?php }else{ ?> 
                <a aria-controls="messages" role="tab">    
                <?php } ?>    
                    <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panels -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="row">
                    <h1 class="tl_seccion">Lista de Negociación</h1>
                </div>
                <div class="rows">
                    <div class="col-md-12">
                        <div class="row highlight">
                            <div class="table-responsive">
                                <table class="tables tablesorter" id="keywords">
                                    <thead>
                                        <tr>
                                            <th><span>Modelo</span></th>
                                            <th><span>Versión</span></th>
                                            <!--<th><span>Precio</span></th>-->
                                            <th><span>Negociación</span></th>
                                            <th><span>Status</span></th>
                                            <th><span>Observaciones</span></th>
                                            <th><span>Solicitud</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($vec as $c): ?>
                                            <tr>
                                                <td><?php echo $this->getModel($c['modelo']); ?> </td>
                                                <td><?php echo $this->getVersion($c['version']); ?> </td>
                                                <td>
                                                    <?php
                                                    $test = $this->getNegociacion($c['id_informacion'], $c['id']);
                                                    ?>
                                                    <?php $fin = $this->getFinanciamientoExo($c['id_informacion']); ?>
                                                    <?php if($fin == 'exonerados'){ ?>
                                                    <a href="<?php echo Yii::app()->createUrl('gestionVehiculo/negociacionex', array('id_informacion' => $c['id_informacion'], 'id_vehiculo' => $c['id'])); ?>" class="btn btn-success btn-xs btn-rf">Negociación</a>
                                                    <?php }else{ ?>
                                                    <?php if ($test > 0): ?>
                                                        <a href="<?php echo Yii::app()->createUrl('gestionVehiculo/negociacionup', array('id_informacion' => $c['id_informacion'], 'id_vehiculo' => $c['id'])); ?>" class="btn btn-success btn-xs btn-rf">Negociación</a>
                                                    <?php else: ?>
                                                        <a href="<?php echo Yii::app()->createUrl('gestionVehiculo/negociacion', array('id_informacion' => $c['id_informacion'], 'id_vehiculo' => $c['id'])); ?>" class="btn btn-danger btn-xs btn-rf">Negociación</a>
                                                    <?php endif; }?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $status_credito = $this->getStatusSolicitud($c['id_informacion'], $c['id']);
                                                    $criteria2 = new CDbCriteria(array(
                                                        "condition" => "id_informacion = ".$c['id_informacion'].' AND id_vehiculo = '.$c['id']
                                                    ));
                                                    $status = GestionFinanciamiento::model()->find($criteria2);
                                                    
                                                    if($status->forma_pago === 'Contado'){
                                                        echo '<a class="btn btn-tomate btn-xs nocursor" target="_blank">Contado</a>';
                                                    }else if($status->forma_pago == null){
                                                        echo '<a class="btn btn-inactive btn-xs nocursor" target="_blank">Sin Status</a>';
                                                    }else{                                                    
                                                        switch ($status_credito) {
                                                            case 'na':
                                                                echo '<a class="btn btn-warning btn-xs nocursor" target="_blank">En Análisis</a>';
                                                                break;                                                             
                                                            case 1:
                                                                echo '<a class="btn btn-warning btn-xs nocursor" target="_blank">En Análisis</a>';
                                                                break;
                                                            case 2:
                                                                echo '<a class="btn btn-success btn-xs nocursor" target="_blank">Aprobado</a>';
                                                                break;
                                                            case 3:
                                                                echo '<a class="btn btn-danger btn-xs nocursor" target="_blank">Negado</a>';
                                                                break;
                                                            case 4:
                                                                echo '<a class="btn btn-danger btn-xs nocursor" target="_blank">Condicionado</a>';

                                                                break;

                                                            default:
                                                                break;
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($status == 3 || $status == 4 || $status == 1) {
                                                        $comentario = $this->getComentarioStatus($c['id_informacion'], $c['id']);
                                                        echo $comentario;
                                                    }
                                                    ?>
                                                </td>
                                                <?php $countsc = $this->getNumSolicitudCredito($c['id_informacion'], $c['id']);?>
                                                <td>
                                                <?php if($countsc > 0): ?>    
                                                    <a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/update', array('id_informacion' => $c['id_informacion'], 'id_vehiculo' => $c['id'])); ?>" class="btn btn-success btn-xs">Solicitud</a>
                                                </td>
                                                <?php else: ?>
                                                    <a href="" class="btn btn-danger btn-xs" disabled="true">Solicitud</a>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $criteria5 = new CDbCriteria(array(
                    'condition' => "id_informacion={$id}"
                ));
                $sl = GestionSolicitudCredito::model()->count($criteria5);
                if ($sl > 0 || $gf > 0):
                ?>
                    <div class="row"></div>
                    <br />
                    <div class="row">
                        <div class="col-md-3">
                            <a href="<?php echo Yii::app()->createUrl('site/cierre/' . $id); ?>" class="btn btn-danger">Continuar</a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php //if ($gf > 216546): ?>
<!--                    <div class="row"></div>
                    <br />
                    <div class="row">
                        <div class="col-md-3">
                            <a href="<?php echo Yii::app()->createUrl('site/cierre/' . $id); ?>" class="btn btn-danger">Continuar</a>
                        </div>
                    </div>-->
                <?php //endif; ?>
                <div class="row"></div>
                <br />
                <div class="highlight">
                    <div class="row">
                        <h1 class="tl_seccion_green">Categorización</h1>
                    </div>
                    <div class="form">
                        <?php $agendamiento = new GestionAgendamiento; ?>

                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'action' => Yii::app()->createUrl('gestionAgendamiento/createCat'),
                            'id' => 'gestion-categorizacion-form',
                            'enableAjaxValidation' => false,
                            'htmlOptions' => array(
                                'onsubmit' => "return false;", /* Disable normal form submit */
                                'onkeypress' => " if(event.keyCode == 13){ sendCat(); } " /* Do ajax call when user presses enter key */
                            ),
                        ));
                        ?>
<?php //echo $form->errorSummary($agendamiento);   ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->labelEx($agendamiento, 'categorizacion'); ?>
                                <?php
                                $categorizacion = $this->getCategorizacion($id);
                                //echo $categorizacion;
                                echo $form->dropDownList($agendamiento, 'categorizacion', array(
                                    '' => '-Seleccione categoría-',
                                    'Hot A (hasta 7 dias)' => 'Hot A(hasta 7 dias)',
                                    'Hot B (hasta 15 dias)' => 'Hot B(hasta 15 dias)',
                                    'Hot C (hasta 30 dias)' => 'Hot C(hasta 30 dias)',
                                    'Warm (hasta 3 meses)' => 'Warm(hasta 3 meses)',
                                    'Cold (hasta 6 meses)' => 'Warm(hasta 6 meses)',
                                    'Very Cold(mas de 6 meses)' => 'Very Cold(mas de 6 meses)'), array('class' => 'form-control', 'options' => array($categorizacion => array('selected' => true))));
                                ?>
<?php echo $form->error($agendamiento, 'categorizacion'); ?>
                            </div>

                        </div>
                        <div class="row buttons">
                            <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                            <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                            <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="7">
                            <input type="hidden" name="GestionAgendamiento[id_informacion]" id="GestionAgendamiento_id_informacion" value="<?php echo $id; ?>">
                            <div class="col-md-2">
                        <?php //echo CHtml::submitButton($agendamiento->isNewRecord ? 'Cambiar' : 'Save', array('class' => 'btn btn-danger', 'onclick' => 'sendCat();'));  ?>
                            </div>
                        </div>
<?php $this->endWidget(); ?>
                    </div><!-- END FORM  -->

                </div><!--  END OF HIGHLIGHT -->
                <br />
                <div class="highlight">
                    <div class="row">
                        <h1 class="tl_seccion_green2">Seguimiento</h1>
                    </div>
                    <div class="form">
                        <?php $agendamiento = new GestionAgendamiento; ?>

                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'action' => Yii::app()->createUrl('gestionAgendamiento/create'),
                            'id' => 'gestion-agendamiento-form',
                            'enableAjaxValidation' => false,
                        ));
                        ?>
                                <?php //echo $form->errorSummary($agendamiento);   ?>
                        <div class="row">
                            <div class="col-md-4" style="display: none;">
                                <?php echo $form->labelEx($agendamiento, 'categorizacion'); ?>
                                <?php
                                $categorizacion = $this->getCategorizacion($id);
                                echo $form->dropDownList($agendamiento, 'categorizacion', array(
                                    '' => '-Seleccione categoría-',
                                    'Hot A (hasta 7 dias)' => 'Hot A(hasta 7 dias)',
                                    'Hot B (hasta 15 dias)' => 'Hot B(hasta 15 dias)',
                                    'Hot C (hasta 30 dias)' => 'Hot C(hasta 30 dias)',
                                    'Warm (hasta 3 meses)' => 'Warm(hasta 3 meses)',
                                    'Cold (hasta 6 meses)' => 'Warm(hasta 6 meses)',
                                    'Very Cold(mas de 6 meses)' => 'Very Cold(mas de 6 meses)'), array('class' => 'form-control', 'options' => array($categorizacion => array('selected' => true))));
                                ?>
                                <?php echo $form->error($agendamiento, 'categorizacion'); ?>
                            </div>
                            <div class="col-md-4">
<?php echo $form->labelEx($agendamiento, 'observaciones'); ?>
                                <?php echo $form->dropDownList($agendamiento, 'observaciones', array('' => '--Seleccione--','Cita' => 'Cita', 'Seguimiento' => 'Seguimiento','Falta de tiempo' => 'Falta de tiempo', 'Llamada de emergencia' => 'Llamada de emergencia', 'Busca solo precio' => 'Busca solo precio', 'Desiste' => 'Desiste', 'Otro' => 'Otro'), array('class' => 'form-control')); ?>
                                <?php echo $form->error($agendamiento, 'observaciones'); ?>
                            </div>
                            <div class="col-md-4 agendamiento">
<?php echo $form->labelEx($agendamiento, 'agendamiento'); ?>
<?php echo $form->textField($agendamiento, 'agendamiento', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control', 'autocomplete'=>"off")); ?>
<?php echo $form->error($agendamiento, 'agendamiento'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div id="cont-otro" style="display: none;">
                                    <label for="">Observaciones</label>
                                    <input type="text" class="form-control" name="GestionAgendamiento[otro]" id="GestionAgendamiento_otro"/>
                                </div>
                            </div>
                        </div>
                        <div class="row buttons">
                            <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                            <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                            <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="7">
                            <input type="hidden" name="GestionAgendamiento[id_informacion]" id="GestionAgendamiento_id_informacion" value="<?php echo $id; ?>">
                            <input type="hidden" name="GestionAgendamiento[nombre_cliente]" id="GestionAgendamiento_nombre_cliente" value="<?php echo $nombre_cliente; ?>">
                            <input type="hidden" name="GestionAgendamiento[nombre_concesionario]" id="GestionAgendamiento_nombre_concesionario" value="<?php echo $nombreConcesionario; ?>">
                            <input type="hidden" name="GestionAgendamiento[direccion_concesionario]" id="GestionAgendamiento_direccion_concesionario" value="<?php echo $direccion_concesionario; ?>">
                            <div class="col-md-2">
<?php echo CHtml::submitButton($agendamiento->isNewRecord ? 'Grabar' : 'Save', array('class' => 'btn btn-danger', 'id'=>'btn_grabar', 'onclick'=> "deshabilitarBoton('GestionInformacion_check','btn_grabar','gestion-agendamiento-form')")); ?>
                            </div>
                            <div class="col-md-3">
                                <div id="calendar-content" style="display: none;">
                                    <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                                </div>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div><!-- END FORM  -->
                    <div class="row">
                        <?php
                        $crit5 = new CDbCriteria(array('condition' => "id_informacion={$id} AND paso = 7"));
                        $agen5 = GestionAgendamiento::model()->count($crit5);

                        $ag5 = GestionAgendamiento::model()->findAll($crit5);
                        if ($agen5 > 0) {
                            ?>
                            <div class="col-md-8">
                                <h4 class="text-danger">Historial</h4>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped">
                                    <thead> <tr><th>Fecha Agendamiento</th> <th>Motivo</th> <th>Categorización</th> <th>Observaciones</th></tr> </thead>
                                    <tbody>
                            <?php } foreach ($ag5 as $a) { ?>
                                        <tr>
                                            <td><?php echo $a['agendamiento']; ?></td>
                                            <td><?php echo $a['observaciones']; ?></td>
                                            <td><?php echo $a['categorizacion']; ?></td>
                                            <td><?php echo $a['otro_observacion']; ?></td>
                                        </tr>

                            <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div><!--  END OF HIGHLIGHT -->
                <br />
                <div class="highlight">
                    <div class="row">
                        <h1 class="tl_seccion_green2">Paso 10 + 1</h1>
                    </div>
                    <div class="form">
                        <?php
                        $pss = new GestionPasoOnce;
                        $form = $this->beginWidget('CActiveForm', array(
                            'action' => Yii::app()->createUrl('gestionPasoOnce/create'),
                            'id'=>'gestion-paso-once-form',
                            'enableAjaxValidation' => false,
                        ));
                        ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->labelEx($pss,'tipo'); ?>
                                <?php echo $form->dropDownList($pss,'tipo', array('' => '--Seleccione--', '1' => 'Si', '0' => 'No'), array('class' => 'form-control')); ?>
                                <?php echo $form->error($pss,'tipo'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $form->labelEx($pss,'observacion'); ?>
                                <?php echo $form->textField($pss,'observacion',array('class'=>'form-control')); ?>
                                <?php echo $form->error($pss,'observacion'); ?>
                            </div>
                        </div>
                        <div class="row buttons">
                            <input type="hidden" name="GestionPasoOnce[paso]" id="GestionPasoOnce_paso" value="7">
                            <input type="hidden" name="GestionPasoOnce[id_informacion]" id="GestionPasoOnce_id_informacion" value="<?php echo $id; ?>">
                            <div class="col-md-2">
                                <?php echo CHtml::submitButton($model->isNewRecord ? 'Grabar' : 'Grabar', array('class' => 'btn btn-danger', 'id'=>'btn_grabar2', 'onclick'=>"deshabilitarBoton2('GestionPasoOnce_tipo','GestionPasoOnce_observacion','btn_grabar2','gestion-paso-once-form')")); ?>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div><!-- END FORM  -->
                </div>
                <?= $this->renderPartial('//layouts/rgd/links');?>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/inhabilitarBoton.js"></script>