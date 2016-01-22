<?= $this->renderPartial('//layouts/rgd/head');?>

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
    function validateruc(e){
        if(e.val().length < 13){
            return false;
        }else{
            if(e.val().substr(e.val().length - 3) == '001'){
                return true;
            }else{
                return false;
            }
        }
    }

    $(function () {

         $('#GestionNuevaCotizacion_ruc').change(function() {
            var resp = validateruc($(this));
            if(resp != true){
                alert('Por favor ingrese correctamente el RUC.');
            }
        });

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
                            var resp = validateruc($('#GestionNuevaCotizacion_ruc'));
                            if(resp != true){
                                alert('Por favor ingrese correctamente el RUC.');
                            }else{   
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
                            }
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
                        if($('#GestionNuevaCotizacion_identificacion').val() == 'ruc'){
                            var resp = validateruc($('#GestionNuevaCotizacion_ruc'));
                            if(resp != true){
                                alert('Por favor ingrese correctamente el RUC.');
                            }else{ 
                                form.submit();
                            }
                        }else{
                            form.submit();
                        }
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
                                    <label for="GestionNuevaCotizacion_cedula">Número 1</label>
                                    <?php echo $form->textField($model, 'cedula', array('size' => 40, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'cedula'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-ruc">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número 2</label>
                                    <?php echo $form->textField($model, 'ruc', array('size' => 40, 'maxlength' => 13, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'ruc'); ?>
                                </div>
                            </div>
                            <div class="row" id="cont-pasaporte" style="display: none;">
                                <div class="col-md-12">
                                    <label for="GestionNuevaCotizacion_cedula">Número 3</label>
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
                 <?= $this->renderPartial('//layouts/rgd/filtros', array('formaction' => 'gestionInformacion/seguimiento', 'cargo_id' => $cargo_id, 'dealer_id' => $dealer_id));?>
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
