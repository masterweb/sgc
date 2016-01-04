<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<?php
$count = 0;
if (isset($id)) {

    $criteria = new CDbCriteria(array(
                'condition' => "id_informacion='{$id}'"
            ));
    $vec = GestionVehiculo::model()->findAll($criteria);
    $count = count($vec);
}
?>
<style>
    #info2,#info3, #info4, #info5, #info6 {top: 19px;}#info2 img,#info3 img {width: 74%;}
</style>
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
        $('#info2').hide();$('#info3').hide();
        $('#GestionVehiculo_version').change(function() {
            var value = $(this).attr('value');
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getPrice"); ?>',
                beforeSend: function(xhr){
                    $('#info3').show();  // #info must be defined somehwere
                },
                type: 'POST',dataType : 'json',data:{id:value},
                success:function(data){
                    $('#GestionVehiculo_precio').val(data.options);$('#info3').hide();
                }
            });
            
        });
        $('#GestionVehiculo_version2').change(function() {
            var value = $(this).attr('value');
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getPrice"); ?>',
                beforeSend: function(xhr){
                    $('#info3').show();  // #info must be defined somehwere
                },type: 'POST',dataType : 'json',data:{id:value},
                success:function(data){$('#GestionVehiculo_precio').val(data.options);$('#info3').hide();}
            });
            
        });
    });
    function send()
    {
        //console.log('enter send');
        $('#gestion-vehiculo-form').validate({
            rules:{
                'GestionVehiculo[modelo]':{
                    required:true
                },
                'GestionVehiculo[version]':{
                    required:true
                }
            },
            messages:{
                'GestionVehiculo[modelo]':{
                    required:'Seleccione modelo'
                },'GestionVehiculo[version]':{
                    required:'Seleccione versión'
                }
            },
            submitHandler: function(form) {
                console.log('enter submit handler');
                var proximoSeguimiento = $('#agendamiento').val();
                //console.log('val of agendamiento: '+proximoSeguimiento);
                var dataform=$("#gestion-vehiculo-form").serialize();
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
                            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/createAjax"); ?>',
                            beforeSend: function(xhr){
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            type: 'POST',
                            data:dataform,
                            success:function(data){
                                $('#bg_negro').hide();
                                //alert('Datos grabados');
                                $('.vehicle-cont').remove();
                                $.ajax({
                                    url: '<?php echo Yii::app()->createAbsoluteUrl("site/getVec"); ?>',
                                    type: 'post',dataType: 'json',data: {id:<?php echo $id; ?>},
                                    success:function(data){
                                        $('.display-vec').html(data.options);$('#cont-agregar').show();
                                    }
                                });
                            }
                        });
                    }
                }
                
            }
        });
        
    }
    function send2()
    {
        //console.log('enter send');
        $('#gestion-vehiculo-form2').validate({
            rules:{
                'GestionVehiculo2[modelo]':{
                    required:true
                },
                'GestionVehiculo2[version]':{
                    required:true
                }
            },
            messages:{
                'GestionVehiculo2[modelo]':{
                    required:'Seleccione modelo'
                },'GestionVehiculo2[version]':{
                    required:'Seleccione versión'
                }},
            submitHandler: function(form) {
                console.log('enter submit handler');
                var proximoSeguimiento = $('#agendamiento2').val();
                var dataform=$("#gestion-vehiculo-form2").serialize();
                if(proximoSeguimiento != ''){
                    if($('#GestionInformacion_check2').val() != 2){
                        console.log('enter gestion informacion check2');
                        var params = proximoSeguimiento.split("/");var fechaDate = params[0]+params[1]+params[2];
                        var params2 = fechaDate.split(":");var endTime = parseInt(params2[1])+100; endTime = endTime.toString();var startTime = params2[0]+params2[1];
                        var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente';
                        $('#event-download2').attr('href',href);$('#calendar-content2').show();
                        $("#event-download2").click(function(){$('#GestionInformacion_calendar2').val(1);$('#calendar-content2').hide();$('#GestionInformacion_check2').val(2)});
                        if($('#GestionInformacion_calendar2').val() == 1){
                            console.log('enter infomacion calendar');
                            
                        }else{alert('Debes descargar agendamiento y luego dar click en Continuar');}
                    }else{
                        $.ajax({
                            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/createAjax2"); ?>',
                            beforeSend: function(xhr){
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            type: 'POST',
                            data:dataform,
                            success:function(data){
                                $('#bg_negro').hide();
                                //alert('Datos grabados');
                                $('.vehicle-cont').remove();
                                $.ajax({
                                    url: '<?php echo Yii::app()->createAbsoluteUrl("site/getVec"); ?>',
                                    type: 'post',dataType: 'json',data: {id:<?php echo $id; ?>},
                                    success:function(data){
                                        $('.display-vec').html(data.options);$('#cont-agregar').show();$('.form-content').hide();$('#gestion-vehiculo-form2').get(0).reset();  
                                    }
                                });
                            }
                        });
                    }
                }
            }
        });
        
    }
    
    function createVec(id){
    
        //        $.ajax({
        //            url: '<?php echo Yii::app()->createAbsoluteUrl("site/createVec"); ?>',
        //            beforeSend: function(xhr){
        //                $('#bg_negro').show();  // #bg_negro must be defined somewhere
        //            },
        //            type: 'POST',dataType : 'json',data:{id:id},
        //            success:function(data){
        //                $('#bg_negro').hide();$('.vehicle-cont').append(data.options);
        //            }
        //        });
            
        $('.form-content').show();
        
    }
    function cancelVec(){
        //$('#gestion-vehiculo-form').remove();
        $('.form-content').hide();
        $('#gestion-vehiculo-form').get(0).reset();  
    }
    function cancelVec2(){
        $('.form-content').hide();
        $('#gestion-vehiculo-form2').get(0).reset();  
    }
    
</script>
<style>
    @media (min-width: 768px){
        .bs-example {
            margin-right: 0;
            margin-left: 0;
            background-color: #E4E4E4;
            border-color: #ddd;
            border-width: 1px;
            border-radius: 4px 4px 0 0;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
    }
    .bs-example {
        position: relative;
        padding: 5px 15px 15px;
        border-color: #e5e5e5 #eee #eee;
        border-style: solid;
        border-width: 1px 0;
        -webkit-box-shadow: inset 0 3px 6px rgba(0,0,0,.05);
        box-shadow: inset 0 3px 6px rgba(0,0,0,.05);
    }
</style>
<div role="tabpanel">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <?php
        echo '<li role="presentation"><a aria-controls="profile" role="tab"><span>1</span> Prospección / <span>2</span> Cita</a></li>';
        echo '<li role="presentation" class="active"><a href="' . Yii::app()->createUrl('gestionVehiculo/create') . '" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>';
        ?>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id); ?>" saria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación / <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>

        <li role="presentation"><a aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane" id="home">
        </div>
        <div role="tabpanel" class="tab-pane active" id="profile">
            <div class="row" id="cont-agregar" <?php
        if ($count == 0) {
            echo 'style="display: none;"';
        }
        ?>><div class="col-md-3"><a class="btn btn-success" style="margin: 20px 0px;" onclick="createVec(<?php echo $id; ?>)">Agregar otro vehículo</a></div>
            </div>
            <div class="row"></div>
            <div class="row highlight">
                <div class="row display-vec"></div>
                <div class="form vehicle-cont">
                    <?php if ($count == 0): ?>
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'gestion-vehiculo-form',
                            'enableAjaxValidation' => false,
                            'htmlOptions' => array(
                                'onsubmit' => "return false;", /* Disable normal form submit */
                                'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                            ),
                                ));
                        ?>
                        <p class="note" style="margin-left: 28px;">Campos con <span class="required">*</span> son requeridos.</p>
                        <div class="col-md-8">
                            <?php // echo $form->errorSummary($model);   ?>

                            <div class="row">
                                <?php //echo $form->hiddenField($model, 'id_informacion', array('size' => 15, 'maxlength' => 15));  ?>
                                <input type="hidden" name="GestionVehiculo[id_informacion]" id="GestionVehiculo_id_informacion" value="<?php echo $id; ?>">
                                <?php //echo $form->error($model,'id_informacion');    ?>
                            </div>
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
                                    <div id="info2"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                    <?php echo $form->labelEx($model, 'version'); ?>
                                    <?php echo $form->dropDownList($model, 'version', array('' => 'Escoja una versión'), array('class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'version'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div id="info3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                    <?php echo $form->labelEx($model, 'precio'); ?>
                                    <?php echo $form->textField($model, 'precio', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'precio'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'dispositivo'); ?>
                                    <?php echo $form->textField($model, 'dispositivo', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'dispositivo'); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 for="">Seguimiento</h4>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Opciones</label>
                                    <select class="form-control" name="opciones_seguimiento" id="opciones_seguimiento">
                                        <option value="">Escoja una versión</option>
                                        <option value="Opcion 1">Opcion 1</option>
                                        <option value="Opcion 2">Opcion 2</option>
                                        <option value="Opcion 2">Opcion 3</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Agendamiento</label>
                                    <input type="text" name="agendamiento" id="agendamiento" class="form-control">
                                </div>
                            </div>
                            <!--<div class="row">
                                 <div class="col-md-6">
                            <?php echo $form->labelEx($model, 'accesorios'); ?>
                            <?php echo $form->textField($model, 'accesorios', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'accesorios'); ?>
                                 </div>
                                 <div class="col-md-6">
                            <?php echo $form->labelEx($model, 'seguro'); ?>
                            <?php
                            echo $form->dropDownList($model, 'seguro', array(
                                'Ace Seguros' => 'Ace Seguros S.A.',
                                'AIG METROPOLITANA CIA. DE SEGUROS Y REASEGUROS S.A.' => 'AIG METROPOLITANA CIA. DE SEGUROS Y REASEGUROS S.A.',
                                'ALIANZA COMPAÑIA DE SEGUROS Y REASEGUROS S.A.' => 'ALIANZA COMPAÑIA DE SEGUROS Y REASEGUROS S.A.',
                                'ASEGURADORA DEL SUR C.A.' => 'ASEGURADORA DEL SUR C.A.',
                                'BALBOA COMPAÑÍA DE SEGUROS Y REASEGUROS S.A.' => 'BALBOA COMPAÑÍA DE SEGUROS Y REASEGUROS S.A.',
                                'BMI DEL ECUADOR' => 'BMI DEL ECUADOR',
                                'EQUIVIDA S.A.' => 'EQUIVIDA S.A.',
                                'HISPANA DE SEGUROS' => 'HISPANA DE SEGUROS',
                                'INTEROCEANICA C.A.' => 'INTEROCEANICA C.A.',
                                'LATINA SEGUROS Y REASEGUROS' => 'LATINA SEGUROS Y REASEGUROS',
                                'QBE SEGUROS COLONIAL S.A.' => 'QBE SEGUROS COLONIAL S.A.',
                                'ROCAFUERTE SEGUROS S.A.' => 'ROCAFUERTE SEGUROS S.A.',
                                    ), array('class' => 'form-control'));
                            ?>
                            <?php echo $form->error($model, 'seguro'); ?>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-md-6">
                            <?php echo $form->labelEx($model, 'total'); ?>
                            <?php echo $form->textField($model, 'total', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'total'); ?>
                                 </div>
                                 <div class="col-md-6">
                            <?php echo $form->labelEx($model, 'plazo'); ?>
                            <?php echo $form->textField($model, 'plazo', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'plazo'); ?>
                                 </div>

                             </div>
                             <div class="row">

                                 <div class="col-md-6">
                            <?php echo $form->labelEx($model, 'forma_pago'); ?>
                            <?php echo $form->textField($model, 'forma_pago', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'forma_pago'); ?>
                                 </div>
                             </div>-->
                            <div class="row buttons">
                                <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                                <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                                <div class="col-md-2">
                                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Grabar', array('class' => 'btn btn-danger', 'onclick' => 'send();')); ?>
                                </div>
                                <div class="col-md-3">
                                    <div id="calendar-content" style="display: none;">
                                        <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div><!-- form -->
                    <div class="form form-content" style="display: none;">
                        <form onsubmit="return false;" onkeypress=" if(event.keyCode == 13){ send(); } " id="gestion-vehiculo-form2" action="/intranet/callcenter/index.php/gestionVehiculo/create/40" method="post">
                            <div class="col-md-8">
                                <div class="row">
                                    <input type="hidden" name="GestionVehiculo2[id_informacion]" id="GestionVehiculo_id_informacion" value="<?php echo $id; ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="GestionVehiculo_modelo" class="required">Modelo <span class="required">*</span></label>
                                        <select class="form-control" name="GestionVehiculo2[modelo]" id="GestionVehiculo_modelo2">
                                            <option value="" selected="selected">--Escoja un Modelo--</option>
                                            <option value="84">Picanto R</option>
                                            <option value="85">Rio R</option>
                                            <option value="24">Cerato Forte</option>
                                            <option value="90">Cerato R</option>
                                            <option value="89">Óptima Híbrido</option>
                                            <option value="88">Quoris</option>
                                            <option value="20">Carens R</option>
                                            <option value="11">Grand Carnival</option>
                                            <option value="21">Sportage Active</option>
                                            <option value="83">Sportage R</option>
                                            <option value="10">Sorento</option>
                                            <option value="25">K 2700 Cabina Simple</option>
                                            <option value="87">K 2700 Cabina Doble</option>
                                            <option value="86">K 3000</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="info2" style="display: none;"><img src="/intranet/callcenter/images/ajax-loader.gif" alt=""></div>
                                        <label for="GestionVehiculo_version">Version</label>
                                        <select class="form-control" name="GestionVehiculo2[version]" id="GestionVehiculo_version2">
                                            <option value="" selected="selected">Escoja una versión</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="info3" style="display: none;"><img src="/intranet/callcenter/images/ajax-loader.gif" alt=""></div>
                                        <label for="GestionVehiculo_precio" class="required">Precio <span class="required">*</span></label>
                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo2[precio]" id="GestionVehiculo_precio" type="text">                                                                    </div>
                                    <div class="col-md-6">
                                        <label for="GestionVehiculo_dispositivo">Dispositivo</label>
                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo2[dispositivo]" id="GestionVehiculo_dispositivo" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 for="">Seguimiento</h4>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Opciones</label>
                                        <select class="form-control" name="opciones_seguimiento" id="opciones_seguimiento">
                                            <option value="">Escoja una versión</option>
                                            <option value="Opcion 1">Opcion 1</option>
                                            <option value="Opcion 2">Opcion 2</option>
                                            <option value="Opcion 2">Opcion 3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Agendamiento</label>
                                        <input type="text" name="agendamiento2" id="agendamiento2" class="form-control">
                                    </div>
                                </div>
                                <!--                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="GestionVehiculo_accesorios">Accesorios</label>
                                                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo2[accesorios]" id="GestionVehiculo_accesorios" type="text">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="GestionVehiculo_seguro">Seguro</label>
                                                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo2[seguro]" id="GestionVehiculo_seguro" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="GestionVehiculo_total">Total</label>
                                                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo2[total]" id="GestionVehiculo_total" type="text">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="GestionVehiculo_plazo">Plazo</label>
                                                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo2[plazo]" id="GestionVehiculo_plazo" type="text">
                                                                    </div>
                                
                                                                </div>
                                                                <div class="row">
                                
                                                                    <div class="col-md-6">
                                                                        <label for="GestionVehiculo_forma_pago">Forma Pago</label>
                                                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo2[forma_pago]" id="GestionVehiculo_forma_pago" type="text"> 
                                                                    </div>
                                                                </div>-->
                                <div class="row buttons">
                                    <input type="hidden" name="GestionInformacion2[calendar]" id="GestionInformacion_calendar2" value="0">
                                    <input type="hidden" name="GestionInformacion2[check]" id="GestionInformacion_check2" value="1">
                                    <div class="col-md-8">
                                        <input class="btn btn-danger" onclick="send2();" type="submit" name="yt0" value="Crear">
                                        <button class="btn btn-primary" style="margin-left: 14px;" onclick="cancelVec2();" name="yt0" value="Cancelar">Cancelar</button>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="calendar-content2" style="display: none;">
                                            <a href="" class="btn btn-primary" id="event-download2">Descargar Evento</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                <?php else: ?>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="tables tablesorter" id="keywords">
                                <thead>
                                    <tr>
                                        <th><span>Modelo</span></th>
                                        <th><span>Versión</span></th>
                                        <th><span>Precio</span></th>
                                        <th><span>Dispositivo</span></th>
                                        <th><span>Accesorios</span></th>
                                        <th><span>Seguro</span></th>
                                        <th><span>Edición</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($vec as $c):
                                        ?>
                                        <tr>
                                            <td><?php echo $this->getModel($c['modelo']); ?> </td>
                                            <td><?php echo $this->getVersion($c['version']); ?> </td>
                                            <td><?php echo number_format($c['precio'], 2) ?> </td>
                                            <td><?php echo $c['dispositivo']; ?> </td>
                                            <td><?php echo $c['accesorios']; ?> </td>
                                            <td><?php echo $c['seguro']; ?> </td>
                                            <td><a href="<?php echo Yii::app()->createUrl('gestionVehiculo/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a>
                                                <a href="<?php echo Yii::app()->createUrl('gestionVehiculo/view', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a>

                                        </tr>
                                        <?php
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                    </div>
                    <div class="form-content" style="display: none;">
                        <form onsubmit="return false;" onkeypress=" if(event.keyCode == 13){ send(); } " id="gestion-vehiculo-form" action="/intranet/callcenter/index.php/gestionVehiculo/create/40" method="post">
                            <div class="col-md-8">
                                <div class="row">
                                    <input type="hidden" name="GestionVehiculo[id_informacion]" id="GestionVehiculo_id_informacion" value="<?php echo $id; ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="GestionVehiculo_modelo" class="required">Modelo <span class="required">*</span></label>
                                        <select class="form-control" name="GestionVehiculo[modelo]" id="GestionVehiculo_modelo">
                                            <option value="" selected="selected">--Escoja un Modelo--</option>
                                            <option value="84">Picanto R</option>
                                            <option value="85">Rio R</option>
                                            <option value="24">Cerato Forte</option>
                                            <option value="90">Cerato R</option>
                                            <option value="89">Óptima Híbrido</option>
                                            <option value="88">Quoris</option>
                                            <option value="20">Carens R</option>
                                            <option value="11">Grand Carnival</option>
                                            <option value="21">Sportage Active</option>
                                            <option value="83">Sportage R</option>
                                            <option value="10">Sorento</option>
                                            <option value="25">K 2700 Cabina Simple</option>
                                            <option value="87">K 2700 Cabina Doble</option>
                                            <option value="86">K 3000</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="info2" style="display: none;"><img src="/intranet/callcenter/images/ajax-loader.gif" alt=""></div>
                                        <label for="GestionVehiculo_version">Version</label>
                                        <select class="form-control" name="GestionVehiculo[version]" id="GestionVehiculo_version">
                                            <option value="" selected="selected">Escoja una versión</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="info3" style="display: none;"><img src="/intranet/callcenter/images/ajax-loader.gif" alt=""></div>
                                        <label for="GestionVehiculo_precio" class="required">Precio <span class="required">*</span></label>                                    <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[precio]" id="GestionVehiculo_precio" type="text">                                                                    </div>
                                    <div class="col-md-6">
                                        <label for="GestionVehiculo_dispositivo">Dispositivo</label>
                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[dispositivo]" id="GestionVehiculo_dispositivo" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 for="">Seguimiento</h4>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Opciones</label>
                                        <select class="form-control" name="opciones_seguimiento" id="opciones_seguimiento">
                                            <option value="">Escoja una versión</option>
                                            <option value="Opcion 1">Opcion 1</option>
                                            <option value="Opcion 2">Opcion 2</option>
                                            <option value="Opcion 2">Opcion 3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Agendamiento</label>
                                        <input type="text" name="agendamiento" id="agendamiento" class="form-control">
                                    </div>
                                </div>
                                <!--                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="GestionVehiculo_accesorios">Accesorios</label>
                                                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[accesorios]" id="GestionVehiculo_accesorios" type="text">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="GestionVehiculo_seguro">Seguro</label>
                                                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[seguro]" id="GestionVehiculo_seguro" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="GestionVehiculo_total">Total</label>
                                                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[total]" id="GestionVehiculo_total" type="text">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="GestionVehiculo_plazo">Plazo</label>
                                                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[plazo]" id="GestionVehiculo_plazo" type="text">
                                                                    </div>
                                
                                                                </div>
                                                                <div class="row">
                                
                                                                    <div class="col-md-6">
                                                                        <label for="GestionVehiculo_forma_pago">Forma Pago</label>
                                                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[forma_pago]" id="GestionVehiculo_forma_pago" type="text"> 
                                                                    </div>-->
                            </div>

                            <div class="row buttons">
                                <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                                <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                                <div class="col-md-5">
                                    <input class="btn btn-danger" onclick="send();" type="submit" name="yt0" value="Crear">
                                    <input class="btn btn-primary" style="margin-left: 14px;" onclick="cancelVec();" type="submit" name="yt0" value="Cancelar">
                                </div>
                                <div class="col-md-3">
                                    <div id="calendar-content" style="display: none;">
                                        <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"><a href="<?php echo Yii::app()->createUrl('site/presentacion', array('id' => $id)); ?>" class="btn btn-danger" style="margin: 20px 0px;">Continuar</a></div>
    </div>
</div>

</div>
<div class="row">
    <div class="col-md-8 links-tabs">
        <div class="col-md-3"><p>También puedes ir a:</p></div>
        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>
        <div class="col-md-3"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>" class="creacion-btn">RGD</a></div>
    </div>
</div>