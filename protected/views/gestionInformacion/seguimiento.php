<?= $this->renderPartial('//layouts/rgd/head'); ?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/rgd_search.js"></script>
<?php
date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
$fecha_actual = date("Y/m/d");
$fecha_actual = (string) $fecha_actual;
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
$cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
$area_id = (int) Yii::app()->user->getState('area_id');
//echo  'dealer id: '.$dealer_id;
$count = count($users);
//echo  'count: '.$count;
// echo '<pre>';
//print_r($_GET);
//echo '</pre>';
?>
<script>
    function validateruc(e)  {
        if (e.val().length < 13) {
            return false;
        } else {
            if (e.val().substr(e.val().length - 3) == '001') {
                return true;
            } else {
                return false;
            }
        }
    }


    $(function () {
        $('.textreasignar').keyup(function(){
            $('#textr easignarerror').hide();
        });
        $('[data-toggle="tooltip"]').tooltip();
        $('#checkMain').click(function(){
            $('.checkAll').attr('checked', ($(this).is(':checked')) ? true:false);
        });
        $('#GestionDiaria_grupo').change(function(){
            var value = $(this).attr('value');
            if ($(this).val() != ''){
                $('#grupo').val(1);
            } else{
                $('#grupo').val(0);
            }
        });
        $('#GestionDiaria_concesionario').change(function(){
            var value = $(this).attr('value');
            if ($(this).val() != ''){
                $('#concesionario').val(1);
            } else{
                $('#concesionario').val(0);
            }
        });

        $('#GestionDiaria_general').keyup(function(){
            if ($(this).val() != ''){
                $('#busqueda_general').val(1);
            } else{
                $('#busqueda_general').val(0);
            }
        });
        $('#gestion_diaria_categorizacion').change(function(){
            var value = $(this).attr('value');
            if (value != '' ){
                    $('#categorizacion').val(1);
            } else{$('#categorizacion').val(0); }
        });
        $('#gestion_diaria_status').change(function(){
            var value = $(this).attr('value');
            if (value != ''){
                $( '#status').val(1);
            } else{$('#status').val(0); }
        });
        $('#GestionDiaria_responsable').change(function(){
            var value = $(this).attr('value');
            if (value != ''){
                $('#responsable').val(1);
            } else{ $('#responsable').val(0); }
        });
        $('#GestionNuevaCotizacion_cedula').keyup(function (){
                $('#cedula2').hide();
        });
        $('#GestionNuevaCotizacion_ruc').change(function() {
            var resp = validateruc($(this));
            if (resp != true){
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
        $("#rango_fecha_seguimiento").daterangepicker(
            {
                locale: {
                format: 'YYYY/MM/DD'
                }
            }
        );
        $('.range_inputs .applyBtn').click(function () {
            if($('#GestionDiaria_seguimiento').val() == '3'){
                console.log('apply');
                $('#rango_fecha_seguimiento').css("color", "#555555");
                $('#fecha_seguimiento').val(1);
            }else{
                $('#fecha-range').css("color", "#555555");
                $('#fecha').val(1);
            }
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
            if (value == 'Flota'){
                $('.empresa-cont').show();
            } else{
                $('.empresa-cont').hide();
            }
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
    }); // END DOCUMENT READY----------------------------------------------
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
                    'GestionNuevaCotizacion[cedula]': {required: 'Ingrese la cédula'}, 'GestionNuevaCotizacion[fuente]': {required: 'Seleccione fuente'},
                    'GestionNuevaCotizacion[identificacion]': {required: 'Seleccione identificación'}
                },
                submitHandler: function (form) {
                    var identificacion = $('#GestionNuevaCotizacion_identificacion').val();
                    var cedula = $('#GestionNuevaCotizacion_cedula').val();
                    var fuente = $('#GestionNuevaCotizacion_fuente').val();
                    if (identificacion == 'ci') {
                        var validateCedula = CedulaValida(cedula);
                        if (validateCedula == false){
                            $('#cedula2').show();
                            return false;
                        }

                        $.ajax({
                            url: '<?php echo Yii::app()->createAbsoluteUrl("site/getCedula"); ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show(); // #bg_negro must be defined somewhere
                            },
                            timeout:8000, // I chose 8 secs for kicks
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
                                if (data.result != false){
                                    var dt = '<a href="https://www.kia.com.ec/intranet/usuario/index.php/gestionInformacion/create/' + data.id_nueva_cotizacion + '?tipo=gestion&fuente=showroom&iden=cedula" class="btn btn-danger">Nueva Cotización</a>';
                                    $('.cont-createc-but').html(dt);
                                }
                                else if (data.result == false && data.flagttga35 == false && data.flagttga36 == false && data.flagvh01 == false){
                                    form.submit();
                                }
                            },
                            error: function (error) {
                                $('#myModal').modal('show');
                                $('#closemodal').click(function(){form.submit();});
                            }
                        });
                    } else if (identificacion == 'ruc') {
                        var ruc = $('#GestionNuevaCotizacion_ruc').val();
                        var resp = validateruc($('#GestionNuevaCotizacion_ruc'));
                        if (resp != true){
                            alert('Por favor ingrese correctamente el RUC.');
                        } else{
                            $.ajax({
                                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getRuc"); ?>',
                                beforeSend: function (xhr) {
                                    $('#bg_negro').show(); // #bg_negro must be defined somewhere
                                    },
                                timeout:8000, // I chose 8 secs for kicks
                                type: 'POST', dataType: 'json', data: {id: ruc, fuente: fuente},
                                success: function (data) {
                                    //alert(data.flagttga35);
                                    $('#bg_negro').hide();
                                    if (data.result == true) {$('.cont-existente').html(data.data);}
                                    if (data.flagttga35 == true) {$('.cont-createc').html(data.datattga35);}
                                    if (data.flagttga36 == true) {$('.cont-createc-tg36').html(data.datattga36);}
                                    if (data.flagvh01 == true) {
                                        $('.cont-createc-vh01').html(data.datavh01);
                                    }
                                    if (data.result != false){
                                        var dt = '<a href="https://www.kia.com.ec/intranet/usuario/index.php/gestionInformacion/create/' + data.id_nueva_cotizacion + '?tipo=gestion&fuente=showroom&iden=ruc" class="btn btn-danger">Nueva Cotización</a>';
                                        $('.cont-createc-but').html(dt);
                                    }
                                    else if (data.result == false && data.flagttga35 == false && data.flagttga36 == false && data.flagvh01 == false){
                                        form.submit();
                                    }
                                },
                                error: function (error) {
                                    $('#myModal').modal('show');
                                    $('#closemodal').click(function(){form.submit();});
                                }
                            });
                        }
                    } 
                    else if (identificacion == 'pasaporte'){
                        var pasaporte = $('#GestionNuevaCotizacion_pasaporte').val();
                        $.ajax({
                            url: '<?php echo Yii::app()->createAbsoluteUrl("site/getPasaporte"); ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show(); // #bg_negro must be defined somewhere
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
                    if (validateCedula == false){
                        $('#cedula2').show();
                        return false;
                    }
                }
                if ($('#GestionNuevaCotizacion_identificacion').val() == 'ruc'){
                    var resp = validateruc($('#GestionNuevaCotizacion_ruc'));
                    if (resp != true){
                        alert('Por favor ingrese correctamente el RUC.');
                    } else{
                        form.submit();
                    }
                } else{
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
                    'GestionNuevaCotizacion[cedula]': {required: 'Ingrese la cédula'}, 'GestionNuevaCotizacion[fuente]': {required: 'Seleccione fuente'},
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
        console.log('cedula ' + cedula);
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
        
        $('#asesorasg').val(id);
        $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("gestionInformacion/getResponsable"); ?>',
                /*beforeSend: function (xhr) {
                    $('#bg_negro').show();  // #bg_negro must be defined somewhere
                },*/
                type: 'POST', data: {id: id},
                success: function (data) {
                    $('.txtasignamiento').html(data).show();
                }
            });
    }
    
    function asignarsave(){
        var checkboxvalues = new Array();
        //recorremos todos los checkbox seleccionados con .each
        $('input[name="asignar[]"]:checked').each(function() {
                //$(this).val() es el valor del checkbox correspondiente
                checkboxvalues.push($(this).val());
        });
        
        if(checkboxvalues.length == 0){
            alert('Seleccione uno o mas usuarios de la lista del RGD');
            return false;
        }
        
        var id = $('#asesorasg').val();
        var textreasignar = $('.textreasignar').val();
        if($('.textreasignar').val() == ''){
            $('#textreasignarerror').show();
            return false;
        }
        if (confirm('Está seguro de reasignar el o los clientes?')) {    
            
            
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
                type: 'POST', dataType: 'json', data: {id: id, checkboxvalues: checkboxvalues, comentario:textreasignar},
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
    #rango_fecha_seguimiento{color: #DCD8D9;}
    #toolinfo{position: absolute;right: -20px;top: 24px;}
    .tool{font-size: 11px;margin: 1px 0;}
    @media (min-width: 992px){
        .container {
            max-width: 1170px;
        }
        em{margin-bottom: 3px;display: block;}
    }
    #checkMain{position: relative;top: 2px;}
    #checklabel{font-size: 14px;font-weight: normal;margin-bottom: 0px;}

    .checkbox-danger input[type="checkbox"]:checked + label::before {
        background-color: #d9534f;
        border-color: #d9534f; }
    .checkbox-danger input[type="checkbox"]:checked + label::after {
        color: #fff; }
    .textreasignar{margin-bottom: 0px;height: 33px;margin-right: 3px;border-radius: 0px;}
    #textreasignarerror{color: #C00;font-size: 12px;resize: none;}
    .txtasignamiento{position: absolute;top: -20px;color: #C21930;font-style: italic;}
    .group-asignamiento{position: relative !important;}
    .tooltip{white-space: normal;}
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
<?php if ($cargo_id != 69) { ?>
    <?=
    $this->renderPartial('//layouts/rgd/registro', array('formaction' => 'gestionNuevaCotizacion/create', 'model' => $model, 'identificacion' => $identificacion, 'tipo' => 'ventas'));
}
?>
        <div class="col-md-8">
            <div class="highlight">
        <?= $this->renderPartial('//layouts/rgd/filtros', array('formaction' => 'gestionInformacion/seguimiento', 'cargo_id' => $cargo_id, 'dealer_id' => $dealer_id, 'tipo_filtro' => 'general', 'tipo_seg' => $tipo_seg)); ?>
            </div>
        </div>
    </div>
    <br />
    <div class="cont-existente"></div>
    <div class="cont-createc-vh01"></div>    
    <div class="cont-createc"></div>
    <div class="cont-createc-tg36"></div>
    <br />
    <div class="cont-createc-but"></div>


<?php if (isset($title)): ?>
        <div class="row">
            <h2><div class="col-md-12"><div class="alert alert-info"><?php echo $title; ?></div></div></h2>     
        </div>
    <?php endif; ?>
    <div class="row">
        <h1 class="tl_seccion">RGD</h1>
    </div>
    <div class="row paleta">
        <h2 class="tl_seccion_gris">Descripción de Iconografía</h2>
        <div class="body-paleta">
            <div class="col-md-4 col-xs-6 paso">
                <div class="row">
                    <div class="col-md-12"><h4>Paso en el que te encuentras</h4></div>
                    <div class="col-md-6 col-xs-6"><button type="button" class="btn btn-xs btn-paso">1-2</button><span>Prospección</span></div>
                    <div class="col-md-6 col-xs-6"><button type="button" class="btn btn-xs btn-paso">7</button><span>Negociación</span></div>
                    <div class="col-md-6 col-xs-6"><button type="button" class="btn btn-xs btn-paso">3</button><span>Recepción</span></div>
                    <div class="col-md-6 col-xs-6"><button type="button" class="btn btn-xs btn-paso">8</button><span>Cierre</span></div>
                    <div class="col-md-6 col-xs-6"><button type="button" class="btn btn-xs btn-paso">4</button><span>Consulta</span></div>
                    <div class="col-md-6 col-xs-6"><button type="button" class="btn btn-xs btn-paso">9</button><span>Entrega</span></div>
                    <div class="col-md-6 col-xs-6"><button type="button" class="btn btn-xs btn-paso">5</button><span>Presentación</span></div>
                    <div class="col-md-6 col-xs-6"><button type="button" class="btn btn-xs btn-paso">10</button><span>Seguimiento</span></div>
                    <div class="col-md-6 col-xs-6"><button type="button" class="btn btn-xs btn-paso">6</button><span>Demostración</span></div>
                </div>
            </div>
            <div class="col-md-3 col-xs-6 estado">
                <div class="row">
                    <div class="col-md-12"><h4>Estado de Seguimiento</h4></div> 
                    <div class="col-md-12"><button type="button" class="btn btn-xs btn-success">S</button><span>Fecha de seguimiento futuro</span></div>
                    <div class="col-md-12"><button type="button" class="btn btn-xs btn-tomate">S</button><span>Fecha de seguimiento presente</span></div>
                    <div class="col-md-12"><button type="button" class="btn btn-xs btn-danger">S</button><span>Fecha de seguimiento pasado</span></div>
                </div>
            </div>
            <div class="col-md-3 col-xs-6 estado-credito">
                <div class="row">
                    <div class="col-md-12"><h4>Estado de Crédito</h4></div>
                    <div class="col-md-12"><button type="button" class="btn btn-xs btn-creditosn">C</button><span>Asesor de crédito no realizó movimientos</span></div>
                    <div class="col-md-12"><button type="button" class="btn btn-xs btn-credito">C</button><span>Asesor de crédito realizó movimientos (Revisa tu bandeja de entrada)</span></div>
                </div>
            </div>
            <div class="col-md-2 col-xs-6 desiste">
                <div class="row">
                    <div class="col-md-12 ft"><button type="button" class="btn btn-xs btn-credito">D</button><span class="titdesiste">Desiste</span></div>
                    <div class="col-md-12"><span>El cliente desiste de la compra</span></div>
                    <hr />
                    <div class="col-md-12"><button type="button" class="btn btn-xs btn-credito">R</button><span class="titdesiste">Reasignado</span></div>
                    <div class="col-md-12"><span>Cliente reasignado de otro asesor de ventas</span></div>
                </div>  
            </div>
        </div>
    </div>
<?php //if($cargo_id == 46 || $cargo_id == 69 || $cargo_id == 70 || $area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14){  ?>
<?php if ($cargo_id == 70) { ?>
        <div class="row">
            <br />
            <div class="col-md-12">
                <!-- Split button -->
                <div class="btn-group">
                    <span class="button-checkbox btn btn-default checkbox-danger" data-color="primary">
                        <!--                    <button type="button" class="btn" data-color="primary" >Unchecked</button>-->
                        <input type="checkbox" class="" id="checkMain" name="multislt"/><label id="checklabel">&nbsp;Todos</label>
                    </span>
                    <!--<button type="button" class="btn btn-default" class="btn-multislt"><input type="checkbox" name="multislt" class="checkMain" id="checkMain"/></button>-->
                    <!--                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                      </button>-->
                    <ul class="dropdown-menu">
                        <li><a class="asign-lt">REASIGNAR</a></li>
                        <!--                <li><a class="asign-lt">BORRAR</a></li>-->
                    </ul>
                </div>
    <?php //if ($cargo_id == 46 || $area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) {  ?>
    <?php if ($cargo_id == 221987) { ?>
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
    <?php if ($cargo_id == 69) { ?>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Concesionario <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" id="concesionarios">
            <?php
            if ($cargo_id == 69) {
                echo $this->getConcesionariosli($grupo_id);
            }
            ?>
            </ul>    
        </div>
    <?php } ?>
    <?php if ($cargo_id == 70) { ?>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Asignar a <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" id="asesores">
            <?php echo $this->getResponsablesAgencia($id_responsable); ?>
            </ul>
        </div>
        <div class="btn-group group-asignamiento">
            <span class="txtasignamiento"></span>
            <textarea rows="2" cols="60" class="textreasignar" name="textreasignar" placeholder="Ingresar Comentario"></textarea>
            <label generated="true" class="error" id="textreasignarerror" style="display:none;">Ingrese un comentario</label>
        </div>    
        <div class="btn-group">
            <button type="button" class="btn btn-default btn-danger" onclick="asignarsave();">Grabar</button>
            <input type="hidden" name="asesorasg" id="asesorasg" value=""/>   
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
<?php //if($cargo_id == 46 || $cargo_id == 69 || $cargo_id == 70 || $area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14){  ?>
                            <?php if ($cargo_id == 70) { ?>
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
                            <th><span>Modelo-Test Drive</span></th>
                            <th><span>Categorización</span></th>
                            <th><span>Exp. de Categ.</span></th>
                            <th><span>10(+1)</span></th>
                            <th><span>Fuente</span></th>
                            <th><span>Resumen</span></th>
                        </tr>
                    </thead>
                    <tbody>
<?php
//echo '<pre>';
//print_r($users);
//echo '</pre>';
//die();
?>
                        <?php foreach ($users as $c): ?>

                            <tr>
                            <?php //if($cargo_id == 46 || $cargo_id == 69 || $cargo_id == 70 || $area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14){  ?>
                                <?php if ($cargo_id == 70) { ?>
                                    <td><input type="checkbox" name="asignar[]" class="checkAll" value="<?php echo $c['id']; ?>,<?php echo $c['responsable']; ?>"/></td>
                                <?php } ?>
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
                                        $url = Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id'], 'tipo' => 'prospeccion'));
                                        if ($fuente == 'prospeccion')
                                            $url = Yii::app()->createUrl('site/consulta', array('id_informacion' => $c['id'], 'tipo' => 'gestion', 'fuente' => 'prospeccion'));
                                        if ($fuente == 'web')
                                                $url = Yii::app()->createUrl('site/consulta', array('id_informacion' => $c['id'], 'tipo' => 'gestion', 'fuente' => 'web'));
                                        break;
                                    case '3':
                                        $url = Yii::app()->createUrl('site/consulta', array('id_informacion' => $c['id'], 'tipo' => 'gestion', 'fuente' => 'web'));
                                        break;
                                    case '4':
                                        $url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $c['id'], 'tipo' => 'gestion', 'fuente' => 'web'));
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
                                        $url = Yii::app()->createUrl('site/cierre', array('id' => $c['id']));
                                        break;
                                    case '9':
                                        $url = Yii::app()->createUrl('site/entrega', array('id_informacion' => $c['id']));
                                        break;
                                    case '10':
                                        $url = Yii::app()->createUrl('site/entrega', array('id_informacion' => $c['id']));
                                        break;
                                    default:
                                        break;
                                }
                                ?>

                                        <!--<button type="button" class="btn btn-xs btn-primary"><?php //echo $status;  ?></button>-->
                                    <button type="button" class="btn btn-xs btn-paso"><?php echo $paso; ?></button>

                                    <?php
                                    if ($medio_contacto == 'web' && $c['tipo_form_web'] == ''):
                                        ?>
                                        <button type="button" class="btn btn-xs btn-warning">Web</button>
                                    <?php endif; ?>
                                    <?php
                                    if ($c['tipo_form_web'] == 'exonerado'):
                                        ?>
                                        <button type="button" class="btn btn-xs btn-warning">VE</button>
                                    <?php endif; ?>
                                    <?php
                                    //die('id info: '.$c['id_info']);
                                    $credito = $this->getStatusSolicitudAll($c['id']);
                                    if ($credito == true) {
                                        echo '<button type="button" class="btn btn-xs btn-credito-sgc">C</button>';
                                    } else {
                                        echo '<button type="button" class="btn btn-xs btn-creditosn-sgc">C</button>';
                                    }
                                    if($desiste != 1 && $paso != 10){
                                        echo '&nbsp' . $data_btn_semaforo;
                                    }
                                    ?>
                                    <?php
                                    //if($c['bdc'] == 1){
                                    //    echo '<button type="button" class="btn btn-xs btn-success">BDC</button>'; 
                                    //}
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
                                <td>
                                    <?php
                                    $pr = explode(' ', $c['fecha']);
                                    echo $pr[0];
                                    ?></td>
                                <td><?php echo ucfirst($c['nombres']); ?> </td>
                                <td><?php echo ucfirst($c['apellidos']); ?> </td>
                                <td><?php
                                    if ($c['cedula'] != '') {
                                        echo $c['cedula'];
                                    }
                                    if ($c['pasaporte'] != '') {
                                        echo $c['pasaporte'];
                                    }
                                    if ($c['ruc'] != '') {
                                        echo $c['ruc'];
                                    }
                                    ?> 
                                </td>
                                <td><?php echo $proximo_seguimiento; if($cita){echo ' (c)';}?></td>
                                <td><?php echo $this->getResponsable($c['responsable']); ?></td>
                                <td><?php
                                    echo $this->getNameConcesionarioById($c['dealer_id']);
                                    //esta dando error en las busquedas revisar 
                                    ?>
                                </td>
                                <td class="nowr">
                                    <?php
                                    $tdsi = 0;
                                    $tdno = 0;
                                    $tdv = GestionTestDrive::model()->findAll(array('condition' => "id_informacion = {$c['id']}", 'order' => 'id_vehiculo desc'));
                                    foreach ($tdv as $vc) {
                                        //echo 'id_vehiculo: '.$vc['id_vehiculo'].'<br />';
                                        if($vc['test_drive'] == 1){$tdsi++;}else{$tdno++;}
                                        //else{echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span><br />';}
                                    }                        
                                    echo $datatd = $this->getListaTD($c['id']);
                                    ?>
                                </td>
                                <td><?php echo $categorizacion; ?> </td>
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
                                <td><?php echo $this->getPasoDiez($c['id']); ?></td>
                                <td> 
                                    <?php if ($fuente == 'showroom') {
                                        echo 'Tráfico';
                                    } else {
                                        echo $fuente;
                                    }
                                    ?> 
                                </td>
                                <td>
                                    <?php //echo $fuente_contacto; echo $url; ?>
                                    <a href="<?php echo Yii::app()->createUrl('gestionDiaria/create', array('id' => $c['id'], 'paso' => $paso, 'id_gt' => $c['id'], 'fuente' => $fuente)); ?>" class="btn btn-primary btn-xs btn-danger">Resumen</a><em></em>
                                    <?php if (($status == 1 || $status == 4) && $desiste != 1) {  ?>
                                        <?php if ($paso == '1-2' && $fuente == 'showroom') { ?>
                                            <?php if ($area_id != 4 && $cargo_id != 69) { ?> 
                                                <a href="<?php echo Yii::app()->createUrl('gestionInformacion/update', array('id' => $c['id'], 'tipo' => 'prospeccion')); ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>   
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php if ($cargo_id != 72 && $cargo_id != 69 && $area_id != 4 && $area_id != 12 && $area_id != 13 && $area_id != 14 && $fuente_contacto == 'showroom') { ?> 
                                                <a href="<?php echo $url; ?>" class="btn btn-primary btn-xs btn-warning">Continuar</a>
                                            <?php } ?>
                                                
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if(($fuente_contacto == 'prospeccion' || $fuente_contacto == 'web') && ($area_id != 4 && $area_id != 12 && $area_id != 13 && $area_id != 14)){ ?> 
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
    <div class="row">
        <div class="col-md-9">
            <?php $this->widget('CLinkPager', array('pages' => $pages, 'maxButtonCount' => 10)); ?>
        </div>
    </div>
    <br />
    <br />
    <?= $this->renderPartial('//layouts/rgd/links'); ?>
</div>
