<?php $this->widget('application.components.Notificaciones'); ?>
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
                $(this).find('.xdsoft_date.xdsoft_weekend')
                        .addClass('xdsoft_disabled');
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
                            var href = '/intranet/usuario/index.php/gestionDiaria/ical?startTime=' + fechaStart + startTime + '&endTime=' + fechaStart + endTime + '&subject=Agendamiento Cita Cliente&desc=Cita con el cliente prospección&location=Por definir&to_name=' + cliente + '&conc=no';
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
                            type: 'post', dataType: 'json', data: {id:<?php echo $id; ?>},
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
                            type: 'post', dataType: 'json', data: {id:<?php echo $id; ?>},
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
                'condition' => "id={$id}"
            ));
            $info = GestionInformacion::model()->count($criteria);
            ?>
            <?php if ($info > 0): ?>
                <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/update/', array('id' => $id, 'tipo' => 'gestion')); ?>"  aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <?php else: ?>
                <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <?php endif; ?>
            <li role="presentation" class="active"><a href="' . Yii::app()->createUrl('gestionVehiculo/create') . '" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta_on.png" alt="" /></span> Consulta</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id); ?>" saria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/demostracion/' . $id); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id); ?>" aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/cierre/' . $id); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane" id="home">
            </div>
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="row">
                    <h1 class="tl_seccion_rf">Resúmen de vehículos recomendados</h1>
                </div>

                <div class="row">
                </div>
                <div class="col-md-12">
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
                                                "91" => "Rio Taxi",
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

                                    <div class="row" style="display: none;">
                                        <div class="col-md-6">
                                            <div id="info3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                            
                                        </div>
                                    </div>
                                    <div class="row buttons">
                                        <div class="col-md-2">
                                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Grabar', array('class' => 'btn btn-danger', 'onclick' => 'send(1);')); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php $this->endWidget(); ?>
                            </div><!-- form -->
                        </div><!-- highlight -->
                    </div>    
                    <br>
                    <div class="form form-content" style="display: none;">
                        <form onsubmit="return false;" onkeypress="if(event.keyCode == 13) {send();}" id="gestion-vehiculo-form2" action="/intranet/usuario/index.php/gestionVehiculo/create/40" method="post">
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
                                            <option value="91">Rio Taxi</option>
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
                                        <div id="info2" style="display: none;"><img src="/intranet/usuario/images/ajax-loader.gif" alt=""></div>
                                        <label for="GestionVehiculo_version">Version</label>
                                        <select class="form-control" name="GestionVehiculo2[version]" id="GestionVehiculo_version2">
                                            <option value="" selected="selected">Escoja una versión</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="display: none;">
                                     <div class="col-md-6">
                                        <div id="info3" style="display: none;"><img src="/intranet/usuario/images/ajax-loader.gif" alt=""></div>
                                        <label for="GestionVehiculo_precio" class="required">Precio <span class="required">*</span></label>
                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo2[precio]" id="GestionVehiculo_precio" type="text">
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
                                            <option value="Falta de tiempo">Falta de tiempo</option>
                                            <option value="Llamada de emergencia">Llamada de emergencia</option>
                                            <option value="Busca solo precio">Busca solo precio</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Agendamiento</label>
                                        <input type="text" name="agendamiento2" id="agendamiento2" class="form-control">
                                    </div>
                                </div>
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

                <?php else:?>
                    
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
                    <div class="form-content" style="display: none;">
                        <form onsubmit="return false;" onkeypress="if (event.keyCode == 13) {send();}" id="gestion-vehiculo-form" action="/intranet/callcenter/index.php/gestionVehiculo/create/40" method="post">
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
                                            <option value="91">Rio Taxi</option>
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
                                <div class="row" style="display: none;">
                                    <div class="col-md-6">
                                        <div id="info3" style="display: none;"><img src="/intranet/callcenter/images/ajax-loader.gif" alt=""></div>
                                        <label for="GestionVehiculo_precio" class="required">Precio <span class="required">*</span></label>
                                        <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[precio]" id="GestionVehiculo_precio" type="text">
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

                                <div class="col-md-5">
                                    <input class="btn btn-danger" onclick="send(2);" type="submit" name="yt0" value="Crear">
                                    <input class="btn btn-primary" style="margin-left: 14px;" onclick="cancelVec();" type="submit" name="yt0" value="Cancelar">
                                </div>

                            </div>
                        </form>
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
        ?>><div class="col-md-3"><a class="btn btn-success" style="margin: 20px 0px;" onclick="createVec(<?php echo $id; ?>)">Agregar otro vehículo</a></div>
        </div>
        <div class="col-md-3"><a href="<?php echo Yii::app()->createUrl('site/presentacion', array('id' => $id)); ?>" class="btn btn-danger" style="margin: 20px 0px;">Continuar</a></div>
    </div>
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
                    $categorizacion = $this->getCategorizacion($id);
                    //echo $categorizacion;
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

            </div>
            <div class="row buttons">
                <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="7">
                <input type="hidden" name="GestionAgendamiento[id_informacion]" id="GestionAgendamiento_id_informacion" value="<?php echo $id; ?>">
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
            <?php //echo $form->errorSummary($agendamiento);   ?>
            <div class="row">
                <div class="col-md-4" style="display: none;">
                    <?php echo $form->labelEx($agendamiento, 'categorizacion'); ?>
                    <?php
                    $categorizacion = $this->getCategorizacion($id);
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
                    <?php echo $form->dropDownList($agendamiento, 'observaciones', array('' => '--Seleccione--', 'Falta de tiempo' => 'Falta de tiempo', 'Llamada de emergencia' => 'Llamada de emergencia', 'Busca solo precio' => 'Busca solo precio', 'Desiste' => 'Desiste', 'Otro' => 'Otro'), array('class' => 'form-control')); ?>
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
                <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="4">
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
        </div><!-- END FORM  -->
        <div class="row">
            <?php
            $crit5 = new CDbCriteria(array('condition' => "id_informacion={$id} AND paso = 4"));
            $agen5 = GestionAgendamiento::model()->count($crit5);

            $ag5 = GestionAgendamiento::model()->findAll($crit5);
            if ($agen5 > 0) {
                ?>
                <div class="col-md-8">
                    <h4 class="text-danger">Historial</h4>
                </div>
                <div class="col-md-8">
                <?php
                }
                foreach ($ag5 as $a) {
                    ?>
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
    <br>
    <div class="row">
        <div class="col-md-8 col-xs-12 links-tabs">
            <div class="col-md-2 col-xs-4"><p>También puedes ir a:</p></div>
            <div class="col-md-2 col-xs-4"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>
            <div class="col-md-2 col-xs-4"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>" class="creacion-btn">RGD</a></div>                         <div class="col-md-3 col-xs-4"><a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>" class="directorio-btn">Directorio de Contactos</a></div>
        </div>
    </div>
</div>