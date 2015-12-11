<?php $this->widget('application.components.Notificaciones'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<?php
/* @var $this GestionPresentacionController */
/* @var $model GestionPresentacion */
/* @var $form CActiveForm */
//die('id vehiculo = '.$_GET['id_vehiculo']);
$id_informacion = $this->getinformacion($id_vehiculo);
$id_modelo = $this->getIdModelo($id_vehiculo);
//echo $id_modelo;die();
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#GestionAgendamiento_agendamiento').datetimepicker({
            lang:'es',
            onGenerate:function( ct ){
                $(this).find('.xdsoft_date.xdsoft_weekend')
                .addClass('xdsoft_disabled');
            },
            weekends:['01.01.2014','02.01.2014','03.01.2014','04.01.2014','05.01.2014','06.01.2014'],
            minDate:'-1970/01/01',//yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates:['03.04.2015','01.05.2015','10.08.2015','09.10.2015','02.11.2015','03.11.2015','25.12.2015'], formatDate:'d.m.Y'
        });
        $('#GestionPresentacion_preg1_duda').change(function () {
            var value = $("#GestionPresentacion_preg1_duda option:selected").val();
            if (value == 1) {
                $('.duda').show();
            } else {
                $('.duda').hide();
            }
        });
        $('#GestionPresentacion_preg2_necesidades').change(function () {
            var value = $("#GestionPresentacion_preg2_necesidades option:selected").val();
            if (value == 0) {
                $('.necesidades').show();
            } else {
                $('.necesidades').hide();
            }
        });
        $('#gestion-agendamiento-form').validate({
            rules:{
                'GestionAgendamiento[agendamiento]':{
                    required:true
                },
                'GestionAgendamiento[observaciones]': {
                    required: true
                },
                'GestionAgendamiento[categorizacion]':{
                    required:true
                }
            },
            messages:{
                'GestionAgendamiento[agendamiento]':{
                    required:'Seleccione una fecha de agendamiento'
                },
                'GestionAgendamiento[categorizacion]':{
                    required:'Seleccione una categoría'
                }
            },
            submitHandler: function(form) {
                var proximoSeguimiento = $('#GestionAgendamiento_agendamiento').val();
                if(proximoSeguimiento != ''){
                    console.log('proximo: '+proximoSeguimiento);
                    if($('#GestionInformacion_check').val() != 2){
                        var cliente = '';
                        var params = proximoSeguimiento.split("/");
                        var fechaDate = params[0]+params[1]+params[2];
                        var secDate = params[2].split(" ");
                        var fechaStart = params[0]+params[1]+secDate[0];
                        var start = secDate[1].split(":");
                        var startTime = start[0]+start[1];
                        var params2 = fechaDate.split(":");
                        var endTime = parseInt(startTime)+100; 
                        //console.log('start time:'+fechaStart+startTime);
                        //console.log('fecha end:'+fechaStart+endTime);
                        var href = '/intranet/ventas/index.php/gestionDiaria/ical?startTime='+fechaStart+startTime+'&endTime='+fechaStart+endTime+'&subject=Agendamiento Cita Cliente&desc=Cita con el cliente prospección&location=Por definir&to_name='+cliente+'&conc=no';
                        //var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente prospección';
                        $('#event-download').attr('href',href);$('#calendar-content').show();
                        $("#event-download").click(function(){$('#GestionInformacion_calendar').val(1);$('#calendar-content').hide();$('#GestionInformacion_check').val(2)});
                        if($('#GestionInformacion_calendar').val() == 1){form.submit();}else{alert('Debes descargar agendamiento y luego dar click en Continuar');}
                    }else{form.submit();}
                }
            }
        });
    });
</script>
<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita.png" alt="" /></span> Cita</a></li>
        <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionVehiculo/create/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
        <li role="presentation" class="active"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id_informacion); ?>" saria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id_informacion); ?>" saria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/cierre/' . $id_informacion); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/seguimiento/', array('id_vehiculo' => $id_informacion)); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="profile">
            <div class="row">
                <h1 class="tl_seccion_rf">Presentación - Modelo: <?php echo $this->getModeloTestDrive($id_vehiculo); ?>, Versión - <?php echo $this->getVersionTestDrive($id_vehiculo); ?></h1>
            </div>
            <!--            <div class="row">
                            <div class="col-md-10">
                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/present.jpg" alt="" style="display: block; margin: 0 auto;">
                            </div>
                        </div>-->
            <div class="cont-pres">
                <div class="row">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/pres-bg.jpg" alt="" style="display: block; margin: 0 auto;">
                </div>
                <?php
                $criteria = new CDbCriteria(array(
                    'condition' => "id_modelo = {$id_modelo}",
                    'order' => 'orden ASC'
                ));
                $pres = GestionFichaPresentacion::model()->findAll($criteria);
                /* echo '<pre>';
                  print_r($pres);
                  echo '</pre>'; */
                ?>
                <br />
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <td>PARTE DELANTERA</td><td><?php echo $pres[0]['descripcion']; ?></td>
                        </tr>
                        <tr>
                            <td>COMPARTIMIENTO DEL MOTOR</td><td><?php echo $pres[1]['descripcion']; ?></td>
                        </tr>
                        <tr>
                            <td>LADO DEL PASAJERO</td><td><?php echo $pres[2]['descripcion']; ?></td>
                        </tr>
                        <tr>
                            <td>PARTE POSTERIOR</td><td><?php echo $pres[3]['descripcion']; ?></td>
                        </tr>
                        <tr>
                            <td>LADO DEL CONDUCTOR</td><td><?php echo $pres[4]['descripcion']; ?></td>
                        </tr>
                        <tr>
                            <td>INTERIOR</td><td><?php echo $pres[5]['descripcion']; ?></td>
                        </tr>
                    </tbody>
                </table>

            </div>
            
            <div class="form">

                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'gestion-presentacion-form',
                    'enableAjaxValidation' => false,
                ));
                ?>
                <?php echo $form->errorSummary($model); ?>
                <div class="cont-qsn" style="display: none;">
                    <div class="row">
                        <label for="">1. ¿Le queda alguna duda con respecto a las características y beneficios del vehículo?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->dropDownList($model, 'preg1_duda', array('No' => 'No', 'Si' => 'Si'), array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="duda" style="display: none;">
                            <label>Cual y especificar duda</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php echo $form->textField($model, 'preg1_sec1_duda', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="">2. ¿Satisface el vehículo sus necesidades y deseos?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->dropDownList($model, 'preg2_necesidades', array('Si' => 'Si', 'No' => 'No'), array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="necesidades" style="display: none;">
                            <label>Cuáles son las necesidades que no satisface el vehículo? </label>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php echo $form->textField($model, 'preg2_sec1_necesidades', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <label for="">3. ¿Está satisfecho con el vehículo recomendado?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->dropDownList($model, 'preg3_satisfecho', array('Si' => 'Si', 'No' => 'No'), array('class' => 'form-control')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row buttons">
                    <div class="col-md-4">
                        <input type="hidden" name="GestionPresentacion[id_vehiculo]" id="GestionPresentacion_id_vehiculo" value="<?php echo $id_vehiculo; ?>">
                        <input type="hidden" name="GestionPresentacion[paso]" id="GestionPresentacion_paso" value="5">
                        <input type="hidden" name="GestionPresentacion[id_informacion]" id="GestionPresentacion_id_informacion" value="<?php echo $id_informacion; ?>">
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Save', array('class' => 'btn btn-danger')); ?>
                    </div>

                </div>

                <?php $this->endWidget(); ?>

            </div><!-- form -->
    </div>
        </div>
    </div>
</div>
