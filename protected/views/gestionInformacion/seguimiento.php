<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/daterangepicker.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/daterangepicker.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/tooltip/css/tooltipster.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/tooltip/js/jquery.tooltipster.min.js"></script>
<?php
$identificacion = '';
if (isset($model->identificacion))
    $identificacion = $model->identificacion;
//echo '-----------identificacion: '.$identificacion;
$grupo_id = (int) Yii::app()->user->getState('grupo_id');
$id_responsable = Yii::app()->user->getId();
//$dealer_id = $this->getDealerId($id_responsable);
$dealer_id = $this->getConcesionarioDealerId($id_responsable);
//$cargo_id = Yii::app()->user->getState('cargo_id');
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
//echo 'dealer id: '.$dealer_id;
$count = count($users);
//echo 'count: '.$count;
//echo '<pre>';
//print_r($_GET);
//echo '</pre>';
?>
<script>
    $(function () {
        $('#GestionDiaria_concesionario').change(function () {
            var value = $(this).attr('value');
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getAsesores"); ?>',
                beforeSend: function (xhr) {
                    //$('#info-3').show();  // #bg_negro must be defined somewhere
                },
                type: 'POST',
                //dataType: 'json', 
                data: {dealer_id: value},
                success: function (data) {
                    //$('#info-3').hide();
                    //alert(data);
                    $('#GestionDiaria_responsable').html(data);

                }
            });
        });
        //$('#toolinfo').tooltip();
        $('#toolinfo').tooltipster({
            content: $('<p style="text-align:left;" class="tool">Prospección:  Ingreso de Base de Datos Externa o Nuevo Cliente Prospectado</p>\n\
<!--p style="text-align:left;" class="tool">Tráfico:  Ingreso de Base de Datos Externa o Nuevo Cliente</p-->\n\
<p style="text-align:left;" class="tool">Tráfico:  10 Pasos de Ventas</p>\n\
<p style="text-align:left;" class="tool">Exhibición:  Registro de Cliente, Consulta Y envío de Proforma</p>\n\
'),
            position: 'right',
            maxWidth: 500,
            theme: 'tooltipster-default '
        });
        $("#keywords").tablesorter();
        $('#fecha-range').daterangepicker(
                {
                    locale: {
                        format: 'YYYY/MM/DD'
                    }
                }
        );
        $('.range_inputs .applyBtn').click(function () {
            console.log('apply');
            $('#fecha-range').css("color", "#555555");
        });
        $('#GestionNuevaCotizacion_identificacion').change(function () {
            var value = $(this).attr('value');
            switch (value) {
                case 'ci':
                    $('#cont-doc').show();
                    $('#cont-ruc').hide();
                    $('#cont-pasaporte').hide();
                    break
                case 'ruc':
                    $('#cont-doc').hide();
                    $('#cont-ruc').show();
                    $('#cont-pasaporte').hide();
                    break
                case 'pasaporte':
                    $('#cont-doc').hide();
                    $('#cont-ruc').hide();
                    $('#cont-pasaporte').show();
                    break
            }
        })
        $('#GestionNuevaCotizacion_tipo').change(function(){
            var value = $(this).attr('value');
            if(value == 'Flota'){
                $('.empresa-cont').show();
            }else{
                $('.empresa-cont').hide();
            }
        });
//        $('#GestionNuevaCotizacion_fuente').change(function (){
//            var valuenc = $(this).attr('value');
//            if(valuenc == 'exhibicion')
//                $('.exh-cont').show();
//            else
//                $('.exh-cont').hide();
//        });

        $('#GestionDiaria_grupo').change(function () {
            var valuenc = $(this).attr('value');
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getConcesionarios"); ?>',
                beforeSend: function (xhr) {
                    //$('#info-3').show();  // #bg_negro must be defined somewhere
                },
                type: 'POST',
                //dataType: 'json', 
                data: {id: valuenc},
                success: function (data) {
                    //$('#info-3').hide();
                    //alert(data);
                    $('#GestionDiaria_concesionario').html(data);

                }
            });
        });
    });
    function send() {
        var fuente = $('#GestionNuevaCotizacion_fuente').val();
        switch (fuente) {
            case 'showroom':
            case 'exhibicion':
                $('#gestion-nueva-cotizacion-form').validate({
                    rules: {
                        'GestionNuevaCotizacion[cedula]': {required: true},
                        'GestionNuevaCotizacion[fuente]': {required: true},
                        'GestionNuevaCotizacion[tipo]': {required: true},
                        'GestionNuevaCotizacion[identificacion]': {required: true}
                    },
                    messages: {
                        'GestionNuevaCotizacion[cedula]': {
                            required: 'Ingrese la cédula'
                        }, 'GestionNuevaCotizacion[fuente]': {required: 'Seleccione fuente'},
                        'GestionNuevaCotizacion[identificacion]': {required: 'Seleccione identificación'}
                    },
                    submitHandler: function (form) {
                        var identificacion = $('#GestionNuevaCotizacion_identificacion').val();
                        var cedula = $('#GestionNuevaCotizacion_cedula').val();
                        var fuente = $('#GestionNuevaCotizacion_fuente').val();
                        if (identificacion == 'ci') {
                            $.ajax({
                                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getCedula"); ?>',
                                beforeSend: function (xhr) {
                                    $('#bg_negro').show();  // #bg_negro must be defined somewhere
                                },
                                type: 'POST', dataType: 'json', data: {id: cedula, fuente: fuente},
                                success: function (data) {
                                    //alert(data);
                                    $('#bg_negro').hide();
                                    if (data.result == true) {
                                        $('.cont-existente').html(data.data);
                                    }
                                    if (data.flagttga35 == true) {
                                        //alert('enter ttga35');
                                        $('.cont-createc').html(data.datattga35);
                                    }
                                    if (data.flagttga36 == true) {
                                        $('.cont-createc-tg36').html(data.datattga36);
                                    }
                                    if (data.flagvh01 == true) {
                                        $('.cont-createc-vh01').html(data.datavh01);
                                    }
                                    if(data.id_informacion != 0){
                                        var dt = '<a href="https://www.kia.com.ec/intranet/usuario/index.php/gestionInformacion/update/'+data.id_informacion+'?tipo=gestion" class="btn btn-danger">Continuar</a>';
                                        $('.cont-createc-but').html(dt);
                                    }
                                    else {
                                        form.submit();
                                    }
                                },
                                error: function (error) {
                                    form.submit();
                                }
                            });
                        } else if (identificacion == 'ruc') {
                            var ruc = $('#GestionNuevaCotizacion_ruc').val();
                            $.ajax({
                                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getRuc"); ?>',
                                beforeSend: function (xhr) {
                                    $('#bg_negro').show();  // #bg_negro must be defined somewhere
                                },
                                type: 'POST', dataType: 'json', data: {id: ruc, fuente: fuente},
                                success: function (data) {
                                    //alert(data.flagttga35);
                                    $('#bg_negro').hide();
                                    if (data.result == true) {
                                        $('.cont-existente').html(data.data);
                                    }
                                    if (data.flagttga35 == true) {
                                        //alert('enter ttga35');
                                        $('.cont-createc').html(data.datattga35);
                                    }
                                    if (data.flagttga36 == true) {
                                        $('.cont-createc-tg36').html(data.datattga36);
                                    }
                                    if (data.flagvh01 == true) {
                                        $('.cont-createc-vh01').html(data.datavh01);
                                    }if(data.id_informacion != 0 && data.result != false){
                                        var dt = '<a href="https://www.kia.com.ec/intranet/usuario/index.php/gestionInformacion/update/'+data.id_informacion+'?tipo=gestion" class="btn btn-danger">Continuar</a>';
                                        $('.cont-createc-but').html(dt);
                                    }
                                    else {
                                        form.submit();
                                    }
                                },
                                error: function (error) {
                                    form.submit();
                                }
                            });
                        } else if (identificacion == 'pasaporte'){
                            var pasaporte = $('#GestionNuevaCotizacion_pasaporte').val();
                            $.ajax({
                                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getPasaporte"); ?>',
                                beforeSend: function (xhr) {
                                    $('#bg_negro').show();  // #bg_negro must be defined somewhere
                                },
                                type: 'POST', dataType: 'json', data: {id: pasaporte, fuente: fuente},
                                success: function (data) {
                                    $('#bg_negro').hide();
                                    if (data.result == true) {
                                        $('.cont-existente').html(data.data);
                                    } else {
                                        form.submit();
                                    }
                                },
                                error: function (error) {
                                    form.submit();
                                }
                            });
                        }

                    }
                });
                break;
            case 'web':
                $('#gestion-nueva-cotizacion-form').validate({
                    rules: {
                        //'GestionNuevaCotizacion[cedula]': {required: true},
                        'GestionNuevaCotizacion[fuente]': {required: true},
                        'GestionNuevaCotizacion[identificacion]': {required: true}
                    },
                    messages: {
                        //'GestionNuevaCotizacion[cedula]': {required: 'Ingrese la cédula'},
                        'GestionNuevaCotizacion[fuente]': {required: 'Seleccione fuente'},
                        'GestionNuevaCotizacion[identificacion]': {required: 'Seleccione identificación'}
                    },
                    submitHandler: function (form) {
                        var identificacion = $('#GestionNuevaCotizacion_identificacion').val();
                        var cedula = $('#GestionNuevaCotizacion_cedula').val();
                        var fuente = $('#GestionNuevaCotizacion_fuente').val();
                        if (identificacion == 'ci') {
                            $.ajax({
                                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getCedula"); ?>',
                                beforeSend: function (xhr) {
                                    $('#bg_negro').show();  // #bg_negro must be defined somewhere
                                },
                                type: 'POST', dataType: 'json', data: {id: cedula, fuente: fuente},
                                success: function (data) {
                                    $('#bg_negro').hide();
                                    if (data.result == true) {
                                        $('.cont-existente').html(data.data);
                                    } else {
                                        form.submit();
                                    }
                                }
                            });
                        } else if (identificacion == 'pasaporte') {
                            form.submit();
                        } else {
                            form.submit();
                        }

                    }
                });
                break;    
            case 'exonerados':
                $('#gestion-nueva-cotizacion-form').validate({
                    rules: {
                        'GestionNuevaCotizacion[cedula]': {required: true},
                        'GestionNuevaCotizacion[tipo]': {required: true},
                        'GestionNuevaCotizacion[motivo_exonerados]': {required: true}
                    },
                    messages: {
                        'GestionNuevaCotizacion[cedula]': {
                            required: 'Ingrese la cédula'
                        },
                        'GestionNuevaCotizacion[motivo_exonerados]': {
                            required: 'Seleccione un motivo'
                        }
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });
                break;
            case 'prospeccion':
            case 'trafico':
                $('#gestion-nueva-cotizacion-form').validate({
                    rules: {
                        'GestionNuevaCotizacion[tipo]': {required: true}
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });
                break;
            case '':
                $('#gestion-nueva-cotizacion-form').validate({
                    rules: {
                        'GestionNuevaCotizacion[cedula]': {required: true},
                        'GestionNuevaCotizacion[fuente]': {required: true},
                        'GestionNuevaCotizacion[tipo]': {required: true},
                        'GestionNuevaCotizacion[identificacion]': {required: true}
                    },
                    messages: {
                        'GestionNuevaCotizacion[cedula]': {
                            required: 'Ingrese la cédula'
                        }, 'GestionNuevaCotizacion[fuente]': {required: 'Seleccione fuente'},
                        'GestionNuevaCotizacion[identificacion]': {required: 'Seleccione identificación'}
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });
                break;
            default:
        }

    }
</script>
<style type="text/css">
    .daterangepicker .ranges, .daterangepicker .calendar {
        float: left !important;
    }
    #fecha-range{color: #DCD8D9;}
    #toolinfo{position: absolute;right: -20px;top: 24px;}
    .tool{font-size: 11px;margin: 1px 0;}
    @media (min-width: 992px){
        .container {
            max-width: 1170px;
        }
        em{margin-bottom: 3px;display: block;}
    }
    
</style>
<?php $this->widget('application.components.Notificaciones'); ?>
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Sistema de Gestión Comercial</h1>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="highlight">
                <h4>Registro de cliente</h4>
                <div class="form">
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'gestion-nueva-cotizacion-form',
                        'action' => Yii::app()->createUrl('gestionNuevaCotizacion/create'),
                        'enableAjaxValidation' => false,
                        'htmlOptions' => array(
                            'onsubmit' => "return false;", /* Disable normal form submit */
                            'onkeypress' => " if(event.keyCode == 13){ send(); } ", /* Do ajax call when user presses enter key */
                            'class' => 'form-horizontal form-search',
                            'autocomplete' => 'off'
                        ),
                    ));
                    ?>
                    <div class="row">
                        <div class="col-md-12" style="position: relative;">
                            <?php echo $form->labelEx($model, 'fuente'); ?>
                            <?php
                            echo $form->dropDownList($model, 'fuente', array(
                                '' => '--Seleccione--',
                                'prospeccion' => 'Prospección',
                                //'trafico' => 'Tráfico',
                                'showroom' => 'Tráfico',
                                'exhibicion' => 'Exhibición',
                                //'web' => 'Web',
                                //'exonerados' => 'Exonerados'
                                    ), array('class' => 'form-control'));
                            ?>
                            <?php echo $form->error($model, 'fuente'); ?>
                            <button type="button" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="Info" id="toolinfo">Info</button>
                        </div>
                    </div>
                    <div class="row tipo-cont">
                        <div class="col-md-12">
                            <label for="GestionNuevaCotizacion_fuente">Tipo</label>
                            <select name="GestionNuevaCotizacion[tipo]" id="GestionNuevaCotizacion_tipo" class="form-control">
                                <option value="">--Seleccione--</option>
                                <option value="Nuevo">Nuevo</option>
                                <option value="Usado">Usado</option>
                                <option value="Exonerado Taxi">Exonerado Taxi</option>
                                <option value="Exonerado Conadis">Exonerado Conadis</option>
                                <option value="Exonerado Diplomatico">Exonerado Diplomático</option>
                                <option value="Flota">Flota</option>
                            </select>
                        </div>
                    </div>
                    <div class="row empresa-cont" style="display: none;">
                        <div class="col-md-12">
                            <label for="GestionNuevaCotizacion_fuente">Empresa</label>
                            <input size="40" maxlength="20" class="form-control" name="GestionNuevaCotizacion[empresa]" id="GestionNuevaCotizacion_empresa" type="text">

                        </div>
                    </div>
                    <div class="row exh-cont" style="display: none;">
                        <div class="col-md-12">
                            <label for="GestionNuevaCotizacion_fuente">Lugar</label>
                            <input size="40" maxlength="80" class="form-control" name="GestionNuevaCotizacion[lugar]" id="GestionNuevaCotizacion_lugar" type="text">
                        </div>
                    </div>
                    <div class="row otro-cont" style="display: none;">
                        <div class="col-md-12">
                            <label for="GestionNuevaCotizacion_fuente">Otro</label>
                            <input size="40" maxlength="20" class="form-control" name="GestionNuevaCotizacion[otro]" id="GestionNuevaCotizacion_otro" type="text">
                        </div>
                    </div>
                    <div class="row motivo-cont" style="display:none;">
                        <div class="col-sm-12">
                            <?php echo $form->labelEx($model, 'motivo_exonerados'); ?>
                            <?php
                            echo $form->dropDownList($model, 'motivo_exonerados', array('' => '--Escoja un motivo--',
                                'diplomáticos' => 'Vehículos Diplomáticos',
                                'renova' => 'Plan Renova',
                                'discapacitados' => 'Personas Capacidades Diferentes'
                                    ), array('class' => 'form-control'));
                            ?>
                            <?php echo $form->error($model, 'motivo_exonerados'); ?>
                        </div>
                    </div>

                    <?php if ($identificacion == 'ci'): ?>
                        <div id="cont-ident">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="GestionNuevaCotizacion_identificacion">Identificación</label>
                                    <?php echo $form->dropDownList($model, 'identificacion', array('' => '--Seleccione--', 'ci' => 'Cédula', 'ruc' => 'RUC', 'pasaporte' => 'Pasaporte'), array('class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'identificacion'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-doc">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'cedula', array('size' => 40, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'cedula'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-ruc" style="display: none;">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'ruc', array('size' => 40, 'maxlength' => 13, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'ruc'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-pasaporte" style="display: none;">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'pasaporte', array('size' => 40, 'maxlength' => 40, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'pasaporte'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($identificacion == 'ruc'): ?>
                        <div id="cont-ident">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="GestionNuevaCotizacion_identificacion">Identificación</label>
                                    <?php echo $form->dropDownList($model, 'identificacion', array('' => '--Seleccione--', 'ci' => 'Cédula', 'ruc' => 'RUC', 'pasaporte' => 'Pasaporte'), array('class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'identificacion'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-doc" style="display: none;">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'cedula', array('size' => 40, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'cedula'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-ruc">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'ruc', array('size' => 40, 'maxlength' => 13, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'ruc'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-pasaporte" style="display: none;">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'pasaporte', array('size' => 40, 'maxlength' => 40, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'pasaporte'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($identificacion == 'pasaporte'): ?>
                        <div id="cont-ident">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="GestionNuevaCotizacion_identificacion">Identificación</label>
                                    <?php echo $form->dropDownList($model, 'identificacion', array('' => '--Seleccione--', 'ci' => 'Cédula', 'ruc' => 'RUC', 'pasaporte' => 'Pasaporte'), array('class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'identificacion'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-doc" style="display: none;">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'cedula', array('size' => 40, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'cedula'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-ruc" style="display: none;">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'ruc', array('size' => 40, 'maxlength' => 13, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'ruc'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-pasaporte">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'pasaporte', array('size' => 40, 'maxlength' => 40, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'pasaporte'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($identificacion == ''): ?>
                        <div id="cont-ident">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="GestionNuevaCotizacion_identificacion">Identificación</label>
                                    <?php echo $form->dropDownList($model, 'identificacion', array('' => '--Seleccione--', 'ci' => 'Cédula', 'ruc' => 'RUC', 'pasaporte' => 'Pasaporte'), array('class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'identificacion'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-doc">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'cedula', array('size' => 40, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'cedula'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-ruc" style="display: none;">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'ruc', array('size' => 40, 'maxlength' => 13, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'ruc'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-pasaporte" style="display: none;">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número</label>
                                    <?php echo $form->textField($model, 'pasaporte', array('size' => 40, 'maxlength' => 40, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'pasaporte'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row buttons">
                        <div class="col-md-12">
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Save', array('class' => 'btn btn-danger', 'onclick' => 'send();')); ?>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div><!-- form -->
            </div>
        </div>
        <div class="col-md-8">
            <div class="highlight">
                <div class="form">
                    <h4>Búsqueda:</h4>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'casos-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('gestionInformacion/seguimiento'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array(
                            'class' => 'form-horizontal form-search'
                        ),
                    ));
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="GestionDiariafecha">Búsqueda General</label>
                            <input type="text" name="GestionDiaria[general]" id="GestionDiaria_general" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label for="">Categorización</label>
                            <select name="GestionDiaria[categorizacion]" id="" class="form-control">
                                <option value="">--Seleccione categorización--</option>
                                <option value="Hot A (hasta 7 dias)">Hot A(hasta 7 dias)</option>
                                <option value="Hot B (hasta 15 dias)">Hot B(hasta 15 dias)</option>
                                <option value="Hot C (hasta 30 dias)">Hot C(hasta 30 dias)</option>
                                <option value="Warm (hasta 3 meses)">Warm(hasta 3 meses)</option>
                                <option value="Cold (hasta 6 meses)">Warm(hasta 6 meses)</option>
                                <option value="Very Cold(mas de 6 meses)">Very Cold(mas de 6 meses)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="GestionNuevaCotizacion_fuente">Status</label>
                            <select type="text" id="" name="GestionDiaria[status]" class="form-control">
                                <option value="">--Seleccione status--</option>
                                <option value="Cierre">Cierre</option>
                                <option value="Desiste">Desiste</option>
                                <option value="Entrega">Entrega</option>
                                <option value="PrimeraVisita">Primera Visita</option>
                                <option value="Seguimiento">Seguimiento</option>
                                <option value="SeguimientoEntrega">Seguimiento Entrega</option>
                            </select>
                        </div>
                        <?php if($cargo_id == 70): ?>
                        <?php  
                        // BUSQUEDA DE RESPONSABLE DE VENTAS CARGO ID 17 Y EL DEALER ID -> concesionarioid
                        $mod = new GestionDiaria;
                        $cre = new CDbCriteria();
                        $cre->condition = " cargo_id =71 AND dealers_id = {$dealer_id} ";
                        $cre->order = " nombres ASC";
                        $usu = CHtml::listData(Usuarios::model()->findAll($cre), "id", "fullname");
                        ?>
                        <div class="col-md-6">
                            <label for="">Responsable</label>
                            <?php echo $form->dropDownList($mod, 'responsable', $usu, array('class' => 'form-control', 'empty' => 'Seleccione un responsable')); ?>
                            
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Fecha</label>
                            <input type="text" name="GestionDiaria[fecha]" id="fecha-range" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label for="">Tipo</label>
                            <select name="GestionDiaria[tipo_fecha]" id="GestionDiaria_tipo_fecha" class="form-control">
                                <option value="">--Seleccione tipo--</option>
                                <option value="proximoseguimiento">Próximo seguimiento</option>
                                <option value="fechsregistro">Fecha de registro</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Fuente</label>
                            <select name="GestionDiaria[fuente]" id="GestionDiaria_fuente" class="form-control">
                                <option value="">--Seleccione fuente--</option>
                                <option value="showroom">Showroom</option>
                                <!--<option value="prospeccion">Prospección</option>-->
                                <option value="trafico">Tráfico</option>
                                <option value="exhibicion">Exhibición</option>
                                <option value="exonerados">Exonerados</option>
                                <option value="web">Web</option>
                            </select>
                        </div>

                    </div>
                    
                    <?php if($cargo_id == 69 || $cargo_id == 46 ): ?>
                    <hr />
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Grupo</label>
                            <?php
//                            $criteria = new CDbCriteria(array(
//                                'order' => 'nombre_grupo'
//                            ));
//                            $grupos = CHtml::listData(Grupo::model()->findAll($criteria), "id", "nombre_grupo");
                            ?>
                            <select name="GestionDiaria[grupo]" id="GestionDiaria_grupo" class="form-control">
                                <option value="">--Seleccione grupo--</option>
                                <option value="1">AEKIA S.A.</option>
                                <option value="6">AUTHESA</option>
                                <option value="7">AUTOSCOREA</option>
                                <option value="2">GRUPO ASIAUTO</option>
                                <option value="5">GRUPO EMPROMOTOR</option>
                                <option value="3">GRUPO KMOTOR</option>
                                <option value="8">GRUPO MERQUIAUTO</option>
                                <option value="9">GRUPO MOTRICENTRO</option>
                                <option value="4">IOKARS</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Concesionario</label>
                            <select name="GestionDiaria[concesionario]" id="GestionDiaria_concesionario" class="form-control">
                                <option value="">--Seleccione concesionario--</option>
                                <option value="0">AEKIA S.A.</option>
                                <option value="60">ASIAUTO CONDADO</option>
                                <option value="7">ASIAUTO CUMBAYA</option>
                                <option value="6">ASIAUTO SUR</option>
                                <option value="2">ASIAUTO ORELLANA'</option>
                                <option value="76">ASIAUTO LOS CHILLOS</option>
                                <option value="5">ASIAUTO MDJ</option>
                                <option value="62">ASIAUTO 6 DIC</option>
                                <option value="63">ASIAUTO LATACUNGA</option>
                                <option value="20">ASIAUTO MANTA</option>
                                <option value="65">ASIAUTO PORTOVIEJO</option>
                                <option value="38">ASIAUTO RIOBAMBA</option>
                                <option value="72">KMOTOR ORELLANA</option>
                                <option value="77">KMOTOR SUR</option>
                                <option value="81">KMOTOR MILAGRO</option>
                                <option value="10">KMOTOR AMERICA</option>
                                <option value="80">KMOTOR MACHALA</option>
                                <option value="78">IOKARS</option>
                                <option value="22">EMPROMOTOR CENTRO</option>
                                <option value="68">EMPROMOTOR DOS</option>
                                <option value="73">EMPROMOTOR ESMERALDAS</option>
                                <option value="19">AUTHESA</option>
                                <option value="14">AUTOSCOREA</option>
                                <option value="59">MERQUIAUTO PUYO</option>
                                <option value="74">MERQUIAUTO QUEVEDO</option>
                                <option value="79">MERQUIAUTO TENA</option>
                                <option value="70">MOTRICENTRO LOJA</option>
                                <option value="12">MOTRICENTRO CUE</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-6">
                                <label for="">Responsable</label>
                                    <select name="GestionDiaria[responsable]" id="GestionDiaria_responsable" class="form-control">
                                        <option value="">--Seleccione responsable--</option>
                                </select>
                        </div>    
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Provincia</label>
                            <select name="GestionDiaria[provincia]" id="GestionDiaria_provincia" class="form-control">
                                <option value="">---Seleccione una provincia---</option>
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
                    </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
                        </div>
                        
                    </div>
                    <?php $this->endWidget(); ?>
                    <div class="row">
                         <?php if($cargo_id == 70 || $cargo_id == 46): ?>
                        <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'casos-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('gestionInformacion/seguimientoexcel'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array(
                            'class' => 'form-horizontal form-search'
                        ),
                    ));
                    ?>
                    <div class="col-md-6">
                        <input type="submit" name="" id="" value="Descargar Excel" class="btn btn-warning"/>
                        <input type="hidden" value="<?php print_r($_GET); ?>" name="Seguimiento[search]" />
                        <input type="hidden" name="GestionDiaria2[general]" id="GestionDiaria2_general" value="<?php echo isset($_GET["GestionDiaria"]['general']) ? $_GET["GestionDiaria"]['general'] : "" ?>"/>
                        <input type="hidden" name="GestionDiaria2[categorizacion]" id="" value="<?php echo isset($_GET["GestionDiaria"]['categorizacion']) ? $_GET["GestionDiaria"]['categorizacion'] : "" ?>">
                        <input type="hidden" name="GestionDiaria2[status]" id="" value="<?php echo isset($_GET["GestionDiaria"]['status']) ? $_GET["GestionDiaria"]['status'] : "" ?>">
                        <input type="hidden" name="GestionDiaria2[responsable]" id="" value="<?php echo isset($_GET["GestionDiaria"]['responsable']) ? $_GET["GestionDiaria"]['responsable'] : "" ?>">
                        <input type="hidden" name="GestionDiaria2[fecha]" id="" value="<?php echo isset($_GET["GestionDiaria"]['fecha']) ? $_GET["GestionDiaria"]['fecha'] : "" ?>">
                        <input type="hidden" name="GestionDiaria2[fuente]" id="" value="<?php echo isset($_GET["GestionDiaria"]['fuente']) ? $_GET["GestionDiaria"]['fuente'] : "" ?>">
                        <input type="hidden" name="GestionDiaria2[grupo]" id="" value="<?php echo isset($_GET["GestionDiaria"]['grupo']) ? $_GET["GestionDiaria"]['grupo'] : "" ?>">
                        <input type="hidden" name="GestionDiaria2[concesionario]" id="" value="<?php echo isset($_GET["GestionDiaria"]['concesionario']) ? $_GET["GestionDiaria"]['concesionario'] : "" ?>">
                        <input type="hidden" name="GestionDiaria2[provincia]" id="" value="<?php echo isset($_GET["GestionDiaria"]['provincia']) ? $_GET["GestionDiaria"]['provincia'] : "" ?>">
                    </div>
                    <?php $this->endWidget(); ?>
                    <?php endif; ?>   
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cont-existente">

    </div>
    <div class="cont-createc">
        
    </div>
    <div class="cont-createc-tg36">

    </div>
    <div class="cont-createc-vh01"></div>
    <div class="cont-createc-but"></div>
    
    <div class="row">
        <h1 class="tl_seccion">RGD</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">

                <table class="table tablesorter table-striped" id="keywords">
                    <thead>
                        <tr>
                            <th><span>Status</span></th>
                            <th><span>ID</span></th>
                            <th><span>Nombres</span></th>
                            <th><span>Apellidos</span></th>
                            <th><span>Identificación</span></th>
                            <th><span>Próximo Seguimiento</span></th>
                            <th><span>Responsable</span></th>
                            <th><span>Email</span></th>
                            <th><span>Categorización</span></th>
                            <th><span>Expiración de Categorización</span></th>
                            <th><span>Fuente</span></th>
                            <th><span>Edición</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $c): ?>
                            <tr>
                                <td>
                                    <?php
                                    //echo $this->getStatus($c['status']);
                                    $status = '';
                                    $paso = '';
                                    $url = '';
                                    if ($c['prospeccion'] != 0) {
                                        $status = 'Prospección';
                                    }
                                    if ($c['primera_visita'] != 0) {
                                        $status = 'Primera Visita';
                                    }
                                    if ($c['seguimiento'] != 0) {
                                        $status = 'Seguimiento';
                                    }
                                    if ($c['cierre'] != 0) {
                                        $status = 'Cierre';
                                    }
                                    if ($c['entrega'] != 0) {
                                        $status = 'Entrega';
                                    }
                                    if ($c['seguimiento_entrega'] != 0) {
                                        $status = 'Seguimiento Entrega';
                                    }
                                    if ($c['desiste'] != 0) {
                                        $status = 'Desiste';
                                    }
                                    $criteria = new CDbCriteria(array(
                                        'condition' => "id_informacion='{$c['id_info']}'"
                                    ));
                                    $vec = GestionVehiculo::model()->findAll($criteria);
                                    $count = count($vec);

                                    $criteria = new CDbCriteria(array(
                                        'condition' => "id_informacion='{$c['id_info']}'"
                                    ));
                                    $td = GestionTestDrive::model()->findAll($criteria);
                                    $countt = count($td);
                                    //echo 'count vec: '.$count.', count test drive: '.$countt;
                                    if ($status == 'Prospección' && $count > 0) {
                                        $paso = '1/2';
                                        //$url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $c['id_info']));
                                    }
                                    if ($count > 0) {
                                        $paso = '3/4';
                                        //$url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $c['id_info']));
                                    }
                                    if ($count == 0 && $countt == 0):
                                        $paso = '3/4';
                                    //$url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $c['id_info']));
                                    endif;
                                    if ($count > 0 && $countt > 0):
                                        $paso = '5/6';
                                    //$url = Yii::app()->createUrl('site/presentacion', array('id' => $c['id_info']));
                                    endif;
                                    if (($count > 0 && $countt > 0) && ($count == $countt)):
                                        $paso = '7';
                                    //$url = Yii::app()->createUrl('site/financiamiento', array('id' => $c['id_info']));
                                    endif;

                                    switch ($c['paso']) {
                                        case '1-2':
                                            $url = Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id_info'], 'tipo' => 'prospeccion'));
                                            if($c['fuente'] == 'prospeccion')
                                               $url = Yii::app()->createUrl('site/consulta', array('id_informacion' => $c['id_info'], 'tipo' => 'gestion', 'fuente' => 'prospeccion')); 
                                            break;
                                        case '3':
                                            $url = Yii::app()->createUrl('site/consulta', array('id_informacion' => $c['id_info'], 'tipo' => 'gestion', 'fuente' => 'web'));
                                            break;
                                        case '4':
                                            $url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $c['id_info'], 'tipo' => 'gestion', 'fuente' => 'web'));
                                            break;
                                        case '5':
                                            $url = Yii::app()->createUrl('site/presentacion', array('id' => $c['id_info']));
                                            break;
                                        case '6':
                                            $url = Yii::app()->createUrl('site/demostracion', array('id' => $c['id_info']));
                                            break;
                                        case '7':
                                            $url = Yii::app()->createUrl('site/negociacion', array('id' => $c['id_info']));
                                            break;
                                        case '8':
                                            $url = Yii::app()->createUrl('site/cierre', array('id' => $c['id_info']));
                                            break;
                                        case '9':
                                            $url = Yii::app()->createUrl('site/cierre', array('id' => $c['id_info']));
                                            break;
                                        default:
                                            break;
                                    }
                                    ?>
                                    <!--<button type="button" class="btn btn-xs btn-primary"><?php //echo $status;  ?></button>-->
                                    <button type="button" class="btn btn-xs btn-success"><?php echo $c['paso']; ?></button>
                                    <?php
                                    if ($c['medio_contacto'] == 'web' && $c['tipo_form_web'] == ''):
                                        ?>
                                        <button type="button" class="btn btn-xs btn-warning">www</button>
                                    <?php endif; ?>
                                    <?php
                                    if ($c['tipo_form_web'] == 'exonerado'):
                                        ?>
                                        <button type="button" class="btn btn-xs btn-warning">VE</button>
                                    <?php endif; ?>
                                    <?php
                                    $credito = $this->getStatusSolicitudAll($c['id_info']);
                                    if ($credito == true) {
                                        echo '<button type="button" class="btn btn-xs btn-success">C</button>';
                                    } else {
                                        echo '<button type="button" class="btn btn-xs btn-default">C</button>';
                                    }
                                    ?>
                                    <?php 
                                    if($c['bdc'] == 1){
                                        echo '<button type="button" class="btn btn-xs btn-success">BDC</button>'; 
                                    }
                                    ?>
                                    <?php 
                                    if($c['desiste'] == 1){
                                        echo '<button type="button" class="btn btn-xs btn-success">Desiste</button>'; 
                                    }
                                    ?>
                                </td>
                                <td><?php echo $c['id_info']; ?> </td>
                                <td><?php echo ucfirst($c['nombres']); ?> </td>
                                <td><?php echo ucfirst($c['apellidos']); ?> </td>
                                <td><?php 
                                 
                                if($c['cedula'] != ''){
                                   echo $c['cedula']; 
                                }
                                if($c['pasaporte'] != ''){
                                   echo $c['pasaporte'];
                                }
                                if($c['ruc'] != ''){
                                   echo $c['ruc']; 
                                }
                                ?> 
                                </td>
                                <td><?php echo $c['proximo_seguimiento']; ?></td>
                                <td><?php echo $this->getResponsable($c['resp']); ?></td>
                                <td><?php echo $c['email']; ?> </td>
                                <td><?php echo $c['categorizacion']; ?> </td>
                                <td> 
                                    <?php
                                    $dias;
                                    switch ($c['categorizacion']) {
                                        case 'Hot A (hasta 7 dias)':
                                            $dias = 7;
                                            $fechas = explode(' ', $c['fecha']);
                                            $fecha_expiracion = strtotime('+' . $dias . ' day', strtotime($fechas[0]));
                                            $fecha_expiracion = date('Y-m-d', $fecha_expiracion);
                                            $fecha_actual = strftime("%Y-%m-%d", time());
                                            $days = (strtotime($fecha_actual) - strtotime($fecha_expiracion)) / 86400;
                                            $days = abs($days);
                                            $days = floor($days);
                                            echo $days;
                                            //echo 'fecha actual: '.$fecha_actual.'<br>'.$fecha_posterior;
                                            break;
                                        case 'Hot B (hasta 15 dias)':
                                            $dias = 15;
                                            $fechas = explode(' ', $c['fecha']);
                                            $fecha_expiracion = strtotime('+' . $dias . ' day', strtotime($fechas[0]));
                                            $fecha_expiracion = date('Y-m-d', $fecha_expiracion);
                                            $fecha_actual = strftime("%Y-%m-%d", time());
                                            $days = (strtotime($fecha_actual) - strtotime($fecha_expiracion)) / 86400;
                                            $days = abs($days);
                                            $days = floor($days);
                                            echo $days;
                                            break;
                                        case 'Hot C(hasta 30 dias)':
                                        case 'Hot C (hasta 30 dias)':
                                            $dias = 30;
                                            $fechas = explode(' ', $c['fecha']);
                                            $fecha_expiracion = strtotime('+' . $dias . ' day', strtotime($fechas[0]));
                                            $fecha_expiracion = date('Y-m-d', $fecha_expiracion);
                                            $fecha_actual = strftime("%Y-%m-%d", time());
                                            $days = (strtotime($fecha_actual) - strtotime($fecha_expiracion)) / 86400;
                                            $days = abs($days);
                                            $days = floor($days);
                                            echo $days;
                                            //echo 'fecha actual: '.$fecha_actual.'<br>'.$fecha_posterior;
                                            break;
                                        case 'Warm (hasta 3 meses)':
                                            $dias = 60;
                                            $fechas = explode(' ', $c['fecha']);
                                            $fecha_expiracion = strtotime('+' . $dias . ' day', strtotime($fechas[0]));
                                            $fecha_expiracion = date('Y-m-d', $fecha_expiracion);
                                            $fecha_actual = strftime("%Y-%m-%d", time());
                                            $days = (strtotime($fecha_actual) - strtotime($fecha_expiracion)) / 86400;
                                            $days = abs($days);
                                            $days = floor($days);
                                            echo $days;
                                            break;
                                        case 'Cold (hasta 6 meses)':
                                            $dias = 180;
                                            $fechas = explode(' ', $c['fecha']);
                                            $fecha_expiracion = strtotime('+' . $dias . ' day', strtotime($fechas[0]));
                                            $fecha_expiracion = date('Y-m-d', $fecha_expiracion);
                                            $fecha_actual = strftime("%Y-%m-%d", time());
                                            $days = (strtotime($fecha_actual) - strtotime($fecha_expiracion)) / 86400;
                                            $days = abs($days);
                                            $days = floor($days);
                                            echo $days;
                                            //echo 'fecha actual: '.$fecha_actual.'<br>'.$fecha_posterior;
                                            break;
                                        case 'Very Cold(mas de 6 meses)':
                                            $dias = 181;
                                            $fechas = explode(' ', $c['fecha']);
                                            $fecha_expiracion = strtotime('+' . $dias . ' day', strtotime($fechas[0]));
                                            $fecha_expiracion = date('Y-m-d', $fecha_expiracion);
                                            $fecha_actual = strftime("%Y-%m-%d", time());
                                            $days = (strtotime($fecha_actual) - strtotime($fecha_expiracion)) / 86400;
                                            $days = abs($days);
                                            $days = floor($days);
                                            echo $days;
                                            break;
                                        default:
                                            break;
                                    }
                                    ?> 
                                </td>
                                <td> 
                                <?php if($c['fuente'] == 'showroom'){ echo 'Tráfico'; }
                                    else{ echo $c['fuente']; } ?> 
                                </td>
                                <td>
                                    <?php if($c['bdc'] == 0){ ?>
                                        <a href="<?php echo Yii::app()->createUrl('gestionDiaria/create', array('id' => $c['id_info'], 'paso' => $c['paso'], 'id_gt' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a><em></em>
                                        <?php if (($c['status'] == 1 || $c['status'] == 4)&& $c['desiste'] != 1): ?>
                                            <?php if ($c['paso'] == '1-2' && $c['fuente'] == 'showroom') { ?>
                                                <a href="<?php echo Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id_info'], 'tipo' => 'prospeccion')); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>    
                                            <?php } else { ?>
                                                <?php if($cargo_id != 70){ ?> 
                                                <a href="<?php echo $url; ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php endif; ?>
                                        <?php if ($c['status'] == 3) { ?>
                                                <a href="<?php echo Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id_info'], 'tipo' => 'prospeccion')); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>    
                                        <?php } ?>
                                    <?php } ?>            
                                </td>
                            </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
