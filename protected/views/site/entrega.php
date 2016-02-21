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
            var url = window.location.href;
            $(location).attr('href', url);
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
    function send() {
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
                    <h1 class="tl_seccion">Lista de Entrega</h1>
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
                                            <th><span>Entrega</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($vec as $c): ?>
                                            <tr>
                                                <td><?php echo $this->getModel($c['modelo']); ?> </td>
                                                <td><?php echo $this->getVersion($c['version']); ?> </td>

                                                <td>
                                                    <?php
                                                    $test = $this->getFactura($c['id_informacion'], $c['id']);
                                                    ?>
                                                    <?php if ($test > 0): ?>
                                                        <a href="<?php echo Yii::app()->createUrl('gestionPasoEntrega/create', array('id_informacion' => $c['id_informacion'], 'id_vehiculo' => $c['id'])); ?>" class="btn btn-success btn-xs btn-rf">Entrega</a>
                                                    <?php else: ?>    

                                                        <a href="<?php echo Yii::app()->createUrl('gestionPasoEntrega/create', array('id_informacion' => $c['id_informacion'], 'id_vehiculo' => $c['id'])); ?>" class="btn btn-danger btn-xs btn-rf">Entrega</a>
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