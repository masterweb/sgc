<?php $this->widget('application.components.Notificaciones'); ?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/daterangepicker.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/daterangepicker.css" type="text/css" />
<script>
    $(function () {
        $("#keywords").tablesorter();
        $('#fecha-range').daterangepicker(
                {
                    locale: {
                        format: 'YYYY/MM/DD'
                    }
                }
        );
    });
    function send() {
        var fuente = $('#GestionNuevaCotizacion_fuente').val();
        if (fuente == 'showroom' || fuente == 'exhibicion' || fuente == 'redes-sociales' || fuente == 'referido') {
            //console.log('enter no exonerados');
            $('#gestion-nueva-cotizacion-form').validate({
                rules: {
                    'GestionNuevaCotizacion[cedula]': {required: true}
                },
                messages: {
                    'GestionNuevaCotizacion[cedula]': {
                        required: 'Ingrese la cédula'
                    }
                },
                submitHandler: function (form) {
                    var cedula = $('#GestionNuevaCotizacion_cedula').val();
                    var fuente = $('#GestionNuevaCotizacion_fuente').val();

                    $.ajax({
                        url: '<?php echo Yii::app()->createAbsoluteUrl("site/getCedula"); ?>',
                        beforeSend: function (xhr) {
                            $('#info3').show();  // #info must be defined somehwere
                        },
                        type: 'POST', dataType: 'json', data: {id: cedula, fuente: fuente},
                        success: function (data) {
                            if (data.result == true) {
                                $('.cont-existente').html(data.data);
                            } else {
                                form.submit();
                            }
                        }
                    });

                }
            });
        } else if (fuente == 'exonerados') {
            //console.log('enter exonerados');
            $('#gestion-nueva-cotizacion-form').validate({
                rules: {
                    'GestionNuevaCotizacion[cedula]': {required: true},
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
        } else if (fuente == 'prospeccion' || fuente == 'trafico') {
            //console.log('enter prospeccion');
            $('#gestion-nueva-cotizacion-form').validate({
                submitHandler: function (form) {
                    form.submit();
                }
            });
        }
    }
</script>
<style type="text/css">
.daterangepicker .ranges, .daterangepicker .calendar {
    float: left !important;
    #fecha-range{color: #DCD8D9;}
}
</style>
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Sistema de Gestión Comercial</h1>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="highlight">
                <div class="form">
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'gestion-nueva-cotizacion-form',
                        'enableAjaxValidation' => false,
                        'htmlOptions' => array(
                            'onsubmit' => "return false;", /* Disable normal form submit */
                            'onkeypress' => " if(event.keyCode == 13){ send(); } ", /* Do ajax call when user presses enter key */
                            'class' => 'form-horizontal form-search'
                        ),
                    ));
                    ?>

                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $form->labelEx($model, 'fuente'); ?>
                            <?php
                            echo $form->dropDownList($model, 'fuente', array(
                                'showroom' => 'Showroom',
                                'web' => 'Web',
                                'redes-sociales' => 'Redes Sociales',
                                'prospeccion' => 'Prospección',
                                'trafico' => 'Tráfico',
                                'exhibicion' => 'Exhibición',
                                'exonerados' => 'Exonerados'
                                    ), array('class' => 'form-control'));
                            ?>
                            <?php echo $form->error($model, 'fuente'); ?>
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
                    <div class="row" id="cont-ident">
                        <div class="col-sm-12">
                            <label for="GestionNuevaCotizacion_identificacion">Identificación</label>
                            <?php echo $form->dropDownList($model, 'identificacion', array('ci' => 'Cédula', 'ruc' => 'RUC', 'pasaporte' => 'Pasaporte'), array('selected' => 'ci', 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'identificacion'); ?>
                        </div>
                    </div>
                    <div class="row" id="cont-doc">
                        <div class="col-md-12">
                            <?php echo $form->labelEx($model, 'cedula'); ?>
                            <?php echo $form->textField($model, 'cedula', array('size' => 40, 'maxlength' => 10, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'cedula'); ?>
                        </div>
                    </div>
                    <div class="row buttons">
                        <div class="col-md-12">
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Buscar' : 'Save', array('class' => 'btn btn-danger', 'onclick' => 'send();')); ?>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>

                </div><!-- form -->
            </div>
        </div>
        <div class="col-md-8">
            <div class="highlight">
                <div class="form">
                    <h4>Filtros de Búsqueda:</h4>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'casos-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('gestionDiaria/search'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array(
                            'class' => 'form-horizontal form-search'
                        ),
                    ));
                    ?>
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
                        <div class="col-md-6">
                            <label for="">Responsable</label>
                            <select name="GestionDiaria[responsable]" id="" class="form-control">
                                <option value="">--Seleccione responsable--</option>
                                <option value=""></option>
                            </select>
                        </div>
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
                            <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="cont-existente">

    </div>
    <div class="row">
        <h1 class="tl_seccion">RGD</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">

                <table class="tables tablesorter" id="keywords">
                    <thead>
                        <tr>
                            <th><span>Status</span></th>
                            <th><span>ID</span></th>
                            <th><span>Nombres</span></th>
                            <th><span>Apellidos</span></th>
                            <th><span>Cédula</span></th>
                            <th><span>Próximo Seguimiento</span></th>
                            <th><span>Responsable</span></th>
                            <th><span>Email</span></th>
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
                                            $url = Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id_info'], 'tipo' => 'gestion'));
                                            break;
                                        case '3':
                                            $url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $c['id_info']));
                                            break;
                                        case '4':
                                            $url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $c['id_info']));
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
                                            $url = Yii::app()->createUrl('site/negociacion', array('id' => $c['id_info']));
                                            break;
                                        case '9':
                                            $url = Yii::app()->createUrl('site/cierre', array('id' => $c['id_info']));
                                            break;
                                        default:
                                            break;
                                    }
                                    ?>
                                    <button type="button" class="btn btn-xs btn-primary"><?php echo $status; ?></button>
                                    <button type="button" class="btn btn-xs btn-success"><?php echo $c['paso']; ?></button>
                                    <?php
                                    $credito = $this->getStatusSolicitudAll($c['id_info']);
                                    if ($credito == true) {
                                        echo '<button type="button" class="btn btn-xs btn-success">C</button>';
                                    } else {
                                        echo '<button type="button" class="btn btn-xs btn-default">C</button>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo $c['id_informacion']; ?> </td>
                                <td><?php echo $c['nombres']; ?> </td>
                                <td><?php echo $c['apellidos']; ?> </td>
                                <td><?php echo $c['cedula']; ?> </td>
                                <td><?php echo $c['proximo_seguimiento']; ?></td>
                                <td><?php echo $this->getResponsable($c['responsable']); ?></td>
                                <td><?php echo $c['email']; ?> </td>
                                <td>
                                    <a href="<?php echo Yii::app()->createUrl('gestionDiaria/create', array('id' => $c['id_info'], 'paso' => $c['paso'], 'id_gt' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a>
                                    <?php if ($c['status'] == 1): ?>
                                        <?php if ($c['paso'] == '1-2') { ?>
                                            <a href="<?php echo Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id_info'], 'tipo' => 'gestion')); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>    
                                        <?php } else { ?>
                                            <a href="<?php echo $url; ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>
                                        <?php } ?>
                                    <?php endif; ?>
                                    <?php if ($c['status'] == 3) { ?>
                                        <a href="<?php echo Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id_info'], 'tipo' => 'prospeccion')); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>    
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
