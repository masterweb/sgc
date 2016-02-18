<?php $this->widget('application.components.Notificaciones'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css"/>               
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css"/>        
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/jasny-bootstrap.css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jasny-bootstrap.js"></script>

<!-- Add fancyBox -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sketch.js"></script>
<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$hoja = $this->getHojaStatus($id_informacion, $id_vehiculo);
//echo 'HOJA DE ENTREGA: ----'.$hoja;
$cri = new CDbCriteria(array(
    'condition' => "id_informacion={$id_informacion} AND tipo = 2"
        ));
$firma = GestionFirma::model()->count($cri);
$tipo = $this->getFinanciamiento($id_informacion);
$nombre_cliente = $this->getNombresInfo($id_informacion);
$crit = new CDbCriteria(array(
    'condition' => "id_informacion={$id_informacion}"
        ));
//    die('id informacion: '.$id_informacion);
$consulta = GestionConsulta::model()->find($crit);
$credito = $consulta->preg6;
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<style type="text/css">
    .accs h4{color: #AB1212; font-weight: bold;}
</style>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#closemodal').click(function () {
            var url      = window.location.href; 
            $(location).attr('href',url);
        });
        $('.fancybox').fancybox();
<?php if (($firma == 0)): ?>
            $.each(['#f00', '#ff0', '#0f0', '#0ff', '#00f', '#f0f', '#000', '#fff'], function () {
                $('#colors_demo .tools').append("<a href='#colors_sketch' data-color='" + this + "' style='width: 10px; background: " + this + ";'></a> ");
            });
            $.each([3, 5, 10, 15], function () {
                $('#colors_demo .tools').append("<a href='#colors_sketch' data-size='" + this + "' style='background: #ccc'>" + this + "</a> ");
            });
            $('#colors_sketch').sketch();
            var sktch = $('#colors_sketch').sketch();
            var cleanCanvas = $('#colors_sketch')[0];

            //Get the canvas &
            var c = $('#colors_sketch');
            var ct = c.get(0).getContext('2d');
            var container = $(c).parent();
<?php endif; ?>
        $('.reset-canvas').click(function () {
            var cnv = $('#colors_sketch').get(0);
            var ctx = cnv.getContext('2d');
            clearCanvas(cnv, ctx); // note, this erases the canvas but not the drawing history!
            $('#colors_sketch').sketch().actions = []; // found it... probably not a very nice solution though

        });
        $('#agendamiento1').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                $(this).find('.xdsoft_date.xdsoft_weekend')
                        .addClass('xdsoft_disabled');
            },
            //format:'d/m/Y H:i',
            minTime: '08:00', maxTime: '20:00',
            weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            allowTimes: [
                '08:00', '08:15', '08:30', '08:45', '09:00', '09:15', '09:30', '09:45', '10:00', '10:15', '10:30', '10:45', '11:00', '11:15', '11:30', '11:45',
                '12:00', '12:15', '12:30', '12:45', '13:00', '13:15', '13:30', '13:45', '14:00', '14:15', '14:30', '14:45', '15:00', '15:15', '15:30', '15:45', '16:00', '16:15', '16:30', '16:45',
                '17:00', '17:15', '17:30', '17:45', '18:00', '18:15', '18:30', '18:45', '19:00'
            ],
            minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
        });
        $('#agendamiento2').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                $(this).find('.xdsoft_date.xdsoft_weekend')
                        .addClass('xdsoft_disabled');
            },
            allowTimes: [
                '08:00', '08:15', '08:30', '08:45', '09:00', '09:15', '09:30', '09:45', '10:00', '10:15', '10:30', '10:45', '11:00', '11:15', '11:30', '11:45',
                '12:00', '12:15', '12:30', '12:45', '13:00', '13:15', '13:30', '13:45', '14:00', '14:15', '14:30', '14:45', '15:00', '15:15', '15:30', '15:45', '16:00', '16:15', '16:30', '16:45',
                '17:00', '17:15', '17:30', '17:45', '18:00', '18:15', '18:30', '18:45', '19:00'
            ], minTime: '08:00', maxTime: '20:00',
            weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
        });
        $("input[name='GestionMatricula[factura_ingreso]']").click(function () {
            if ($(this).prop('checked')) {
                $("input[name='GestionMatricula[envio_factura]']").removeAttr('disabled');
                $("input[name='GestionMatricula[envio_factura]']").addClass('dscchk');
            } else {
                $("input[name='GestionMatricula[envio_factura]']").attr('disabled', 'true');
                $("input[name='GestionMatricula[envio_factura]']").removeAttr('checked');
            }
        });
        $("input[name='GestionMatricula[envio_factura]']").click(function () {
            if ($(this).prop('checked')) {
                $("input[name='GestionMatricula[pago_consejo]']").removeAttr('disabled');
            } else {
                $("input[name='GestionMatricula[pago_consejo]']").attr('disabled', 'true');
                $("input[name='GestionMatricula[pago_consejo]']").removeAttr('checked');
            }
        });
<?php if ($credito == 1) { ?>
            $("input[name='GestionMatricula[pago_consejo]']").click(function () {
                if ($(this).prop('checked')) {
                    $("input[name='GestionMatricula[venta_credito]']").removeAttr('disabled');
                } else {
                    $("input[name='GestionMatricula[venta_credito]']").attr('disabled', 'true');
                    $("input[name='GestionMatricula[venta_credito]']").removeAttr('checked');
                }
            });
<?php } ?>
<?php if ($credito == 0) { ?>
            $("input[name='GestionMatricula[pago_consejo]']").click(function () {
                if ($(this).prop('checked')) {
                    $("input[name='GestionMatricula[entrega_documentos_gestor]']").removeAttr('disabled');
                } else {
                    $("input[name='GestionMatricula[entrega_documentos_gestor]']").attr('disabled', 'true');
                    $("input[name='GestionMatricula[entrega_documentos_gestor]']").removeAttr('checked');
                }
            });
<?php } ?>
        $("input[name='GestionMatricula[venta_credito]']").click(function () {
            if ($(this).prop('checked')) {
                $("input[name='GestionMatricula[entrega_documentos_gestor]']").removeAttr('disabled');
            } else {
                $("input[name='GestionMatricula[entrega_documentos_gestor]']").attr('disabled', 'true');
                $("input[name='GestionMatricula[entrega_documentos_gestor]']").removeAttr('checked');
            }
        });
        $("input[name='GestionMatricula[entrega_documentos_gestor]']").click(function () {
            if ($(this).prop('checked')) {
                $("input[name='GestionMatricula[ente_regulador_placa]']").removeAttr('disabled');
            } else {
                $("input[name='GestionMatricula[ente_regulador_placa]']").attr('disabled', 'true');
                $("input[name='GestionMatricula[ente_regulador_placa]']").removeAttr('checked');
            }
        });
        $("input[name='GestionMatricula[ente_regulador_placa]']").click(function () {
            if ($(this).prop('checked')) {
                $("input[name='GestionMatricula[info_satelital]']").removeAttr('disabled');
            } else {
                $("input[name='GestionMatricula[info_satelital]']").attr('disabled', 'true');
                $("input[name='GestionMatricula[info_satelital]']").removeAttr('checked');
            }
        });
    });
    function clearCanvas(canvas, context) {
        context.clearRect(0, 0, canvas.width, canvas.height);
    }
    function UploadPic() {

        // generate the image data
        var data = document.getElementById("colors_sketch").toDataURL("image/png");
        var output = data.replace(/^data:image\/(png|jpg);base64,/, "");
        // Sending the image data to Server
        if (confirm("Antes de continuar, esta seguro que ha realizado su firma correctamente?")) {
            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::app()->createUrl("/site/grabarFirma") ?>',
                data: {imageData: output, id_informacion: "<?php echo $id_informacion; ?>", tipo: 2},
                success: function (msg) {
                    if (msg == 1) {
                        alert('Firma cargada exitosamente.');

                        $('#cont-firma').hide();
                        $.ajax({
                            type: 'POST',
                            dataType: "json",
                            url: '<?php echo Yii::app()->createUrl("/site/getFirma") ?>',
                            data: {id_informacion: "<?php echo $id_informacion; ?>"},
                            success: function (data) {
                                //console.log('firma digital: '+data.firma);
                                $('#img-firma').attr('src', '/intranet/usuario/upload/firma/' + data.firma);
                                $('#cont-firma').hide();
                                $('#cont-firma-img').show();
                                $('#cont-btn').show();
                            }
                        });

                        $.fancybox.close();
                    }
                }
            });

        }
    }
    function send(){
        //console.log('enter send');
        $('#gestion-entrega-form').validate({
            submitHandler: function (form) {
                console.log('enter submit hanlder 1');
                var control = 0;
                //var d = document.getElementById('GestionEntrega_firma');
                var e = document.getElementById('GestionEntrega_foto_cliente');
                if (e.value == '') {
                    control++;
                }
                //var dataform = $("#gestion-entrega-form").serialize();
                if (control > 0) {
                    alert('Debe seleccionar imágen en foto del cliente.');
                    return false;
                }
                //console.log('dataform: ');
                var formData = new FormData(document.getElementById("gestion-entrega-form"));
                /*$.ajax({
                 url: '<?php echo Yii::app()->createAbsoluteUrl("site/entregaAjax"); ?>',
                 beforeSend: function (xhr) {
                 //$('#bg_negro').show();  // #bg_negro must be defined somewhere
                 },
                 dataType: "json",
                 type: 'POST',
                 data: formData,
                 success: function (data) {
                 $('#bg_negro').hide();
                 $('#finalizar').hide();
                 $('#generatepdf').show();
                 $('#btn-continuar').show();
                 //$('#GestionFinanciamiento_ipdfid').val(returnedData.id);
                 }
                 });*/
                $.ajax({
                    url: "<?php echo Yii::app()->createAbsoluteUrl("site/entregaAjax"); ?>",
                    beforeSend: function (xhr) {
                        $('#bg_negro').show(); // #bg_negro must be defined somewhere
                    },
                    dataType: "json",
                    type: "post",
                    data: formData,
                    cache: false,
                    success: function (data) {
                        $('#bg_negro').hide();
                        $('#finalizar').hide();
                        $('#generatepdf').show();
                        $('#continue').show();
                        //$('.cont-carta').show();
                        //$('#GestionFinanciamiento_ipdfid').val(returnedData.id);
                    },
                    contentType: false,
                    processData: false
                });
            }
        });
    }

    function sendBienvenida() {
        var id_vehiculo = $('#GestionMatricula_id_vehiculo').val();
        var id_informacion = $('#GestionMatricula_id_informacion').val();
        //console.log('id informacion: '+id_informacion);
        $.ajax({
            url: "<?php echo Yii::app()->createAbsoluteUrl("site/bienvenida"); ?>",
            beforeSend: function (xhr) {
                $('#bg_negro').show(); // #bg_negro must be defined somewhere
            },
            dataType: "json",
            type: "post",
            data: {id_vehiculo: id_vehiculo, id_informacion: id_informacion},
            cache: false,
            success: function (data) {
                $('#bg_negro').hide();
                alert('Email enviado al cliente');
                $('#btn-continuar').show();
                //$('#GestionFinanciamiento_ipdfid').val(returnedData.id);
            }
        });
    }
    function check()
    {
        var d = document.getElementById('GestionEntrega_firma');
        if (d.value == '') {
            return true;
        }
    }
    function sendmail() {

        $('#gestion-matriculacion').validate({
            rules: {
                'GestionMatricula[agendamiento1]': {
                    required: true
                }
            },
            messages: {
                'GestionMatricula[agendamiento1]': {
                    required: 'Seleccione una fecha de agendamiento'
                }
            },
            submitHandler: function (form) {
                console.log('enter hanlder');
                var dataform = $("#gestion-matriculacion").serialize();
                var proximoSeguimiento = $('#agendamiento1').val();
                if (proximoSeguimiento != '') {
                    console.log('proximo: ' + proximoSeguimiento);
                    if ($('#GestionInformacion_check').val() != 2) {
                        var cliente = '<?php echo $nombre_cliente; ?>';
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
                        var href = '/intranet/usuario/index.php/gestionDiaria/ical?startTime=' + fechaStart + startTime + '&endTime=' + fechaStart + endTime + '&subject=Agendamiento Cita con Cliente' + cliente + '&desc=Cita con el cliente prospección&location=Por definir&to_name=' + cliente + '&conc=no';
                        //var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente prospección';
                        $('#event-download').attr('href', href);
                        $('#calendar-content').show();
                        $("#event-download").click(function () {
                            $('#GestionInformacion_calendar').val(1);
                            $('#calendar-content').hide();
                            $('#GestionInformacion_check').val(2);
                        });
                        if ($('#GestionInformacion_calendar').val() == 1) {
                            form.submit();
                        } else {
                            alert('Debes descargar agendamiento y luego dar click en Continuar');
                        }
                    } else {
                        var id_informacion = $('#GestionMatricula_id_informacion').val();
                        $.ajax({
                            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionMatricula/createAjax"); ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show(); // #bg_negro must be defined somewhere
                            },
                            type: 'POST',
                            dataType: 'json',
                            data: dataform,
                            success: function (data) {
                                $('#bg_negro').hide();
                                //$('.cont-mat').hide();
                                $('#myModal').modal('show');
                                //alert(data.result);
                                //alert('Datos grabados');
                            }
                        });
                    }
                }

            }
        });
    }
</script>
<div class="container">
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita.png" alt="" /></span> Cita</a></li>
            <?php
            $criteria = new CDbCriteria(array(
                'condition' => "id={$id_informacion}"
            ));
            $info = GestionInformacion::model()->count($criteria);
            ?>
            <?php if ($info > 0): ?>
                <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/update/', array('id' => $id_informacion, 'tipo' => 'gestion')); ?>"  aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <?php else: ?>
                <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <?php endif; ?>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionVehiculo/create/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id_informacion); ?>" saria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/demostracion/' . $id_informacion); ?>" saria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/cierre/' . $id_informacion); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
            <li role="presentation" class="active"><a href="<?php echo Yii::app()->createUrl('site/entrega/', array('id_vehiculo' => $id_vehiculo, 'id_informacion' => $id_informacion)); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega_on.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="row">
                    <h1 class="tl_seccion_rf">Entrega del Vehículo: Modelo - <?php echo $this->getModeloTestDrive($id_vehiculo); ?>, Versión - <?php echo $this->getVersionTestDrive($id_vehiculo); ?></h1>
                </div>
                <div class="row">
                    <h1 class="tl_seccion_rf">Datos del Cliente y Vehículo</h1>
                </div>


                <p class="note">Campos con <span class="required">*</span> son obligatorios.</p>
                <?php
                $criteria = new CDbCriteria(array('condition' => "id = {$id_informacion}"));
                $cl = GestionInformacion::model()->findAll($criteria);
                ?>
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-striped">
                            <tbody>
                                <tr class="odd"><th>Modelo</th><td><?php echo $this->getModeloTestDrive($id_vehiculo); ?></td><th>Cliente</th><td><?php echo ucfirst($cl[0]['nombres']); ?> <?php echo ucfirst($cl[0]['apellidos']); ?></td></tr>
                                <tr class="odd"><th>Versión</th><td><?php echo $this->getVersionTestDrive($id_vehiculo); ?></td><th>Email</th><td><?php echo $cl[0]['email']; ?></td></tr>
                                <tr class="odd"><th>Motor</th><td></td><th>Celular</th><td><?php echo $cl[0]['celular']; ?></td></tr>
                                <tr class="odd"><th>Factura</th><td></td><th>No. Chasis</th><td></td></tr>
                                <tr class="odd"><th>Color</th><td></td><th></th><td></td></tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="highlight">


                    <div class="form">
                        <form action="/intranet/usuario/index.php/site/matticula" method="post" id="gestion-matriculacion" onsubmit="return false;" onkeypress="if (event.keyCode == 13) {
                                    sendmail();
                                }" >
                            <!--                            <div class="row">
                                                            <h1 class="tl_seccion_rf">Proceso de Matriculación</h1>
                                                        </div>-->
                            <?php
                            $criteria2 = new CDbCriteria(array(
                                'condition' => "id_informacion={$id_informacion}",
                                'order' => 'id DESC',
                                'limit' => 1
                            ));
                            $count = GestionMatricula::model()->count($criteria2);
                            $art = GestionMatricula::model()->findAll($criteria2);
                            ?>
                            <?php
                            if ($count > 0):
                                foreach ($art as $v):
                                    ?>

                                    <div class="cont-mat">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        FACTURA E INGRESO DE LOS DATOS DE CHASIS Y MOTOR AL SRI.
                                                        <?php if ($v['factura_ingreso'] == 1) : ?>
                                                            <input type="checkbox" value="1" name="GestionMatricula[factura_ingreso]" checked="" disabled="">
                                                        <?php else: ?>
                                                            <input type="checkbox" value="1" name="GestionMatricula[factura_ingreso]">
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        ENVÍO DE LA FACTURA ELECTRÓNICA A LA DIRECCIÓN DEL CLIENTE DESPUÉS DE TENER LA AUTORIZACIÓN DEL SRI, PARA QUE PROCEDA EL PAGO
                                                        MATRÍCULA, REVISIÓN VEHICULAR Y OTROS EN LAS ENTIDADES BANCARIAS AUTORIZADAS.
                                                        <?php if ($v['envio_factura'] == 2) : ?>
                                                            <input type="checkbox" value="2" name="GestionMatricula[envio_factura]" checked="" disabled="">
                                                        <?php endif; ?>
                                                        <?php if ($v['envio_factura'] == NULL) : ?>
                                                            <input type="checkbox" value="2" name="GestionMatricula[envio_factura]">
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        PAGO CONSEJO PROVINCIAL Y SI APLICA PAGO RODAJE. NORMALMENTE SE INCLUYE EN EL PAGO DE LA MATRÍCULA.
                                                        <?php if ($v['pago_consejo'] == 3): ?>
                                                            <input type="checkbox" value="3" name="GestionMatricula[pago_consejo]" checked="" disabled="">
                                                        <?php endif; ?>
                                                        <?php if ($v['pago_consejo'] == NULL) : ?>
                                                            <input type="checkbox" value="3" name="GestionMatricula[pago_consejo]" disabled class="dsc">
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        EN CASO DE VENTA A CRÉDITO LA ENTIDAD BANCARIA DEBE ENTREGAR LOS CONTRATOS PARA PROCEDER CON LA MATRICULACIÓN. 
                                                        <?php if ($v['pago_consejo'] == 3 && $v['venta_credito'] == '') : ?>
                                                            <input type="checkbox" value="4" name="GestionMatricula[venta_credito]">
                                                        <?php endif; ?>    
                                                        <?php if ($v['venta_credito'] == NULL) : ?>
                                                            <input type="checkbox" value="4" name="GestionMatricula[venta_credito]" disabled class="dsc">
                                                        <?php endif; ?>    
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        ENTREGAR TODOS LOS DOCUMENTOS AL GESTOR CALIFICADO.
                                                        <?php if ($v['venta_credito'] == 4) : ?>
                                                            <input type="checkbox" value="5" name="GestionMatricula[entrega_documentos_gestor]">
                                                        <?php endif; ?>
                                                        <?php if ($v['entrega_documentos_gestor'] == NULL) : ?>
                                                            <input type="checkbox" value="5" name="GestionMatricula[entrega_documentos_gestor]" checked="">
                                                        
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        ENTE REGULADOR ENTREGA PLACA Y MATRÍCULA AL GESTOR.
                                                        <?php if ($v['ente_regulador_placa'] == 6) : ?>
                                                            <input type="checkbox" value="6" name="GestionMatricula[ente_regulador_placa]" checked="">
                                                        <?php else: ?>
                                                            <input type="checkbox" value="6" name="GestionMatricula[ente_regulador_placa]" disabled class="dsc">
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        EL VEHÍCULO SERÁ ENTREGADO ÚNICAMENTE CON MATRÍCULA Y PLACAS, DE ACUERDO A LO QUE DICE LA RESOLUCIÓN 123-DIR-2013-ANT.
                                                        <?php if ($v['vehiculo_matricula_placas'] == 7) : ?>
                                                            <input type="checkbox" value="7" name="GestionMatricula[vehiculo_matricula_placas]">
                                                        <?php else: ?>
                                                            <input type="checkbox" value="7" name="GestionMatricula[vehiculo_matricula_placas]" disabled class="dsc">
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        <?php
                                    endforeach;
                                else:
                                    ?>
                                    <div class="cont-mat">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        FACTURA E INGRESO DE LOS DATOS DE CHASIS Y MOTOR AL SRI.
                                                        <input type="checkbox" value="1" name="GestionMatricula[factura_ingreso]">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label class="dsc">
                                                        ENVÍO DE LA FACTURA ELECTRÓNICA A LA DIRECCIÓN DEL CLIENTE DESPUÉS DE TENER LA AUTORIZACIÓN DEL SRI, PARA QUE PROCEDA EL PAGO
                                                        MATRÍCULA, REVISIÓN VEHICULAR Y OTROS EN LAS ENTIDADES BANCARIAS AUTORIZADAS.
                                                        <input type="checkbox" value="2" name="GestionMatricula[envio_factura]" disabled class="dsc">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label class="dsc">
                                                        PAGO CONSEJO PROVINCIAL Y SI APLICA PAGO RODAJE. NORMALMENTE SE INCLUYE EN EL PAGO DE LA MATRÍCULA.
                                                        <input type="checkbox" value="3" name="GestionMatricula[pago_consejo]" disabled class="dsc">
                                                    </label>
                                                </div>
                                            </div>
                                            <?php
                                            if ($credito == 1):
                                                ?>
                                                <div class="col-md-12">
                                                    <div class="checkbox">
                                                        <label class="dsc">
                                                            EN CASO DE VENTA A CRÉDITO LA ENTIDAD BANCARIA DEBE ENTREGAR LOS CONTRATOS PARA PROCEDER CON LA MATRICULACIÓN. 
                                                            <input type="checkbox" value="4" name="GestionMatricula[venta_credito]" disabled class="dsc">
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label class="dsc">
                                                        ENTREGAR TODOS LOS DOCUMENTOS AL GESTOR CALIFICADO.
                                                        <input type="checkbox" value="5" name="GestionMatricula[entrega_documentos_gestor]" disabled class="dsc">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label class="dsc">
                                                        PLACA Y MATRÍCULA. 
                                                        <input type="checkbox" value="6" name="GestionMatricula[ente_regulador_placa]" disabled class="dsc">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label class="dsc">
                                                        INGRESO INFORMACIÓN DE KIA SATELITAL.
                                                        <input type="checkbox" value="7" name="GestionMatricula[info_satelital]" disabled class="dsc">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>
                                    <div class="row accs">
                                        <div class="col-md-4"><h4>Fecha de entrega</h4></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">Fecha de entrega<span class="required">*</span></label>
                                            <input type="text" name="GestionMatricula[agendamiento1]" id="agendamiento1" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="row accs">
                                        <div class="col-md-4"><h4>Observaciones</h4></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <textarea class="form-control" rows="5" name="GestionMatricula[observaciones]" id="GestionEntrega_observaciones"></textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                                            <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                                            <input type="hidden" name="GestionMatricula[id_informacion]" id="GestionMatricula_id_informacion" value="<?php echo $id_informacion; ?>">
                                            <input type="hidden" name="GestionMatricula[id_vehiculo]" id="GestionMatricula_id_vehiculo" value="<?php echo $id_vehiculo; ?>">
                                            <input class="btn btn-warning" id="matricula" type="submit" onclick="sendmail();" value="Grabar">
                                        </div>
                                        <div class="col-md-3">
                                            <div id="calendar-content" style="display: none;">
                                                <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row" id="alert-cont" style="display: none;">
                                    <div class="alert alert-warning alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Agendamiento satisfactorio. Email enviado</strong>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
                <div class="form">
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'gestion-entrega-form',
                        'enableAjaxValidation' => false,
                        'htmlOptions' => array(
                            //'enctype' => 'multipart/form-data',
                            'onsubmit' => "return false;", /* Disable normal form submit */
                            'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                        ),
                    ));
                    ?>

                    <?php
                    //echo 'hoja: '.$hoja;
                    //echo 'tipo de credito: '.$tipo;
                    //if ($hoja == 'aprobado' || $tipo == 0) :
                    ?>
                    <div class="row">
                        <h1 class="tl_seccion_rf">CHECK LISTA DE ENTREGA</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    ACEITE MOTOR
                                    <input type="checkbox" value="Aceite Motor" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    AGUA LIMPIA PARABRISAS
                                    <input type="checkbox" value="Agua Limpia Parabrisas" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    ANTENAS DE RADIO
                                    <input type="checkbox" value="Antenas de radio" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    AROS/TAPACUBOS/PERNOS
                                    <input type="checkbox" value="Aros/Tapacubos/Pernos" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    BATERÍA
                                    <input type="checkbox" value="Batería" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    CERTIFICADO DE PRODUCCIÓN CAE
                                    <input type="checkbox" value="Certificado de Producción CAE" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    A/C CALEFACCIÓN
                                    <input type="checkbox" value="A/C Calefacción" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    CENICERO
                                    <input type="checkbox" value="Cenicero" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    CINTURONES DE SEGURIDAD
                                    <input type="checkbox" value="Cinturones de Seguridad" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    EMBLEMAS
                                    <input type="checkbox" value="Emblemas" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    ESPEJO INTERIOR IZQUIERDO
                                    <input type="checkbox" value="Espejo Interior Izquierdo" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    ESPEJO EXTERIOR DERECHO
                                    <input type="checkbox" value="Espejo Exterior Derecho" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    ESPEJO INTERIOR
                                    <input type="checkbox" value="Espejo Interior" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    FACTURA
                                    <input type="checkbox" value="Factura" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    FALDONES
                                    <input type="checkbox" value="Faldones" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    GATA
                                    <input type="checkbox" value="Gata" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    HERRAMIENTAS
                                    <input type="checkbox" value="Herramientas" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    LÍQUIDO DE FRENOS
                                    <input type="checkbox" value="Líquido de Frenos" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    LLANTAS DE EMERGENCIA
                                    <input type="checkbox" value="Llantas de Energencia" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    2 LLAVES (ENCENDIDO Y PUERTAS)
                                    <input type="checkbox" value="2 Llaves Encendido y Puertas" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    LLAVE DE RUEDAS
                                    <input type="checkbox" value="Llave de Ruedas" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    LUCES (FUNCIONAMIENTO)
                                    <input type="checkbox" value="Luces Funcionamiento" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    MOLDURAS Y NIQUELADOS
                                    <input type="checkbox" value="Molduras y Niquelados" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    MANUAL DE GARANTÍA
                                    <input type="checkbox" value="Manual de Garantía" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    MANUAL DE PROPIETARIO
                                    <input type="checkbox" value="Manual de Propietario" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    NEBLINEROS
                                    <input type="checkbox" value="Neblineros" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    PARASOLES
                                    <input type="checkbox" value="Parasoles" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    PARLANTES
                                    <input type="checkbox" value="Parlantes" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    PINTURA
                                    <input type="checkbox" value="Pintura" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    PITO
                                    <input type="checkbox" value="Pito" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    PLUMAS LIMPIAPARABRISAS
                                    <input type="checkbox" value="Plumas Limpiadoras" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    RADIO CON PANTALLA
                                    <input type="checkbox" value="Radio con Pantalla" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    REFRIGERANTE DE RADIADOR
                                    <input type="checkbox" value="Refrigerante de Radiador" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    SEGUROS PUERTAS (FUNCIONAMIENTO)
                                    <input type="checkbox" value="Seguros Puertas Funcionamiento" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    SEGURO LLANTA DE EMERGENCIA
                                    <input type="checkbox" value="Segurl Llanta de Emergencia" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    TABLERO DE CONTROL
                                    <input type="checkbox" value="Tablero de Control" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    TAPA DE GASOLINA    
                                    <input type="checkbox" value="Tablero de Gasolina" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    TAPA VÁLVULAS
                                    <input type="checkbox" value="Tapa Válvulas" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    TAPICERÍA
                                    <input type="checkbox" value="Tapicería" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    VIDRIOS (FUNCIONAMIENTO)
                                    <input type="checkbox" value="Vidrios Funcionamiento" name="GestionEntrega[accesorios][]">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h1 class="tl_seccion_rf">Firma y Foto del Cliente</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Firma del Cliente</h4>
                        </div>
                    </div>
                    <?php
                    if ($firma > 0):
                        $fr = GestionFirma::model()->find($cri);
                        $imgfr = $fr->firma;
                        ?>
                        <div class="row">
                            <div class="col-md-5">
                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/upload/firma/<?php echo $imgfr; ?>" alt="" width="200" height="100">

                            </div>
                        </div>

                    <?php else: ?>
                        <div class="row" id="cont-firma-img" style="display: none;">
                            <img src="" alt="" id="img-firma" width="200" height="100"/>
                        </div>
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
                        <div class="row" id="cont-firma">
                            <div class="col-md-4">
                                <!--<a href="/intranet/ventas/index.php/site/signature/176" target="_blank" class="btn btn-xs btn-primary">Ingresar Firma</a>-->
                                <a href="#inline1" class="fancybox btn btn-xs btn-primary">Ingresar Firma</a> 
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Foto de entrega de vehículo de cliente</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 100px;"></div>
                                <div>
                                    <span class="btn btn-default btn-file"><span class="fileinput-new">Foto del Cliente</span><span class="fileinput-exists">Cambiar</span>
                                        <input type="file" name="GestionEntrega[foto_cliente]" id="GestionEntrega_foto_cliente">
                                    </span>
                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cont-carta" style="display: none;">
                        <div class="row">
                            <h1 class="tl_seccion_rf">Carta de Bienvenida</h1>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <a target="_blank" class="btn btn-xs btn-warning" onclick="sendBienvenida();">Enviar mail</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="">Observaciones</label>
                            <textarea cols="10" rows="10" name="GestionEntrega[observaciones]" id="GestionEntregaObservaciones"></textarea>
                        </div>
                    </div>
                    <br />
                    <div class="row buttons">
                        <div class="col-md-2">
                            <input type="hidden" name="GestionEntrega[id_vehiculo]" id="GestionEntrega_id_vehiculo" value="<?php echo $id_vehiculo; ?>" />
                            <input type="hidden" name="GestionEntrega[id_informacion]" id="GestionEntrega_id_informacion" value="<?php echo $id_informacion; ?>" />
                            <input type="hidden" name="GestionInformacion2[calendar]" id="GestionInformacion_calendar2" value="0">
                            <input type="hidden" name="GestionInformacion2[check]" id="GestionInformacion_check2" value="1">
                            <input type="hidden" name="GestionInformacion[nombres]" id="GestionInformacion_nombres" value="<?php echo $this->getNombresInfo($id_informacion); ?>">
                            <input type="hidden" name="GestionInformacion[apellidos]" id="GestionInformacion_apellidos" value="<?php echo $this->getApellidosInfo($id_informacion); ?>">
                            <input class="btn btn-danger" id="finalizar" type="submit" value="Continuar" onclick="send();">
                        </div>
                        <div class="col-md-2">
                            <div id="calendar-content2" style="display: none;">
                                <a href="" class="btn btn-primary" id="event-download2">Descargar Evento</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <a href="<?php echo Yii::app()->createUrl('gestionSeguimiento/create/', array('id_vehiculo' => $id_vehiculo, 'id_informacion' => $id_informacion)); ?>" class="btn btn-danger" id="continue" style="display: none;" target="_blank">Continuar</a>
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo Yii::app()->createUrl('gestionVehiculo/hojaentrega/', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" class="btn btn-warning" id="generatepdf" style="display: none;" target="_blank">Imprimir</a>
                        </div>
                    </div>
                    <?php //endif; ?>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
        <br />
                <br />
                <div class="row">
                    <div class="col-md-8  col-xs-12 links-tabs">
                        <div class="col-md-2 col-xs-4"><p>También puedes ir a:</p></div>
                        <div class="col-md-2 col-xs-4"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>
                        <div class="col-md-2 col-xs-4"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>" class="creacion-btn">RGD</a></div>                         <div class="col-md-3 col-xs-4"><a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>" class="directorio-btn">Directorio de Contactos</a></div>
                    </div>
                </div>
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">SGC</h4>
                </div>
                <div class="modal-body">
                    <h4>Agendamiento satisfactorio. Email enviado al cliente</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="closemodal">Cerrar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
</div>