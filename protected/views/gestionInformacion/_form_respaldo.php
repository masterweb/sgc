<?php
/* @var $this GestionInformacionController */
/* @var $model GestionInformacion */
/* @var $form CActiveForm */
?>
<?php 
$id_responsable = Yii::app()->user->getId();
//echo 'responsable id: '.$id_responsable;
$dealer_id = $this->getDealerId($id_responsable);
//echo '<br>dealer id: '.$dealer_id;
$city_id = $this->getCityId($dealer_id);
$provincia_id = $this->getProvinciaId($city_id);
//echo '<br>Ciudad id: '.$city_id.', Provincia id: '.$provincia_id;
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/jasny-bootstrap.css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jasny-bootstrap.js"></script>
<style>
    form li{margin-left: -14px;}
</style>
<script>
    $( document ).ready(function() {
        $('#GestionInformacion_fecha_cita').datetimepicker({
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
        $('#agendamiento2').datetimepicker({
            lang:'es',
            onGenerate:function( ct ){
                $(this).find('.xdsoft_date.xdsoft_weekend')
                .addClass('xdsoft_disabled');
            },
            weekends:['01.01.2014','02.01.2014','03.01.2014','04.01.2014','05.01.2014','06.01.2014'],
            minDate:'-1970/01/01',//yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates:['03.04.2015','01.05.2015','10.08.2015','09.10.2015','02.11.2015','03.11.2015','25.12.2015'], formatDate:'d.m.Y'
        });
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
                    $('.cont-accesorios').show();
                }
            });
            
        });
        $('#GestionConsulta_preg2').change(function(){
            var value = $( "#GestionConsulta_preg2 option:selected" ).val();
            if(value == 1){
                $('.cont-img').show();$('.cont-link').show();
            }else{
                $('.cont-img').hide();$('.cont-link').hide();
            }
        });
        $('#GestionConsulta_preg3').change(function(){
            var value = $( "#GestionConsulta_preg3 option:selected" ).val();
            if(value == 2){
                $('.cont-utl').hide();
            }else{
                $('.cont-utl').show();
            }
        });
        $('#GestionInformacion_visita').change(function() {
            var value = $(this).attr('value');
            if(value == 'si'){
                $('.datepick').show();$('#continuar').hide();$('#finalizar').show();
            }else{
                $('.datepick').hide();$('#GestionInformacion_fecha_cita').val('');$('#continuar').show();$('#finalizar').hide();
            }
        });
        $('#GestionConsulta_preg1_sec5').click(function(){
            if ($('#GestionConsulta_preg1_sec5').is(':checked')){
                $('.cont-vec-new').hide();
            }else{
                $('.cont-vec-new').show();
            }
        });
        /*$('#gestion-informacion-form').validate({
            submitHandler: function(form) { 
                //alert('enter submit handler');
                var proximoSeguimiento = $('#GestionInformacion_fecha_cita').val();
                if(proximoSeguimiento != ''){
                    //console.log('enter proximo seguimiento');
                    if($('#GestionInformacion_check').val() != 2){
                        var params = proximoSeguimiento.split("/");var fechaDate = params[0]+params[1]+params[2];
                        var params2 = fechaDate.split(":");var endTime = parseInt(params2[1])+100; endTime = endTime.toString();var startTime = params2[0]+params2[1];
                        var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente prospección';
                        $('#event-download').attr('href',href);$('#calendar-content').show();
                        $("#event-download").click(function(){$('#GestionInformacion_calendar').val(1);$('#calendar-content').hide();$('#GestionInformacion_check').val(2)});
                        if($('#GestionInformacion_calendar').val() == 1){form.submit();}else{alert('Debes descargar agendamiento y luego dar click en Continuar');}
                    }else{form.submit();}
                }else{form.submit();}
            }
        });*/
        $('#GestionInformacion_provincia_domicilio').change(function() {
                var value = $( "#GestionInformacion_provincia_domicilio option:selected" ).val();
                //console.log('valor seleccionado: '+value);
                var data = '';
                $.ajax({
                    url:'<?php echo Yii::app()->createAbsoluteUrl("site/getciudades"); ?>',
                    //dataType: "json",
                    beforeSend: function(xhr){
                        $('#info3').show();  // #info must be defined somehwere
                    },
                    type: 'post',
                    data:{
                        id:value
                    },
                    success:function(data){
                        //alert(data.options)
                        $('#GestionInformacion_ciudad_domicilio').html(data); $('#info3').hide();
                    }
                });
                //alert(value)
                $('#GestionInformacion_ciudad_domicilio').html(data);
                
            });
            $('#GestionProspeccionPr_pregunta').change(function(){
                var value = $( "#GestionProspeccionPr_pregunta option:selected" ).val();
                switch(value) {
                    case '3':
                        $('.cont-vec').show();$('.cont-interesado').hide();$('.cont-nocont').hide();
                        break;
                    case '4':
                        $('.cont-vec').hide();$('.cont-interesado').show();$('.cont-nocont').hide();
                        break;
                    case '5':
                        $('.cont-vec').hide();$('.cont-interesado').hide();$('.cont-nocont').show();
                        break;
                    case '1':
                    case '2':
                    case '6':    
                        $('.cont-vec').hide();$('.cont-interesado').hide();$('.cont-nocont').hide();
                        break;
                    default:
                        break;
                }
            });
            $('#GestionProspeccion_lugar').change(function(){
                var value = $( "#GestionProspeccion_lugar option:selected" ).val();
                if(value == 1 || value == 2){
                    $('.cont-lugar').show();$('.cont-conc').hide();
                }else if(value == 0){
                    $('.cont-conc').show();$('.cont-lugar').hide();
                }else{$('.cont-conc').hide();$('.cont-lugar').hide();}
            });
            
            $('#GestionConsulta_preg1_sec1').change(function() {
            var marca = $(this).attr('value');
            $.ajax({
                type: 'post',
                url:'<?php echo Yii::app()->createUrl("site/getmodelos"); ?>',
                //dataType: "json",
                data:{marca:marca},
                success: function(data){
                    //alert(data.options)
                    $('#Cotizador_modelo').html(data);
                }
            });
            
        });
        $('#GestionProspeccionRp_preg3_sec2').change(function() {
            var marca = $(this).attr('value');
            $.ajax({
                type: 'post',
                url:'<?php echo Yii::app()->createUrl("site/getmodelos"); ?>',
                //dataType: "json",
                data:{marca:marca},
                success: function(data){
                    //alert(data.options)
                    $('#Cotizador_modelo').html(data);
                }
            });
            
        });

        $('#Cotizador_modelo').change(function() {
            var modelo = $(this).attr('value');
            //alert(value);
            $.ajax({
                type: 'post',
                url:'<?php echo Yii::app()->createUrl("site/getmodelsyears"); ?>',
                //dataType: "json",
                data:{modelo:modelo},
                success: function(data){
                    //alert(data.options)
                    $('#Cotizador_year').html(data);
                }
            });
        });

    });
    function sendSeg(){
        var observaciones = $('#GestionProspeccionPr_pregunta').val();
        switch(observaciones) {
            case '3':
                
                break;
            default:
                break;
        }
    }
    function sendInfo(){
        //console.log('enter send info');
        var tipo = $('#GestionInformacion_tipo').val();
        if(tipo == 'gestion'){
            //console.log('enter gestion');
            if ($('#GestionConsulta_preg1_sec5').is(':checked')){
                    $('#gestion-informacion-form').validate({                
                        rules:{'GestionInformacion[nombres]':{required:true},'GestionInformacion[apellidos]':{required:true},
                        'GestionInformacion[cedula]':{required:true},'GestionInformacion[direccion]':{required:true},
                        'GestionInformacion[provincia_domicilio]':{required:true},'GestionInformacion[ciudad_domicilio]':{required:true},
                        'GestionInformacion[email]':{required:true, email:true},'GestionInformacion[celular]':{required:true},'GestionInformacion[telefono_oficina]':{required:true},
                        'GestionInformacion[telefono_casa]':{required:true},'GestionConsulta[preg5]':{required:true},
                        'GestionVehiculo[modelo]':{required:true},'GestionVehiculo[version]':{required:true}},
                        messages:{'GestionInformacion[nombres]':{required:'Ingrese los nombres'},'GestionInformacion[apellidos]':{required:'Ingrese los apellidos'},
                        'GestionInformacion[cedula]':{required:'Ingrese la cécula'},'GestionInformacion[direccion]':{required:'Ingrese la dirección'},
                        'GestionInformacion[provincia_domicilio]':{required:'Seleccione la provincia'},'GestionInformacion[ciudad_domicilio]':{required:'Seleccione la ciudad'},
                        'GestionInformacion[email]':{required:'Ingrese el email',email:'Ingrese un email válido'},'GestionInformacion[celular]':{required:'Ingrese el celular'},
                        'GestionInformacion[telefono_oficina]':{required:'Ingrese el teléfono'},'GestionInformacion[telefono_casa]':{required:'Ingrese el teléfono'},'GestionConsulta[preg5]':{required:'Ingrese el presupuesto'},
                        'GestionVehiculo[modelo]':{required:'Seleccione modelo'},'GestionVehiculo[version]':{required:'Seleccione versión'}},
                        submitHandler: function(form) {
                            form.submit();
                        }
                    });
                }else{
                   $('#gestion-informacion-form').validate({                
                        rules:{'GestionInformacion[nombres]':{required:true},'GestionInformacion[apellidos]':{required:true},
                        'GestionInformacion[cedula]':{required:true},'GestionInformacion[direccion]':{required:true},
                        'GestionInformacion[provincia_domicilio]':{required:true},'GestionInformacion[ciudad_domicilio]':{required:true},
                        'GestionInformacion[email]':{required:true, email:true},'GestionInformacion[celular]':{required:true},'GestionInformacion[telefono_oficina]':{required:true},
                        'GestionInformacion[telefono_casa]':{required:true},'GestionConsulta[preg1_sec1]':{required:true},'Cotizador[modelo]':{required:true},'Cotizador[year]':{required:true},'GestionConsulta[preg1_sec4]':{required:true},'GestionConsulta[preg5]':{required:true},
                        'GestionVehiculo[modelo]':{required:true},'GestionVehiculo[version]':{required:true}},
                        messages:{'GestionInformacion[nombres]':{required:'Ingrese los nombres'},'GestionInformacion[apellidos]':{required:'Ingrese los apellidos'},
                        'GestionInformacion[cedula]':{required:'Ingrese la cécula'},'GestionInformacion[direccion]':{required:'Ingrese la dirección'},
                        'GestionInformacion[provincia_domicilio]':{required:'Seleccione la provincia'},'GestionInformacion[ciudad_domicilio]':{required:'Seleccione la ciudad'},
                        'GestionInformacion[email]':{required:'Ingrese el email',email:'Ingrese un email válido'},'GestionInformacion[celular]':{required:'Ingrese el celular'},
                        'GestionInformacion[telefono_oficina]':{required:'Ingrese el teléfono'},'GestionInformacion[telefono_casa]':{required:'Ingrese el teléfono'},
                        'GestionConsulta[preg1_sec1]':{required:'Seleccione la marca'},'Cotizador[modelo]':{required:'Seleccione el modelo'},'Cotizador[year]':{required:'Seleccione el año'},'GestionConsulta[preg1_sec4]':{required:'Ingrese kilometraje'},'GestionConsulta[preg5]':{required:'Ingrese el presupuesto'},
                        'GestionVehiculo[modelo]':{required:'Seleccione modelo'},'GestionVehiculo[version]':{required:'Seleccione versión'}},
                        submitHandler: function(form) {
                            form.submit();
                        }
                    });
                }
            
        }else if(tipo == 'prospeccion'){
            var observaciones = $('#GestionProspeccionPr_pregunta').val();
            switch(observaciones) {
                    case '1':// no estoy interesado
                    case '2':// falta de dinero
                    case '6':// telefono equivocado    
                        $('.cont-vec').hide();$('.cont-interesado').hide();$('.cont-nocont').hide();
                        $('#gestion-informacion-form').validate({                
                            rules:{'GestionInformacion[nombres]':{required:true},'GestionInformacion[apellidos]':{required:true},
                            'GestionInformacion[email]':{required:true, email:true}},
                            messages:{'GestionInformacion[nombres]':{required:'Ingrese los nombres'},'GestionInformacion[apellidos]':{required:'Ingrese los apellidos'},
                            'GestionInformacion[email]':{required:'Ingrese el email',email:'Ingrese un email válido'}},
                        submitHandler: function(form) {form.submit();}
                        });
                        break;
                    case '3':// compro otro vehiculo
                        $('.cont-vec').show();$('.cont-interesado').hide();$('.cont-nocont').hide();
                        $('#gestion-informacion-form').validate({                
                            rules:{'GestionInformacion[nombres]':{required:true},'GestionInformacion[apellidos]':{required:true},
                            'GestionInformacion[email]':{required:true, email:true},'GestionProspeccionRp[marca]':{required:true},'GestionProspeccionRp[modelo]':{required:true},'GestionProspeccionRp[year]':{required:true}},
                            messages:{'GestionInformacion[nombres]':{required:'Ingrese los nombres'},'GestionInformacion[apellidos]':{required:'Ingrese los apellidos'},
                            'GestionInformacion[email]':{required:'Ingrese el email',email:'Ingrese un email válido'},'GestionProspeccionRp[marca]':{required:'Ingrese la marca'},'GestionProspeccionRp[modelo]':{required:'Ingrese el modelo'},'GestionProspeccionRp[year]':{required:'Ingrese el año'}},
                        submitHandler: function(form) {form.submit();}
                        });
                        break;
                    case '4':// si estoy interesado
                        $('.cont-vec').hide();$('.cont-interesado').show();$('.cont-nocont').hide();
                        $('#gestion-informacion-form').validate({                
                            rules:{'GestionInformacion[nombres]':{required:true},'GestionInformacion[apellidos]':{required:true},
                            'GestionInformacion[email]':{required:true, email:true},'GestionDiaria[agendamiento]':{required:true},'GestionProspeccionRp[lugar]':{required:true},'GestionProspeccionRp[agregar]':{required:true}},
                            messages:{'GestionInformacion[nombres]':{required:'Ingrese los nombres'},'GestionInformacion[apellidos]':{required:'Ingrese los apellidos'},
                            'GestionInformacion[email]':{required:'Ingrese el email',email:'Ingrese un email válido'},'GestionDiaria[agendamiento]':{required:'Ingrese agendamiento'},'GestionProspeccionRp[lugar]':{required:'Seleccione lugar de encuentro'},'GestionProspeccionRp[agregar]':{required:'Seleccione agregar'}},
                        submitHandler: function(form) {
                            var proximoSeguimiento = $('#agendamiento').val();
                            if(proximoSeguimiento != ''){
                                if($('#GestionInformacion_check').val() != 2){
                                    var params = proximoSeguimiento.split("/");var fechaDate = params[0]+params[1]+params[2];
                                    var params2 = fechaDate.split(":");var endTime = parseInt(params2[1])+100; endTime = endTime.toString();var startTime = params2[0]+params2[1];
                                    var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente prospección';
                                    $('#event-download').attr('href',href);$('#calendar-content').show();
                                    $("#event-download").click(function(){$('#GestionInformacion_calendar').val(1);$('#calendar-content').hide();$('#GestionInformacion_check').val(2)});
                                    if($('#GestionInformacion_calendar').val() == 1){form.submit();}else{alert('Debes descargar agendamiento y luego dar click en Continuar');}
                                }else{form.submit();}
                            }
                        }
                        });
                        break;
                    case '5':// no contesta
                        $('.cont-vec').hide();$('.cont-interesado').hide();$('.cont-nocont').show();
                        $('#gestion-informacion-form').validate({                
                            rules:{'GestionInformacion[nombres]':{required:true},'GestionInformacion[apellidos]':{required:true},
                            'GestionInformacion[email]':{required:true, email:true},'GestionDiaria[agendamiento2]':{required:true}},
                            messages:{'GestionInformacion[nombres]':{required:'Ingrese los nombres'},'GestionInformacion[apellidos]':{required:'Ingrese los apellidos'},
                            'GestionInformacion[email]':{required:'Ingrese el email',email:'Ingrese un email válido'},'GestionDiaria[agendamiento2]':{required:'Selecione Re Agendar'}},
                        submitHandler: function(form) {
                            var proximoSeguimiento = $('#agendamiento2').val();
                            if(proximoSeguimiento != ''){
                                if($('#GestionInformacion_check2').val() != 2){
                                    var params = proximoSeguimiento.split("/");var fechaDate = params[0]+params[1]+params[2];
                                    var params2 = fechaDate.split(":");var endTime = parseInt(params2[1])+100; endTime = endTime.toString();var startTime = params2[0]+params2[1];
                                    var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente prospección';
                                    $('#event-download2').attr('href',href);$('#calendar-content2').show();
                                    $("#event-download2").click(function(){$('#GestionInformacion_calendar2').val(1);$('#calendar-content2').hide();$('#GestionInformacion_check2').val(2)});
                                    if($('#GestionInformacion_calendar2').val() == 1){form.submit();}else{alert('Debes descargar agendamiento y luego dar click en Continuar');}
                                }else{form.submit();}
                            }
                        }
                        });
                        break;
                    default:
                        break;
                }
            
        }
         
    }
</script>
<div role="tabpanel">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <?php
        if ($tipo == 'prospeccion'):
            echo '<li role="presentation" class="active"><a aria-controls="profile" role="tab"><span>1</span> Prospección / <span>2</span> Cita</a></li>';
            echo '<li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>';
        else:
            echo '<li role="presentation"><a aria-controls="profile" role="tab"><span>1</span> Prospección / <span>2</span> Cita</a></li>';
            echo '<li role="presentation" class="active"><a href="' . Yii::app()->createUrl('gestionVehiculo/create') . '" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>';
        endif;
        ?>
        <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
        <li role="presentation"><a aria-controls="profile" role="tab"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
        <li role="presentation"><a aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <div class="form">
                <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'gestion-informacion-form',
                        'enableAjaxValidation' => false,
                       'htmlOptions' => array(
                           'enctype' => 'multipart/form-data',
                           'onsubmit' => "return false;", /* Disable normal form submit */
                           'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                       ),
                    ));
                ?>
            <div class="highlight">
                <div class="row">
                    <h1 class="tl_seccion_rf">Datos del Cliente</h1>
                </div>  
                    <div class="row"><p class="note">Campos con <span class="required">*</span> son requeridos.</p></div>

                    <?php echo $form->errorSummary($model);   ?>

                    <div class="row">
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'nombres', array('required' => 'required')); ?>
                            <label class="" for="">Nombres <?php if($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion'){echo '<span class="required">*</span>';} ?></label>
                            <?php echo $form->textField($model, 'nombres', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'nombres'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'apellidos'); ?>
                            <label class="" for="">Apellidos <?php if($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion'){echo '<span class="required">*</span>';} ?></label>
                            <?php echo $form->textField($model, 'apellidos', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'apellidos'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'cedula'); ?>
                            <label class="" for="">Cédula <?php if($_GET['tipo'] == 'gestion'){echo '<span class="required">*</span>';} ?></label>
                            <?php //echo $form->textField($model, 'cedula', array('size' => 20, 'maxlength' => 10, 'class' => 'form-control')); ?>
                            <input size="20" maxlength="10" class="form-control" name="GestionInformacion[cedula]" id="GestionInformacion_cedula" type="text" value="<?php if (isset($id)) {echo $this->getCedula($id);} ?>">
                        <?php echo $form->error($model, 'cedula'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'direccion'); ?>
                            <label class="" for="">Dirección <?php if($_GET['tipo'] == 'gestion'){echo '<span class="required">*</span>';} ?></label>
                            <?php echo $form->textField($model, 'direccion', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'direccion'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'email'); ?>
                            <label class="" for="">Provincia Domicilio <?php if($_GET['tipo'] == 'gestion'){echo '<span class="required">*</span>';} ?></label>
                            <?php
                            $criteria = new CDbCriteria(array(
                                        'order' => 'nombre'
                                    ));
                            $provincias = CHtml::listData(Provincias::model()->findAll($criteria), "id_provincia", "nombre");
                            ?>
                            <?php
                            echo $form->dropDownList($model, 'provincia_domicilio', $provincias,array('empty' => '---Seleccione una provincia---','class' => 'form-control'));
                            ?>
                            <?php echo $form->error($model, 'provincia_domicilio'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'celular'); ?>
                            <label class="" for="">Ciudad Domicilio <?php if($_GET['tipo'] == 'gestion'){echo '<span class="required">*</span>';} ?></label>
                            <div id="info3" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                            <?php echo $form->dropDownList($model, 'ciudad_domicilio', array('' => '---Seleccione una ciudad---'),array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'ciudad_domicilio'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'email'); ?>
                            <label class="" for="">Email <?php if($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion'){echo '<span class="required">*</span>';} ?></label>
                            <?php echo $form->textField($model, 'email', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'email'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'celular'); ?>
                            <label class="" for="">Celular <?php if($_GET['tipo'] == 'gestion'){echo '<span class="required">*</span>';} ?></label>
                            <?php echo $form->textField($model, 'celular', array('size' => 15, 'maxlength' => 10, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'celular'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'telefono_oficina'); ?>
                            <label class="" for="">Teléfono Oficina <?php if($_GET['tipo'] == 'gestion'){echo '<span class="required">*</span>';} ?></label>
                            <?php echo $form->textField($model, 'telefono_oficina', array('size' => 15, 'maxlength' => 9, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'telefono_oficina'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'telefono_casa'); ?>
                            <label class="" for="">Teléfono Domicilio <?php if($_GET['tipo'] == 'gestion'){echo '<span class="required">*</span>';} ?></label>
                            <?php echo $form->textField($model, 'telefono_casa', array('size' => 15, 'maxlength' => 9, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'telefono_casa'); ?>
                        </div>

                    </div>

                    <?php if (isset($_GET['tipo']) && $_GET['tipo'] == 'gestion'): ?>
                    <div class="row">
                        <h1 class="tl_seccion_rf">Datos del Concesionario</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'provincia_conc'); ?>
                            <?php
                            $criteria = new CDbCriteria(array(
                                        'condition' => "estado='s'",
                                        'order' => 'nombre'
                                    ));
                            $provincias = CHtml::listData(Provincias::model()->findAll($criteria), "id_provincia", "nombre");
                            ?>
                            <?php echo $form->dropDownList($model, 'provincia_conc', $provincias, array('class' => 'form-control', 'empty' => 'Selecciona una provincia','options' => array($provincia_id=>array('selected'=>true)))); ?>
                            <?php  //echo $form->dropDownList($model,'sex',array('1'=>'men','2'=>'women'), array('options' => array('2'=>array('selected'=>true)))); ?>
                            <?php echo $form->error($model, 'provincia_conc'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'ciudad_conc'); ?>
                            <div id="info5" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                            <?php 
                            $criteria2 = new CDbCriteria(array('condition' => "id={$city_id}",'order' => 'name'));
                            $ciudades = CHtml::listData(Dealercities::model()->findAll($criteria2), "id", "name");
                            ?>
                            <?php echo $form->dropDownList($model, 'ciudad_conc', $ciudades, array('class' => 'form-control', 'options' => array($city_id=>array('selected'=>true)))); ?>
                            <?php echo $form->error($model, 'ciudad_conc'); ?>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'concesionario'); ?>
                            <div id="info6" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                            <?php 
                            $criteria3 = new CDbCriteria(array('condition' => "cityid={$city_id}",'order' => 'name'));
                            $dealers = CHtml::listData(Dealers::model()->findAll($criteria3), "id", "name");
                            ?>
                            <?php //echo $form->dropDownList($model, 'concesionario', array('' => 'Concesionario'), array('class' => 'form-control')); ?>
                            <?php echo $form->dropDownList($model, 'concesionario', $dealers, array('class' => 'form-control', 'options' => array($dealer_id=>array('selected'=>true)))); ?>
                            <?php echo $form->error($model, 'concesionario'); ?>
                        </div>
                    </div>
                    <?php endif; ?>
            </div>
            <br>
            <?php if(isset($_GET['tipo']) && ($_GET['tipo'] == 'prospeccion')): ?>
            <div class="highlight"><!-- Seguimiento -->
                <div class="row">
                    <h1 class="tl_seccion_rf">Seguimiento</h1>
                </div>
                <div class="alert alert-success alert-dismissible" role="alert" id="cont-alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Datos grabados correctamente en seguimiento.
                </div>
                <div class="form cont-seguimiento">
                    <?php $prospeccion = new GestionProspeccionPr; ?>
                    
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Observaciones</label>
                                <select class="form-control" name="GestionProspeccionPr[pregunta]" id="GestionProspeccionPr_pregunta">
                                    <option value="1">No estoy interesado</option>
                                    <option value="2">Falta de dinero</option>
                                    <option value="3">Compró otro vehículo</option>
                                    <option value="4">Si estoy interesado</option>
                                    <option value="5">No contesta</option>
                                    <option value="6">Teléfono equivocado</option>
                                </select>
                            </div>
                        </div>
                        <div class="cont-vec bs-example bs-example-type" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Opción otro vehículo</label>
                                    <select name="GestionProspeccionRp[nuevousado]" id="GestionProspeccionRp_nuevp_usado" class="form-control">
                                        <option value="1">Nuevo</option>
                                        <option value="0">Usado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="">Marca</label>
                                                <?php
                                                $prospeccionrp = new GestionProspeccionRp;
                                                $consulta = new GestionConsulta;
                                                $criteria = new CDbCriteria(array('group' => 'modelo','order' => 'id asc'));
                                                $ciudades = CHtml::listData(Marcas::model()->findAll($criteria), "marca", "marca");

                                                echo $form->dropDownList($prospeccionrp, "preg3_sec2", $ciudades, array('empty' => '---Seleccione una marca---', 'class' => 'form-control'));
                                                ?>                        
                                                <?php echo $form->error($prospeccionrp, 'preg3_sec2'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Modelo</label>
                                                <select name="Cotizador[modelo]" id="Cotizador_modelo" class="form-control">
                                                <option value="" selected="selected">---Seleccione un modelo---</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="">Año</label>
                                                <select name="Cotizador[year]" id="Cotizador_year" class="form-control">
                                                <option value="" selected="selected">---Seleccione el año---</option>
                                                </select>
                                            </div>
                                        </div>
<!--                                        <div class="col-md-2">
                                            <label for="">Marca</label>
                                            <input type="text" name="GestionProspeccionRp[marca]" id="GestionProspeccion_marca" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="">Modelo</label>
                                            <input type="text" name="GestionProspeccionRp[modelo]" id="GestionProspeccion_modelo" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="">Año</label>
                                            <input type="text" name="GestionProspeccionRp[year]" id="GestionProspeccion_year" class="form-control">
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cont-interesado" style="display:none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Agendamiento</label>
                                    <input type="text" name="GestionDiaria[agendamiento]" id="agendamiento" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Lugar de Encuentro</label>
                                    <select name="GestionProspeccionRp[lugar]" id="GestionProspeccion_lugar" class="form-control">
                                        <option value="0">Concesionario</option>
                                        <option value="1">Lugar de Trabajo</option>
                                        <option value="2">Domicilio</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row cont-conc">
                                <div class="col-md-3 col-md-offset-4">
                                    <label for="">Provincia Concesionario</label>
                                    <select class="form-control" name="Casos[provincia]" id="Casos_provincia">
                                    <option value="">Selecciona una provincia</option>
                                    <option value="1">Azuay</option>
                                    <option value="5">Chimborazo</option>
                                    <option value="7">El Oro</option>
                                    <option value="8">Esmeraldas</option>
                                    <option value="10">Guayas</option>
                                    <option value="11">Imbabura</option>
                                    <option value="12">Loja</option>
                                    <option value="13">Los Ríos</option>
                                    <option value="14">Manabí</option>
                                    <option value="16">Napo</option>
                                    <option value="18">Pastaza</option>
                                    <option value="19">Pichincha</option>
                                    <option value="21">Tsachilas</option>
                                    <option value="23">Tungurahua</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Ciudad</label>
                                    <select class="form-control valid" name="Casos[ciudad]" id="Casos_ciudad">
                                    <option value="value">Seleccione</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Concesionario</label>
                                    <select class="form-control" name="Casos[concesionario]" id="Casos_concesionario">
                                    <option value="" selected="selected">Concesionario</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-4 cont-lugar" style="display: none;">
                                    <label for="">Ingreso</label>
                                    <input type="text" name="GestionProspeccionRp[ingresolugar]" id="GestionProspeccion_ingreso_lugar" class="form-control">
                                </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Agregar Persona</label>
                                    <select name="GestionProspeccionRp[agregar]" id="GestionProspeccionRp_agregar" class="form-control">
                                        <option value="0">Jefe de Agencia</option>
                                        <option value="1">Plan Renova</option>
                                        <option value="2">Flotas</option>
                                    </select>
                                </div>
                                
                            </div>
                        </div>
                        <div class="cont-nocont" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Re agendar</label>
                                    <input type="text" name="GestionDiaria[agendamiento2]" id="agendamiento2" class="form-control">
                                </div>
                            </div>
                            
                        </div>
                        <div class="row buttons">
                            <input type="hidden" name="GestionInformacion[id_cotizacion]" id="GestionInformacion_id_cotizacion" value="<?php echo $id; ?>">
                            <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                            <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                            <input name="GestionInformacion[tipo]" id="GestionInformacion_tipo" type="hidden" value="<?php if(isset($_GET['tipo'])){ echo $_GET['tipo'];} ?>">
                            <input name="GestionInformacion[paso]" id="GestionInformacion_paso" type="hidden" value="1-2">
                            <input name="GestionDiaria[id_informacion]" id="GestionDiaria_id_informacion" type="hidden" value="<?php //echo $id_informacion; ?>">
                            <input name="GestionDiaria[id_vehiculo]" id="GestionDiaria_id_vehiculo" type="hidden" value="<?php //echo $id_vehiculo; ?>">
                            <input name="GestionDiaria[primera_visita]" id="GestionDiaria_seguimiento" type="hidden" value="1">
                            <input name="GestionDiaria[seguimiento]" id="GestionDiaria_seguimiento" type="hidden" value="0">
                            
                        </div>
                    
                </div>
                <div class="row buttons">
                    <div class="col-md-2">
                        <input type="hidden" name="GestionInformacion2[calendar]" id="GestionInformacion_calendar2" value="0">
                        <input type="hidden" name="GestionInformacion2[check]" id="GestionInformacion_check2" value="1">
                        <input type="hidden" name="GestionInformacion[fuente]" id="GestionInformacion_fuente" value="<?php echo $fuente; ?>">
                        <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET['tipo']; ?>">
                        <?php
                        if ($_GET['tipo'] == 'prospeccion'):
                            echo '<input type="hidden" name="GestionInformacion[status]" id="GestionInformacion_status" value="prospeccion">';
                        else:
                            echo '<input type="hidden" name="GestionInformacion[status]" id="GestionInformacion_status" value="primera_visita">';
                        endif;
                        ?>
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Grabar', array('class' => 'btn btn-danger', 'id' => 'finalizar','onclick' => 'sendInfo();')); ?>
                        <input class="btn btn-primary" style="display: none;" onclick=";" type="submit" name="yt0"  id="continuar" value="Abandonar">
                    </div>
                    <div class="col-md-2">
                        <div id="calendar-content2" style="display: none;">
                            <a href="" class="btn btn-primary" id="event-download2">Descargar Evento</a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div id="calendar-content" style="display: none;">
                            <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                        </div>
                    </div>
                </div>
            </div><!-- End Seguimiento -->
            <?php endif; ?>
            <?php if(isset($_GET['tipo']) && ($_GET['tipo'] == 'gestion')): ?>
            <div class="highlight"><!-- Seguimiento -->
                <div class="row">
                    <h1 class="tl_seccion_rf">Seguimiento</h1>
                </div>
                <div class="form cont-seguimiento">
                    <div class="row">
                        
                        <label for="">1. ¿Qué clase de vehículo conduce en la actualidad?</label>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Marca</label>
                                <?php
                                $consulta = new GestionConsulta;
                                $criteria = new CDbCriteria(array('group' => 'modelo','order' => 'id asc'));
                                $ciudades = CHtml::listData(Marcas::model()->findAll($criteria), "marca", "marca");

                                echo $form->dropDownList($consulta, "preg1_sec1", $ciudades, array('empty' => '---Seleccione una marca---', 'class' => 'form-control'));
                                ?>                        
                                <?php echo $form->error($consulta, 'preg1_sec1'); ?>
                            </div>
                            <div class="col-md-3">
                                <label for="">Modelo</label>
                                <select name="Cotizador[modelo]" id="Cotizador_modelo" class="form-control">
                                <option value="" selected="selected">---Seleccione un modelo---</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Año</label>
                                <select name="Cotizador[year]" id="Cotizador_year" class="form-control">
                                <option value="" selected="selected">---Seleccione el año---</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="kilometraje">Kilometraje</label>
                                <?php echo $form->dropDownList($consulta,'preg1_sec4',array('0-5000'=>'0-5000',
                                '5001-10000'=>'5001-10000',
                                '10001-20000'=>'10001-20000',
                                '20001-30000'=>'20001-30000',
                                '30001-50000'=>'30001-50000'), array('class' => 'form-control')); ?>
                                <label for="kilometraje" generated="true" class="error" style="display: none;">Este campo es requerido</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label style="color: #aa1f2c;">
                                        <input type="checkbox" name="GestionConsulta[preg1_sec5]" id="GestionConsulta_preg1_sec5" value="1"> Primer Vehículo
                                    </label>
                                  </div>
                            </div>
                        </div>    
                    </div>
                    <div class="row cont-vec-new">
                        <label for="">2. ¿Qué tiene pensado hacer con su vehículo actual?</label>
                        <div class="row">
                            <div class="col-md-5">
                                <?php echo $form->dropDownList($consulta,'preg2',array('0'=>'Vender', '1' => 'Entrega de vehículo usado como parte de pago', '2' => 'Mantenerlo'), array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="row cont-img" style="display: none;">
                            <div class="row">
                                <div class="col-sm-5">
                                    <label for="">Ingreso de imágenes</label>
                                </div>
                            </div>
                            
                            <div class="col-sm-5">
                                <?php
                                $this->widget('CMultiFileUpload', array(
                                    'model' => $model,
                                    'attribute' => 'thumb',
                                    'accept' => 'jpg|gif|png',
                                    'name' => 'photos',
                                    'options' => array(
                                    // 'onFileSelect'=>'function(e, v, m){ alert("onFileSelect - "+v) }',
                                    // 'afterFileSelect'=>'function(e, v, m){ alert("afterFileSelect - "+v) }',
                                    // 'onFileAppend'=>'function(e, v, m){ alert("onFileAppend - "+v) }',
                                    // 'afterFileAppend'=>'function(e, v, m){ alert("afterFileAppend - "+v) }',
                                    // 'onFileRemove'=>'function(e, v, m){ alert("onFileRemove - "+v) }',
                                    // 'afterFileRemove'=>'function(e, v, m){ alert("afterFileRemove - "+v) }',
                                    ),
                                    'denied' => 'File is not allowed',
                                    'max' => 5, // max 10 files
                                ));
                                ?>
                                <?php echo $form->error($model, 'thumb'); ?>
                            </div>
                        </div>
                        <div class="row cont-link" style="display: none;">
                            <div class="col-sm-12">
                                <label for="">Link</label>
                            </div>
                            <div class="col-md-3">
                                <input size="10" maxlength="200" class="form-control" name="GestionConsulta[link]" id="GestionConsulta_link" type="text">
                            </div>
                        </div>
<!--                        <div class="row cont-img">
                            <div class="col-md-5">
                                <?php //echo $form->FileField($model, 'img', array('class' => 'form-control')); ?>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                            <?php echo $form->FileField($consulta, 'preg2_sec1', array('class' => 'form-control')); ?>
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                    </div>
                                </div>
                                <?php echo $form->error($model, 'img'); ?>
                            </div>
                        </div>-->
                    </div>
                    <div class="row">
                        <label for="">3. ¿Para qué utilizará el nuevo vehículo?</label>
                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $form->dropDownList($consulta,'preg3',array('0'=>'Primer Vehículo del hogar', '1' => 'Segundo Vehículo del hogar', '2' => 'Renovación de vehículo'), array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="row cont-utl">
                            <div class="col-md-3">
                                <?php echo $form->dropDownList($consulta,'preg3_sec1',array('0'=>'Familiar', '1' => 'Trabajo'), array('class' => 'form-control')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="">4. ¿Quién más participa en la decisión de compra?</label>
                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $form->dropDownList($consulta,'preg4',array('0'=>'Esposa/o', '1' => 'Familiar', '2' => 'Departamento de compras'), array('class' => 'form-control')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="">5. ¿Qué clase de presupuesto tiene previsto para su nuevo vehículo?</label>
                        <div class="row">
                                <div class="col-md-3">
                                    <?php echo $form->textField($consulta, 'preg5', array('size' => 10, 'maxlength' => 10, 'class' => 'form-control')); ?>
                                </div>
                        </div>
                                
                    </div>
                    <div class="row">
                        <label for="">6. ¿Cuál sería su forma de pago para su nuevo vehículo?</label>
                        <input type="hidden" name="GestionInformacion[id_cotizacion]" id="GestionInformacion_id_cotizacion" value="<?php echo $id; ?>">
                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $form->dropDownList($consulta,'preg6',array('0'=>'Contado', '1' => 'Financiado'), array('class' => 'form-control')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div><!-- End Seguimiento -->
            <br>
            <div class="highlight"><!-- Vehiculo recomendado -->
                <div class="row">
                    <h1 class="tl_seccion_rf">Vehículo Recomendado</h1>
                </div>
                <div class="form vehicle-cont">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <?php $vehiculo = new GestionVehiculo; ?>
                                <?php echo $form->labelEx($vehiculo, 'modelo'); ?>
                                <?php
                                echo $form->dropDownList($vehiculo, 'modelo', array(
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
                                <?php echo $form->error($vehiculo, 'modelo'); ?>
                            </div>
                            <div class="col-md-6">
                                <div id="info2" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                <?php echo $form->labelEx($vehiculo, 'version'); ?>
                                <?php echo $form->dropDownList($vehiculo, 'version', array('' => 'Escoja una versión'), array('class' => 'form-control')); ?>
                                <?php echo $form->error($vehiculo, 'version'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div id="info3" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                <?php echo $form->labelEx($vehiculo, 'precio'); ?>
                                <?php echo $form->textField($vehiculo, 'precio', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                <?php echo $form->error($vehiculo, 'precio'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 cont-accesorios" style="display: none;">
                                <label for="">Accesorios</label>
                                <div class="well well-sm">
                                    <ul class="list-accesorios">
                                        <li><u>Dispositivo GPS</u></li>
                                        <li><u>Aros</u></li>
                                        <li><u>Vidrios Eléctricos</u></li>
                                        <li><u>Radio Táctil</u></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row buttons">
                        <div class="col-md-8">
                            <input type="hidden" name="GestionInformacion2[calendar]" id="GestionInformacion_calendar2" value="0">
                            <input type="hidden" name="GestionInformacion2[check]" id="GestionInformacion_check2" value="1">
                            <input type="hidden" name="GestionInformacion[fuente]" id="GestionInformacion_fuente" value="<?php echo $fuente; ?>">
                            <input name="GestionInformacion[paso]" id="GestionInformacion_paso" type="hidden" value="3-4">
                            <input name="GestionInformacion[tipo]" id="GestionInformacion_tipo" type="hidden" value="<?php if(isset($_GET['tipo'])){ echo $_GET['tipo'];} ?>">
                            <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET['tipo']; ?>">
                            <?php
                            if ($_GET['tipo'] == 'prospeccion'):
                                echo '<input type="hidden" name="GestionInformacion[status]" id="GestionInformacion_status" value="prospeccion">';
                            else:
                                echo '<input type="hidden" name="GestionInformacion[status]" id="GestionInformacion_status" value="primera_visita">';
                            endif;
                            ?>
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Grabar', array('class' => 'btn btn-danger', 'id' => 'finalizar','onclick' => 'sendInfo();')); ?>
                            <input class="btn btn-primary" style="display: none;" onclick=";" type="submit" name="yt0"  id="continuar" value="Abandonar">
                        </div>
                        <div class="col-md-2">
                            <div id="calendar-content2" style="display: none;">
                                <a href="" class="btn btn-primary" id="event-download2">Descargar Evento</a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div id="calendar-content" style="display: none;">
                                <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div><!-- End Vehiculo recomendado -->
            <?php endif; ?>
            <?php $this->endWidget(); ?>
            </div><!-- form -->
        </div>
        <div role="tabpanel" class="tab-pane" id="profile"></div>
        <div role="tabpanel" class="tab-pane" id="settings"></div>
        <div role="tabpanel" class="tab-pane" id="messages"></div>
    </div>

</div>
