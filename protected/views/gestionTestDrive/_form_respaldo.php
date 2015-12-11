<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css"/>               
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css"/>        
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sketch.js"></script>
<!-- Add fancyBox -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sketch.js"></script>
<?php
/* @var $this GestionTestDriveController */
/* @var $model GestionTestDrive */
/* @var $form CActiveForm */
$test_drive = '';
if (isset($model->test_drive))
    $test_drive = $model->test_drive;
?>

<?php
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$android = FALSE;
if (stripos($ua, 'android') !== false) { // && stripos($ua,'mobile') !== false) {
    $android = TRUE;
}
$criteria = new CDbCriteria(array(
    'condition' => "id='{$id_informacion}'"
        ));
$info = GestionInformacion::model()->findAll($criteria);
//echo '<pre>';
//print_r($info);
//echo '</pre>';
$cri = new CDbCriteria(array(
    'condition' => "id_informacion={$id_informacion} AND tipo = 1"
));
$firma = GestionFirma::model()->count($cri);
?>
<script type="text/javascript">
    var ident = '<?php echo $test_drive; ?>';
    console.log('IDENTIFICACION:-----------' + ident);
    $(function () {
        
        $('.fancybox').fancybox();
        <?php if($firma == 0): ?>
        $.each(['#f00', '#ff0', '#0f0', '#0ff', '#00f', '#f0f', '#000', '#fff'], function () {
            $('#colors_demo .tools').append("<a href='#colors_sketch' data-color='" + this + "' style='width: 10px; background: " + this + ";'></a> ");
        });
        $.each([3, 5, 10, 15], function () {
            $('#colors_demo .tools').append("<a href='#colors_sketch' data-size='" + this + "' style='background: #ccc'>" + this + "</a> ");
        });
        $('#colors_sketch').sketch();
        var sktch =  $('#colors_sketch').sketch();
        var cleanCanvas = $('#colors_sketch')[0];

        //Get the canvas &
        var c = $('#colors_sketch');
        var ct = c.get(0).getContext('2d');
        var container = $(c).parent();
        <?php endif; ?>
        //Run function when browser resizes
        //$(window).resize(respondCanvas);

        /*function respondCanvas() {
            c.attr('width', $(container).width()); //max width
            c.attr('height', $(container).height()); //max height

            //Call a function to redraw other content (texts, images etc)
        }

        //Initial call 
        respondCanvas();*/
        $('.reset-canvas').click(function(){
             var cnv = $('#colors_sketch').get(0);
            var ctx = cnv.getContext('2d');
            clearCanvas(cnv, ctx); // note, this erases the canvas but not the drawing history!
            $('#colors_sketch').sketch().actions = []; // found it... probably not a very nice solution though

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
        $('#agendamiento').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                $(this).find('.xdsoft_date.xdsoft_weekend')
                        .addClass('xdsoft_disabled');
            },
            weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
        });
        $('#GestionTestDrive_preg1_agendamiento').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                $(this).find('.xdsoft_date.xdsoft_weekend')
                        .addClass('xdsoft_disabled');
            },
            weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
        });
        $('#GestionTestDrive_test_drive').change(function () {
            var value = $(this).attr('value');
            if (value == 1) {
                $('.cont-img').show();
                $('.cont-obs').hide();
                $('.cont-bot-form-manejo').show();
            } else if (value == 2) {
                $('.cont-obs').show();
            } else {
                $('.cont-obs').show();
                $('.cont-img').hide();
                $('.cont-bot-form-manejo').hide();
                $('.cont-form-manejo').hide();
            }
        });
        $('#GestionTestDrive_preg1').change(function () {
            var value = $(this).attr('value');
            if (value == 'Si') {
                $('.cont-test-drive').show();
                $('.cont-form-manejo').show();
                $('.cont-obs-seg').hide();$('#cont-seg').hide();
            } else {
                $('.cont-form-manejo').hide();
                $('.cont-test-drive').hide();
                $('.cont-obs-seg').show();$('#cont-seg').show();
            }
        });
        $('#GestionTestDrive_obs').change(function () {
            var value = $(this).attr('value');
            if (value == 1) {
                $('.cont-agendamiento').show();
            } else {
                $('.cont-agendamiento').hide();
            }
        });
        $('.btn-ing-form').click(function () {
            $('.cont-form-manejo').show();
        });
        $('.btn-des-form').click(function () {
            $('.cont-form-manejo').hide();
        });
        $('#gestion-test-drive-form').validate({
            submitHandler: function (form) {
                console.log('enter submit');
                var preg1 = $('#GestionTestDrive_preg1').val();
                if(preg1 == 'Si'){ // debe seleccionar licencia y firma
                    var control = 0;
                    var d = document.getElementById('GestionTestDrive_img');
                    //var e = document.getElementById('GestionTestDrive_firma');
                    if (d.value == '') {
                        control ++;
                    }
                    if(control > 0){
                        alert('Debe seleccionar imágen en licencia');
                        return false;
                    }else{
                        form.submit();
                    }
                }else{ //  cliente no desea hacer el test drive
                    form.submit();
                }
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
    function clearCanvas(canvas, context) {            
        context.clearRect(0, 0, canvas.width, canvas.height);
    }

    function UploadPic() {

        // generate the image data
        var data = document.getElementById("colors_sketch").toDataURL("image/png");
        var output=data.replace(/^data:image\/(png|jpg);base64,/, "");
             // Sending the image data to Server
        if(confirm("Antes de continuar, esta seguro que ha realizado su firma correctamente?")){
            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::app()->createUrl("/site/grabarFirma")?>',
                data: { imageData: output,id_informacion :"<?php echo $id_informacion;?>",tipo:1 },
                
                 success: function (msg) {
                    if(msg==1){
                        alert('Firma cargada exitosamente.');
                        //var ur = 'https://www.kia.com.ec/intranet/ventas/index.php/gestionTestDrive/create/'+<?php echo $_GET['id'] ?>+'?id_informacion='+<?php echo $id_informacion; ?>;
                        //$(location).attr('href', ur)
                        $('#cont-firma').hide();
                        $.ajax({
                            type: 'POST',
                            dataType: "json",
                            url: '<?php echo Yii::app()->createUrl("/site/getFirma")?>',
                            data: { id_informacion :"<?php echo $id_informacion;?>" },
                            success: function (data) {
                                //console.log('firma digital: '+data.firma);
                                $('#img-firma').attr('src','/intranet/ventas/upload/firma/'+data.firma);
                                $('#cont-firma').hide();
                                $('#cont-firma-img').show();$('#cont-btn').show();
                            }
                        });
                        
                        $.fancybox.close();
                    } 
                }
            });
            
        }
    }
    
    function send() {
        $('#gestion-diaria-form').validate({
            submitHandler: function (form) {
                var proximoSeguimiento = $('#agendamiento').val();
                //console.log('val of agendamiento: '+proximoSeguimiento);
                var dataform = $("#gestion-diaria-form").serialize();
                if (proximoSeguimiento != '') {
                    if ($('#GestionInformacion_check').val() != 2) {
                        console.log('enter gestion informacion check');
                        var params = proximoSeguimiento.split("/");
                        var fechaDate = params[0] + params[1] + params[2];
                        var params2 = fechaDate.split(":");
                        var endTime = parseInt(params2[1]) + 100;
                        endTime = endTime.toString();
                        var startTime = params2[0] + params2[1];
                        var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date=' + fechaDate + '&startTime=' + startTime + '&endTime=' + endTime + '&subject=Cita con Cliente&desc=Cita con el cliente';
                        $('#event-download').attr('href', href);
                        $('#calendar-content').show();
                        $("#event-download").click(function () {
                            $('#GestionInformacion_calendar').val(1);
                            $('#calendar-content').hide();
                            $('#GestionInformacion_check').val(2)
                        });
                        if ($('#GestionInformacion_calendar').val() == 1) {
                            console.log('enter infomacion calendar');
                        } else {
                            alert('Debes descargar agendamiento y luego dar click en Continuar');
                        }
                    } else {
                        $.ajax({
                            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionDiaria/createAjax"); ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            type: 'POST',
                            data: dataform,
                            success: function (data) {
                                $('#bg_negro').hide();
                                $('#cont-alert').show();
                                $('#gestion-diaria-form').get(0).reset();
                            }
                        });
                    }
                }
            }
        });
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
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
            <li role="presentation" class="active"><a href="<?php echo Yii::app()->createUrl('site/demostracion/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion_on.png" alt="" /></span> Demostración</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/cierre/' . $id_informacion); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panels -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="row">
                    <h1 class="tl_seccion_rf">Test Drive: Modelo - <?php echo $this->getModeloTestDrive($id); ?>, Versión - <?php echo $this->getVersionTestDrive($id); ?></h1>
                    <div class="highlight">
                        <div class="form">

                            <?php
                            $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'gestion-test-drive-form',
                                'enableAjaxValidation' => false,
                                'htmlOptions' => array('enctype' => 'multipart/form-data','onsubmit' => "return false;", /* Disable normal form submit */),
                            ));
                            ?>
<!--                            <p class="note">Campos con <span class="required">*</span> son requeridos.</p>-->
                            <?php echo $form->errorSummary($model); ?>

                            <label for="">1. ¿Desea realizar una prueba de manejo?</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <select name="GestionTestDrive[preg1]" id="GestionTestDrive_preg1" class="form-control">
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <input name="GestionTestDrive[id_informacion]" id="GestionTestDrive_id_informacion" type="hidden" value="<?php echo $id_informacion; ?>">
                                <input name="GestionTestDrive[id_vehiculo]" id="GestionTestDrive_id_vehiculo" type="hidden" value="<?php echo $id; ?>">
                            </div>

                            <?php if ($test_drive == 1 && $test_drive != NULL): ?>
                                <label for="">i. Solicitar Licencia</label>
                                <div class="row cont-img">
                                    <div class="col-md-4">
                                        <?php //echo $form->FileField($model, 'img', array('class' => 'form-control'));  ?>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                            <div>
                                                <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                    <?php echo $form->FileField($model, 'img', array('class' => 'form-control')); ?>
                                                </span>
                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                            </div>
                                        </div>
                                        <?php echo $form->error($model, 'img'); ?>
                                    </div>
                                </div>
                                <div class="row cont-obs" style="display: none;">
                                    <div class="col-md-4">
                                        <?php echo $form->labelEx($model, 'observacion'); ?>
                                        <?php echo $form->textArea($model, 'observacion', array('rows' => 6, 'cols' => 50)); ?>
                                        <?php echo $form->error($model, 'observacion'); ?>
                                    </div>
                                </div>
                            <?php elseif ($test_drive == 0 && $test_drive != NULL): ?>
                                <label for="">i. Solicitar Licencia</label>
                                <div class="row cont-img" style="display: none;">
                                    <div class="col-md-4">
                                        <?php //echo $form->FileField($model, 'img', array('class' => 'form-control'));  ?>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                            <div>
                                                <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                    <?php echo $form->FileField($model, 'img', array('class' => 'form-control')); ?>
                                                </span>
                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                            </div>
                                        </div>
                                        <?php echo $form->error($model, 'img'); ?>
                                    </div>
                                </div>
                                <div class="row cont-obs" style="display: none;">
                                    <div class="col-md-4">
                                        <?php echo $form->labelEx($model, 'observacion'); ?>
                                        <?php echo $form->textArea($model, 'observacion', array('rows' => 6, 'cols' => 50)); ?>
                                        <?php echo $form->error($model, 'observacion'); ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="cont-test-drive">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">i. Solicitar Licencia</label>
                                        </div>
                                    </div>
                                    <div class="row cont-img">
                                        <div class="col-md-4">
                                            <?php //echo $form->FileField($model, 'img', array('class' => 'form-control'));  ?>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 100px;"></div>
                                                <div>
                                                    <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                        <?php echo $form->FileField($model, 'img', array('class' => 'form-control')); ?>
                                                    </span>
                                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                                </div>
                                            </div>
                                            <?php echo $form->error($model, 'img'); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="">ii. Texto Legal </label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <p style="font-size: 12px;">Por medio del presente acepto relizar una prueba de manejo en este establecimiento, declaro que dejo la copia
                                                    de la licencia de conducción vigente. Me comprometo a conducir el vehículo de prueba de manera responsable y cumpliendo 
                                                    con las normas de tránsito respectivas.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label for="">iii. Firma de documento de respaldo de prueba de manejo </label>
                                        </div>
                                    </div>

<!--                                    <div class="row cont-bot-form-manejo">
                                        <div class="col-md-3"><button type="button" class="btn btn-primary btn-ing-form">Ingresar Formulario</button></div>
                                        <div class="col-md-3"><a href="<?php echo Yii::app()->createUrl('site/pdf', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id)); ?>" class="btn btn-primary btn-des-form" target="_blank">Descargar Formulario</a></div>
                                    </div>-->
                                    
                                </div>
                                <div class="row">
                                    <div class="cont-form-manejo well well-sm col-md-8">
                                        <?php foreach ($info as $key => $value): ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3>Formulario de Prueba de Manejo</h3>
                                                    <p>Kia Motors Ecuador</p>
                                                </div> 
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="">Nombres y Apellidos</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[nombre]" id="GestionTestDrive_nombres" value="<?php echo ucfirst($value['nombres']) . ' ' . ucfirst($value['apellidos']); ?>" disabled="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="">C.I.</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[cedula]" id="GestionTestDrive_cedula" value="<?php echo $value['cedula']; ?>" disabled="">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Fecha</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[fecha]" id="GestionTestDrive_fecha" value="<?php echo date("d") . "/" . date("m") . "/" . date("Y"); ?>" disabled="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="">Dirección</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[direccion]" id="GestionTestDrive_cedula" value="<?php echo $value['direccion']; ?>" disabled="">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Teléfono Convencional</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[telefono_convencional]" id="GestionTestDrive_telefono_convencional" value="<?php echo $value['telefono_casa']; ?>" disabled="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="">Teléfono Celular</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[celular]" id="GestionTestDrive_celular" value="<?php echo $value['celular']; ?>" disabled="">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Email</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[email]" id="GestionTestDrive_email" value="<?php echo $value['email']; ?>" disabled="">
                                                </div>
                                            </div>
                                        <?php $havevec = $this->getIfVehicle($id_informacion);
                                        if($havevec != ''):
                                        ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Modelo de vehículo que posee actualmente</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[modelo_actual]" id="GestionTestDrive_modelo_actual" value="<?php echo $this->getMarcaPosee($value['id']); ?>" disabled="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="">Marca</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[marca]" id="GestionTestDrive_marca" value="<?php echo $this->getMarcaPosee($value['id']); ?>" disabled="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">Modelo</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[modelo]" id="GestionTestDrive_modelo" value="<?php echo $this->getModeloPosee($value['id']); ?>" disabled="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">Año</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[year]" id="GestionTestDrive_year" value="<?php echo $this->getModeloYear($value['id']); ?>" disabled="">
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Modelo de vehículo Kia que realiza la prueba de manejo</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[modelo_kia]" id="GestionTestDrive_modelo_kia" value="<?php echo $this->getModeloTestDrive($id); ?> - <?php echo $this->getVersionTestDrive($id); ?>" disabled="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Nombre Asesor</label>
                                                    <input type="text" class="form-control" name="GestionTestDrive[nombre_asesor]" id="GestionTestDrive_nombre_asesor" value="<?php echo $this->getResponsable($value['responsable']); ?>" disabled="">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Observaciones</label>
                                                <select name="GestionTestDrive[observaciones_form]" id="GestionTestDrive_observaciones_form" class="form-control">
                                                    <option value="Primera prueba de manejo">Primera prueba de manejo</option>
                                                    <option value="prueba de manejo familiar amistad">Prueba de manejo familiar/amistad</option>
                                                </select>
                                                <!--<textarea name="GestionTestDrive[observaciones_form]" id="GestionTestDrive_observaciones_form" cols="30" rows="10"></textarea>-->
                                            </div>
                                        </div>
                                        
                                        <div class="row"></div>
                                        <?php 
                                        
                                        if($firma > 0):
                                            $fr = GestionFirma::model()->find($cri);
                                            $imgfr = $fr->firma;
                                        ?>
                                        
                                        <div class="row">
                                            <div class="col-md-5">
                                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/upload/firma/<?php echo $imgfr; ?>" alt="" width="200" height="100">
                                                <hr>
                                                Firma Cliente
                                            </div>
                                            <div class="col-md-2">

                                            </div>
                                            <div class="col-md-4">
                                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/firma3.png" alt="" width="200" height="100"/>
                                                <hr>
                                                Firma Asesor
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <div id="inline1" style="width:800px;display: none;height: 400px;">
                                            <div class="row">
                                                <h1 class="tl_seccion_rf">Ingreso de firma</h1>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <canvas id="colors_sketch" width="800" height="300"></canvas>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="tools">
                                                        <!--<a href="#colors_sketch" data-download="png" class="btn btn-success">Descargar firma</a>-->
                                                        <input type="button"  data-clear='true' class="reset-canvas btn btn-warning" value="Borrar Firma">
                                                        <input type="button"  onclick="UploadPic()" class=" btn btn-info" value="Subir Firma">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4" id="cont-firma">
                                                <!--<a href="<?php echo Yii::app()->createUrl('site/signature/' ,array('id'=>$id,'id_informacion'=>$id_informacion)); ?>" class="btn btn-xs btn-primary">Ingresar Firma</a>-->
                                                <a href="#inline1" class="fancybox btn btn-xs btn-primary">Ingresar Firma</a> 
                                            </div>
                                            <div class="col-md-5" id="cont-firma-img" style="display: none;">
                                                <img src="" alt="" width="200" height="100" id="img-firma">
                                                <hr>
                                                Firma Cliente
                                            </div>
                                            <div class="col-md-2">

                                            </div>
                                            <div class="col-md-4">
                                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/firma3.png" alt="" width="200" height="100"/>
                                                <hr>
                                                Firma Asesor
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Nota: El vehículo se encuentra asegurado</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cont-obs-seg" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">Observaciones</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select name="GestionTestDrive[preg1_observaciones]" id="GestionTestDrive_preg1_observaciones" class="form-control">
                                                <option value="4">No, pero realizará en el futuro</option>
                                                <option value="0">No tiene licencia</option>
                                                <option value="1">No tiene tiempo</option>
                                                <option value="2">No desea</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="cont-agendamiento" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">Agendamiento</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" name="GestionTestDrive[preg1_agendamiento]" id="GestionTestDrive_preg1_agendamiento" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row cont-obs-2" style="display: none;">
                                    <div class="col-md-8">
                                        <?php echo $form->labelEx($model, 'observacion'); ?>
                                        <?php echo $form->textArea($model, 'observacion', array('rows' => 6, 'cols' => 50)); ?>
                                        <?php echo $form->error($model, 'observacion'); ?>
                                    </div>
                                </div>
                                <div class="row buttons" id="cont-btn" style="display: none;">
                                    <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                                    <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                                    <div class="col-md-2">
                                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Save', array('class' => 'btn btn-danger', 'id' => 'finalizar')); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="calendar-content" style="display: none;">
                                            <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                                        </div>
                                    </div>
                                </div>
                                
                            <?php endif; ?>
                            <?php if($firma > 0): ?>    
                            <div class="row buttons">
                                <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                                <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                                <div class="col-md-2">
                                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Save', array('class' => 'btn btn-danger', 'id' => 'finalizar')); ?>
                                </div>
                                <div class="col-md-3">
                                    <div id="calendar-content" style="display: none;">
                                        <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>    
                            <?php $this->endWidget(); ?>

                        </div><!-- form -->

                    </div>
                    
        
                </div>
                <br />
                <div class="row">
                    <div class="highlight" id="cont-seg" style="display: none;">
                    <div class="row">
                        <h1 class="tl_seccion_green">Seguimiento</h1>
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
                            <div class="col-md-4">
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
                                <?php echo $form->dropDownList($agendamiento,'observaciones',array('No, pero realizará en el futuro'=>'Falta de tiempo','Falta de tiempo'=>'Falta de tiempo', 'Llamada de emergencia'=>'Llamada de emergencia', 'Busca solo precio'=>'Busca solo precio','Desiste' => 'Desiste','Otro' => 'Otro'), array('class' => 'form-control')); ?>
                                <?php echo $form->error($agendamiento, 'observaciones'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $form->labelEx($agendamiento, 'agendamiento'); ?>
                                <?php echo $form->textField($agendamiento, 'agendamiento', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
                                <?php echo $form->error($agendamiento, 'agendamiento'); ?>
                            </div>
                        </div>
                        <div class="row" id="cont-otro" style="display: none;">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <label for="">Otra observación</label>
                                <input type="text" class="form-control" name="GestionAgendamiento[otro]" id="GestionAgendamiento_otro"/>
                            </div>
                        </div>

                        <div class="row buttons">
                            <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                            <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                            <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="7">
                            <input type="hidden" name="GestionAgendamiento[id_informacion]" id="GestionAgendamiento_id_informacion" value="<?php echo $id; ?>">
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
                    </div>
                    <div class="row">
                        
                        <?php $crit5 = new CDbCriteria(array('condition' => "id_informacion={$id} AND paso = 7"));
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
                </div
            </div>
                </div>
            <br>
                
            </div>
            
            <?php
            $obs = $this->getTestObs($id_informacion, $id);
            if ($obs > 0):
                ?>
                <div class="row">
                    <div class="col-md-6"><h3>Historial Observaciones</h3></div>
                </div>
                <?php
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id} AND test_drive = 0",
                    'order' => 'id DESC'
                ));
                $historial = GestionTestDrive::model()->findAll($criteria);
                ?>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Observación</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($historial as $c):
                                $fecha = '';
                                $nuevafecha = '';
                                $fecha = date($c['fecha']);
                                $nuevafecha = strtotime('-5 hour', strtotime($fecha));
                                $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);
                                ?>
                                <tr>
                                    <td><?php echo $c['fecha']; ?> </td>
                                    <td><?php echo $c['observacion']; ?> </td>

                                </tr>
                                <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <?php
            $obs = $this->getTestDriveRep($id_informacion, $id);
            if ($obs > 0):
                ?>
                <div class="row">
                    <div class="col-md-6"><h3>Historial Test Drive</h3></div>
                </div>
                <?php
                $criteria = new CDbCriteria(array(
                    'condition' => "(id_informacion={$id_informacion} AND id_vehiculo = {$id}) AND (test_drive = 1 OR test_drive = 2)",
                    'order' => 'id DESC'
                ));
                $historial = GestionTestDrive::model()->findAll($criteria);
                ?>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Observación</th>
                                <th>Foto</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($historial as $c):
                                $fecha = '';
                                $nuevafecha = '';
                                $fecha = date($c['fecha']);
                                $nuevafecha = strtotime('-5 hour', strtotime($fecha));
                                $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);
                                ?>
                                <tr>
                                    <td><?php echo $c['fecha']; ?> </td>
                                    <td><?php echo $c['observacion']; ?> </td>
                                    <td>
                                        <?php if (!empty($c['img'])): ?>
                                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $c['img']; ?>" width="80" height="80" /> 
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
</div>
