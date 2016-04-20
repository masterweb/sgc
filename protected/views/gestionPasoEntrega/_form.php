<?php
/* @var $this GestionPasoEntregaController */
/* @var $model GestionPasoEntrega */
/* @var $form CActiveForm */

$paso = $this->getPasoEntregaCon($id_informacion, $id_vehiculo);
//echo 'PASO:++++++ ' . $paso . '<br>';
$tipo = $this->getFinanciamiento($id_informacion, $id_vehiculo); // tipo de financiamiento 1 - credito, 0 - contado
if($tipo == 0 && $paso == 0){
    $paso = 3;
}elseif($tipo == 1){
    $paso = $this->getPasoEntregaCon($id_informacion, $id_vehiculo);
}
$id_gestion_paso_entrega = $this->getIdPasoEntrega($id_informacion, $id_vehiculo);

//echo 'TIPO DE FINANCIAMIENTO: '.$tipo;

//echo 'id paso entrega: ' . $id_gestion_paso_entrega;
?>
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
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script type="text/javascript">
    function addRemoveRules() {
        var gestion_paso = $('#GestionPasoEntrega_paso').val();
        //console.log('gestion paso: '+gestion_paso);
        switch (gestion_paso) {
            case '0':
                $("#GestionPasoEntrega_envio_factura").rules("add", "required");
                $("#GestionPasoEntregaDetail_envio_factura_fecha1").rules("add", "required");
                $("#GestionPasoEntregaDetail_observaciones1").rules("add", "required");
                break
            case '1':
                $("#GestionPasoEntrega_emision_contrato").rules("add", "required");
                $("#GestionPasoEntregaDetail_envio_factura_fecha2").rules("add", "required");
                $("#GestionPasoEntregaDetail_observaciones2").rules("add", "required");
                break;
            case '2':
                $("#GestionPasoEntrega_agendar_firma").rules("add", "required");
                $("#GestionPasoEntregaDetail_envio_factura_fecha3").rules("add", "required");
                $("#GestionPasoEntregaDetail_observaciones3").rules("add", "required");
                break;
            case '3':
                $("#GestionPasoEntrega_alistamiento_unidad").rules("add", "required");
                $("#GestionPasoEntregaDetail_envio_factura_fecha4").rules("add", "required");
                $("#GestionPasoEntregaDetail_observaciones4").rules("add", "required");
                break;
            case '4':
                $("#GestionPasoEntrega_pago_matricula").rules("add", "required");
                $("#GestionPasoEntregaDetail_envio_factura_fecha5").rules("add", "required");
                $("#GestionPasoEntregaDetail_observaciones5").rules("add", "required");
                break;
            case '5':
                $("#GestionPasoEntrega_recepcion_contratos").rules("add", "required");
                $("#GestionPasoEntregaDetail_envio_factura_fecha6").rules("add", "required");
                $("#GestionPasoEntregaDetail_observaciones6").rules("add", "required");
                break;
            case '6':
                $("#GestionPasoEntrega_recepcion_matricula").rules("add", "required");
                $("#GestionPasoEntregaDetail_envio_factura_fecha7").rules("add", "required");
                $("#GestionPasoEntregaDetail_placa").rules("add", "required");
                break;
            case '7':
                $("#GestionPasoEntrega_vehiculo_revisado").rules("add", "required");
                $("#GestionPasoEntregaDetail_envio_factura_fecha8").rules("add", "required");
                $("#GestionPasoEntregaDetail_tesponsable").rules("add", "required");
                break;
            case '8':
                $("#GestionPasoEntrega_entrega_vehiculo").rules("add", "required");
                $("#GestionPasoEntregaDetail_envio_factura_fecha9").rules("add", "required");
                $("#GestionPasoEntregaDetail_observaciones9").rules("add", "required");
                break;
            case '9':
                $("#GestionPasoEntrega_foto_entrega").rules("add", "required");
                break;
            case '10':
                break;
        }
    }
    $(document).ready(function () {
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
        var paso_entrega = $('#GestionPasoEntrega_paso').val();
        var tipo = '<?php echo $tipo; ?>';
        $('.fancybox').fancybox();

        if (tipo == 0 && paso_entrega == 0) {
            paso_entrega = 4;
        } else {
            paso_entrega++;
        }

        console.log(paso_entrega);

        $('#GestionPasoEntregaDetail_envio_factura_fecha' + paso_entrega).datetimepicker({
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
        $('#gestion-paso-entrega-form').validate({
            submitHandler: function (form) {
                if ($('#GestionPasoEntrega_paso').val() == 9) {
                    var d = document.getElementById('GestionPasoEntrega_foto_entrega');
                    //var e = document.getElementById('GestionTestDrive_firma');
                    if(d.value == '')
                    {
                        alert('Debe seleccionar una imágen para la foto de entrega');
                        $('#GestionPasoEntrega_foto_entrega').focus();
                        return false;
                    }
                    var e = document.getElementById('GestionPasoEntrega_foto_hoja_entrega');
                    //var e = document.getElementById('GestionTestDrive_firma');
                    if(e.value == '')
                    {
                        alert('Debe seleccionar una imágen para la hoja entrega');
                        $('#GestionPasoEntrega_foto_hoja_entrega').focus();
                        return false;
                    }
                }
                form.submit();
            }
        });
        addRemoveRules();
    });
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
                <?php
                $criteria = new CDbCriteria(array('condition' => "id = {$id_informacion}"));
                $cl = GestionInformacion::model()->findAll($criteria);
                $datos_vehiculo = GestionFactura::model()->findAll(array('condition' => "id_informacion = {$id_informacion}"));
                //echo(count($datos_vehiculo));
                //echo $datos_vehiculo[0]['datos_vehiculo'];
                $array_datos_vehiculo = explode(',', $datos_vehiculo[0]['datos_vehiculo']);
//                echo '<pre>';
//                print_r($array_datos_vehiculo);
//                echo '</pre>';    
                ?>
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-striped">
                            <tbody>
                                <tr class="odd"><th>Modelo</th><td><?php echo $this->getModeloTestDrive($id_vehiculo); ?></td><th>Cliente</th><td><?php echo ucfirst($cl[0]['nombres']); ?> <?php echo ucfirst($cl[0]['apellidos']); ?></td></tr>
                                <tr class="odd"><th>Versión</th><td><?php echo $this->getVersionTestDrive($id_vehiculo); ?></td><th>Email</th><td><?php echo $cl[0]['email']; ?></td></tr>
                                <tr class="odd"><th>Motor</th><td><?php echo $array_datos_vehiculo[2]; ?></td><th>Celular</th><td><?php echo $cl[0]['celular']; ?></td></tr>
                                <tr class="odd"><th>Factura</th><td><?php echo $array_datos_vehiculo[5]; ?></td><th>No. Chasis</th><td><?php echo $array_datos_vehiculo[0]; ?></td></tr>
                                <tr class="odd"><th>Color</th><td><?php echo $array_datos_vehiculo[4]; ?></td><th>Concesionario</th><td><?php echo $array_datos_vehiculo[6]; ?></td></tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <h1 class="tl_seccion_rf">Entrega Vehículo <?php echo ($tipo == 1) ? 'Crédito - 10 pasos' : 'Contado - 7 pasos'; ?></h1>
                </div>
                <div class="highlight">
                    <div class="form">

                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'gestion-paso-entrega-form',
                            'enableAjaxValidation' => false,
                            'htmlOptions' => array('enctype' => 'multipart/form-data'),
                        ));
                        ?>

                        <?php if ($tipo == 1) { // SI ES A CREDITO  ?>
                            <?php if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 1)) { ?>

                                <div class="row">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Envío de factura del vehículo y de los accesorios</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-md-4">
                                                <?php $data = $this->getFechaObsEntrega($id_gestion_paso_entrega, 1); ?>
                                                <label for="">Fecha: </label>
                                                <?php echo $data['fecha']; ?>
                                            </div>
                                            <div class="col-md-8">
                                                <label for="">Observaciones: </label>
                                                <?php echo $data['observaciones']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else if (!$this->getEntregaPaso($id_informacion, $id_vehiculo, 1)) { ?>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="checkbox">
                                            <label>                                        
                                                <?php echo $form->checkBox($model, 'envio_factura', array('value' => 1, 'uncheckValue' => 0)); ?> Envío de factura del vehículo y de los accesorios 
                                            </label>
                                        </div>
                                        <label for="GestionPasoEntrega[envio_factura]" generated="true" class="error" style="display: none;">Este campo es requerido.</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Fecha</label>
                                        <input type="text" name="GestionPasoEntregaDetail[envio_factura_fecha1]" id="GestionPasoEntregaDetail_envio_factura_fecha1" class="form-control" autocomplete="off"/>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Observaciones</label>
                                        <textarea name="GestionPasoEntregaDetail[observaciones1]" id="GestionPasoEntregaDetail_observaciones1" cols="30" rows="3" autocomplete="off"></textarea>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 2)) { ?>                

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Emisión de los contratos</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-4">
                                            <?php $data = $this->getFechaObsEntrega($id_gestion_paso_entrega, 2); ?>
                                            <label for="">Fecha: </label>
                                            <?php echo $data['fecha']; ?>
                                        </div>
                                        <div class="col-md-8">
                                            <label for="">Observaciones: </label>
                                            <?php echo $data['observaciones']; ?>
                                        </div>
                                    </div>
                                </div>

                            <?php } else if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 1) && !$this->getEntregaPaso($id_informacion, $id_vehiculo, 2)) { ?>
                                <div class="well">    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="checkbox">
                                                <label>                                        
                                                    <?php echo $form->checkBox($model, 'emision_contrato', array('value' => 1, 'uncheckValue' => 0)); ?> Emisión de los contratos  
                                                </label>
                                            </div>
                                            <label for="GestionPasoEntrega[emision_contrato]" generated="true" class="error" style="display: none;">Este campo es requerido.</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">Fecha</label>
                                            <input type="text" name="GestionPasoEntregaDetail[envio_factura_fecha2]" id="GestionPasoEntregaDetail_envio_factura_fecha2" class="form-control" autocomplete="off"/>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="">Observaciones</label>
                                            <textarea name="GestionPasoEntregaDetail[observaciones2]" id="GestionPasoEntregaDetail_observaciones2" cols="30" rows="3" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                </div>    
                            <?php } ?>
                            <?php if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 3)) { ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Agendar firma de contratos por parte del cliente </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-4">
                                            <?php $data = $this->getFechaObsEntrega($id_gestion_paso_entrega, 3); ?>
                                            <label for="">Fecha: </label>
                                            <?php echo $data['fecha']; ?>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="">Observaciones: </label>
                                            <?php echo $data['observaciones']; ?>
                                        </div>
                                    </div>
                                </div>

                            <?php } else if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 2) && !$this->getEntregaPaso($id_informacion, $id_vehiculo, 3)) { ?>
                                <div class="well">      
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="checkbox">
                                                <label>                                        
                                                    <?php echo $form->checkBox($model, 'agendar_firma', array('value' => 1, 'uncheckValue' => 0)); ?> Agendar firma de contratos por parte del cliente 
                                                </label>
                                            </div>
                                            <label for="GestionPasoEntrega[agendar_firma]" generated="true" class="error" style="display: none;">Este campo es requerido.</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">Fecha</label>
                                            <input type="text" name="GestionPasoEntregaDetail[envio_factura_fecha3]" id="GestionPasoEntregaDetail_envio_factura_fecha3" class="form-control" autocomplete="off"/>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="">Observaciones</label>
                                            <textarea name="GestionPasoEntregaDetail[observaciones3]" id="GestionPasoEntregaDetail_observaciones3" cols="30" rows="3" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } // FIN DE CREDITO ?>
                        <!-- //Paso 4 - Alistamiento de la unidad en PDI y accesorización=================================   -->
                        <?php if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 4)) { ?>               

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Alistamiento de la unidad en PDI y accesorización</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <?php $data = $this->getFechaObsEntrega($id_gestion_paso_entrega, 4); ?>
                                        <label for="">Fecha: </label>
                                        <?php echo $data['fecha']; ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Observaciones: </label>
                                        <?php echo $data['observaciones']; ?>
                                    </div>
                                </div>
                            </div>

                        <?php } else if (($this->getEntregaPaso($id_informacion, $id_vehiculo, 3) && !$this->getEntregaPaso($id_informacion, $id_vehiculo, 4)) || ($tipo == 0)) { ?>
                            <div class="well">      
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="checkbox">
                                            <label>                                        
                                                <?php echo $form->checkBox($model, 'alistamiento_unidad', array('value' => 1, 'uncheckValue' => 0)); ?> Alistamiento de la unidad en PDI y accesorización  
                                            </label>
                                        </div>
                                        <label for="GestionPasoEntrega[alistamiento_unidad]" generated="true" class="error" style="display: none;">Este campo es requerido.</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Fecha</label>
                                        <input type="text" name="GestionPasoEntregaDetail[envio_factura_fecha4]" id="GestionPasoEntregaDetail_envio_factura_fecha4" class="form-control" autocomplete="off"/>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Observaciones</label>
                                        <textarea name="GestionPasoEntregaDetail[observaciones4]" id="GestionPasoEntregaDetail_observaciones4" cols="30" rows="3" autocomplete="off"></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- //Paso 5 - Pago de la matrícula e impuestos=================================   -->
                        <?php if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 5)) { ?>                

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Pago de la matrícula e impuestos</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <?php $data = $this->getFechaObsEntrega($id_gestion_paso_entrega, 5); ?>
                                        <label for="">Fecha: </label>
                                        <?php echo $data['fecha']; ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Observaciones: </label>
                                        <?php echo $data['observaciones']; ?>
                                    </div>
                                </div>
                            </div

                        <?php } else if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 4) && !$this->getEntregaPaso($id_informacion, $id_vehiculo, 5)) { ?>
                            <div class="well">      
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="checkbox">
                                            <label>                                        
                                                <?php echo $form->checkBox($model, 'pago_matricula', array('value' => 1, 'uncheckValue' => 0)); ?> Pago de la matrícula e impuestos 
                                            </label>
                                        </div>
                                        <label for="GestionPasoEntrega[pago_matricula]" generated="true" class="error" style="display: none;">Este campo es requerido.</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Fecha</label>
                                        <input type="text" name="GestionPasoEntregaDetail[envio_factura_fecha5]" id="GestionPasoEntregaDetail_envio_factura_fecha5" class="form-control" autocomplete="off"/>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Observaciones</label>
                                        <textarea name="GestionPasoEntregaDetail[observaciones5]" id="GestionPasoEntregaDetail_observaciones5" cols="30" rows="3" autocomplete="off"></textarea>
                                    </div>
                                </div>
                            </div>    
                        <?php } ?>
                        <!-- //Paso 6 - Recepción de contratos legalizados =================================   -->
                        <?php if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 6)) { ?> 
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Recepción de contratos legalizados</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <?php $data = $this->getFechaObsEntrega($id_gestion_paso_entrega, 6); ?>
                                        <label for="">Fecha: </label>
                                        <?php echo $data['fecha']; ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Observaciones: </label>
                                        <?php echo $data['observaciones']; ?>
                                    </div>
                                </div>
                            </div>

                        <?php } else if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 5) && !$this->getEntregaPaso($id_informacion, $id_vehiculo, 6)) { ?>
                            <div class="well">      
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="checkbox">
                                            <label>                                        
                                                <?php echo $form->checkBox($model, 'recepcion_contratos', array('value' => 1, 'uncheckValue' => 0)); ?> Recepción de contratos legalizados  
                                            </label>
                                        </div>
                                        <label for="GestionPasoEntrega[recepcion_contratos]" generated="true" class="error" style="display: none;">Este campo es requerido.</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Fecha</label>
                                        <input type="text" name="GestionPasoEntregaDetail[envio_factura_fecha6]" id="GestionPasoEntregaDetail_envio_factura_fecha6" class="form-control" autocomplete="off"/>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Observaciones</label>
                                        <textarea name="GestionPasoEntregaDetail[observaciones6]" id="GestionPasoEntregaDetail_observaciones6" cols="30" rows="3" autocomplete="off"></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- //Paso 7 - Recepción de matrícula  y placas   =================================   -->
                        <?php if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 7)) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Recepción de matrícula  y placas</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <?php $data = $this->getFechaObsEntrega($id_gestion_paso_entrega, 7); ?>
                                        <label for="">Fecha: </label>
                                        <?php echo $data['fecha']; ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Placa: </label>
                                        <?php echo $data['placa']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } else if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 6) && !$this->getEntregaPaso($id_informacion, $id_vehiculo, 7)) { ?>
                            <div class="well">  
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="checkbox">
                                            <label>                                        
                                                <?php echo $form->checkBox($model, 'recepcion_matricula', array('value' => 1, 'uncheckValue' => 0)); ?> Recepción de matrícula  y placas  
                                            </label>
                                        </div>
                                        <label for="GestionPasoEntrega[recepcion_matricula]" generated="true" class="error" style="display: none;">Este campo es requerido.</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Fecha</label>
                                        <input type="text" name="GestionPasoEntregaDetail[envio_factura_fecha7]" id="GestionPasoEntregaDetail_envio_factura_fecha7" class="form-control" autocomplete="off"/>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Placa</label>
                                        <input type="text" name="GestionPasoEntregaDetail[placa]" id="GestionPasoEntregaDetail_placa" class="form-control" maxlength="7" autocomplete="off"/>
                                    </div>
                                </div>
                            </div>    
                        <?php } ?>
                        <!-- //Paso 8 - Vehículo Revisado =================================   -->
                        <?php if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 8)) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Vehículo Revisado</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <?php $data = $this->getFechaObsEntrega($id_gestion_paso_entrega, 8); ?>
                                        <label for="">Fecha: </label>
                                        <?php echo $data['fecha']; ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Responsable: </label>
                                        <?php echo $data['responsable']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } else if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 7) && !$this->getEntregaPaso($id_informacion, $id_vehiculo, 8)) { ?>
                            <div class="well">      
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="checkbox">
                                            <label>                                        
                                                <?php echo $form->checkBox($model, 'vehiculo_revisado', array('value' => 1, 'uncheckValue' => 0)); ?> Vehículo Revisado  
                                            </label>
                                        </div>
                                        <label for="GestionPasoEntrega[vehiculo_revisado]" generated="true" class="error" style="display: none;">Este campo es requerido.</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Fecha</label>
                                        <input type="text" name="GestionPasoEntregaDetail[envio_factura_fecha8]" id="GestionPasoEntregaDetail_envio_factura_fecha8" class="form-control" autocomplete="off"/>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Responsable</label>
                                        <input type="text" name="GestionPasoEntregaDetail[responsable]" id="GestionPasoEntregaDetail_tesponsable" class="form-control" autocomplete="off"/>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- //Paso 9 - Entrega al Vehículo  =================================   -->
                        <?php if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 9)) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Entrega al Vehículo</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <?php $data = $this->getFechaObsEntrega($id_gestion_paso_entrega, 9); ?>
                                        <label for="">Fecha: </label>
                                        <?php echo $data['fecha']; ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Observaciones: </label>
                                        <?php echo $data['observaciones']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } else if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 8) && !$this->getEntregaPaso($id_informacion, $id_vehiculo, 9)) { ?>
                            <div class="well">      
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="checkbox">
                                            <label>                                        
                                                <?php echo $form->checkBox($model, 'entrega_vehiculo', array('value' => 1, 'uncheckValue' => 0)); ?> Entrega al Vehículo  
                                            </label>
                                        </div>
                                        <label for="GestionPasoEntrega[entrega_vehiculo]" generated="true" class="error" style="display: none;">Este campo es requerido.</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Fecha</label>
                                        <input type="text" name="GestionPasoEntregaDetail[envio_factura_fecha9]" id="GestionPasoEntregaDetail_envio_factura_fecha9" class="form-control" autocomplete="off"/>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Observaciones</label>
                                        <textarea name="GestionPasoEntregaDetail[observaciones9]" id="GestionPasoEntregaDetail_observaciones9" cols="30" rows="3" autocomplete="off"></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 10)) { ?> 
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Foto de entrega</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <?php $data = $this->getFechaObsEntrega($id_gestion_paso_entrega, 10); ?>
                                        <a class="fancybox btn btn-success btn-xs"  href="#inline1">Foto de Entrega</a>
                                    </div>
                                    <div class="col-md-5">
                                        <a class="fancybox btn btn-success btn-xs" href="#inline2">Foto de Hoja de Entrega</a>
                                    </div>
                                </div>
                            </div>
                            <div id="inline1" style="width:600;display: none;">
                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $data['foto_entrega']; ?>" width="600"/> 
                            </div>
                            <div id="inline2" style="width:600;display: none;">
                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $data['foto_hoja_entrega']; ?>" width="600"/> 
                            </div>

                        <?php } else if ($this->getEntregaPaso($id_informacion, $id_vehiculo, 9) && !$this->getEntregaPaso($id_informacion, $id_vehiculo, 10)) { ?>
                            <div class="well">      
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="checkbox">
                                            <label>                                        
                                                <?php echo $form->checkBox($model, 'foto_entrega', array('value' => 1, 'uncheckValue' => 0)); ?> Foto de entrega
                                            </label>
                                        </div>
                                        <label for="GestionPasoEntrega[foto_entrega]" generated="true" class="error" style="display: none;">Este campo es requerido.</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Foto de Entrega</label>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                            <div>
                                                <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                    <?php echo $form->FileField($model, 'foto_entrega', array('class' => 'form-control')); ?>
                                                </span>
                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Foto de Hoja de Entrega</label>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                            <div>
                                                <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                    <?php echo $form->FileField($model, 'foto_hoja_entrega', array('class' => 'form-control')); ?>
                                                </span>
                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row buttons">
                            <?php if($paso == 10){ ?>
                            <a href="<?php echo Yii::app()->createUrl('gestionSeguimiento/create/', array('id_informacion' => $id_informacion,'id_vehiculo' => $id_vehiculo)); ?>" class="btn btn-danger" id="continue">Continuar</a>
                            <?php }else{ ?>
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Grabar', array('class' => 'btn btn-danger')); ?>
                            <?php } ?>
                            
                            <input type="hidden" name="GestionPasoEntrega[paso]" id="GestionPasoEntrega_paso" value="<?php echo $paso; ?>"/>
                            <input type="hidden" name="GestionPasoEntrega[id_informacion]" id="GestionPasoEntrega_id_informacion" value="<?php echo $id_informacion; ?>"/>
                            <input type="hidden" name="GestionPasoEntrega[id_vehiculo]" id="GestionPasoEntrega_id_informacion" value="<?php echo $id_vehiculo; ?>"/>
                            <input type="hidden" name="GestionPasoEntrega[id_gestion_paso_entrega]" id="GestionPasoEntrega_id_informacion" value="<?php echo $id_gestion_paso_entrega; ?>"/>
                        </div>

                        <?php $this->endWidget(); ?>

                    </div><!-- form -->
                    <br />
                <br />
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
                            
                            <div class="col-md-4">
                            <?php echo $form->labelEx($agendamiento, 'observaciones'); ?>
                                <?php echo $form->dropDownList($agendamiento, 'observaciones', array('' => '--Seleccione--', 'Seguimiento' => 'Seguimiento','Falta de tiempo' => 'Falta de tiempo', 'Llamada de emergencia' => 'Llamada de emergencia', 'Busca solo precio' => 'Busca solo precio', 'Desiste' => 'Desiste', 'Otro' => 'Otro'), array('class' => 'form-control')); ?>
                                <?php echo $form->error($agendamiento, 'observaciones'); ?>
                            </div>
                            <div class="col-md-4 agendamiento">
                            <?php echo $form->labelEx($agendamiento, 'agendamiento'); ?>
                            <?php echo $form->textField($agendamiento, 'agendamiento', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
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
                            <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                            <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                            <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="9">
                            <input type="hidden" name="GestionAgendamiento[id_informacion]" id="GestionAgendamiento_id_informacion" value="<?php echo $id_informacion; ?>">
                            <input type="hidden" name="GestionAgendamiento[nombre_cliente]" id="GestionAgendamiento_nombre_cliente" value="<?php echo $nombre_cliente; ?>">
                            <input type="hidden" name="GestionAgendamiento[nombre_concesionario]" id="GestionAgendamiento_nombre_concesionario" value="<?php echo $nombreConcesionario; ?>">
                            <input type="hidden" name="GestionAgendamiento[direccion_concesionario]" id="GestionAgendamiento_direccion_concesionario" value="<?php echo $direccion_concesionario; ?>">
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
                <?= $this->renderPartial('//layouts/rgd/links');?>
                </div>
            </div>
        </div>
    </div>