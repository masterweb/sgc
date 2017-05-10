<?php $this->widget('application.components.Notificaciones'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<?php
$model = new GestionVehiculo;
$id_asesor = Yii::app()->user->getId();
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
if ($cargo_id != 46) {
    $concesionarioid = $this->getConcesionarioDealerId($id_asesor);
    $nombreConcesionario = urlencode($this->getNameConcesionarioById($concesionarioid));
    $nombre_cliente = urlencode($this->getNombresInfo($id_informacion) . ' ' . $this->getApellidosInfo($id_informacion));
    $direccion_concesionario = urlencode($this->getConcesionarioDireccionById($concesionarioid));
}
//echo $nombre_cliente;
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
    #info2,#info3, #info4, #info5, #info6 {top: 19px;}#info2 img,#info3 img {width: 74%;}form li{margin-left: -14px;}
</style>
<script type="text/javascript">
    $(document).ready(function () {
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
        $('#info2').hide();
        $('#info3').hide();
        $('#GestionVehiculo_version').change(function () {
            var value = $(this).attr('value');
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getPrice"); ?>',
                beforeSend: function (xhr) {
                    $('#info3').show();  // #info must be defined somehwere
                },
                type: 'POST', dataType: 'json', data: {id: value},
                success: function (data) {
                    $('#GestionVehiculo_precio').val(data.options);
                    $('#info3').hide();
                    //$('.cont-accesorios').show();
                }
            });

        });
        $('#GestionVehiculo_version2').change(function () {
            var value = $(this).attr('value');
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getPrice"); ?>',
                beforeSend: function (xhr) {
                    $('#info3').show();  // #info must be defined somehwere
                }, type: 'POST', dataType: 'json', data: {id: value},
                success: function (data) {
                    $('#GestionVehiculo_precio').val(data.options);
                    $('#info3').hide();
                }
            });

        });
//        $('#GestionAgendamiento_observaciones').change(function () {
//            var value = $(this).attr('value');
//            if (value == 'Otro') {
//                $('#cont-otro').show();
//            } else {
//                $('#cont-otro').hide();
//            }
//        });
        $('#gestion-agendamiento-form2').validate({
            rules: {
                'GestionAgendamiento[observaciones]': {
                    required: true
                }
            },
            messages: {
                'GestionAgendamiento[observaciones]': {
                    required: 'Seleccione una opción'
                }
            },
            submitHandler: function (form) {
                var proximoSeguimiento = $('#GestionAgendamiento_agendamiento').val();
                var observaciones = $('#GestionAgendamiento_observaciones').val();
                if (observaciones == 'Busca solo precio' || observaciones == 'Desiste' || observaciones == 'Otro') {
                    form.submit();
                } else {
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
                            dias = 7;
                            break;
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
                                var href = '/intranet/usuario/index.php/gestionDiaria/ical?startTime=' + fechaStart + startTime + '&endTime=' + fechaStart + endTime + '&subject=Agendamiento Cita Cliente <?php echo $nombre_cliente; ?>&desc=Cita con el cliente paso consulta: <?php echo $nombre_cliente; ?>&location= <?php echo $direccion_concesionario; ?>&to_name=' + cliente + '&conc=<?php echo $nombreConcesionario; ?>';
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
                        alert('Su fecha de agendamiento debe ser máxima en un rango de 48 horas..');
                        return false;
                    }
                }
            }
        });
    });
    function send(n)
    {
        //console.log('enter send');
        $('#gestion-vehiculo-form').validate({
            rules: {
                'GestionVehiculo[modelo]': {
                    required: true
                },
                'GestionVehiculo[version]': {
                    required: true
                }
            },
            messages: {
                'GestionVehiculo[modelo]': {
                    required: 'Seleccione modelo'
                }, 'GestionVehiculo[version]': {
                    required: 'Seleccione versión'
                }
            },
            submitHandler: function (form) {
                console.log('enter submit');
                var dataform = $("#gestion-vehiculo-form").serialize();
                $.ajax({
                    url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/createAjax"); ?>',
                    beforeSend: function (xhr) {
                        $('#bg_negro').show();  // #bg_negro must be defined somewhere
                    },
                    type: 'POST',
                    data: dataform,
                    success: function (data) {
                        $('#bg_negro').hide();
                        //alert('Datos grabados');
                        $('.vehicle-cont').remove();
                        $.ajax({
                            url: '<?php echo Yii::app()->createAbsoluteUrl("site/getVec"); ?>',
                            type: 'post', dataType: 'json', data: {id:<?php echo $id_informacion; ?>},
                            success: function (data) {
                                $('.display-vec').html(data.options);
                                $('#cont-agregar').show();
                            }
                        });
                    }
                });

            }
        });

    }
    function send2()
    {
        //console.log('enter send');
        $('#gestion-vehiculo-form2').validate({
            rules: {
                'GestionVehiculo2[modelo]': {
                    required: true
                },
                'GestionVehiculo2[version]': {
                    required: true
                }
            },
            messages: {
                'GestionVehiculo2[modelo]': {
                    required: 'Seleccione modelo'
                }, 'GestionVehiculo2[version]': {
                    required: 'Seleccione versión'
                }},
            submitHandler: function (form) {
                var dataform = $("#gestion-vehiculo-form2").serialize();
                $.ajax({
                    url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/createAjax2"); ?>',
                    beforeSend: function (xhr) {
                        $('#bg_negro').show();  // #bg_negro must be defined somewhere
                    },
                    type: 'POST',
                    data: dataform,
                    success: function (data) {
                        $('#bg_negro').hide();
                        //alert('Datos grabados');
                        $('.vehicle-cont').remove();
                        $.ajax({
                            url: '<?php echo Yii::app()->createAbsoluteUrl("site/getVec"); ?>',
                            type: 'post', dataType: 'json', data: {id:<?php echo $id_informacion; ?>},
                            success: function (data) {
                                $('.display-vec').html(data.options);
                                $('#cont-agregar').show();
                                $('.form-content').hide();
                                $('#gestion-vehiculo-form2').get(0).reset();
                            }
                        });
                    }
                });
            }
        });

    }


    function createVec(id) {

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
    function cancelVec() {
        //$('#gestion-vehiculo-form').remove();
        //console.log('enter cancel vec');
        $('.form-content').hide();
        $('#gestion-vehiculo-form').validate().currentForm = '';
        //$('#gestion-vehiculo-form').get(0).reset();
    }
    function cancelVec2() {
        $('.form-content').hide();
        //$('#gestion-vehiculo-form2').get(0).reset();
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
            <li role="presentation" class="active"><a href="' . Yii::app()->createUrl('gestionVehiculo/create') . '" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta_on.png" alt="" /></span> Consulta</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id_informacion); ?>" saria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/demostracion/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/cierre/' . $id_informacion); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane" id="home">
            </div>
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="row">
                    <h1 class="tl_seccion_rf">Inicio de proceso</h1>
                </div>
                <div class="row">
                </div>
                <div class="col-md-12">
                    <div class="row highlight">
                        <div class="row display-vec"></div>
                        <div class="row">
                            <div class="cont-vc col-md-12">
                                <div class="table-responsive">
                                    <table class="tables tablesorter" id="keywords">
                                        <thead>
                                            <tr>
                                                <th><span>Modelo</span></th>
                                                <th><span>Versión</span></th>
                                                <th><span>Necesidad</span></th>
                                                <th><span>Consulta</span></th>
                                                <th><span>Lista de Precios</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><a href="<?php echo Yii::app()->createUrl('gestionConsulta/update/', array('id_informacion' => $id_informacion, 'tipo' => $tipo, 'fuente' => $fuente)); ?>" class="btn btn-danger btn-xs">Consulta</a></td>
                                                <td><?php echo '<a href="' . Yii::app()->request->baseUrl . '/images/LISTA-DE-PRECIOS-KIA-09-05-2017.pdf" class="btn btn-default btn-xs" type="submit" name="yt0" target="_blank">Lista de Precios</a>'; ?></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <hr>
                        </div>
                        <div class="form vehicle-cont">
<?php if ($count == 0): ?>
                            </div><!-- form -->
                        </div><!-- highlight -->
                    </div>    
                    <br>
<?php else: ?>
                    <div class="row">
                        <div class="cont-vc">
                            <div class="table-responsive">
                                <table class="tables tablesorter" id="keywords">
                                    <thead>
                                        <tr>
                                            <th><span>Modelo</span></th>
                                            <th><span>Versión</span></th>
                                            <th><span>Necesidad</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($vec as $c):
                                            ?>
                                            <tr>
                                                <td><?php echo $this->getModel($c['modelo']); ?> </td>
                                                <td><?php echo $this->getVersion($c['version']); ?> </td>
                                                <td><?php echo $this->getNecesidad($c['id_informacion']); ?> </td>
                                                <td><?php echo '<a href="' . Yii::app()->request->baseUrl . '/images/LISTA-DE-PRECIOS-KIA-09-05-2017.pdf" class="btn btn-warning btn-xs" type="submit" name="yt0" target="_blank">Lista de Precios</a>';
                                            ; ?></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr>
                    </div>
                </div>
<?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="" id="cont-agregar" <?php
        if ($count == 0) {
            echo 'style="display: none;"';
        }
        ?>><div class="col-md-3"><a class="btn btn-success" style="margin: 20px 0px;" onclick="createVec(<?php echo $id_informacion; ?>)">Agregar otro vehículo</a></div>
        </div>

    </div>

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
                'id' => 'gestion-agendamiento-form2',
                'enableAjaxValidation' => false,
            ));
            ?>
                    <?php //echo $form->errorSummary($agendamiento);    ?>
            <div class="row">
                <div class="col-md-4" style="display: none;">
                    <?php echo $form->labelEx($agendamiento, 'categorizacion'); ?>
                    <?php
                    $categorizacion = $this->getCategorizacion($id_informacion);
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
<?php echo $form->dropDownList($agendamiento, 'observaciones', array('' => '--Seleccione--', 'Cita' => 'Cita','Seguimiento' => 'Seguimiento', 'Falta de tiempo' => 'Falta de tiempo', 'Llamada de emergencia' => 'Llamada de emergencia', 'Busca solo precio' => 'Busca solo precio', 'Desiste' => 'Desiste', 'Otro' => 'Otro'), array('class' => 'form-control')); ?>
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
                <?php $paso4 = GestionVehiculo::model()->count(array("condition" => "id_informacion = {$_GET['id_informacion']}")); ?>
                <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                <?php if ($_GET['fuente'] == 'prospeccion') { ?>
                    <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="4">
                <?php } else { ?>
                    <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="<?php echo ($paso4 > 0) ? '4' : '1-2'; ?>">
<?php } ?>

                <input type="hidden" name="GestionAgendamiento[id_informacion]" id="GestionAgendamiento_id_informacion" value="<?php echo $id_informacion; ?>">
                <div class="col-md-2">
<?php echo CHtml::submitButton($agendamiento->isNewRecord ? 'Grabar' : 'Save', array('class' => 'btn btn-danger', 'id'=>'btn_grabar', 'onclick'=> "deshabilitarBoton('GestionAgendamiento_observaciones','GestionAgendamiento_agendamiento','btn_grabar','gestion-agendamiento-form2')")); ?>
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
            $crit5 = new CDbCriteria(array('condition' => "id_informacion={$id_informacion} AND paso = 4"));
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
                <input type="hidden" name="GestionPasoOnce[paso]" id="GestionPasoOnce_paso" value="4">
                <input type="hidden" name="GestionPasoOnce[id_informacion]" id="GestionPasoOnce_id_informacion" value="<?php echo $id_informacion; ?>">
                <div class="col-md-2">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Grabar' : 'Grabar', array('class' => 'btn btn-danger', 'id'=>'btn_grabar2', 'onclick'=>"deshabilitarBoton('GestionPasoOnce_tipo','GestionPasoOnce_observacion','btn_grabar2','gestion-paso-once-form')")); ?>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div><!-- END FORM  -->
    </div>
    <br>
    
<?= $this->renderPartial('//layouts/rgd/links'); ?>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/inhabilitarBoton.js"></script>