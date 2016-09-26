<?= $this->renderPartial('//layouts/rgd/head');?>

<?php
$identificacion = '';
if (isset($model->identificacion))
    $identificacion = $model->identificacion;
//echo '-----------identificacion: '.$identificacion;
$id_responsable = Yii::app()->user->getId();
$dealer_id = $this->getDealerId($id_responsable);
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$area_id = (int) Yii::app()->user->getState('area_id');
$grupo_id = (int) Yii::app()->user->getState('grupo_id');
$tipo_grupo = 1; // GRUPOS ASIAUTO, KMOTOR POR DEFECTO
if($grupo_id == 4 || $grupo_id == 5 || $grupo_id == 6 || $grupo_id == 7 || $grupo_id == 8 || $grupo_id == 9 ){
    $tipo_grupo = 0; // GRUPOS MOTRICENTRO, MERQUIAUTO, AUTHESA, AUTOSCOREA, IOKARS
}
//echo 'REPONSABLE ID: '.$id_responsable;
?>
<script>
    $(function () {
        //$('#toolinfo').tooltip();
        $("#rango_fecha_seguimiento").daterangepicker(
            {
                locale: {
                format: 'YYYY/MM/DD'
                }
            }
        );
        $('.range_inputs .applyBtn').click(function () {
            alert('asdqwedwqe2ee');
            if($('#GestionDiaria_seguimiento').val() == '3'){
                console.log('apply');
                $('#rango_fecha_seguimiento').css("color", "#555555");
                $('#fecha_seguimiento').val(1);
            }else{
                $('#fecha-range').css("color", "#555555");
                $('#fecha').val(1);
            }
        });
        $('#toolinfo').tooltipster({
            content: $('<p style="text-align:left;" class="tool">Prospección:  Ingreso de Base de Datos Externa o Nuevo Cliente Prospectado</p>'),
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
        /*$('.range_inputs .applyBtn').click(function () {
            console.log('apply');
            $('#fecha-range').css("color", "#555555");
        });*/
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
        $('#GestionDiaria_concesionario').change(function () {
            var value = $(this).attr('value');
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getAsesores"); ?>',
                beforeSend: function (xhr) {
                    //$('#info-3').show();  // #bg_negro must be defined somewhere
                },
                type: 'POST',
                //dataType: 'json', 
                data: {dealer_id: value,tipo:'bdc'},
                success: function (data) {
                    //$('#info-3').hide();
                    //alert(data);
                    $('#GestionDiaria_responsable').html(data);

                }
            });
        });

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
                                    $('#bg_negro').hide();
                                    if (data.result == true) {
                                        $('.cont-existente').html(data.data);
                                    } 
                                    if (data.result != false){
                                        var dt = '<a href="https://www.kia.com.ec/intranet/usuario/index.php/gestionInformacion/create/' + data.id_nueva_cotizacion + '?tipo=gestion&fuente=showroom&iden=cedula" class="btn btn-danger">Nueva Cotización</a>';
                                        $('.cont-createc-but').html(dt);
                                    }
                                    else {
                                        form.submit();
                                    }
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
                                    $('#bg_negro').hide();
                                    if (data.result == true) {
                                        $('.cont-existente').html(data.data);
                                    } else {
                                        form.submit();
                                    }
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
    #rango_fecha_seguimiento{color: #DCD8D9;}
    @media (min-width: 992px){
        .container {
            max-width: 1170px;
        }
    }
</style>
<?php $this->widget('application.components.Notificaciones'); ?>
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Sistema de Gestión Comercial</h1>
    </div>
    <div class="row">
        <?php if($cargo_id != 69 && $cargo_id != 7200 && $cargo_id != 85){ ?>
        <?= $this->renderPartial('//layouts/rgd/registro', array('formaction' => 'gestionNuevaCotizacion/create', 'model' => $model, 'identificacion' => $identificacion, 'tipo' => 'bdc'));?>
        <?php } ?>
        <div class="col-md-8">
            <div class="highlight">
                <?= $this->renderPartial('//layouts/rgd/filtros', array('formaction' => 'gestionInformacion/seguimientobdc', 'cargo_id' => $cargo_id, 'dealer_id' => $dealer_id, 'tipo_filtro' => 'general', 'tipo' => 'bdc'));?>
            </div>
        </div>
    </div>
    <div class="cont-existente"></div>
    <br />
    <div class="cont-createc-but"></div>
    <?php if(isset($title)): ?>
    <div class="row">
        <h2><div class="col-md-12"><div class="alert alert-info"><?php echo $title; ?></div></div></h2>     
    </div>
    <?php endif; ?>
    <div class="row">
        <h1 class="tl_seccion">RGD Ventas Externas</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">

                <table class="table tablesorter" id="keywords">
                    <thead>
                        <tr>
                            <th><span>Status</span></th>
                            <th><span>ID</span></th>
                            <th><span>Nombres</span></th>
                            <th><span>Apellidos</span></th>
                            <th><span>Identificación</span></th>
                            <th><span>Seguimiento Caducado</span></th>
                            <th><span>Responsable</span></th>
                            <th><span>Concesionario</span></th>
                            <th><span>Email</span></th>
                            <th><span>Categorización</span></th>
                            <th><span>Expiración de Categorización</span></th>
                            <th><span>Fuente</span></th>
                            <th><span>Resumen</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $c): ?>
                            <tr>
                                <td class="nowr">
                                    <?php
                                    //echo $this->getStatus($c['status']);
                                    $status = '';
                                    $paso = '';
                                    $url = '';
                                    
                                    $vec = GestionVehiculo::model()->findAll(array('condition' => "id_informacion='{$c['id']}'"));
                                    $count = count($vec);
                                    $td = GestionTestDrive::model()->findAll(array('condition' => "id_informacion='{$c['id']}'"));
                                    $countt = count($td);
                                    $paso = $this->getPasoGestionDiaria($c['id']);
                                    $medio_contacto = $this->getMedioContacto($c['id']);
                                    $desiste = $this->getDesiste($c['id']);
                                    $proximo_seguimiento = $this->getSeguimiento($c['id']);
                                    $cita = $this->getCita($c['id']);
                                    $categorizacion = $this->getCategorizacionSGC($c['id']);
                                    $fuente = $this->getFuenteSGC($c['id']);
                                    $status = $this->getStatusSGC($c['id']);
                                    $fuente_contacto = $this->getFuenteContacto($c['id']);
                                    //echo 'fuente de contacto: '.$fuente_contacto;
                                    $data_btn_semaforo = "";
                                    if (!empty($proximo_seguimiento)) {
                                        $fecha_array = explode(' ', $proximo_seguimiento);
                                        //print_r($fecha_array);
                                        //echo $fecha_array[0];
                                        if (strtotime($fecha_actual) == strtotime($fecha_array[0])) {
                                            $data_btn_semaforo = '<button type="button" class="btn btn-tomate btn-xs">S</button>';
                                        }
                                        if (strtotime($fecha_actual) > strtotime($fecha_array[0])) {
                                            $data_btn_semaforo = '<button type="button" class="btn btn-danger btn-xs">S</button>';
                                        }
                                        if (strtotime($fecha_actual) < strtotime($fecha_array[0])) {
                                            $data_btn_semaforo = '<button type="button" class="btn btn-success btn-xs">S</button>';
                                        }
                                    } else {
                                        $data_btn_semaforo = '<button type="button" class="btn btn-danger btn-xs">S</button>';
                                    }
                                    switch ($paso) {
                                        case '1-2':
                                            //$url = Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id_info'], 'tipo' => 'prospeccion'));
                                            $url = Yii::app()->createUrl('site/consulta', array('id_informacion' => $c['id'], 'tipo' => 'gestion', 'fuente' => 'gestion')); 
                                            if ($fuente == 'prospeccion')
                                                $url = Yii::app()->createUrl('site/consulta', array('id_informacion' => $c['id'], 'tipo' => 'gestion', 'fuente' => 'prospeccion'));
                                            if ($fuente == 'web')
                                                $url = Yii::app()->createUrl('site/consulta', array('id_informacion' => $c['id'], 'tipo' => 'gestion', 'fuente' => 'web'));
                                            break;
                                        case '3':
                                            $url = Yii::app()->createUrl('gestionConsulta/create', array('id_informacion' => $c['id'], 'tipo' => 'gestion', 'fuente' => 'web'));
                                            break;
                                        case '4':
                                            $url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $c['id']));
                                            break;
                                        case '5':
                                            $url = Yii::app()->createUrl('site/presentacion', array('id' => $c['id']));
                                            break;
                                        case '6':
                                            $url = Yii::app()->createUrl('site/demostracion', array('id' => $c['id']));
                                            break;
                                        case '7':
                                            $url = Yii::app()->createUrl('site/negociacion', array('id' => $c['id']));
                                            break;
                                        case '8':
                                            $url = Yii::app()->createUrl('site/negociacion', array('id' => $c['id']));
                                            break;
                                        case '9':
                                            $url = Yii::app()->createUrl('site/cierre', array('id' => $c['id']));
                                            break;
                                        case '10':
                                            $url = Yii::app()->createUrl('site/entrega', array('id_informacion' => $c['id']));
                                            break;
                                        default:
                                            break;
                                    }
                                    ?>
                                    <!--<button type="button" class="btn btn-xs btn-primary"><?php //echo $status;  ?></button>-->
                                    <button type="button" class="btn btn-xs btn-success"><?php echo $paso; ?></button>
                                    <?php
                                    if ($medio_contacto == 'web' && $c['tipo_form_web'] == ''):
                                        ?>
                                        <button type="button" class="btn btn-xs btn-warning">web</button>
                                    <?php endif; ?>
                                    <?php
                                    if ($medio_contacto == 'caduco'):
                                        ?>
                                        <button type="button" class="btn btn-xs btn-warning">Caduco</button>
                                    <?php endif; ?>
                                    <?php
                                    if ($c['tipo_form_web'] == 'exonerado'):
                                        ?>
                                        <button type="button" class="btn btn-xs btn-warning">VE</button>
                                    <?php endif; ?>
                                    <?php
                                    $credito = $this->getStatusSolicitudAll($c['id']);
                                    if ($credito == true) {
                                        echo '<button type="button" class="btn btn-xs btn-success">C</button>';
                                    } else {
                                        echo '<button type="button" class="btn btn-xs btn-default">C</button>';
                                    }
                                    if($desiste != 1 && $paso != 10){
                                        echo '&nbsp' . $data_btn_semaforo;
                                    }
                                    ?>
                                    <?php if ($c['reasignado'] == 1): ?>
                                        <button type="button" class="btn btn-xs btn-credito-sgc" data-toggle="tooltip" title="<?php echo $this->getResponsable($c['responsable_cesado']) . ' - ' . $this->getComentarioAsignamiento($c['id_comentario']); ?>">R</button>
                                        <?php endif; ?>
                                        <?php
                                        if ($desiste == 1) {
                                            echo '<button type="button" class="btn btn-xs btn-credito-sgc">D</button>';
                                        }
                                    ?>
                                </td>
                                <td><?php echo $c['id']; ?> </td>
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
                                <td><?php echo $proximo_seguimiento; if($cita){echo ' (c)';}?></td>
                                <td><?php echo $this->getResponsable($c['responsable']); ?></td>
                                <td><?php echo $this->getNameConcesionarioById($c['dealer_id']); ?></td>
                                <td><?php echo $c['email']; ?> </td>
                                <td><?php echo $categorizacion; ?></td>
                                <td> 
                                    <?php
                                    $dias;
                                    switch ($categorizacion) {
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
                                    <?php 
                                    if($fuente == 'showroom'){ echo 'Tráfico'; }
                                    else if($fuente_contacto == 'showroom'){echo 'Tráfico';}
                                    else{echo $fuente_contacto;}
                                    ?>  
                                </td>
                                <td>
                                    <?php //echo $fuente_contacto;  
                                    //echo $url;?>
                                    <a href="<?php echo Yii::app()->createUrl('gestionDiaria/create', array('id' => $c['id'], 'paso' => $paso, 'id_gt' => $c['id'], 'fuente' => $fuente)); ?>" class="btn btn-primary btn-xs btn-danger">Resumen</a><em></em>
                                    <?php if (($status == 1 || $status == 4) && $desiste != 1) { ?>
                                        <?php if ($paso == '1-2' && $fuente == 'showroom') { ?>
                                            <?php if ($area_id != 4 && $cargo_id != 69 ) { ?> 
                                                <a href="<?php echo Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id'], 'tipo' => 'prospeccion')); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>   
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php if ($cargo_id != 72 && $cargo_id != 69 &&  $area_id != 4 && $area_id != 12 && $area_id != 13 && $area_id != 14 && $fuente_contacto == 'showroom') { ?> 
                                                <a href="<?php echo $url; ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>
                                            <?php } ?>
                                                
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if($fuente_contacto == 'prospeccion' || $fuente_contacto == 'web' ){ ?> 
                                    <a href="<?php echo $url; ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>            
                                    <?php } ?>
                                    <?php if($fuente_contacto == 'exhibicion'){ ?> 
                                    <a href="<?php echo Yii::app()->createUrl('gestionVehiculo/create', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>           
                                    <?php } ?>   
                                    <?php if ($status == 3 && $cargo_id != 72 && $cargo_id != 69 && $area_id != 4 && $area_id != 12 && $area_id != 13 && $area_id != 14) { ?>
                                        <a href="<?php echo Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id'], 'tipo' => 'prospeccion')); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>    
                                    <?php } ?>
                                    <?php //} ?>
                                    <?php if ($c['bdc'] == 1 && ( $area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14)) { ?>
                                        <a href="<?php echo Yii::app()->createUrl('gestionDiaria/create', array('id' => $c['id'], 'paso' => $paso, 'id_gt' => $c['id'], 'fuente' => $fuente)); ?>" class="btn btn-primary btn-xs btn-danger">Resumen</a><em></em>
                                    <?php } ?> 
                                        <em></em>
                                        <a href="<?php echo Yii::app()->createUrl('gestionComentarios/create', array('id_informacion' => $c['id'])); ?>" class="btn btn-danger btn-xs">Comentarios</a>            
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <br />
    <br />
    <?= $this->renderPartial('//layouts/rgd/links'); ?>
</div>
