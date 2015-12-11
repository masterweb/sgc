<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<?php
/* @var $this GestionVehiculoController */
/* @var $model GestionVehiculo */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
    $( document ).ready(function() {
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
    });
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
                        $('#event-download').attr('href',href);$('#calendar-content').show();
                        $("#event-download").click(function(){$('#GestionInformacion_calendar').val(1);$('#calendar-content').hide();$('#GestionInformacion_check').val(2)});
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
<div class="row highlight">
    <div class="form vehicle-cont">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'gestion-vehiculo-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <p class="note">Campos con <span class="required">*</span> son requeridos.</p>
        <div class="col-md-6">
            <?php // echo $form->errorSummary($model);  ?>

            <div class="row">
                <div class="col-md-6">
                    <?php echo $form->labelEx($model, 'modelo'); ?>
                    <?php
                    echo $form->dropDownList($model, 'modelo', array(
                        "" => "--Escoja un Modelo--",
                        "84" => "Picanto R",
                        "85" => "Rio R",
                        "24" => "Cerato Forte",
                        "90" => "Cerato R",
                        "89" => "Óptima Híbrido",
                        "88" => "Quoris",
                        "20" => "Carens R",
                        "11" => "Grand Carnival",
                        "21" => "Sportage Active",
                        "83" => "Sportage R",
                        "10" => "Sorento",
                        "25" => "K 2700 Cabina Simple",
                        "87" => "K 2700 Cabina Doble",
                        "86" => "K 3000"), array('class' => 'form-control'));
                    ?>
                    <?php echo $form->error($model, 'modelo'); ?>
                </div>
                <div class="col-md-6">
                    <div id="info2" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                    <?php echo $form->labelEx($model, 'version'); ?>
                    <?php echo $form->dropDownList($model, 'version', array('' => 'Escoja una versión'), array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'version'); ?>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $form->labelEx($model, 'precio'); ?>
                    <?php echo $form->textField($model, 'precio', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'precio'); ?>
                </div>
            </div>
            
            <div class="row buttons">
                <div class="col-md-6">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Grabar', array('class' => 'btn btn-danger', 'onclick' => 'send();')); ?>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>
<br>
<div class="row">
    <div class="highlight">
        <h4>Seguimiento</h4>
        <div class="alert alert-success alert-dismissible" role="alert" id="cont-alert" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            Datos grabados correctamente en seguimiento.
        </div>
        <div class="form cont-seguimiento">
            <form onsubmit="return false;" onkeypress=" if(event.keyCode == 13){ send(); } " id="gestion-diaria-form" action="/intranet/callcenter/index.php/gestionDiaria/create/<?php //echo $id; ?>" method="post">
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
                    <input name="GestionDiaria[primera_visita]" id="GestionDiaria_seguimiento" type="hidden" value="1">
                    <input name="GestionDiaria[seguimiento]" id="GestionDiaria_seguimiento" type="hidden" value="0">
                    <div class="col-md-3">
                        <input class="btn btn-danger" id="finalizar" type="submit" name="yt0" value="Crear Seguimiento" onclick="sendSeg();">
                    </div>
                    <div class="col-md-3">
                        <div id="calendar-content" style="display: none;">
                            <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>   
    </div>
</div>