<?php
/* @var $this GestionFinanciamientoController */
/* @var $model GestionFinanciamiento */
/* @var $form CActiveForm */
$count = 0;
if (isset($id_vehiculo)) {

    $criteria = new CDbCriteria(array(
                'condition' => "id_vehiculo='{$id_vehiculo}'"
            ));
    $vec = GestionFinanciamiento::model()->findAll($criteria);
    $count = count($vec);
}
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script>
    $( document ).ready(function() {
        $('#GestionFinanciamiento_fecha_cita').datetimepicker({
            lang:'es',
            onGenerate:function( ct ){
                $(this).find('.xdsoft_date.xdsoft_weekend')
                .addClass('xdsoft_disabled');
            },
            weekends:['01.01.2014','02.01.2014','03.01.2014','04.01.2014','05.01.2014','06.01.2014'],
            minDate:'-1970/01/01',//yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates:['03.04.2015','01.05.2015','10.08.2015','09.10.2015','02.11.2015','03.11.2015','25.12.2015'], formatDate:'d.m.Y'
        });
        $('#agendamiento').datetimepicker({
            lang:'es',
            onGenerate:function( ct ){
                $(this).find('.xdsoft_date.xdsoft_weekend')
                .addClass('xdsoft_disabled');
            },
            weekends:['01.01.2014','02.01.2014','03.01.2014','04.01.2014','05.01.2014','06.01.2014'],
            minDate:'-1970/01/01',//yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates:['03.04.2015','01.05.2015','10.08.2015','09.10.2015','02.11.2015','03.11.2015','25.12.2015'], formatDate:'d.m.Y'
        });
        /*$('#gestion-financiamiento-form').validate({
            submitHandler: function(form) { 
                //alert('enter submit handler');
                var proximoSeguimiento = $('#GestionFinanciamiento_fecha_cita').val();
                if(proximoSeguimiento != ''){
                    //console.log('enter proximo seguimiento');
                    if($('#GestionFinanciamiento_check').val() != 2){
                        var params = proximoSeguimiento.split("/");var fechaDate = params[0]+params[1]+params[2];
                        var params2 = fechaDate.split(":");var endTime = parseInt(params2[1])+100; endTime = endTime.toString();var startTime = params2[0]+params2[1];
                        var href = '/intranet/callcenter/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Seguimiento con Cliente&desc=Agenda de Seguimiento con el cliente';
                        $('#event-download').attr('href',href);$('#calendar-content').show();
                        $("#event-download").click(function(){$('#GestionFinanciamiento_calendar').val(1);$('#calendar-content').hide();$('#GestionFinanciamiento_check').val(2)});
                        if($('#GestionFinanciamiento_calendar').val() == 1){form.submit();}else{alert('Debes descargar agendamiento y luego dar click en Continuar');}
                    }else{form.submit();}
                }else{form.submit();}
            }
        });*/
        $('#GestionFinanciamiento_saldo_financiar').focus(function() {
            var cuota_inicial = $('#GestionFinanciamiento_cuota_inicial').val();
            var id_gestion_vehiculo = $('#GestionInformacion_id_vehiculo').val();
            var precio = $('#GestionInformacion_precio').val();
            var saldo = precio - cuota_inicial;
            $('#GestionFinanciamiento_saldo_financiar').val(saldo);
        });
    });
    function send()
    {
        //alert('enter send');
        $('#gestion-financiamiento-form').validate({
            submitHandler: function(form) {
                //alert('enter submit');
                var dataform=$('#gestion-financiamiento-form').serialize();
                var proximoSeguimiento = $('#GestionFinanciamiento_fecha_cita').val();
                if(proximoSeguimiento != ''){
                    //console.log('enter proximo seguimiento');
                    if($('#GestionFinanciamiento_check').val() != 2){
                        var params = proximoSeguimiento.split("/");var fechaDate = params[0]+params[1]+params[2];
                        var params2 = fechaDate.split(":");var endTime = parseInt(params2[1])+100; endTime = endTime.toString();var startTime = params2[0]+params2[1];
                        var href = '/intranet/callcenter/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Seguimiento con Cliente&desc=Agenda de Seguimiento con el cliente';
                        $('#event-download').attr('href',href);$('#calendar-content').show();
                        $("#event-download").click(function(){$('#GestionFinanciamiento_calendar').val(1);$('#calendar-content').hide();$('#GestionFinanciamiento_check').val(2)});
                        if($('#GestionFinanciamiento_calendar').val() == 1){form.submit();}else{alert('Debes descargar agendamiento y luego dar click en Continuar');}
                    }else{
                        
                    }
                }else{
                    form.submit();
                }
                
                
            }
        });        
    }
    function sendSeg(){
        $('#gestion-diaria-form').validate({
            submitHandler: function(form) {
                var proximoSeguimiento = $('#agendamiento').val();
                //console.log('val of agendamiento: '+proximoSeguimiento);
                var dataform=$("#gestion-diaria-form").serialize();
                if(proximoSeguimiento != ''){
                    if($('#GestionInformacion_check').val() != 2){
                        console.log('enter gestion informacion check');
                        var params = proximoSeguimiento.split("/");var fechaDate = params[0]+params[1]+params[2];
                        var params2 = fechaDate.split(":");var endTime = parseInt(params2[1])+100; endTime = endTime.toString();var startTime = params2[0]+params2[1];
                        var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente';
                        $('#event-download2').attr('href',href);$('#calendar-content2').show();
                        $("#event-download2").click(function(){$('#GestionInformacion_calendar').val(1);$('#calendar-content2').hide();$('#GestionInformacion_check').val(2)});
                        if($('#GestionInformacion_calendar').val() == 1){
                            console.log('enter infomacion calendar');                            
                        }else{alert('Debes descargar agendamiento y luego dar click en Continuar');}
                    }else{
                        $.ajax({
                            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionDiaria/createAjax"); ?>',
                            beforeSend: function(xhr){
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            type: 'POST',
                            data:dataform,
                            success:function(data){
                                $('#bg_negro').hide();
                                $('#cont-alert').show();$('#gestion-diaria-form').get(0).reset();  
                            }
                        });
                    }
                }
            }
        });
    }
</script>
<div role="tabpanel">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a aria-controls="profile" role="tab"><span>1</span> Prospección / <span>2</span> Cita</a></li>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionVehiculo/create/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación / <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
        <li role="presentation" class="active"><a aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane" id="home">
        </div>
        <div role="tabpanel" class="tab-pane active" id="profile">
            <div class="row">
                <h1 class="tl_seccion_rf">Financiamiento: Modelo - <?php echo $this->getModelFin($id_vehiculo); ?>, Versión - <?php echo $this->getVersionFin($id_vehiculo); ?></h1>
            </div>
            <?php if ($count == 0): ?>
                <div class="highlight">
                    <div class="form">

                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'gestion-financiamiento-form',
                            'enableAjaxValidation' => false,
                            'htmlOptions' => array(
                                'onsubmit' => "return false;", /* Disable normal form submit */
                                'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                            ),
                                ));
                        ?>

                        <div class="row"><p class="note">Campos con <span class="required">*</span> son requeridos.</p></div>

                        <?php //echo $form->errorSummary($model);  ?>

                        <div class="row">
                            <?php //echo $form->labelEx($model,'id_informacion');  ?>
                            <?php //echo $form->textField($model,'id_informacion'); ?>
                            <input type="hidden" name="GestionFinanciamiento[calendar]" id="GestionFinanciamiento_calendar" value="0">
                            <input type="hidden" name="GestionFinanciamiento[check]" id="GestionFinanciamiento_check" value="1">
                            <input type="hidden" name="GestionFinanciamiento[id_informacion]" id="GestionInformacion_id_informacion" value="<?php echo $id_informacion; ?>">
                            <input type="hidden" name="GestionFinanciamiento[id_vehiculo]" id="GestionInformacion_id_vehiculo" value="<?php echo $id_vehiculo; ?>">
                            <input type="hidden" name="GestionFinanciamiento[precio]" id="GestionInformacion_precio" value="<?php echo $this->getPrecio($id_vehiculo); ?>">
                            <?php //echo $form->error($model,'id_informacion');  ?>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'cuota_inicial'); ?>
                                <?php echo $form->textField($model, 'cuota_inicial', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'cuota_inicial'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'saldo_financiar'); ?>
                                <?php echo $form->textField($model, 'saldo_financiar', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'saldo_financiar'); ?>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'tarjeta_credito'); ?>
                                <?php echo $form->textField($model, 'tarjeta_credito', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'tarjeta_credito'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'otro'); ?>
                                <?php echo $form->textField($model, 'otro', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'otro'); ?>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Seguro</label>
                                <select name="GestionFinanciamiento[seguro]" id="GestionFinanciamiento_seguro" class="form-control">
                                <option value="Ace Seguros S.A.">Ace Seguros S.A.</option>
                                <option value="AIG METROPOLITANA CIA. DE SEGUROS Y REASEGUROS S.A.">AIG METROPOLITANA CIA. DE SEGUROS Y REASEGUROS S.A.</option>
                                <option value="ALIANZA COMPAÑIA DE SEGUROS Y REASEGUROS S.A.">ALIANZA COMPAÑIA DE SEGUROS Y REASEGUROS S.A.</option>
                                <option value="ASEGURADORA DEL SUR C.A.">ASEGURADORA DEL SUR C.A.</option>
                                <option value="BALBOA COMPAÑÍA DE SEGUROS Y REASEGUROS S.A.">BALBOA COMPAÑÍA DE SEGUROS Y REASEGUROS S.A.</option>
                                <option value="BMI DEL ECUADOR">BMI DEL ECUADOR</option>
                                <option value="EQUIVIDA S.A.">EQUIVIDA S.A.</option>
                                <option value="HISPANA DE SEGUROS">HISPANA DE SEGUROS</option>
                                <option value="INTEROCEANICA C.A.">INTEROCEANICA C.A.</option>
                                <option value="LATINA SEGUROS Y REASEGUROS">LATINA SEGUROS Y REASEGUROS</option>
                                <option value="QBE SEGUROS COLONIAL S.A.">QBE SEGUROS COLONIAL S.A.</option>
                                <option value="ROCAFUERTE SEGUROS S.A.">ROCAFUERTE SEGUROS S.A.</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'plazos'); ?>
                                <?php echo $form->textField($model, 'plazos', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'plazos'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'cuota_mensual'); ?>
                                <?php echo $form->textField($model, 'cuota_mensual', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'cuota_mensual'); ?>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'avaluo'); ?>
                                <?php echo $form->textField($model, 'avaluo', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'avaluo'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'categoria'); ?>
                                <?php
                                echo $form->dropDownList($model, 'categoria', array(
                                    'hotA' => 'HOT A (HASTA 7 DÍAS)',
                                    'hotB' => 'HOT B (HASTA 15 DÍAS)', 'hotC' => 'HOT C (HASTA 30 DÍAS)'
                                    , 'warm' => 'WARM (HASTA 3 MESES)', 'cold' => 'COLD (HASTA 6 MESES)', 'very-cold' => 'VERY COLD (MAS DE 6 MESES)'), array('class' => 'form-control'));
                                ?>
                                <?php echo $form->error($model, 'categoria'); ?>
                            </div>
                        </div>
                        <div class="row buttons">
                            <div class="col-md-2">
                                <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Save', array('class' => 'btn btn-danger', 'onclick' => 'send()')); ?>
                            </div>
                            <div class="col-md-3">
                                <div id="calendar-content" style="display: none;">
                                    <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                                </div>
                            </div>
                        </div>

                        <?php $this->endWidget(); ?>

                    </div><!-- form -->
                </div>
            <?php endif; ?>
            <br>
            <div class="highlight">
                <h4>Seguimiento</h4>
                <div class="alert alert-success alert-dismissible" role="alert" id="cont-alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Datos grabados correctamente en seguimiento.
                </div>
                <div class="form cont-seguimiento">
                    <form onsubmit="return false;" onkeypress=" if(event.keyCode == 13){ send(); } " id="gestion-diaria-form" action="/intranet/callcenter/index.php/gestionDiaria/create/<?php //echo $id;    ?>" method="post">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Opciones</label>
                                <select class="form-control" name="GestionDiaria[opciones_seguimiento]" id="opciones_seguimiento">
                                    <option value="">Escoja una versión</option>
                                    <option value="Opcion 1">Opcion 1</option>
                                    <option value="Opcion 2">Opcion 2</option>
                                    <option value="Opcion 2">Opcion 3</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="">Agendamiento</label>
                                <input type="text" name="GestionDiaria[agendamiento]" id="agendamiento" class="form-control">
                            </div>
                        </div>
                        <div class="row buttons">
                            <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                            <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                            <input name="GestionDiaria[id_informacion]" id="GestionDiaria_id_informacion" type="hidden" value="<?php echo $id_informacion; ?>">
                            <input name="GestionDiaria[id_vehiculo]" id="GestionDiaria_id_vehiculo" type="hidden" value="<?php echo $id_vehiculo; ?>">
                            <input name="GestionDiaria[primera_visita]" id="GestionDiaria_seguimiento" type="hidden" value="0">
                            <input name="GestionDiaria[seguimiento]" id="GestionDiaria_seguimiento" type="hidden" value="1">
                            <div class="col-md-3">
                                <input class="btn btn-danger" id="finalizar" type="submit" name="yt0" value="Crear Seguimiento" onclick="sendSeg();">
                            </div>
                            <div class="col-md-3">
                                <div id="calendar-content2" style="display: none;">
                                    <a href="" class="btn btn-primary" id="event-download2">Descargar Evento</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>   
            </div>
            <?php if ($count > 0): ?>
                <div class="row">
                    <div class="col-md-3" id="btn-proforma">
                        <a href="<?php echo Yii::app()->createUrl('site/proforma', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" type="button" class="btn btn-danger" target="_blank">Imprimir Cotización</a>
                    </div>
                    <div class="col-md-2" id="btn-pdf">
                        <a href="<?php echo Yii::app()->createUrl('site/pdf2', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" type="button" class="btn btn-danger" target="_blank">Enviar PDF</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="settings"></div>
        <div role="tabpanel" class="tab-pane" id="messages"></div>
    </div>

</div>