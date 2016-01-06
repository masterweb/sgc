<?php $this->widget('application.components.Notificaciones'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<?php
$id_asesor = Yii::app()->user->getId();
$concesionarioid = $this->getConcesionarioDealerId($id_asesor);
$nombreConcesionario = $this->getNameConcesionarioById($concesionarioid);
$nombre_cliente = $this->getNombresInfo($id_informacion).' '.$this->getApellidosInfo($id_informacion);
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$android = FALSE;
if (stripos($ua, 'android') !== false) { // && stripos($ua,'mobile') !== false) {
    $android = TRUE;
}
$testAll = $this->getTestDriveOnly($id_informacion);
//echo 'id informacion: '.$id_informacion;
?>
<script type="text/javascript">
    $(function () {
        $('#GestionAgendamiento_agendamiento').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                $(this).find('.xdsoft_date.xdsoft_weekend')
                        .addClass('xdsoft_disabled');
            },
            weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
        });
        $('#GestionAgendamiento_observaciones').change(function () {
            var value = $(this).attr('value');
            if (value == 'Otro') {
                $('#cont-otro').show();
            } else {
                $('#cont-otro').hide();
            }
        });
        $('#gestion-agendamiento-form').validate({
            rules: {
                'GestionAgendamiento[agendamiento]': {
                    required: true
                },
                'GestionAgendamiento[observaciones]': {
                    required: true
                },
                'GestionAgendamiento[categorizacion]': {
                    required: true
                }
            },
            messages: {
                'GestionAgendamiento[agendamiento]': {
                    required: 'Seleccione una fecha de agendamiento'
                },
                'GestionAgendamiento[categorizacion]': {
                    required: 'Seleccione una categoría'
                }
            },
            submitHandler: function (form) {
                var proximoSeguimiento = $('#GestionAgendamiento_agendamiento').val();
                //console.log(proximoSeguimiento);
                var fechaSeguimiento = proximoSeguimiento.replace('/', '-');
                fechaSeguimiento = fechaSeguimiento.replace('/', '-');
                var fechaArray = fechaSeguimiento.split(' ');

                //console.log('fecha seguimiento: '+fechaSeguimiento);
                var categorizacion = $('#GestionAgendamiento_categorizacion').val();
                var dias = 0;
                switch (categorizacion) {
                    case 'Hot A (hasta 7 dias)':
                        dias = 7;
                        break;
                    case 'Hot B (hasta 15 dias)':
                        dias = 15;
                        break;
                    case 'Hot C (hasta 30 dias)':
                        dias = 30;
                        break;
                    case 'Warm (hasta 3 meses)':
                        dias = 60;
                        break;
                    case 'Cold (hasta 6 meses)':
                        dias = 180;
                        break;
                    case 'Very Cold(mas de 6 meses)':
                        dias = 181;
                        break;
                    default:
                }
                //console.log(proximoSeguimiento);
                var fechaActual = new Date().toJSON().slice(0, 10);
                //console.log('Fecha Actual: '+hoy);
                var diferencia = restaFechas(fechaActual, fechaArray[0]);
                if (diferencia <= dias) {
                    if (proximoSeguimiento != '') {
                        //console.log('proximo: '+proximoSeguimiento);
                        if ($('#GestionInformacion_check').val() != 2) {
                            var cliente = '';
                            var params = proximoSeguimiento.split("/");
                            var fechaDate = params[0] + params[1] + params[2];
                            var secDate = params[2].split(" ");
                            var fechaStart = params[0] + params[1] + secDate[0];
                            var start = secDate[1].split(":");
                            var startTime = start[0] + start[1];
                            var params2 = fechaDate.split(":");
                            var endTime = parseInt(startTime) + 100;
                            //console.log('start time:'+fechaStart+startTime);
                            //console.log('fecha end:'+fechaStart+endTime);
                            var href = '/intranet/usuario/index.php/gestionDiaria/ical?startTime=' + fechaStart + startTime + '&endTime=' + fechaStart + endTime + '&subject=Agendamiento Cita Cliente <?php echo $nombre_cliente; ?>&desc=Cita con el cliente demostracion:  <?php echo $nombre_cliente; ?>&location=Por definir&to_name=' + cliente + '&conc=<?php echo $nombreConcesionario; ?>';
                            //var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente prospección';
                            $('#event-download').attr('href', href);
                            $('#calendar-content').show();
                            $("#event-download").click(function () {
                                $('#GestionInformacion_calendar').val(1);
                                $('#calendar-content').hide();
                                $('#GestionInformacion_check').val(2)
                            });
                            if ($('#GestionInformacion_calendar').val() == 1) {
                                form.submit();
                            } else {
                                alert('Debes descargar agendamiento y luego dar click en Continuar');
                            }
                        } else {
                            form.submit();
                        }
                    }
                } else {
                    alert('Seleccione una fecha menor o igual a la fecha de Categorización.');
                    return false;
                }

            }
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


    function mostrarFecha(days) {
        milisegundos = parseInt(35 * 24 * 60 * 60 * 1000);

        fecha = new Date();
        day = fecha.getDate();
        // el mes es devuelto entre 0 y 11
        month = fecha.getMonth() + 1;
        year = fecha.getFullYear();

        document.write("Fecha actual: " + day + "/" + month + "/" + year);

        //Obtenemos los milisegundos desde media noche del 1/1/1970
        tiempo = fecha.getTime();
        //Calculamos los milisegundos sobre la fecha que hay que sumar o restar...
        milisegundos = parseInt(days * 24 * 60 * 60 * 1000);
        //Modificamos la fecha actual
        total = fecha.setTime(tiempo + milisegundos);
        day = fecha.getDate();
        month = fecha.getMonth() + 1;
        year = fecha.getFullYear();

        document.write("Fecha modificada: " + day + "/" + month + "/" + year);
    }
</script>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/jasny-bootstrap.css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jasny-bootstrap.js"></script>
<div class="container">
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">            
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita.png" alt="" /></span> Cita</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionVehiculo/create/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
            <li role="presentation" class="active"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion_on.png" alt="" /></span> Demostración</a></li>
            <li role="presentation">
                <?php if($testAll > 0){ ?>
                <a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" aria-controls="settings" role="tab">
                    <?php }else{ ?> 
                <a aria-controls="messages" role="tab">    
                <?php } ?> 
                    <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panels -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="row">
                    <h1 class="tl_seccion">Lista de Test Drive</h1>
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
                                        <th><span>Test Drive</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vec as $c): ?>
                                        <tr>
                                            <td><?php echo $this->getModel($c['modelo']); ?> </td>
                                            <td><?php echo $this->getVersion($c['version']); ?> </td>
                                            <td>
                                                <?php
                                                $test = $this->getTestDrive($c['id_informacion'], $c['id']);
                                                $preg1 = $this->getTestDemostracion($c['id_informacion'], $c['id']);
                                                $preg_fin = $preg1['preg1'];
                                                //echo $preg_fin;
                                                ?>
                                                <?php 
                                                if($preg_fin === 'Si'){
                                                    $class_activa = 'btn-warning';
                                                    $resp = 'Si';
                                                }else if($preg_fin === 'No'){
                                                    $class_activa = 'btn-tomate';
                                                    $resp = 'No';
                                                }else{
                                                    $class_activa = 'btn-danger';
                                                    $resp = 'Test Drive';
                                                }
                                                if ($test > 0): ?>
                                                    <!--                                            <a class="btn btn-success btn-xs btn-rf">Test Drive</a>-->
                                                                                                <!--<a href="<?php echo Yii::app()->createUrl('gestionVehiculo/negociacion', array('id_informacion' => $c['id_informacion'], 'id_vehiculo' => $c['id'])); ?>" class="btn btn-success btn-xs btn-rf">Test Drive</a>-->
                                                    <a href="<?php echo Yii::app()->createUrl('gestionTestDrive/create', array('id_informacion' => $c['id_informacion'], 'id' => $c['id'])); ?>" class="btn <?= $class_activa ?> btn-xs btn-rf"><?= $resp ?></a>
                                                <?php else: ?>    
                                                    <a href="<?php echo Yii::app()->createUrl('gestionTestDrive/create', array('id_informacion' => $c['id_informacion'], 'id' => $c['id'])); ?>" class="btn <?= $class_activa ?> btn-xs btn-rf"><?= $resp ?></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        </div>    
                    </div>
                </div>
                <div class="row"></div>
                <br />
                <?php
                
                //echo 'test drive: '.$testAll;
                if ($testAll > 0):
                    ?>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" class="btn btn-danger">Continuar</a>
                        </div>
                    </div>
                <?php endif; ?>
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
                        <?php //echo $form->errorSummary($agendamiento);  ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->labelEx($agendamiento, 'categorizacion'); ?>
                               <?php
                                $categorizacion = $this->getCategorizacion($id_informacion);
                                //echo $categorizacion;
                                echo $form->dropDownList($agendamiento,'categorizacion',array(
                            ''=>'-Seleccione categoría-',
                            'Hot A (hasta 7 dias)'=>'Hot A(hasta 7 dias)',
                            'Hot B (hasta 15 dias)'=>'Hot B(hasta 15 dias)',
                            'Hot C (hasta 30 dias)'=>'Hot C(hasta 30 dias)',
                            'Warm (hasta 3 meses)'=>'Warm(hasta 3 meses)',
                            'Cold (hasta 6 meses)'=>'Warm(hasta 6 meses)',
                            'Very Cold(mas de 6 meses)'=>'Very Cold(mas de 6 meses)'), array('class' => 'form-control', 'options' => array($categorizacion => array('selected' => true)))); ?>
                        <?php echo $form->error($agendamiento,'categorizacion'); ?>
                            </div>
                            
                        </div>
                        <div class="row buttons">
                            <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                            <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                            <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="7">
                            <input type="hidden" name="GestionAgendamiento[id_informacion]" id="GestionAgendamiento_id_informacion" value="<?php echo $id_informacion; ?>">
                            <div class="col-md-2">
                                <?php //echo CHtml::submitButton($agendamiento->isNewRecord ? 'Cambiar' : 'Save', array('class' => 'btn btn-danger', 'onclick' => 'sendCat();')); ?>
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
                        <?php //echo $form->errorSummary($agendamiento);  ?>
                        <div class="row">
                            <div class="col-md-4" style="display: none;">
                                <?php echo $form->labelEx($agendamiento, 'categorizacion'); ?>
                               <?php
                                $categorizacion = $this->getCategorizacion($id_informacion);
                                echo $form->dropDownList($agendamiento,'categorizacion',array(
                            ''=>'-Seleccione categoría-',
                            'Hot A (hasta 7 dias)'=>'Hot A(hasta 7 dias)',
                            'Hot B (hasta 15 dias)'=>'Hot B(hasta 15 dias)',
                            'Hot C (hasta 30 dias)'=>'Hot C(hasta 30 dias)',
                            'Warm (hasta 3 meses)'=>'Warm(hasta 3 meses)',
                            'Cold (hasta 6 meses)'=>'Warm(hasta 6 meses)',
                            'Very Cold(mas de 6 meses)'=>'Very Cold(mas de 6 meses)'), array('class' => 'form-control', 'options' => array($categorizacion => array('selected' => true)))); ?>
                            <?php echo $form->error($agendamiento,'categorizacion'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $form->labelEx($agendamiento, 'observaciones'); ?>
                                <?php echo $form->dropDownList($agendamiento,'observaciones',array(''=> '--Seleccione--','Falta de tiempo'=>'Falta de tiempo', 'Llamada de emergencia'=>'Llamada de emergencia', 'Busca solo precio'=>'Busca solo precio','Desiste' => 'Desiste','Otro' => 'Otro'), array('class' => 'form-control')); ?>
                                <?php echo $form->error($agendamiento, 'observaciones'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $form->labelEx($agendamiento, 'agendamiento'); ?>
                                <?php echo $form->textField($agendamiento, 'agendamiento', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
                                <?php echo $form->error($agendamiento, 'agendamiento'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div id="cont-obs-cat" style="display: none;">
                                    <label for="">Observación de Categorización</label>
                                    <input type="text" class="form-control" name="GestionAgendamiento[observacion_categorizacion]" id="GestionAgendamiento_observacion_categorizacion"/>
                                </div>
                            </div>
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
                            <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="6">
                            <input type="hidden" name="GestionAgendamiento[id_informacion]" id="GestionAgendamiento_id_informacion" value="<?php echo $id_informacion; ?>">
                            <div class="col-md-2">
                                <?php echo CHtml::submitButton($agendamiento->isNewRecord ? 'Grabar' : 'Save', array('class' => 'btn btn-danger')); ?>
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
                        <?php $crit5 = new CDbCriteria(array('condition' => "id_informacion={$id_informacion} AND paso = 6"));
                            $agen5 = GestionAgendamiento::model()->count($crit5);

                            $ag5 = GestionAgendamiento::model()->findAll($crit5);
                            if ($agen5 > 0) { ?>
                        <div class="col-md-8">
                            <h4 class="text-danger">Historial</h4>
                        </div>
                        <div class="col-md-8">
                            <?php }
                            foreach ($ag5 as $a) { ?>
                                <div class="row">
                                <div class="col-md-4"><strong>Fecha Agendamiento: </strong><?php echo $a['agendamiento']; ?></div>
                                <div class="col-md-4"><strong>Motivo: </strong><?php echo $a['observaciones']; ?></div>
                                <div class="col-md-4"><strong>Categorización: </strong><?php echo $a['categorizacion']; ?></div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div><!--  END OF HIGHLIGHT -->
                <br />
                <br />
                <div class="row">
                    <div class="col-md-8  col-xs-12 links-tabs">
                        <div class="col-md-3 col-xs-4"><p>También puedes ir a:</p></div>
                        <div class="col-md-2 col-xs-4"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>
                        <div class="col-md-3 col-xs-4"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>" class="creacion-btn">RGD</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>