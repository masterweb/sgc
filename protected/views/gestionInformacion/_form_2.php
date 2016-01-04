<?php
/* @var $this GestionInformacionController */
/* @var $model GestionInformacion */
/* @var $form CActiveForm */
//echo 'VALUE FORM';
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
    $(document).ready(function () {
        $('#savebtn').click(function(){$('.snbt').show();$('#savebtn').hide();})
        $('#GestionInformacion_fecha_cita').datetimepicker({
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
        $('#agendamiento').datetimepicker({
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
                    $('.cont-accesorios').show();
                }
            });

        });
        $('#GestionConsulta_preg2').change(function () {
            var value = $("#GestionConsulta_preg2 option:selected").val();
            if (value == 1) {
                $('.cont-img').show();
                $('.cont-link').show();
            } else {
                $('.cont-img').hide();
                $('.cont-link').hide();
            }
        });
        $('#GestionConsulta_preg3').change(function () {
            var value = $("#GestionConsulta_preg3 option:selected").val();
            if (value == 2) {
                $('.cont-utl').hide();
            } else {
                $('.cont-utl').show();
            }
        });
        $('#GestionInformacion_visita').change(function () {
            var value = $(this).attr('value');
            if (value == 'si') {
                $('.datepick').show();
                $('#continuar').hide();
                $('#finalizar').show();
            } else {
                $('.datepick').hide();
                $('#GestionInformacion_fecha_cita').val('');
                $('#continuar').show();
                $('#finalizar').hide();
            }
        });
        $('#GestionConsulta_preg1_sec5').click(function () {
            if ($('#GestionConsulta_preg1_sec5').is(':checked')) {
                $('.cont-vec-new').hide();
            } else {
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
        $('#GestionInformacion_provincia_domicilio').change(function () {
            var value = $("#GestionInformacion_provincia_domicilio option:selected").val();
            //console.log('valor seleccionado: '+value);
            var data = '';
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getciudades"); ?>',
                //dataType: "json",
                beforeSend: function (xhr) {
                    $('#info3').show();  // #info must be defined somehwere
                },
                type: 'post',
                data: {
                    id: value
                },
                success: function (data) {
                    //alert(data.options)
                    $('#GestionInformacion_ciudad_domicilio').html(data);
                    $('#info3').hide();
                }
            });
            //alert(value)
            $('#GestionInformacion_ciudad_domicilio').html(data);

        });
        $('#GestionProspeccionPr_pregunta').change(function () {
            var value = $("#GestionProspeccionPr_pregunta option:selected").val();
            switch (value) {
                case '3':
                    $('.cont-vec').show();
                    $('.cont-interesado').hide();
                    $('.cont-nocont').hide();
                    break;
                case '4':
                    $('.cont-vec').hide();
                    $('.cont-interesado').show();
                    $('.cont-nocont').hide();
                    break;
                case '5':
                    $('.cont-vec').hide();
                    $('.cont-interesado').hide();
                    $('.cont-nocont').show();
                    break;
                case '1':
                case '2':
                case '6':
                    $('.cont-vec').hide();
                    $('.cont-interesado').hide();
                    $('.cont-nocont').hide();
                    break;
                default:
                    break;
            }
        });
        $('#GestionProspeccion_lugar').change(function () {
            var value = $("#GestionProspeccion_lugar option:selected").val();
            if (value == 1 || value == 2) {
                $('.cont-lugar').show();
                $('.cont-conc').hide();
            } else if (value == 0) {
                $('.cont-conc').show();
                $('.cont-lugar').hide();
            } else {
                $('.cont-conc').hide();
                $('.cont-lugar').hide();
            }
        });

        $('#GestionConsulta_preg1_sec1').change(function () {
            var marca = $(this).attr('value');
            $.ajax({
                type: 'post',
                url: '<?php echo Yii::app()->createUrl("site/getmodelos"); ?>',
                //dataType: "json",
                data: {marca: marca},
                success: function (data) {
                    //alert(data.options)
                    $('#Cotizador_modelo').html(data);
                }
            });

        });
        $('#GestionProspeccionRp_preg3_sec2').change(function () {
            var marca = $(this).attr('value');
            $.ajax({
                type: 'post',
                url: '<?php echo Yii::app()->createUrl("site/getmodelos"); ?>',
                //dataType: "json",
                data: {marca: marca},
                success: function (data) {
                    //alert(data.options)
                    $('#Cotizador_modelo').html(data);
                }
            });

        });

        $('#Cotizador_modelo').change(function () {
            var modelo = $(this).attr('value');
            //alert(value);
            $.ajax({
                type: 'post',
                url: '<?php echo Yii::app()->createUrl("site/getmodelsyears"); ?>',
                //dataType: "json",
                data: {modelo: modelo},
                success: function (data) {
                    //alert(data.options)
                    $('#Cotizador_year').html(data);
                }
            });
        });

    });
    function sendSeg() {
        var observaciones = $('#GestionProspeccionPr_pregunta').val();
        switch (observaciones) {
            case '3':

                break;
            default:
                break;
        }
    }
    function sendInfo() {
        //console.log('enter send info');
        var tipo = $('#GestionInformacion_tipo').val();
        if (tipo == 'trafico') {
            console.log('enter trafico');
            $('#gestion-informacion-form').validate({
                rules: {'GestionVehiculo[modelo]': {required: true}, 'GestionVehiculo[version]': {required: true}, 'GestionInformacion[nombres]': {required: true},
                    'GestionInformacion[apellidos]': {required: true},
                    'GestionInformacion[cedula]': {required: true, number: true},
                    'GestionInformacion[celular]': {required: true}
                },
                messages: {'GestionVehiculo[modelo]': {required: 'Seleccione un modelo'}, 'GestionVehiculo[version]': {required: 'Seleccione una versión'}, 'GestionInformacion[nombres]': {required: 'Ingrese los nombres'},
                    'GestionInformacion[apellidos]': {required: 'Ingrese los apellidos'},
                    'GestionInformacion[cedula]': {required: 'Ingrese la cédula', number: 'Ingrese solo números'},
                    'GestionInformacion[celular]': {required: 'Ingrese el celular'}
                },
                submitHandler: function (form) {
                    $('#GestionInformacion_provincia_conc').removeAttr('disabled');
                    $('#GestionInformacion_ciudad_conc').removeAttr('disabled');
                    $('#GestionInformacion_concesionario').removeAttr('disabled');
                    form.submit();
                }
            });
        }
    }
    function sendCoti() {
        //console.log('enter send info');
        var tipo = $('#GestionInformacion_tipo').val();
        if (tipo == 'trafico') {
            console.log('enter trafico');
            $('#gestion-informacion-form').validate({
                rules: {'GestionVehiculo[modelo]': {required: true}, 'GestionVehiculo[version]': {required: true}, 'GestionInformacion[nombres]': {required: true},
                    'GestionInformacion[apellidos]': {required: true},
                    'GestionInformacion[cedula]': {required: true, number: true},
                    'GestionInformacion[celular]': {required: true}
                },
                messages: {'GestionVehiculo[modelo]': {required: 'Seleccione un modelo'}, 'GestionVehiculo[version]': {required: 'Seleccione una versión'}, 'GestionInformacion[nombres]': {required: 'Ingrese los nombres'},
                    'GestionInformacion[apellidos]': {required: 'Ingrese los apellidos'},
                    'GestionInformacion[cedula]': {required: 'Ingrese la cédula', number: 'Ingrese solo números'},
                    'GestionInformacion[celular]': {required: 'Ingrese el celular'}
                },
                submitHandler: function (form) {
                    $('#GestionInformacion_provincia_conc').removeAttr('disabled');
                    $('#GestionInformacion_ciudad_conc').removeAttr('disabled');
                    $('#GestionInformacion_concesionario').removeAttr('disabled');
                    form.submit();
                }
            });
        }
    }
</script>
<div role="tabpanel">

    <!-- Nav tabs -->


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
                    //'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                    ),
                ));
                ?>
                <div class="highlight"><!--=========DATOS DEL CLIENTE Y CONCESIONARIO===============-->
                    <div class="row">
                        <h1 class="tl_seccion_rf">Datos del Cliente</h1>
                    </div>  
                    <div class="row"><p class="note">Campos con <span class="required">*</span> son requeridos.</p></div>

                    <?php //echo $form->errorSummary($model);   ?>
                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div id="info2" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                            <?php echo $form->labelEx($vehiculo, 'version'); ?>
                            <?php echo $form->dropDownList($vehiculo, 'version', array('' => 'Escoja una versión'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($vehiculo, 'version'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'nombres', array('required' => 'required'));  ?>
                            <label class="" for="">Nombres <?php
                                if ($_GET['tipo'] == 'trafico' || $_GET['tipo'] == 'prospeccion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <?php echo $form->textField($model, 'nombres', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'nombres'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'apellidos'); ?>
                            <label class="" for="">Apellidos <?php
                                if ($_GET['tipo'] == 'trafico' || $_GET['tipo'] == 'prospeccion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <?php echo $form->textField($model, 'apellidos', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'apellidos'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'cedula');  ?>
                            <label class="" for="">Cédula <?php
                                if ($_GET['tipo'] == 'trafico' || $_GET['tipo'] == 'prospeccion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <?php //echo $form->textField($model, 'cedula', array('size' => 20, 'maxlength' => 10, 'class' => 'form-control'));   ?>
                            <input size="20" maxlength="10" class="form-control" name="GestionInformacion[cedula]" id="GestionInformacion_cedula" type="text" value="<?php
                            if (isset($id)) {
                                echo $this->getCedula($id);
                            }
                            ?>">
                                   <?php echo $form->error($model, 'cedula'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'celular');   ?>
                            <label class="" for="">Celular <?php
                                if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <?php echo $form->textField($model, 'celular', array('size' => 15, 'maxlength' => 10, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'celular'); ?>
                        </div>

                    </div>

                    <div class="row buttons">
                        <div class="col-md-8">
                            <input type="hidden" name="GestionInformacion[fuente]" id="GestionInformacion_fuente" value="<?php echo $fuente; ?>">
                            <input name="GestionInformacion[paso]" id="GestionInformacion_paso" type="hidden" value="3">
                            <input name="GestionInformacion[tipo]" id="GestionInformacion_tipo" type="hidden" value="<?php
                            if (isset($_GET['tipo'])) {
                                echo $_GET['tipo'];
                            }
                            ?>">
                            <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET['tipo']; ?>">
                            <input type="hidden" name="GestionInformacion[id_cotizacion]" id="GestionInformacion_id_cotizacion" value="<?php echo $id; ?>">
                            <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                            <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                            <input name="GestionInformacion[tipo]" id="GestionInformacion_tipo" type="hidden" value="<?php
                            if (isset($_GET['tipo'])) {
                                echo $_GET['tipo'];
                            }
                            ?>">
                            <input name="GestionInformacion[paso]" id="GestionInformacion_paso" type="hidden" value="1-2">
                            <input name="GestionDiaria[id_informacion]" id="GestionDiaria_id_informacion" type="hidden" value="<?php //echo $id_informacion;    ?>">
                            <input name="GestionDiaria[id_vehiculo]" id="GestionDiaria_id_vehiculo" type="hidden" value="<?php //echo $id_vehiculo;      ?>">
                            <input name="GestionDiaria[primera_visita]" id="GestionDiaria_seguimiento" type="hidden" value="1">
                            <input name="GestionDiaria[seguimiento]" id="GestionDiaria_seguimiento" type="hidden" value="0">
                            <?php
                            if ($_GET['tipo'] == 'prospeccion'):
                                echo '<input type="hidden" name="GestionInformacion[status]" id="GestionInformacion_status" value="prospeccion">';
                            else:
                                echo '<input type="hidden" name="GestionInformacion[status]" id="GestionInformacion_status" value="primera_visita">';
                            endif;
                            ?>
                            <input class="btn btn-danger" id="savebtn" name="ce" value="Guardar" type="submit">
                            <div class="snbt" style="display: none;">
                                <input class="btn btn-danger" id="guardar" onclick="sendInfo();" type="submit" name="yt0" value="Guardar">
                                <input class="btn btn-danger" id="cotizar" onclick="sendCoti();" type="submit" name="yt1" value="Cotizar">
                            </div>

                        </div>
                    </div>

                </div><!-- ==========END DATOS CLIENTE Y CONCESIONARIO=============-->
                <br>
                <?php if (isset($_GET['tipo']) && ($_GET['tipo'] == 'prospeccion')): ?>
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
                                                    <div class="row">
                                                        <label for="">Marca</label>
                                                    </div>
                                                    <?php
                                                    $prospeccionrp = new GestionProspeccionRp;
                                                    $consulta = new GestionConsulta;
                                                    $criteria = new CDbCriteria(array('group' => 'modelo', 'order' => 'id asc'));
                                                    $marcas = CHtml::listData(Marcas::model()->findAll($criteria), "marca", "marca");

                                                    //echo $form->dropDownList($prospeccionrp, "preg3_sec2", $marcas, array('empty' => '---Seleccione una marca---', 'class' => 'form-control'));
                                                    ?> 
                                                    <?php
                                                    $this->widget('ext.select2.ESelect2', array(
                                                        'model' => $prospeccionrp,
                                                        'attribute' => 'preg3_sec2',
                                                        'data' => $marcas
                                                    ));
                                                    ?>
                                                    <?php echo $form->error($prospeccionrp, 'preg3_sec2'); ?>

                                                </div>
                                                <div class="col-md-3">
                                                    <div class="row"><label for="">Modelo</label></div>
                                                    <?php
                                                    $this->widget('ext.select2.ESelect2', array(
                                                        'name' => 'Cotizador[modelo]',
                                                        'id' => 'Cotizador_modelo',
                                                        'data' => array(
                                                            '' => '--Seleccione un modelo--'
                                                        ),
                                                    ));
                                                    ?>
    <!--                                                <select name="Cotizador[modelo]" id="Cotizador_modelo" class="form-control">
                                                    <option value="" selected="selected">---Seleccione un modelo---</option>
                                                    </select>-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="">Año</label>
                                                    <?php
                                                    $this->widget('ext.select2.ESelect2', array(
                                                        'name' => 'Cotizador[year]',
                                                        'id' => 'Cotizador_year',
                                                        'data' => array(
                                                            '' => '--Seleccione el año--'
                                                        ),
                                                    ));
                                                    ?>

                                                </div>
                                            </div>

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
                                        <div class="row"><label for="">Provincia Concesionario</label></div>
                                        <?php $dataprv = array(); ?>
                                        <?php
                                        $this->widget('ext.select2.ESelect2', array(
                                            'name' => 'Casos[provincia]',
                                            'id' => 'Casos_provincia',
                                            'data' => array(
                                                "1" => 'Azuay',
                                                "5" => 'Chimborazo',
                                                "7" => 'El Oro',
                                                "8" => 'Esmeraldas',
                                                "10" => 'Guayas',
                                                "11" => 'Imbabura',
                                                "12" => 'Loja',
                                                "13" => 'Los Ríos',
                                                "14" => 'Manabí',
                                                "16" => 'Napo',
                                                "18" => 'Pastaza',
                                                "19" => 'Pichincha',
                                                "21" => 'Tsachilas',
                                                "23" => 'Tungurahua'
                                            ),
                                        ));
                                        ?>

                                    </div>
                                    <div class="col-md-2">
                                        <div class="row"><label for="">Ciudad</label></div>
                                        <?php
                                        $this->widget('ext.select2.ESelect2', array(
                                            'name' => 'Casos[ciudad]',
                                            'id' => 'Casos_ciudad',
                                            'data' => array(
                                                '' => 'Seleccione'
                                            ),
                                        ));
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="row"><label for="">Concesionario</label></div>
                                        <?php
                                        $this->widget('ext.select2.ESelect2', array(
                                            'name' => 'Casos[concesionario]',
                                            'id' => 'Casos_concesionario',
                                            'data' => array(
                                                '' => 'Concesionario'
                                            ),
                                        ));
                                        ?>

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
                                            <option value="0">Ninguno</option>
                                            <option value="1">Jefe de Agencia</option>
                                            <option value="2">Plan Renova</option>
                                            <option value="3">Flotas</option>
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
                                <input name="GestionInformacion[tipo]" id="GestionInformacion_tipo" type="hidden" value="<?php
                                if (isset($_GET['tipo'])) {
                                    echo $_GET['tipo'];
                                }
                                ?>">
                                <input name="GestionInformacion[paso]" id="GestionInformacion_paso" type="hidden" value="1-2">
                                <input name="GestionDiaria[id_informacion]" id="GestionDiaria_id_informacion" type="hidden" value="<?php //echo $id_informacion;     ?>">
                                <input name="GestionDiaria[id_vehiculo]" id="GestionDiaria_id_vehiculo" type="hidden" value="<?php //echo $id_vehiculo;      ?>">
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
                                <?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Grabar', array('class' => 'btn btn-danger', 'id' => 'finalizar', 'onclick' => 'sendInfo();')); ?>
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
                <?php $this->endWidget(); ?>
            </div><!-- form -->
            <br />
                <br />
                <div class="row">
                    <div class="col-md-8  col-xs-12 links-tabs">
                        <div class="col-md-3 col-xs-4"><p>También puedes ir a:</p></div>
                        <div class="col-md-2 col-xs-4"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>
                        <div class="col-md-3 col-xs-4"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>" class="creacion-btn">RGD</a></div>
                    </div>
                </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="profile"></div>
        <div role="tabpanel" class="tab-pane" id="settings"></div>
        <div role="tabpanel" class="tab-pane" id="messages"></div>
    </div>

</div>
