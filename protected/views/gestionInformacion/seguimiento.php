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
$area_id = (int) Yii::app()->user->getState('area_id');
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
        $('#checkMain').click(function(){
            $('.checkAll').attr('checked',($(this).is(':checked')) ? true:false);
        });
        
//        $('#checkMain').live('click',function(){
//            $('.checkAll').attr('checked',($(this).is(':checked')) ? true:false);
//        });
        
        $('#GestionDiaria_general').keyup(function(){
            if($(this).val() != ''){
               $('#busqueda_general').val(1); 
            }else{
                $('#busqueda_general').val(0); 
            }
        });
        $('#gestion_diaria_categorizacion').change(function(){
            var value = $(this).attr('value');
            if(value != ''){
                $('#categorizacion').val(1);
            }else{$('#categorizacion').val(0);}
        });
        $('#gestion_diaria_status').change(function(){
            var value = $(this).attr('value');
            if(value != ''){
                $('#status').val(1);
            }else{$('#status').val(0);}
        });
        $('#GestionDiaria_responsable').change(function(){
            var value = $(this).attr('value');
            if(value != ''){
                $('#responsable').val(1);
            }else{$('#responsable').val(0);}
        });
        $('#GestionNuevaCotizacion_cedula').keyup(function (){
            $('#cedula2').hide();
        });
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
                data: {dealer_id: value, tipo:'seg'},
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
                console.log('enter showroom');
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
                            var validateCedula = CedulaValida(cedula);
                            if(validateCedula == false){
                                $('#cedula2').show();
                                return false;
                            }
                            
                            $.ajax({
                                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getCedula"); ?>',
                                beforeSend: function (xhr) {
                                    $('#bg_negro').show();  // #bg_negro must be defined somewhere
                                },
                                timeout:8000,  // I chose 8 secs for kicks
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
                                    if(data.result != false){
                                        var dt = '<a href="https://www.kia.com.ec/intranet/usuario/index.php/gestionInformacion/create/'+data.id_nueva_cotizacion+'?tipo=gestion&fuente=showroom" class="btn btn-danger">Nueva Cotización</a>';
                                        $('.cont-createc-but').html(dt);
                                    }
                                    else if(data.result == false && data.flagttga35 == false && data.flagttga36 == false && data.flagvh01 == false){
                                        form.submit();
                                    }
                                },
                                error: function (error) {
                                    $('#myModal').modal('show');
                                    $('#closemodal').click(function(){
                                        form.submit();
                                    });
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
                                    timeout:8000,  // I chose 8 secs for kicks
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
                                        }if(data.result != false){
                                            var dt = '<a href="https://www.kia.com.ec/intranet/usuario/index.php/gestionInformacion/create/'+data.id_nueva_cotizacion+'?tipo=gestion&fuente=showroom" class="btn btn-danger">Continuar</a>';
                                            $('.cont-createc-but').html(dt);
                                        }
                                        else if(data.result == false && data.flagttga35 == false && data.flagttga36 == false && data.flagvh01 == false){
                                            form.submit();
                                        }
                                    },
                                    error: function (error) {
                                        $('#myModal').modal('show');
                                        $('#closemodal').click(function(){
                                            form.submit();
                                        });
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
                        var identificacion = $('#GestionNuevaCotizacion_identificacion').val();
                        var cedula = $('#GestionNuevaCotizacion_cedula').val();
                        if (identificacion == 'ci') {
                            var validateCedula = CedulaValida(cedula);
                            if(validateCedula == false){
                                $('#cedula2').show();
                                return false;
                            }
                        }    
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
    function CedulaValida(cedula) {
        console.log('cedula '+cedula);
        //Si no tiene el guión, se lo pone para la validación
        if (cedula.match(/\d{10}/)) {
            cedula = cedula.substr(0, 9) + "-" + cedula.substr(9);
        }

        //Valida que la cédula sea de la forma ddddddddd-d
        if (!cedula.match(/^\d{9}-\d{1}$/))
            return false;

        //Valida que el # formado por los dos primeros dígitos esté entre 1 y 24
        var dosPrimerosDigitos = parseInt(cedula.substr(0, 2), 10);
        if (dosPrimerosDigitos < 1 || dosPrimerosDigitos > 24)
            return false;
        //Valida que el valor acumulado entre los primeros 9 números coincida con el último
        var acumulado = 0, digito, aux;
        for (var i = 1; i <= 9; i++) {
            digito = parseInt(cedula.charAt(i - 1));
            if (i % 2 == 0) { //si está en una posición par
                acumulado += digito;
            } else { //si está en una posición impar
                aux = 2 * digito;
                if (aux > 9)
                    aux -= 9;
                acumulado += aux;
            }
        }
        acumulado = 10 - (acumulado % 10);
        if (acumulado == 10)
            acumulado = 0;
        var ultimoDigito = parseInt(cedula.charAt(10));
        if (ultimoDigito != acumulado)
            return false;

        //La cédula es válida
        return true;
    }
    
    function asignar(id){
        if (confirm('Está seguro de reasignar el o los clientes?')) {
            var checkboxvalues = new Array();
            //recorremos todos los checkbox seleccionados con .each
            $('input[name="asignar[]"]:checked').each(function() {
                    //$(this).val() es el valor del checkbox correspondiente
                    checkboxvalues.push($(this).val());
            });
            //console.log(checkboxvalues.length);
            if(checkboxvalues.length == 0){
                alert('Seleccione uno o mas usuarios de la lista del RGD');
                return false;
            }
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("gestionInformacion/setAsignamiento"); ?>',
                beforeSend: function (xhr) {
                    $('#bg_negro').show();  // #bg_negro must be defined somewhere
                },
                type: 'POST', dataType: 'json', data: {id: id, checkboxvalues: checkboxvalues},
                success: function (data) {
                    //$('#bg_negro').hide();
                    if (data.result == true) {
                        location.reload();
                    }

                }
            });
            
        }
        
    }
    
    function conc(id){
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("site/getConcesionariosli"); ?>',
            beforeSend: function (xhr) {
                //$('#bg_negro').show();  // #bg_negro must be defined somewhere
            },
            type: 'POST', data: {id: id},
            success: function (data) {
                $('#concesionarios').html(data);
            }
        });
    }
    
    function asesor(id){
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("site/getAsesoresli"); ?>',
            beforeSend: function (xhr) {
                //$('#bg_negro').show();  // #bg_negro must be defined somewhere
            },
            type: 'POST', data: {dealer_id: id, tipo:'seg'},
            success: function (data) {
                $('#asesores').html(data);
            }
        });
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
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">SGC</h4>
                </div>
                <div class="modal-body">
                    <h4>Conexión con sistema pirámide fallido</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="closemodal">Continuar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="row">
        <h1 class="tl_seccion">Sistema de Gestión Comercial</h1>
    </div>
    <div class="row">
        <?php if($cargo_id != 69){ ?>
        <?= $this->renderPartial('//layouts/rgd/registro', array('formaction' => 'gestionNuevaCotizacion/create', 'model' => $model, 'identificacion' => $identificacion));
        }
        ?>
        <div class="col-md-8">
            <div class="highlight">
                 <?= $this->renderPartial('//layouts/rgd/filtros', array('formaction' => 'gestionInformacion/seguimiento', 'cargo_id' => $cargo_id, 'dealer_id' => $dealer_id, 'tipo_filtro' => 'general'));?>
            </div>
        </div>
    </div>
    <div class="cont-existente">

    </div>
    <div class="cont-createc-vh01"></div>
    <div class="cont-createc-but"></div>
    <div class="cont-createc">
        
    </div>
    <div class="cont-createc-tg36">

    </div>
    
    
    <?php if(isset($title)): ?>
    <div class="row">
        <h2><div class="col-md-12"><div class="alert alert-info"><?php echo $title; ?></div></div></h2>     
    </div>
    <?php endif; ?>
    <div class="row">
        <h1 class="tl_seccion">RGD</h1>
    </div>
    <?php if($cargo_id == 2015){ ?>
    <div class="row">
        <div class="col-md-8">
                 <!-- Split button -->
            <div class="btn-group">
                <span class="button-checkbox btn btn-default" data-color="primary">
<!--                    <button type="button" class="btn" data-color="primary" >Unchecked</button>-->
                    <input type="checkbox" class="" id="checkMain" name="multislt"/>
                </span>
                <!--<button type="button" class="btn btn-default" class="btn-multislt"><input type="checkbox" name="multislt" class="checkMain" id="checkMain"/></button>-->
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              <ul class="dropdown-menu">
                <li><a class="asign-lt">REASIGNAR</a></li>
                <li><a class="asign-lt">BORRAR</a></li>
              </ul>
            </div>
                 <?php if ($cargo_id == 2015) { ?>
                <div class="btn-group">
                     <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Grupo <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                       <li><a id="1" class="asign-lt" onclick="conc(1);">AEKIA S.A.</a></li>
                       <li><a id="2" class="asign-lt" onclick="conc(2);">GRUPO ASIAUTO</a></li>
                       <li><a id="3" class="asign-lt" onclick="conc(3);">GRUPO KMOTOR</a></li>
                       <li><a id="4" class="asign-lt" onclick="conc(4);">IOKARS</a></li>
                       <li><a id="5" class="asign-lt" onclick="conc(5);">GRUPO EMPROMOTOR</a></li>
                       <li><a id="6" class="asign-lt" onclick="conc(6);">AUTHESA</a></li>
                       <li><a id="7" class="asign-lt" onclick="conc(7);">AUTOSCOREA</a></li>
                       <li><a id="8" class="asign-lt" onclick="conc(8);">GRUPO MERQUIAUTO</a></li>
                       <li><a id="9" class="asign-lt" onclick="conc(9);">GRUPO MOTRICENTRO</a></li>
                   </ul>    
                </div>
                 <?php } ?>
                 <?php if($cargo_id == 69){ ?>
                 <div class="btn-group">
                     <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Concesionario <span class="caret"></span>
                   </button>
                     <ul class="dropdown-menu" id="concesionarios">
                         <?php if($cargo_id == 69){
                             echo $this->getConcesionariosli($grupo_id);
                         } ?>
                     </ul>    
                </div>
                 <?php } ?>
                 <?php if($cargo_id == 70){ ?>
                <div class="btn-group">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Mover <span class="caret"></span>
                   </button>
                    <ul class="dropdown-menu" id="asesores">
                     <?php echo $this->getResponsablesAgencia($id_responsable); ?>
                   </ul>
                </div>
                 <?php } ?>
        </div>
    </div>
    <?php } ?>
    <br />
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">

                <table class="table tablesorter table-striped" id="keywords">
                    <thead>
                        <tr>
                            <?php if($cargo_id == 2015){ ?>
                            <th>Asignar</th>
                            <?php } ?>
                            <th><span>Status</span></th>
                            <th><span>ID</span></th>
                            <th><span>Fecha de Registro</span></th>
                            <th><span>Nombres</span></th>
                            <th><span>Apellidos</span></th>
                            <th><span>Identificación</span></th>
                            <th><span>Próximo Seguimiento</span></th>
                            <th><span>Responsable</span></th>
                            <th><span>Concesionario</span></th>
                            <th><span>Modelo Vehículo</span></th>
                            <th><span>Categorización</span></th>
                            <th><span>Exp. de Categ.</span></th>
                            <th><span>Fuente</span></th>
                            <th><span>Resumen</span></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($users as $c): ?>

                            <tr>
                                <?php if($cargo_id == 2025){ ?>
                                <td><input type="checkbox" name="asignar[]" class="checkAll" value="<?php echo $c['id_info']; ?>,<?php echo $c['id_resp']; ?>"/></td>
                                <?php } ?>
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
                                            $url = Yii::app()->createUrl('site/entrega', array('id_informacion' => $c['id_info']));
                                            break;
                                        case '10':
                                            $url = Yii::app()->createUrl('site/entrega', array('id_informacion' => $c['id_info']));
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
                                    //if($c['bdc'] == 1){
                                    //    echo '<button type="button" class="btn btn-xs btn-success">BDC</button>'; 
                                    //}
                                    ?>
                                    <?php if($c['reasignado'] == 1): ?>
                                        <button type="button" class="btn btn-xs btn-warning">Reasignado</button>
                                    <?php endif; ?>
                                    <?php 
                                    if($c['desiste'] == 1){
                                        echo '<button type="button" class="btn btn-xs btn-success">Desiste</button>'; 
                                    }

                                    ?>
                                </td>
                                <td><?php echo $c['id_info']; ?> </td>
                                <td>
                                <?php 
                                $pr = explode(' ', $c['fecha']);
                                echo $pr[0];
                                ?></td>
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
                                <td><?php echo $this->getResponsable($c['id_resp']); ?></td>
                                <td><?php 
                                echo $this->getNameConcesionarioById($c['dealer_id']); 
                                //esta dando error en las busquedas revisar ?></td>
                                <td>
                                <?php
                                $countvec = GestionVehiculo::model()->count(array('condition' => "id_informacion = {$c['id_info']}")); 
                                if ($countvec > 0) {
                                    $vec = GestionVehiculo::model()->findAll(array('condition' => "id_informacion = {$c['id_info']}",'limit' => '1', 'offset' => '0','order' => 'id desc'));
                                    foreach ($vec as $val) {
                                        $data = '<em>' . $this->getVersion($val['version']) . '. </em><br />';
                                    }
                                } 
                                echo $data;
                                ?>
                                </td>
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
                                    <?php //if($c['bdc'] == 0){ ?>
                                        <a href="<?php echo Yii::app()->createUrl('gestionDiaria/create', array('id' => $c['id_info'], 'paso' => $c['paso'], 'id_gt' => $c['id'],'fuente' => $c['fuente'])); ?>" class="btn btn-primary btn-xs btn-danger">Resúmen</a><em></em>
                                        <?php if (($c['status'] == 1 || $c['status'] == 4)&& $c['desiste'] != 1){ ?>
                                            <?php if ($c['paso'] == '1-2' && $c['fuente'] == 'showroom') { ?>
                                                <?php if($area_id != 4 && $cargo_id != 69){ ?> 
                                                    <a href="<?php echo Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id_info'], 'tipo' => 'prospeccion')); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>   
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php if($cargo_id != 72 && $cargo_id != 69 && $area_id != 4 &&  $area_id != 12 &&  $area_id != 13 &&  $area_id != 14){ ?> 
                                                    <a href="<?php echo $url; ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if ($c['status'] == 3 && $cargo_id != 72 && $cargo_id != 69 && $area_id != 4 &&  $area_id != 12 &&  $area_id != 13 &&  $area_id != 14) { ?>
                                                <a href="<?php echo Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id_info'], 'tipo' => 'prospeccion')); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>    
                                        <?php } ?>
                                    <?php //} ?>
                                    <?php if($c['bdc'] == 1  && ( $area_id == 4 ||  $area_id == 12 ||  $area_id == 13 ||  $area_id == 14)){ ?>
                                        <a href="<?php echo Yii::app()->createUrl('gestionDiaria/create', array('id' => $c['id_info'], 'paso' => $c['paso'], 'id_gt' => $c['id'],'fuente' => $c['fuente'])); ?>" class="btn btn-primary btn-xs btn-danger">Resúmen</a><em></em>
                                    <?php } ?>            
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
    <?= $this->renderPartial('//layouts/rgd/links');?>
</div>
