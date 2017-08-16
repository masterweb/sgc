<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css"/>               
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css"/>        
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.maskMoney.js" type="text/javascript"></script>
<!-- Add fancyBox -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sketch.js"></script>
<?php
/* @var $this GestionSolicitudCreditoController */
/* @var $model GestionSolicitudCredito */
/* @var $form CActiveForm */

# CONSULTA SI EXISTE UNA SOLICITUD DE CREDITO YA INGRESADA
$countsc = $this->getNumSolicitudCredito($id_informacion,$id_vehiculo);
//echo 'countsc:'.$countsc.'<br>';
if($countsc > 0){ // SI EXISTE SOLICITUD UPDATE 
    $url = Yii::app()->createAbsoluteUrl("gestionSolicitudCredito/update");
    $url_load = Yii::app()->request->baseUrl.'/index.php/gestionSolicitudCredito/update?id_informacion='.$id_informacion.'&id_vehiculo='.$id_vehiculo;
}else{ // SI NO EXISTE CREA UNA NUEVA
    $url = Yii::app()->createAbsoluteUrl("gestionSolicitudCredito/createAjax");
    $url_load = Yii::app()->request->baseUrl.'/index.php/gestionSolicitudCredito/create?id_informacion='.$id_informacion.'&id_vehiculo='.$id_vehiculo;
}
//echo 'url load: '.$url_load;

# CONSULTA A WEBSERVICE DE DATABOOK=======================================================================================
$ced = GestionInformacion::model()->find(array("condition" => "id = {$id_informacion}"));
$valid_cedula = 0;
$vartrf = array();
$vartrf['read_first_time'] = 0;
//$vartrf['salarioactual'] = 0;
/*if($ced){
    $dat = GestionDatabook::model()->count(array("condition" => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}"));
    if($dat == 0){
        //die('enter dat 0');
        $uriservicio = "http://www.playavip.com.ec/webservicesinpsercom.php?ced=".$ced->cedula."&usr=inpsercom";
        libxml_use_internal_errors(TRUE); // this turns off spitting errors on your screen
        try{
            $xml = simplexml_load_file($uriservicio);
            if($xml->civil->cedula != ''){
                $xml_string = $xml->asXML();
                //echo '<pre>' . utf8_decode($xml_string) . '</pre>';
                $modeldb = new GestionDatabook;
                $modeldb->id_informacion = $id_informacion;
                $modeldb->id_vehiculo = $id_vehiculo;
                $modeldb->identificacion = $ced->cedula;
                $modeldb->xml_databook = utf8_decode($xml_string);
                date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                $modeldb->fecha = date("Y-m-d H:i:s"); 
                $modeldb->save();
                $valid_cedula = 1;
                $vartrf['read_first_time'] = 1;
                $vartrf['cedula'] = $xml->civil->cedula;
                $vartrf['estadocivil'] = $xml->civil->cedula;
                $vartrf['dianacimiento'] = $xml->civil->dianacimiento;
                $vartrf['mesnacimiento'] = $xml->civil->mesnacimiento;
                $vartrf['anionacimiento'] = $xml->civil->anionacimiento;
                $vartrf['nombreempleador'] = $xml->actual->nombreempleador;
                $vartrf['direccionempleador'] = $xml->actual->direccionempleador;
                $vartrf['salarioactual'] = $xml->actual->salarioactual;
                $vartrf['telefonoempleador'] = $xml->actual->telefonoempleador;
                $vartrf['fechaentrada'] = $xml->actual->fechaentrada;
                $vartrf['cargo'] = $xml->actual->cargo;
                $vartrf['actividadempleador'] = $xml->actual->actividadempleador;
                $vartrf['nombreconyuge'] = $xml->civil->nombreconyuge;
                $vartrf['conyugecedula'] = $xml->conyugecedula->conyugecedula;
                // CALCULAR TIEMPO DE TRABAJO EN MESES
                /*$dt = time();
                $vartrf['fecha_actual'] = strftime("%d/%m/%Y", $dt);
                $fechaanterior = new DateTime($vartrf['fechaentrada']);
                $fechaactual = new DateTime();
                $fechaactual->format('d/m/Y');
                $diferencia = $fechaactual->diff($fechaanterior);
                $meses = ( $diferencia->y * 12 ) + $diferencia->m;
                $years = floor($meses / 12);
                $meses_resto = $meses % 12;*/


/*            }
        } catch(Exception $e){
            //ctadie('enter exception');
        }
        
        
        
        /*if ($modeldb->validate()) {
            echo 'no errors';
        } else {
            // validation failed: $errors is an array containing error messages
            $errors = $modeldb->errors;
            echo 'Error';
            echo '<pre>';
            print_r($errors);
            echo '</pre>';
            
        }*/
        
        
/*    }else{
        //echo 'ELSE-------------';
        $valid_cedula = 1;
        $dat = GestionDatabook::model()->find(array("condition" => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}"));
        $xml = simplexml_load_string($dat->xml_databook);

        if($countsc == 0){
            $vartrf['cedula'] = $xml->civil->cedula;
            $vartrf['estadocivil'] = $xml->civil->cedula;
            $vartrf['dianacimiento'] = $xml->civil->dianacimiento;
            $vartrf['mesnacimiento'] = $xml->civil->mesnacimiento;
            $vartrf['anionacimiento'] = $xml->civil->anionacimiento;
            $vartrf['nombreempleador'] = $xml->actual->nombreempleador;
            $vartrf['direccionempleador'] = $xml->actual->direccionempleador;
            //$vartrf['salarioactual'] = $xml->actual->salarioactual;
            $vartrf['telefonoempleador'] = $xml->actual->telefonoempleador;
            $vartrf['fechaentrada'] = $xml->actual->fechaentrada;
            $vartrf['cargo'] = $xml->actual->cargo;
            $vartrf['actividadempleador'] = $xml->actual->actividadempleador;
            $vartrf['nombreconyuge'] = $xml->civil->nombreconyuge;
            $vartrf['conyugecedula'] = $xml->conyugecedula->conyugecedula;
        }
        
        // CALCULAR TIEMPO DE TRABAJO EN MESES
        /*$dt = time();
        $vartrf['fecha_actual'] = strftime("%d/%m/%Y", $dt);
        $fechaanterior = new DateTime($vartrf['fechaentrada']);
        $fechaactual = new DateTime();
        $fechaactual->format('d/m/Y');
        $diferencia = $fechaactual->diff($fechaanterior);
        $meses = ( $diferencia->y * 12 ) + $diferencia->m;
        $years = floor($meses / 12);
        $meses_resto = $meses % 12;*/

        /*echo '<pre>';
        print_r($vartrf);
        echo '</pre>';*/
        //die();
//    }

//}
//echo 'salarioactual: '.$vartrf['salarioactual'];
//echo 'valid cedula: '.$valid_cedula;
# END CONSULTA A WEBSERVICE DE DATABOOK=======================================================================================

$grupo_id = (int) Yii::app()->user->getState('grupo_id');
$id_asesor = Yii::app()->user->getId();
$dealer_id = $this->getConcesionarioDealerId($id_asesor);
if((int) Yii::app()->user->getState('cargo_id') == 87){
    $dealer_id = $this->getConcesionarioDealerIdIngresado($id_informacion);
}
if((int) Yii::app()->user->getState('cargo_id') == 86 || (int) Yii::app()->user->getState('cargo_adicional')){
    $dealer_id = $this->getConcesionarioId($id_informacion);
}
$id_responsable = $this->getResponsableId($id_informacion);
$id_modelo = $this->getIdModelo($id_vehiculo);
//$nombre_modelo = $this->getVersion($id_vehiculo);
//$id_version = $this->getVersion($id_vehiculo);
//echo 'id repsonsable:'.$id_asesor;
$modelo = $this->getNombreModelo($id_informacion, $id_vehiculo);
$credito = $this->getFinanciamiento($id_informacion, $id_vehiculo);
$nombre_concesionario = $this->getNameConcesionarioById($dealer_id);
//echo 'nombre concesionario : '.$nombre_concesionario;
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<style type="text/css">
    .tl_seccion_rf_s {background-color: #aa1f2c;padding: 5px 28px;font-family: Arial, Helvetica, sans-serif;font-size: 18px;color: #fff;font-weight: bold;margin-top: 20px;width: 100%;}
    .activos label{font-size: 0.7em !important;}
    .activos .checkbox{margin-bottom: -6px;}
    .tl_seccion_rft{margin-left: 0px;width:100%;}
</style>
<script type="text/javascript">
    //getIngresosTotal();
    //getEgresosTotal();
    $(document).ready(function () {
        $.each(['#f00', '#ff0', '#0f0', '#0ff', '#00f', '#f0f', '#000', '#fff'], function () {
                $('#colors_demo .tools').append("<a href='#colors_sketch' data-color='" + this + "' style='width: 10px; background: " + this + ";'></a> ");
            });
            $.each([3, 5, 10, 15], function () {
                $('#colors_demo .tools').append("<a href='#colors_sketch' data-size='" + this + "' style='background: #ccc'>" + this + "</a> ");
            });
        $('.reset-canvas').click(function () {
            var cnv = $('#colors_sketch').get(0);
            var ctx = cnv.getContext('2d');
            clearCanvas(cnv, ctx); // note, this erases the canvas but not the drawing history!
            $('#colors_sketch').sketch().actions = [10]; // found it... probably not a very nice solution though

        });    
        $('.fancybox').fancybox();
        var sueldo_mensual = parseInt($('#GestionSolicitudCredito_sueldo_mensual').val());
        if(!isNaN(sueldo_mensual)){
            sueldo_mensual = format2(sueldo_mensual, '$');
            $('#GestionSolicitudCredito_sueldo_mensual').val(sueldo_mensual);
        }else{
            $('#GestionSolicitudCredito_sueldo_mensual').val('$ 0.00');
        }
        var read_first_time = parseInt('<?php echo $vartrf['read_first_time']; ?>');
        if(read_first_time == 1){
            var salario_solicitante = parseInt('<?php echo $vartrf['salarioactual']; ?>');
            salario_solicitante = format2(salario_solicitante,'$');
            $('#GestionSolicitudCredito_sueldo_mensual').val(salario_solicitante);
        }
        //salario_solicitante = format2(salario_solicitante,'$');

        //var salario_solicitante = parseInt('<?php echo $vartrf['salarioactual']; ?>');
        //salario_solicitante = format2(salario_solicitante,'$');
        //$('#GestionSolicitudCredito_sueldo_mensual').val(salario_solicitante);
        var estadocv  = $("#GestionSolicitudCredito_estado_civil").val();
        switch (estadocv) {
            case 'Soltero':
            case 'Viudo':
            case 'Divorciado':
            case 'Casado con separación de bienes':
                $('.conyugue').hide();
            break;
            case 'Casado sin separación de bienes':
            case 'Casado':
            case 'Union Libre':
            case 'Union_hecho':
                $('.conyugue').show();
            break;
        }
        var tipo_propiedad  = $("#GestionSolicitudCredito_habita").val();
        switch (tipo_propiedad) {
            case 'Propia':
                //$('#cont-avaluo').show();
                break;
            case 'Rentada':
                $('#cont-arriendo').show();
                break;
            case 'Vive con Familiares':
                break;
                
        }
        $('#GestionSolicitudCredito_select_cotizacion').change(function(){
           var value = $(this).attr('value');
           var tipo = value.split("-");
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getfinanciamiento"); ?>',
                dataType: "json",
                beforeSend: function (xhr) {
                    $('#info3').show();  // #info must be defined somehwere
                },
                type: 'post',
                data: {type: tipo[0], id:tipo[1]},
                success: function (data) {
                    //alert(data.options)
                    var valor = parseInt(data.valor);
                    valor = format2(valor, '$');
                    var monto_financiar = parseInt(data.monto_financiar);
                    monto_financiar = format2(monto_financiar, '$');
                    var entrada = parseInt(data.entrada);
                    entrada = format2(entrada, '$');
                    var cuota_mensual = parseInt(data.cuota_mensual);
                    cuota_mensual = format2(cuota_mensual, '$');
                    $('#GestionSolicitudCredito_valor').val(valor);
                    $('#GestionSolicitudCredito_monto_financiar').val(monto_financiar);
                    $('#GestionSolicitudCredito_entrada').val(entrada);
                    $('#GestionSolicitudCredito_plazo').val(data.plazo);
                    $('#GestionSolicitudCredito_taza').val(data.tasa);
                    $('#GestionSolicitudCredito_cuota_mensual').val(cuota_mensual);
                    $('#info3').hide();
                }
            });
        });

        $('#GestionSolicitudCredito_apellido_paterno_conyugue').keyup(function () {$('#GestionSolicitudCredito_apellido_paterno_conyugue').removeClass('error');$('#GestionSolicitudCredito_apellido_paterno_conyugue_error').hide();});
        $('#GestionSolicitudCredito_telefonos_trabajo').keyup(function(){$('#telefonos_trabajo_error').hide();});
        $('#GestionSolicitudCredito_nombres_conyugue').keyup(function () {$('#GestionSolicitudCredito_nombres_conyugue').removeClass('error');$('#GestionSolicitudCredito_nombres_conyugue_error').hide();});
        $('#GestionSolicitudCredito_cedula_conyugue').keyup(function () {$('#GestionSolicitudCredito_cedula_conyugue').removeClass('error');$('#GestionSolicitudCredito_cedula_conyugue_error').hide();});
        $('#GestionSolicitudCredito_fecha_nacimiento_conyugue').click(function () {$('#GestionSolicitudCredito_fecha_nacimiento_conyugue').removeClass('error');$('#GestionSolicitudCredito_fecha_nacimiento_conyugue_error').hide();});
        $('#GestionSolicitudCredito_empresa_trabajo_conyugue').keyup(function () {$('#GestionSolicitudCredito_empresa_trabajo_conyugue').removeClass('error');$('#GestionSolicitudCredito_empresa_trabajo_conyugue_error').hide();});
        $('#GestionSolicitudCredito_telefono_trabajo_conyugue').keyup(function () {$('#GestionSolicitudCredito_telefono_trabajo_conyugue').removeClass('error');$('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').hide();});
        $('#GestionSolicitudCredito_tiempo_trabajo_conyugue').click(function () {$('#GestionSolicitudCredito_tiempo_trabajo_conyugue').removeClass('error');$('#GestionSolicitudCredito_tiempo_trabajo_conyugue_error').hide();});
        $('#GestionSolicitudCredito_meses_trabajo_conyugue').click(function () {$('#GestionSolicitudCredito_meses_trabajo_conyugue').removeClass('error');$('#GestionSolicitudCredito_meses_trabajo_conyugue_error').hide();});
        $('#GestionSolicitudCredito_cargo_conyugue').keyup(function () {$('#GestionSolicitudCredito_cargo_conyugue').removeClass('error');$('#GestionSolicitudCredito_cargo_conyugue_error').hide();});
        $('#GestionSolicitudCredito_direccion_empresa_conyugue').keyup(function () {$('#GestionSolicitudCredito_direccion_empresa_conyugue').removeClass('error');$('#GestionSolicitudCredito_direccion_empresa_conyugue_error').hide();});
        $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').keyup(function () {$('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').removeClass('error');$('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue_error').hide();});
        $('#GestionSolicitudCredito_sueldo_mensual_conyugue').keyup(function () {$('#GestionSolicitudCredito_sueldo_mensual_conyugue').removeClass('error');$('#GestionSolicitudCredito_sueldo_mensual_conyugue_error').hide();$('#GestionSolicitudCredito_sueldo_mensual_conyugue_error2').hide();});
        $('#GestionSolicitudCredito_sueldo_mensual').keyup(function () {$('#GestionSolicitudCredito_sueldo_mensual').removeClass('error');$('#GestionSolicitudCredito_sueldo_mensual_error').hide();});
        $('#GestionSolicitudCredito_avaluo_propiedad').keyup(function () {$('#GestionSolicitudCredito_avaluo_propiedad_error').hide();});
        $('#GestionSolicitudCredito_conyugue_trabaja').change(function () {$('#GestionSolicitudCredito_conyugue_trabaja_error').hide();});
        $('#GestionSolicitudCredito_vehiculo_valor1').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_vehiculo_valor2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_valor_inversion').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_direccion_valor_comercial1').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_direccion_valor_comercial2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCreditovehiculo_valor1').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_valor_otros_activos1').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_valor_otros_activos2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_avaluo_propiedad').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_valor_arriendo').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_gastos_alimentacion_otros').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_gastos_educacion').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_gastos_prestamos').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_gastos_tarjetas_credito').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_sueldo_mensual').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_sueldo_mensual_conyugue').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_gastos_arriendo').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});

        $('#GestionSolicitudCredito_otros_ingresos').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_total_ingresos').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_total_egresos').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_monto_financiar').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_valor').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_entrada').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_cuota_mensual').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});

        var valor = parseInt($('#GestionSolicitudCredito_valor').val());
        var valorformat = format2(valor, '$');
        $('#GestionSolicitudCredito_valor').val(valorformat);

        var financiar = parseInt($('#GestionSolicitudCredito_monto_financiar').val());
        var financiarformat = format2(financiar, '$');
        $('#GestionSolicitudCredito_monto_financiar').val(financiarformat);

        var entrada = parseInt($('#GestionSolicitudCredito_entrada').val());
        var entradaformat = format2(entrada, '$');
        $('#GestionSolicitudCredito_entrada').val(entradaformat);

        
        var sueldo_mensual = parseInt($('#GestionSolicitudCredito_sueldo_mensual_conyugue').val());
        if(!isNaN(sueldo_mensual)){
            sueldo_mensual = format2(sueldo_mensual, '$');
            $('#GestionSolicitudCredito_sueldo_mensual_conyugue').val(sueldo_mensual);
        }else{
            $('#GestionSolicitudCredito_sueldo_mensual_conyugue').val('$ 0.00');
        }
        var cuotamensual = parseInt($('#GestionSolicitudCredito_cuota_mensual').val());
        var cuotamensualformat = format2(cuotamensual, '$');
        $('#GestionSolicitudCredito_cuota_mensual').val(cuotamensualformat);
        
        var avaluo_prop = parseInt($('#GestionSolicitudCredito_avaluo_propiedad').val());
        if(!isNaN(avaluo_prop)){
            avaluo_prop = format2(avaluo_prop, '$');
            $('#GestionSolicitudCredito_avaluo_propiedad').val(avaluo_prop);
        }else{
            $('#GestionSolicitudCredito_avaluo_propiedad').val('$ 0.00');
        }

        var otros_ingresos = parseInt($('#GestionSolicitudCredito_otros_ingresos').val());
        if(!isNaN(otros_ingresos)){
            otros_ingresos = format2(otros_ingresos, '$');
            $('#GestionSolicitudCredito_otros_ingresos').val(otros_ingresos);
        }else{
            $('#GestionSolicitudCredito_otros_ingresos').val('$ 0.00');
        }

        var total_ingresos = parseInt($('#GestionSolicitudCredito_total_ingresos').val());
        if(!isNaN(total_ingresos)){
            total_ingresos = format2(total_ingresos, '$');
            $('#GestionSolicitudCredito_total_ingresos').val(total_ingresos);
        }else{
            $('#GestionSolicitudCredito_total_ingresos').val('$ 0.00');
        }

        var gastos_alimentacion_otros = parseInt($('#GestionSolicitudCredito_gastos_alimentacion_otros').val());
        if(!isNaN(gastos_alimentacion_otros)){
            gastos_alimentacion_otros = format2(gastos_alimentacion_otros, '$');
            $('#GestionSolicitudCredito_gastos_alimentacion_otros').val(gastos_alimentacion_otros);
        }else{
            $('#GestionSolicitudCredito_gastos_alimentacion_otros').val('$ 0.00');
        }

        var gastos_prestamos = parseInt($('#GestionSolicitudCredito_gastos_prestamos').val());
        if(!isNaN(gastos_prestamos)){
            gastos_prestamos = format2(gastos_prestamos, '$');
            $('#GestionSolicitudCredito_gastos_prestamos').val(gastos_prestamos);
        }else{
            $('#GestionSolicitudCredito_gastos_prestamos').val('$ 0.00');
        }

        var gastos_tarjetas_credito = parseInt($('#GestionSolicitudCredito_gastos_tarjetas_credito').val());
        if(!isNaN(gastos_tarjetas_credito)){
            gastos_tarjetas_credito = format2(gastos_tarjetas_credito, '$');
            $('#GestionSolicitudCredito_gastos_tarjetas_credito').val(gastos_tarjetas_credito);
        }else{
            $('#GestionSolicitudCredito_gastos_tarjetas_credito').val('$ 0.00');
        }

        var gastos_educacion = parseInt($('#GestionSolicitudCredito_gastos_educacion').val());
        if(!isNaN(gastos_educacion)){
            gastos_educacion = format2(gastos_educacion, '$');
            $('#GestionSolicitudCredito_gastos_educacion').val(gastos_educacion);
        }else{
            $('#GestionSolicitudCredito_gastos_educacion').val('$ 0.00');
        }

        var total_egresos = parseInt($('#GestionSolicitudCredito_total_egresos').val());
        if(!isNaN(total_egresos)){
            total_egresos = format2(total_egresos, '$');
            $('#GestionSolicitudCredito_total_egresos').val(total_egresos);
        }else{
            $('#GestionSolicitudCredito_total_egresos').val('$ 0.00');
        }

        var sueldo_mensual = parseInt($('#GestionSolicitudCredito_gastos_arriendo').val());
        if(!isNaN(sueldo_mensual)){
            sueldo_mensual = format2(sueldo_mensual, '$');
            $('#GestionSolicitudCredito_gastos_arriendo').val(sueldo_mensual);
        }else{
            $('#GestionSolicitudCredito_gastos_arriendo').val('$ 0.00');
        }
        $("[name='GestionSolicitudCredito[avaluo_propiedad]']").keyup(function () {getvalortotal();});    
        $("[name='GestionSolicitudCredito[direccion_valor_comercial1]']").keyup(function () {getvalortotal();});
        $("[name='GestionSolicitudCredito[direccion_valor_comercial2]']").keyup(function () {getvalortotal();});
        $("[name='GestionSolicitudCredito[vehiculo_valor1]']").keyup(function () {getvalortotal();});
        $("[name='GestionSolicitudCredito[vehiculo_valor2]']").keyup(function () {getvalortotal();});
        $("[name='GestionSolicitudCredito[valor_inversion]']").keyup(function () {getvalortotal();});
        $("[name='GestionSolicitudCredito[valor_otros_activos1]']").keyup(function () {getvalortotal();});
        $("[name='GestionSolicitudCredito[valor_otros_activos2]']").keyup(function () {getvalortotal();});
        $("[name='GestionSolicitudCredito[sueldo_mensual]']").keyup(function () {getIngresosTotal();});
        $("[name='GestionSolicitudCredito[sueldo_mensual_conyugue]']").keyup(function () {getIngresosTotal();});
        $("[name='GestionSolicitudCredito[otros_ingresos]']").keyup(function () {getIngresosTotal();});
        $("[name='GestionSolicitudCredito[gastos_arriendo]']").keyup(function () {getEgresosTotal();});
        $("[name='GestionSolicitudCredito[gastos_alimentacion_otros]']").keyup(function () {getEgresosTotal();});
        $("[name='GestionSolicitudCredito[gastos_educacion]']").keyup(function () {getEgresosTotal();});
        $("[name='GestionSolicitudCredito[gastos_prestamos]']").keyup(function () {getEgresosTotal();});
        $("[name='GestionSolicitudCredito[gastos_tarjetas_credito]']").keyup(function () {getEgresosTotal();});
        $("#GestionSolicitudCredito_fecha_nacimiento").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1940, 10 - 1, 25),
            yearRange: '1940:1996'
        });
        $("#GestionSolicitudCredito_fecha_nacimiento_conyugue").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1940, 10 - 1, 25),
            yearRange: '1940:1996'
        });
        $('#GestionSolicitudCredito_estado_civil').change(function () {
            var value = $(this).attr('value');
            //alert(value);
            switch (value) {
                case 'Soltero':
                case 'Viudo':
                case 'Divorciado':
                case 'Casado con separación de bienes':
                    //validateCasado();
                    $('.conyugue').slideUp();
                    $('#GestionSolicitudCredito_sueldo_mensual_conyugue').val('');
                    $('.conyugue_trabaja').hide();
                    $('#GestionSolicitudCredito_apellido_paterno_conyugue').val('');
                    $('#GestionSolicitudCredito_apellido_materno_conyugue').val('');
                    $('#GestionSolicitudCredito_nombres_conyugue').val('');
                    $('#GestionSolicitudCredito_cedula_conyugue').val('');
                    $('#GestionSolicitudCredito_fecha_nacimiento_conyugue').val('');
                    $('#GestionSolicitudCredito_conyugue_trabaja').val('');
                    $('#GestionSolicitudCredito_empresa_trabajo_conyugue').val('');
                    $('#GestionSolicitudCredito_telefono_trabajo_conyugue').val('');
                    $('#GestionSolicitudCredito_tiempo_trabajo_conyugue').val('');
                    $('#GestionSolicitudCredito_meses_trabajo_conyugue').val('');
                    $('#GestionSolicitudCredito_cargo_conyugue').val('');
                    $('#GestionSolicitudCredito_direccion_empresa_conyugue').val('');
                    $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').val('');
                    $('#GestionSolicitudCredito_nacionalidad_conyugue').val('');
                    $('#GestionSolicitudCredito_nacionalidad_conyugue option[value="EC"]').attr('selected',false);
                    break;
                case 'Casado sin separación de bienes':
                case 'Casado':
                case 'Union Libre':
                case 'Union_hecho':
                    //validateSoltero();    
                    $('.conyugue').slideDown();$('.conyugue_trabaja').show();$('#GestionSolicitudCredito_conyugue_trabaja').focus();
                    break;
            }

        });
        $('#GestionSolicitudCredito_habita').change(function () {
            var value = $(this).attr('value');
            //alert(value);
            switch (value) {
                case 'Rentada':
                    $('#cont-arriendo').show();
                    //$('#cont-avaluo').hide();
                    break;
                case 'Propia':
                    $('#cont-arriendo').hide();
                   // $('#cont-avaluo').show();
                    break;
                case 'Vive con Familiares':
                    $('#cont-arriendo').hide();
                    //$('#cont-avaluo').hide();
                    break;
            }

        });
        $('#GestionSolicitudCredito_banco1').change(function () {
            var value = $(this).attr('value');
            if (value == 'Otros') {
                $('.otro-bn-1').show();
            } else {
                $('.otro-bn-1').hide();
            }
        }
        );
        $('#GestionSolicitudCredito_banco2').change(function () {
            var value = $(this).attr('value');
            if (value == 'Otros') {
                $('.otro-bn-2').show();
            } else {
                $('.otro-bn-2').hide();
            }
        }
        );
        $('#GestionSolicitudCredito_provincia_domicilio').change(function () {
            var value = $("#GestionSolicitudCredito_provincia_domicilio option:selected").val();
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
                    $('#GestionSolicitudCredito_ciudad_domicilio').html(data);
                    $('#info3').hide();
                }
            });
            //alert(value)
            $('#GestionInformacion_ciudad_domicilio').html(data);

        });
    });
    function createsc(){
        var error = 0;
        $('#gestion-solicitud-credito-form').validate({
            rules: {
                'GestionSolicitudCredito[modelo]': {required: true},
                'GestionSolicitudCredito[valor]': {required: true},
                'GestionSolicitudCredito[monto_financiar]': {required: true},
                'GestionSolicitudCredito[entrada]': {required: true},
                'GestionSolicitudCredito[year]': {required: true},
                'GestionSolicitudCredito[plazo]': {required: true},
                'GestionSolicitudCredito[taza]': {required: true},
                'GestionSolicitudCredito[cuota_mensual]': {required: true},
                'GestionSolicitudCredito[apellido_paterno]': {required: true},
                'GestionSolicitudCredito[nombres]': {required: true},
                'GestionSolicitudCredito[cedula]': {required: true},
                'GestionSolicitudCredito[estado_civil]': {required: true},
                'GestionSolicitudCredito[fecha_nacimiento]': {required: true},
                'GestionSolicitudCredito[empresa_trabajo]': {required: true},
                'GestionSolicitudCredito[sector]': {required: true},
                'GestionSolicitudCredito[telefonos_trabajo]': {required: true},
                'GestionSolicitudCredito[tiempo_trabajo]': {required: true},
                'GestionSolicitudCredito[cargo]': {required: true},
                'GestionSolicitudCredito[direccion_empresa]': {required: true},
                'GestionSolicitudCredito[tipo_relacion_laboral]': {required: true},
                'GestionSolicitudCredito[email]': {required: true},
                'GestionSolicitudCredito[actividad_empresa]': {required: true}
            },
            messages: {},
            submitHandler: function (form) {
                var dataform = $("#gestion-solicitud-credito-form").serialize();
                var fechaNac = $('#GestionSolicitudCredito_fecha_nacimiento').val();
                var fechaActual = new Date().toJSON().slice(0, 10);
                //console.log('Fecha Actual: '+fechaActual);
                var diferencia = restaFechas(fechaNac, fechaActual);
                //console.log('dias: '+diferencia);
                if (diferencia < 6480) {
                    alert('El cliente debe ser mayor de 18 años');
                    return false;
                }
                var conyugue_trabaja = $('#GestionSolicitudCredito_conyugue_trabaja').val();
                if(conyugue_trabaja == ''){
                    $('#GestionSolicitudCredito_conyugue_trabaja_error').show();
                    $('#GestionSolicitudCredito_conyugue_trabaja').focus().addClass('error');
                    error++;
                }
                $.ajax({
                    url: '<?php echo $url; ?>',
                    beforeSend: function (xhr) {
                        $('#bg_negro').show();  // #bg_negro must be defined somewhere
                    },
                    datatype: "json",
                    type: 'POST',
                    data: dataform,
                    success: function (data) {
                        //var returnedData = JSON.parse(data);
                        //alert(returnedData.result);
                        //$('#bg_negro').hide();
                        $(location).attr('href', '<?php echo $url_load; ?>');
                        //$('#GestionFinanciamiento_ipdfid').val(returnedData.id);
                    }
                });
            }
        });
    }
    function createsc2(){
        $('#gestion-solicitud-credito-form').validate({
            rules: {
                'GestionSolicitudCredito[modelo]': {required: true},
                'GestionSolicitudCredito[valor]': {required: true},
                'GestionSolicitudCredito[monto_financiar]': {required: true},
                'GestionSolicitudCredito[entrada]': {required: true},
                'GestionSolicitudCredito[year]': {required: true},
                'GestionSolicitudCredito[plazo]': {required: true},
                'GestionSolicitudCredito[taza]': {required: true},
                'GestionSolicitudCredito[cuota_mensual]': {required: true},
                'GestionSolicitudCredito[apellido_paterno]': {required: true},
                'GestionSolicitudCredito[nombres]': {required: true},
                'GestionSolicitudCredito[cedula]': {required: true},
                'GestionSolicitudCredito[estado_civil]': {required: true},
                'GestionSolicitudCredito[fecha_nacimiento]': {required: true},
                'GestionSolicitudCredito[empresa_trabajo]': {required: true},
                'GestionSolicitudCredito[sector]': {required: true},
                'GestionSolicitudCredito[telefonos_trabajo]': {required: true},
                'GestionSolicitudCredito[tiempo_trabajo]': {required: true},
                'GestionSolicitudCredito[cargo]': {required: true},
                'GestionSolicitudCredito[direccion_empresa]': {required: true},
                'GestionSolicitudCredito[tipo_relacion_laboral]': {required: true},
                'GestionSolicitudCredito[email]': {required: true},
                'GestionSolicitudCredito[actividad_empresa]': {required: true}
            },
            messages: {},
            submitHandler: function (form) {
                console.log('ENTER SUBMIT CREATE2');
                var error = 0;
                var estado_civil = $('#GestionSolicitudCredito_estado_civil').val();
                var telefono_trabajo = $('#GestionSolicitudCredito_telefonos_trabajo').val();
                var conyugue_trabaja = $('#GestionSolicitudCredito_conyugue_trabaja').val();
                //alert(conyugue_trabaja);
                if(countChar(telefono_trabajo) == false){
                    $('#telefonos_trabajo_error').show();
                    $('#GestionSolicitudCredito_telefonos_trabajo').focus();
                    return false;
                }
                if(estado_civil == 'Casado' || estado_civil == 'Casado sin separación de bienes'){
                   if(countChar($('#GestionSolicitudCredito_telefono_trabajo_conyugue').val()) == false){
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').show();
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue').focus();
                        return false;
                    } 
                }
                error = validate_estado_civil(estado_civil, error, conyugue_trabaja, 2);
                if(error > 0)
                    return false;
                
                var fechaNac = $('#GestionSolicitudCredito_fecha_nacimiento').val();
                var fechaActual = new Date().toJSON().slice(0, 10);
                //console.log('Fecha Actual: '+fechaActual);
                var diferencia = restaFechas(fechaNac, fechaActual);
                //console.log('dias: '+diferencia);
                if (diferencia < 6480) {
                    alert('El cliente debe ser mayor de 18 años');
                    return false;
                }
                
                var dataform = $("#gestion-solicitud-credito-form").serialize();
                var count = 0;
                var cedulaConyugue = $('#GestionSolicitudCredito_cedula_conyugue').val();
                console.log('valor cedula: ' + cedulaConyugue);
                if (cedulaConyugue != '' && error == 0) {
                    var validateCedula = CedulaValida(cedulaConyugue);
                    if (validateCedula) {
                        $.ajax({
                            url: '<?php echo $url; ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            datatype: "json",
                            type: 'POST',
                            data: dataform,
                            success: function (data) {
                                //var returnedData = JSON.parse(data);
                                //alert(returnedData.result);
                                //$('#bg_negro').hide();
                                $(location).attr('href', '<?php echo $url_load; ?>');
                            }
                        });
                    } else {
                        alert('Cédula inválida de cónyugue');
                        $('#GestionSolicitudCredito_cedula_conyugue').focus();
                    }
                } else if (error == 0) {
                    $.ajax({
                        url: '<?php echo $url; ?>',
                        beforeSend: function (xhr) {
                            $('#bg_negro').show();  // #bg_negro must be defined somewhere
                        },
                        datatype: "json",
                        type: 'POST',
                        data: dataform,
                        success: function (data) {
                            //var returnedData = JSON.parse(data);
                            //alert(returnedData.result);
                            //$('#bg_negro').hide();
                            $(location).attr('href', '<?php echo $url_load; ?>');
                        }
                    });
                }
            }
        });
    }
    function createsc3(){
        $('#gestion-solicitud-credito-form').validate({
            rules: {
                'GestionSolicitudCredito[modelo]': {required: true},
                'GestionSolicitudCredito[valor]': {required: true},
                'GestionSolicitudCredito[monto_financiar]': {required: true},
                'GestionSolicitudCredito[entrada]': {required: true},
                'GestionSolicitudCredito[year]': {required: true},
                'GestionSolicitudCredito[plazo]': {required: true},
                'GestionSolicitudCredito[taza]': {required: true},
                'GestionSolicitudCredito[cuota_mensual]': {required: true},
                'GestionSolicitudCredito[apellido_paterno]': {required: true},
                'GestionSolicitudCredito[nombres]': {required: true},
                'GestionSolicitudCredito[cedula]': {required: true},
                'GestionSolicitudCredito[estado_civil]': {required: true},
                'GestionSolicitudCredito[fecha_nacimiento]': {required: true},
                'GestionSolicitudCredito[empresa_trabajo]': {required: true},
                'GestionSolicitudCredito[sector]': {required: true},
                'GestionSolicitudCredito[telefonos_trabajo]': {required: true},
                'GestionSolicitudCredito[tiempo_trabajo]': {required: true},
                'GestionSolicitudCredito[cargo]': {required: true},
                'GestionSolicitudCredito[direccion_empresa]': {required: true},
                'GestionSolicitudCredito[tipo_relacion_laboral]': {required: true},
                'GestionSolicitudCredito[email]': {required: true},
                'GestionSolicitudCredito[actividad_empresa]': {required: true},
                'GestionSolicitudCredito[ciudad_domicilio]': {required: true},
                'GestionSolicitudCredito[barrio]': {required: true},
                <?php if($grupo_id == 3 || $grupo_id == 4)
                echo "'GestionSolicitudCredito[manzana]': {required: true},";
                 ?>
                'GestionSolicitudCredito[calle]': {required: true},
                'GestionSolicitudCredito[telefono_residencia]': {required: true},
                'GestionSolicitudCredito[sueldo_mensual]': {required: true},
                'GestionSolicitudCredito[total_ingresos]': {required: true},
                'GestionSolicitudCredito[celular]': {required: true},
                'GestionSolicitudCredito[habita]': {required: true},
                'GestionSolicitudCredito[numero]': {required: true},
                'GestionSolicitudCredito[gastos_alimentacion_otros]': {required: true},
                'GestionSolicitudCredito[numero]': {required: true},
                'GestionSolicitudCredito[total_egresos]': {required: true}
            },
            messages: {},
            submitHandler: function (form) {
                var error = 0;
                var estado_civil = $('#GestionSolicitudCredito_estado_civil').val();
                var telefono_trabajo = $('#GestionSolicitudCredito_telefonos_trabajo').val();
                var conyugue_trabaja = $('#GestionSolicitudCredito_conyugue_trabaja').val();
                //alert(conyugue_trabaja);
                if(countChar(telefono_trabajo) == false){
                    $('#telefonos_trabajo_error').show();
                    $('#GestionSolicitudCredito_telefonos_trabajo').focus();
                    return false;
                }
                if(estado_civil == 'Casado' || estado_civil == 'Casado sin separación de bienes'){
                   if(countChar($('#GestionSolicitudCredito_telefono_trabajo_conyugue').val()) == false){
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').show();
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue').focus();
                        return false;
                    } 
                }
                var conyugue_validate = validate_estado_civil(estado_civil, error, conyugue_trabaja,3);
                if(conyugue_validate > 0)
                    return false;
                
                var fechaNac = $('#GestionSolicitudCredito_fecha_nacimiento').val();
                var fechaActual = new Date().toJSON().slice(0, 10);
                //console.log('Fecha Actual: '+fechaActual);
                var diferencia = restaFechas(fechaNac, fechaActual);
                //console.log('dias: '+diferencia);
                if (diferencia < 6480) {
                    alert('El cliente debe ser mayor de 18 años');
                    return false;
                }
                var tipo_propiedad = $('#GestionSolicitudCredito_habita').val();
            /*    if(tipo_propiedad == 'Propia'){
                    var avaluo_propiedad = formatnumber($('#GestionSolicitudCredito_avaluo_propiedad').val())
                    if(avaluo_propiedad < 9999){
                        $('#GestionSolicitudCredito_avaluo_propiedad_error').show();
                        $('#GestionSolicitudCredito_avaluo_propiedad').focus().addClass('error');
                        return false;
                    }
                }*/
                
                var dataform = $("#gestion-solicitud-credito-form").serialize();
                var count = 0;
                var cedulaConyugue = $('#GestionSolicitudCredito_cedula_conyugue').val();
                console.log('valor cedula: ' + cedulaConyugue);
                var otros_ingresos = formatnumber($('#GestionSolicitudCredito_otros_ingresos').val());
                //alert(otros_ingresos);
                if(otros_ingresos > 0){
                    if($('#GestionSolicitudCredito_descripcion_otros_ingresos').val() == ''){
                        //alert('Ingrese descripcion de otros ingresos');
                        $('#GestionSolicitudCredito_descripcion_otros_ingresos_error').show();
                        $('#GestionSolicitudCredito_descripcion_otros_ingresos').focus();
                        return false;
                    }
                }else{
                    $('#GestionSolicitudCredito_descripcion_otros_ingresos_error').hide();
                }

                var sueldo_soltero = formatnumber($('#GestionSolicitudCredito_sueldo_mensual').val());
                if(sueldo_soltero < 300){
                    $('#GestionSolicitudCredito_sueldo_mensual_error').show();
                    $('#GestionSolicitudCredito_sueldo_mensual').focus().addClass('error');
                    error++;
                    return false;
                }
                if(conyugue_trabaja == 1){
                    var sueldo_casado = formatnumber($('#GestionSolicitudCredito_sueldo_mensual_conyugue').val());
                    if(sueldo_casado < 300){
                        $('#GestionSolicitudCredito_sueldo_mensual_conyugue_error2').show();
                        $('#GestionSolicitudCredito_sueldo_mensual_conyugue').focus().addClass('error');
                        errot++;
                        return false;
                    }
                }
                
                if (cedulaConyugue != '' && error == 0) {
                    console.log('enter primero');
                    var validateCedula = CedulaValida(cedulaConyugue);
                    if (validateCedula) {
                        $.ajax({
                            url: '<?php echo $url; ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            datatype: "json",
                            type: 'POST',
                            data: dataform,
                            success: function (data) {
                                //var returnedData = JSON.parse(data);
                                //alert(returnedData.result);
                                //$('#bg_negro').hide();
                                $(location).attr('href', '<?php echo $url_load; ?>');
                            }
                        });
                    } else {
                        alert('Cédula inválida de cónyugue');
                        $('#GestionSolicitudCredito_cedula_conyugue').focus();
                    }
                } else if (error == 0) {
                    console.log('enter error 0');
                    $.ajax({
                        url: '<?php echo $url; ?>',
                        beforeSend: function (xhr) {
                            $('#bg_negro').show();  // #bg_negro must be defined somewhere
                        },
                        datatype: "json",
                        type: 'POST',
                        data: dataform,
                        success: function (data) {
                            //var returnedData = JSON.parse(data);
                            //alert(returnedData.result);
                            //$('#bg_negro').hide();
                            $(location).attr('href', '<?php echo $url_load; ?>');
                        }
                    });
                }
            }
        });
    }
    function createsc4(){
        $('#gestion-solicitud-credito-form').validate({
            rules: {
                'GestionSolicitudCredito[modelo]': {required: true},
                'GestionSolicitudCredito[valor]': {required: true},
                'GestionSolicitudCredito[monto_financiar]': {required: true},
                'GestionSolicitudCredito[entrada]': {required: true},
                'GestionSolicitudCredito[year]': {required: true},
                'GestionSolicitudCredito[plazo]': {required: true},
                'GestionSolicitudCredito[taza]': {required: true},
                'GestionSolicitudCredito[cuota_mensual]': {required: true},
                'GestionSolicitudCredito[apellido_paterno]': {required: true},
                'GestionSolicitudCredito[nombres]': {required: true},
                'GestionSolicitudCredito[cedula]': {required: true},
                'GestionSolicitudCredito[estado_civil]': {required: true},
                'GestionSolicitudCredito[fecha_nacimiento]': {required: true},
                'GestionSolicitudCredito[empresa_trabajo]': {required: true},
                'GestionSolicitudCredito[sector]': {required: true},
                'GestionSolicitudCredito[telefonos_trabajo]': {required: true},
                'GestionSolicitudCredito[tiempo_trabajo]': {required: true},
                'GestionSolicitudCredito[cargo]': {required: true},
                'GestionSolicitudCredito[direccion_empresa]': {required: true},
                'GestionSolicitudCredito[tipo_relacion_laboral]': {required: true},
                'GestionSolicitudCredito[email]': {required: true},
                'GestionSolicitudCredito[actividad_empresa]': {required: true},
                'GestionSolicitudCredito[ciudad_domicilio]': {required: true},
                'GestionSolicitudCredito[barrio]': {required: true},
                <?php if($grupo_id == 3 || $grupo_id == 4)
                echo "'GestionSolicitudCredito[manzana]': {required: true},";
                 ?>
                'GestionSolicitudCredito[calle]': {required: true},
                'GestionSolicitudCredito[telefono_residencia]': {required: true},
                'GestionSolicitudCredito[sueldo_mensual]': {required: true},
                'GestionSolicitudCredito[celular]': {required: true},
                'GestionSolicitudCredito[habita]': {required: true},
                'GestionSolicitudCredito[numero]': {required: true}
            },
            messages: {},
            submitHandler: function (form) {
                var error = 0;
                var estado_civil = $('#GestionSolicitudCredito_estado_civil').val();
                var telefono_trabajo = $('#GestionSolicitudCredito_telefonos_trabajo').val();
                var conyugue_trabaja = $('#GestionSolicitudCredito_conyugue_trabaja').val();
                //alert(conyugue_trabaja);
                if(countChar(telefono_trabajo) == false){
                    $('#telefonos_trabajo_error').show();
                    $('#GestionSolicitudCredito_telefonos_trabajo').focus();
                    return false;
                }
                if(estado_civil == 'Casado' || estado_civil == 'Casado sin separación de bienes'){
                   if(countChar($('#GestionSolicitudCredito_telefono_trabajo_conyugue').val()) == false){
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').show();
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue').focus();
                        return false;
                    } 
                }
                validate_estado_civil(estado_civil, error, conyugue_trabaja,4);
                
                var fechaNac = $('#GestionSolicitudCredito_fecha_nacimiento').val();
                var fechaActual = new Date().toJSON().slice(0, 10);
                //console.log('Fecha Actual: '+fechaActual);
                var diferencia = restaFechas(fechaNac, fechaActual);
                //console.log('dias: '+diferencia);
                if (diferencia < 6480) {
                    alert('El cliente debe ser mayor de 18 años');
                    return false;
                }
                var tipo_propiedad = $('#GestionSolicitudCredito_habita').val();
                /*if(tipo_propiedad == 'Propia'){
                    var avaluo_propiedad = formatnumber($('#GestionSolicitudCredito_avaluo_propiedad').val())
                    if(avaluo_propiedad < 9999){
                        $('#GestionSolicitudCredito_avaluo_propiedad_error').show();
                        $('#GestionSolicitudCredito_avaluo_propiedad').focus().addClass('error');
                        return false;
                    }
                }*/
                
                var dataform = $("#gestion-solicitud-credito-form").serialize();
                var count = 0;
                var cedulaConyugue = $('#GestionSolicitudCredito_cedula_conyugue').val();
                console.log('valor cedula: ' + cedulaConyugue);
                
                var sueldo_soltero = formatnumber($('#GestionSolicitudCredito_sueldo_mensual').val());
                if(sueldo_soltero < 300){
                    $('#GestionSolicitudCredito_sueldo_mensual_error').show();
                    $('#GestionSolicitudCredito_sueldo_mensual').focus().addClass('error');
                    error++;
                    return false;
                }
                if(conyugue_trabaja == 1){
                    var sueldo_casado = formatnumber($('#GestionSolicitudCredito_sueldo_mensual_conyugue').val());
                    if(sueldo_casado < 300){
                        $('#GestionSolicitudCredito_sueldo_mensual_conyugue_error2').show();
                        $('#GestionSolicitudCredito_sueldo_mensual_conyugue').focus().addClass('error');
                        errot++;
                        return false;
                    }
                }
                
                if (cedulaConyugue != '' && error == 0) {
                    var validateCedula = CedulaValida(cedulaConyugue);
                    if (validateCedula) {
                        $.ajax({
                            url: '<?php echo $url; ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            datatype: "json",
                            type: 'POST',
                            data: dataform,
                            success: function (data) {
                                //var returnedData = JSON.parse(data);
                                //alert(returnedData.result);
                                //$('#bg_negro').hide();
                                $(location).attr('href', '<?php echo $url_load; ?>');
                            }
                        });
                    } else {
                        alert('Cédula inválida de cónyugue');
                        $('#GestionSolicitudCredito_cedula_conyugue').focus();
                    }
                } else if (error == 0) {
                    $.ajax({
                        url: '<?php echo $url; ?>',
                        beforeSend: function (xhr) {
                            $('#bg_negro').show();  // #bg_negro must be defined somewhere
                        },
                        datatype: "json",
                        type: 'POST',
                        data: dataform,
                        success: function (data) {
                            //var returnedData = JSON.parse(data);
                            //alert(returnedData.result);
                            //$('#bg_negro').hide();
                            $(location).attr('href', '<?php echo $url_load; ?>');
                        }
                    });
                }
            }
        });
    }

    function createsc5() {
        //console.log('enter send');
        $('#gestion-solicitud-credito-form').validate({
            rules: {
                'GestionSolicitudCredito[modelo]': {required: true},
                'GestionSolicitudCredito[valor]': {required: true},
                'GestionSolicitudCredito[monto_financiar]': {required: true},
                'GestionSolicitudCredito[entrada]': {required: true},
                'GestionSolicitudCredito[year]': {required: true},
                'GestionSolicitudCredito[plazo]': {required: true},
                'GestionSolicitudCredito[taza]': {required: true},
                'GestionSolicitudCredito[cuota_mensual]': {required: true},
                'GestionSolicitudCredito[apellido_paterno]': {required: true},
                'GestionSolicitudCredito[nombres]': {required: true},
                'GestionSolicitudCredito[cedula]': {required: true},
                'GestionSolicitudCredito[estado_civil]': {required: true},
                'GestionSolicitudCredito[fecha_nacimiento]': {required: true},
                'GestionSolicitudCredito[empresa_trabajo]': {required: true},
                'GestionSolicitudCredito[sector]': {required: true},
                'GestionSolicitudCredito[telefonos_trabajo]': {required: true},
                'GestionSolicitudCredito[tiempo_trabajo]': {required: true},
                'GestionSolicitudCredito[cargo]': {required: true},
                'GestionSolicitudCredito[direccion_empresa]': {required: true},
                'GestionSolicitudCredito[tipo_relacion_laboral]': {required: true},
                'GestionSolicitudCredito[email]': {required: true},
                'GestionSolicitudCredito[actividad_empresa]': {required: true},
                'GestionSolicitudCredito[ciudad_domicilio]': {required: true},
                'GestionSolicitudCredito[barrio]': {required: true},
                <?php if($grupo_id == 3 || $grupo_id == 4)
                echo "'GestionSolicitudCredito[manzana]': {required: true},";
                 ?>
                'GestionSolicitudCredito[calle]': {required: true},
                'GestionSolicitudCredito[telefono_residencia]': {required: true},
                'GestionSolicitudCredito[sueldo_mensual]': {required: true},
                //'GestionSolicitudCredito[banco1]': {required: true},
                //'GestionSolicitudCredito[cuenta_ahorros1]':{required: true},
                'GestionSolicitudCredito[celular]': {required: true},
                'GestionSolicitudCredito[referencia_personal1]': {required: true},
                'GestionSolicitudCredito[parentesco1]': {required: true},
                'GestionSolicitudCredito[telefono_referencia1]': {required: true},
                //'GestionSolicitudCredito[referencia_personal2]': {required: true},
                //'GestionSolicitudCredito[parentesco2]': {required: true},
                //'GestionSolicitudCredito[telefono_referencia2]': {required: true},
                'GestionSolicitudCredito[habita]': {required: true},
                'GestionSolicitudCredito[numero]': {required: true}
            },
            messages: {},
            submitHandler: function (form) {
                var error = 0;
                console.log('enter submit');
                var estado_civil = $('#GestionSolicitudCredito_estado_civil').val();
                var telefono_trabajo = $('#GestionSolicitudCredito_telefonos_trabajo').val();
                var conyugue_trabaja = $('#GestionSolicitudCredito_conyugue_trabaja').val();
                //alert(conyugue_trabaja);
                if(countChar(telefono_trabajo) == false){
                    $('#telefonos_trabajo_error').show();
                    $('#GestionSolicitudCredito_telefonos_trabajo').focus();
                    return false;
                }
                if(estado_civil == 'Casado' || estado_civil == 'Casado sin separación de bienes'){
                   if(countChar($('#GestionSolicitudCredito_telefono_trabajo_conyugue').val()) == false){
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').show();
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue').focus();
                        return false;
                    } 
                }
                
                switch (estado_civil) {
                    case 'Soltero':
                    case 'Viudo':
                    case 'Divorciado':
                    case 'Casado con separación de bienes':
                        var cadena = $('#GestionSolicitudCredito_telefonos_trabajo').val();
                            var letra = cadena.charAt(1);
                            if(letra == '0'){
                               error++; 
                               $('#telefonos_trabajo_error').show();
                               $('#GestionSolicitudCredito_telefonos_trabajo').focus().addClass('error');
                            }
                        var sueldo_soltero = formatnumber($('#GestionSolicitudCredito_sueldo_mensual').val());
                        if(sueldo_soltero < 300){
                            $('#GestionSolicitudCredito_sueldo_mensual_error').show();
                            $('#GestionSolicitudCredito_sueldo_mensual').focus().addClass('error');
                            error++;
                            return false;
                        }
                        //validateCasado();
                        break;
                    case 'Casado sin separación de bienes':
                    case 'Casado':
                    case 'Union Libre':
                    case 'Union_hecho':
                        //validateSoltero();
                        if ($('#GestionSolicitudCredito_apellido_paterno_conyugue').val() == '') {
                            $('#GestionSolicitudCredito_apellido_paterno_conyugue_error').show();
                            $('#GestionSolicitudCredito_apellido_paterno_conyugue').focus().addClass('error');
                            error++;
                        }
                        if ($('#GestionSolicitudCredito_nombres_conyugue').val() == '') {
                            $('#GestionSolicitudCredito_nombres_conyugue_error').show();
                            $('#GestionSolicitudCredito_nombres_conyugue').focus().addClass('error');
                            error++;
                        }
                        if ($('#GestionSolicitudCredito_cedula_conyugue').val() == '') {
                            $('#GestionSolicitudCredito_cedula_conyugue_error').show();
                            $('#GestionSolicitudCredito_cedula_conyugue').focus().addClass('error');
                            error++;
                        }
                        if ($('#GestionSolicitudCredito_fecha_nacimiento_conyugue').val() == '') {
                            $('#GestionSolicitudCredito_fecha_nacimiento_conyugue_error').show();
                            $('#GestionSolicitudCredito_fecha_nacimiento_conyugue').focus().addClass('error');
                            error++;
                        }
                        if(conyugue_trabaja == ''){
                            $('#GestionSolicitudCredito_conyugue_trabaja_error').show();
                            $('#GestionSolicitudCredito_conyugue_trabaja').focus().addClass('error');
                            error++;
                        }
                        if(conyugue_trabaja == 1){
                            if ($('#GestionSolicitudCredito_empresa_trabajo_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_empresa_trabajo_conyugue_error').show();
                                $('#GestionSolicitudCredito_empresa_trabajo_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_sector_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_sector_conyugue_error').show();
                                $('#GestionSolicitudCredito_sector_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_telefono_trabajo_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').show();
                                $('#GestionSolicitudCredito_telefono_trabajo_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_tiempo_trabajo_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_tiempo_trabajo_conyugue_error').show();
                                $('#GestionSolicitudCredito_tiempo_trabajo_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_meses_trabajo_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_meses_trabajo_conyugue_error').show();
                                $('#GestionSolicitudCredito_meses_trabajo_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_cargo_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_cargo_conyugue_error').show();
                                $('#GestionSolicitudCredito_cargo_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_direccion_empresa_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_direccion_empresa_conyugue_error').show();
                                $('#GestionSolicitudCredito_direccion_empresa_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue_error').show();
                                $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_sueldo_mensual_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_sueldo_mensual_conyugue_error').show();
                                $('#GestionSolicitudCredito_sueldo_mensual_conyugue').focus().addClass('error');
                                error++;
                            }
                        }
                        var sueldo_soltero = formatnumber($('#GestionSolicitudCredito_sueldo_mensual').val());
                        if(sueldo_soltero < 300){
                            $('#GestionSolicitudCredito_sueldo_mensual_error').show();
                            $('#GestionSolicitudCredito_sueldo_mensual').focus().addClass('error');
                            error++;
                            return false;
                        }
                        if(conyugue_trabaja == 1){
                            var sueldo_casado = formatnumber($('#GestionSolicitudCredito_sueldo_mensual_conyugue').val());
                            if(sueldo_casado < 300){
                                $('#GestionSolicitudCredito_sueldo_mensual_conyugue_error2').show();
                                $('#GestionSolicitudCredito_sueldo_mensual_conyugue').focus().addClass('error');
                                errot++;
                                return false;
                            }
                        }
                        break;
                }
                var fechaNac = $('#GestionSolicitudCredito_fecha_nacimiento').val();
                var fechaActual = new Date().toJSON().slice(0, 10);
                //console.log('Fecha Actual: '+fechaActual);
                var diferencia = restaFechas(fechaNac, fechaActual);
                //console.log('dias: '+diferencia);
                if (diferencia < 6480) {
                    alert('El cliente debe ser mayor de 18 años');
                    return false;
                }
                
                var tipo_propiedad = $('#GestionSolicitudCredito_habita').val();
                /*if(tipo_propiedad == 'Propia'){
                    var avaluo_propiedad = formatnumber($('#GestionSolicitudCredito_avaluo_propiedad').val())
                    if(avaluo_propiedad < 9999){
                        $('#GestionSolicitudCredito_avaluo_propiedad_error').show();
                        $('#GestionSolicitudCredito_avaluo_propiedad').focus().addClass('error');
                        return false;
                    }
                }*/
                //console.log('enter submit');
                //if (confirm('Desea grabar los datos ingresados y enviar la solicitud al Asesor de Crédito?')) {
                var dataform = $("#gestion-solicitud-credito-form").serialize();
                var count = 0;
                var cedulaConyugue = $('#GestionSolicitudCredito_cedula_conyugue').val();
                console.log('valor cedula: ' + cedulaConyugue);
                if (cedulaConyugue != '' && error == 0) {
                    var validateCedula = CedulaValida(cedulaConyugue);
                    if (validateCedula) {
                        $.ajax({
                            url: '<?php echo $url; ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            datatype: "json",
                            type: 'POST',
                            data: dataform,
                            success: function (data) {
                                //var returnedData = JSON.parse(data);
                                //alert(returnedData.result);
                                $('#bg_negro').hide();
                                $(location).attr('href', '<?php echo $url_load; ?>');
                            }
                        });
                    } else {
                        alert('Cédula inválida de cónyugue');
                        $('#GestionSolicitudCredito_cedula_conyugue').focus();
                    }
                } else if (error == 0) {
                    console.log('enter no cony');
                    $.ajax({
                        url: '<?php echo $url; ?>',
                        beforeSend: function (xhr) {
                            $('#bg_negro').show();  // #bg_negro must be defined somewhere
                        },
                        datatype: "json",
                        type: 'POST',
                        data: dataform,
                        success: function (data) {
                            //var returnedData = JSON.parse(data);
                            //alert(returnedData.result);
                            $('#bg_negro').hide();
                            $(location).attr('href', '<?php echo $url_load; ?>');
                        }
                    });
                }

                //}
            }
        });
    }
    function createsc6(){
        $('#gestion-solicitud-credito-form').validate({
            rules: {
                'GestionSolicitudCredito[modelo]': {required: true},
                'GestionSolicitudCredito[valor]': {required: true},
                'GestionSolicitudCredito[monto_financiar]': {required: true},
                'GestionSolicitudCredito[entrada]': {required: true},
                'GestionSolicitudCredito[year]': {required: true},
                'GestionSolicitudCredito[plazo]': {required: true},
                'GestionSolicitudCredito[taza]': {required: true},
                'GestionSolicitudCredito[cuota_mensual]': {required: true},
                'GestionSolicitudCredito[apellido_paterno]': {required: true},
                'GestionSolicitudCredito[nombres]': {required: true},
                'GestionSolicitudCredito[cedula]': {required: true},
                'GestionSolicitudCredito[estado_civil]': {required: true},
                'GestionSolicitudCredito[fecha_nacimiento]': {required: true},
                'GestionSolicitudCredito[empresa_trabajo]': {required: true},
                'GestionSolicitudCredito[sector]': {required: true},
                'GestionSolicitudCredito[telefonos_trabajo]': {required: true},
                'GestionSolicitudCredito[tiempo_trabajo]': {required: true},
                'GestionSolicitudCredito[cargo]': {required: true},
                'GestionSolicitudCredito[direccion_empresa]': {required: true},
                'GestionSolicitudCredito[tipo_relacion_laboral]': {required: true},
                'GestionSolicitudCredito[email]': {required: true},
                'GestionSolicitudCredito[actividad_empresa]': {required: true}
            },
            messages: {},
            submitHandler: function (form) {
                var error = 0;
                var estado_civil = $('#GestionSolicitudCredito_estado_civil').val();
                var telefono_trabajo = $('#GestionSolicitudCredito_telefonos_trabajo').val();
                var conyugue_trabaja = $('#GestionSolicitudCredito_conyugue_trabaja').val();
                //alert(conyugue_trabaja);
                if(countChar(telefono_trabajo) == false){
                    $('#telefonos_trabajo_error').show();
                    $('#GestionSolicitudCredito_telefonos_trabajo').focus();
                    return false;
                }
                if(estado_civil == 'Casado' || estado_civil == 'Casado sin separación de bienes'){
                   if(countChar($('#GestionSolicitudCredito_telefono_trabajo_conyugue').val()) == false){
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').show();
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue').focus();
                        return false;
                    } 
                }
                error = validate_estado_civil(estado_civil, error, conyugue_trabaja, 6);
                if(error > 0)
                    return false;
                
                var fechaNac = $('#GestionSolicitudCredito_fecha_nacimiento').val();
                var fechaActual = new Date().toJSON().slice(0, 10);
                //console.log('Fecha Actual: '+fechaActual);
                var diferencia = restaFechas(fechaNac, fechaActual);
                //console.log('dias: '+diferencia);
                if (diferencia < 6480) {
                    alert('El cliente debe ser mayor de 18 años');
                    return false;
                }
                
                var dataform = $("#gestion-solicitud-credito-form").serialize();
                var count = 0;
                var cedulaConyugue = $('#GestionSolicitudCredito_cedula_conyugue').val();
                console.log('valor cedula: ' + cedulaConyugue);
                if (cedulaConyugue != '' && error == 0) {
                    var validateCedula = CedulaValida(cedulaConyugue);
                    if (validateCedula) {
                        $.ajax({
                            url: '<?php echo $url; ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            datatype: "json",
                            type: 'POST',
                            data: dataform,
                            success: function (data) {
                                //var returnedData = JSON.parse(data);
                                //alert(returnedData.result);
                                //$('#bg_negro').hide();
                                $(location).attr('href', '<?php echo $url_load; ?>');
                            }
                        });
                    } else {
                        alert('Cédula inválida de cónyugue');
                        $('#GestionSolicitudCredito_cedula_conyugue').focus();
                    }
                } else if (error == 0) {
                    $.ajax({
                        url: '<?php echo $url; ?>',
                        beforeSend: function (xhr) {
                            $('#bg_negro').show();  // #bg_negro must be defined somewhere
                        },
                        datatype: "json",
                        type: 'POST',
                        data: dataform,
                        success: function (data) {
                            //var returnedData = JSON.parse(data);
                            //alert(returnedData.result);
                            //$('#bg_negro').hide();
                            $(location).attr('href', '<?php echo $url_load; ?>');
                        }
                    });
                }
            }
        });
    }
    function createsc7(){
        $('#gestion-solicitud-credito-form').validate({
            rules: {
                'GestionSolicitudCredito[modelo]': {required: true},
                'GestionSolicitudCredito[valor]': {required: true},
                'GestionSolicitudCredito[monto_financiar]': {required: true},
                'GestionSolicitudCredito[entrada]': {required: true},
                'GestionSolicitudCredito[year]': {required: true},
                'GestionSolicitudCredito[plazo]': {required: true},
                'GestionSolicitudCredito[taza]': {required: true},
                'GestionSolicitudCredito[cuota_mensual]': {required: true},
                'GestionSolicitudCredito[apellido_paterno]': {required: true},
                'GestionSolicitudCredito[nombres]': {required: true},
                'GestionSolicitudCredito[cedula]': {required: true},
                'GestionSolicitudCredito[estado_civil]': {required: true},
                'GestionSolicitudCredito[fecha_nacimiento]': {required: true},
                'GestionSolicitudCredito[empresa_trabajo]': {required: true},
                'GestionSolicitudCredito[sector]': {required: true},
                'GestionSolicitudCredito[telefonos_trabajo]': {required: true},
                'GestionSolicitudCredito[tiempo_trabajo]': {required: true},
                'GestionSolicitudCredito[cargo]': {required: true},
                'GestionSolicitudCredito[direccion_empresa]': {required: true},
                'GestionSolicitudCredito[tipo_relacion_laboral]': {required: true},
                'GestionSolicitudCredito[email]': {required: true},
                'GestionSolicitudCredito[actividad_empresa]': {required: true},
                'GestionSolicitudCredito[habita]': {required: true},
                'GestionSolicitudCredito[ciudad_domicilio]': {required: true},
                'GestionSolicitudCredito[numero]': {required: true},
                'GestionSolicitudCredito[barrio]': {required: true},
                <?php if($grupo_id == 3 || $grupo_id == 4)
                echo "'GestionSolicitudCredito[manzana]': {required: true},";
                 ?>
                
                'GestionSolicitudCredito[calle]': {required: true},
                'GestionSolicitudCredito[telefono_residencia]': {required: true},
                
            },
            messages: {},
            submitHandler: function (form) {
                var error = 0;
                var estado_civil = $('#GestionSolicitudCredito_estado_civil').val();
                var telefono_trabajo = $('#GestionSolicitudCredito_telefonos_trabajo').val();
                var conyugue_trabaja = $('#GestionSolicitudCredito_conyugue_trabaja').val();
                //alert(conyugue_trabaja);
                if(countChar(telefono_trabajo) == false){
                    $('#telefonos_trabajo_error').show();
                    $('#GestionSolicitudCredito_telefonos_trabajo').focus();
                    return false;
                }
                if(estado_civil == 'Casado' || estado_civil == 'Casado sin separación de bienes'){
                   if(countChar($('#GestionSolicitudCredito_telefono_trabajo_conyugue').val()) == false){
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').show();
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue').focus();
                        return false;
                    } 
                }
                var conyugue_validate = validate_estado_civil(estado_civil, error, conyugue_trabaja,7);
                if(conyugue_validate > 0)
                    return false;
                
                var fechaNac = $('#GestionSolicitudCredito_fecha_nacimiento').val();
                var fechaActual = new Date().toJSON().slice(0, 10);
                //console.log('Fecha Actual: '+fechaActual);
                var diferencia = restaFechas(fechaNac, fechaActual);
                //console.log('dias: '+diferencia);
                if (diferencia < 6480) {
                    alert('El cliente debe ser mayor de 18 años');
                    return false;
                }
                var tipo_propiedad = $('#GestionSolicitudCredito_habita').val();
            /*    if(tipo_propiedad == 'Propia'){
                    var avaluo_propiedad = formatnumber($('#GestionSolicitudCredito_avaluo_propiedad').val())
                    if(avaluo_propiedad < 9999){
                        $('#GestionSolicitudCredito_avaluo_propiedad_error').show();
                        $('#GestionSolicitudCredito_avaluo_propiedad').focus().addClass('error');
                        return false;
                    }
                }*/
                
                var dataform = $("#gestion-solicitud-credito-form").serialize();
                var count = 0;
                var cedulaConyugue = $('#GestionSolicitudCredito_cedula_conyugue').val();
                console.log('valor cedula: ' + cedulaConyugue);
                if (cedulaConyugue != '' && error == 0) {
                    console.log('enter primero');
                    var validateCedula = CedulaValida(cedulaConyugue);
                    if (validateCedula) {
                        $.ajax({
                            url: '<?php echo $url; ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            datatype: "json",
                            type: 'POST',
                            data: dataform,
                            success: function (data) {
                                //var returnedData = JSON.parse(data);
                                //alert(returnedData.result);
                                //$('#bg_negro').hide();
                                $(location).attr('href', '<?php echo $url_load; ?>');
                            }
                        });
                    } else {
                        alert('Cédula inválida de cónyugue');
                        $('#GestionSolicitudCredito_cedula_conyugue').focus();
                    }
                } else if (error == 0) {
                    console.log('enter error 0');
                    $.ajax({
                        url: '<?php echo $url; ?>',
                        beforeSend: function (xhr) {
                            $('#bg_negro').show();  // #bg_negro must be defined somewhere
                        },
                        datatype: "json",
                        type: 'POST',
                        data: dataform,
                        success: function (data) {
                            //var returnedData = JSON.parse(data);
                            //alert(returnedData.result);
                            //$('#bg_negro').hide();
                            $(location).attr('href', '<?php echo $url_load; ?>');
                        }
                    });
                }
            }
        });
    }
    function validate_estado_civil(estado_civil, error, conyugue_trabaja,tipo){
        var error = 0;
        switch (estado_civil) {
            case 'Soltero':
            case 'Viudo':
            case 'Divorciado':
            case 'Casado con separación de bienes':
                var cadena = $('#GestionSolicitudCredito_telefonos_trabajo').val();
                    var letra = cadena.charAt(1);
                    if(letra == '0'){
                       error++; 
                       $('#telefonos_trabajo_error').show();
                       $('#GestionSolicitudCredito_telefonos_trabajo').focus().addClass('error');
                    }
                var sueldo_soltero = formatnumber($('#GestionSolicitudCredito_sueldo_mensual').val());
                if(sueldo_soltero < 300 && tipo != 7){
                    $('#GestionSolicitudCredito_sueldo_mensual_error').show();
                    $('#GestionSolicitudCredito_sueldo_mensual').focus().addClass('error');
                    error++;
                    // return false;
                }
                $('#GestionSolicitudCredito_apellido_paterno_conyugue').val('');
                $('#GestionSolicitudCredito_nombres_conyugue').val('');
                $('#GestionSolicitudCredito_cedula_conyugue').val('');
                $('#GestionSolicitudCredito_fecha_nacimiento_conyugue').val('');
                $('#GestionSolicitudCredito_conyugue_trabaja').val('');
                $('#GestionSolicitudCredito_empresa_trabajo_conyugue').val('');
                $('#GestionSolicitudCredito_sector_conyugue').val('');
                $('#GestionSolicitudCredito_telefono_trabajo_conyugue').val('');
                $('#GestionSolicitudCredito_tiempo_trabajo_conyugue').val('');
                $('#GestionSolicitudCredito_meses_trabajo_conyugue').val('');
                $('#GestionSolicitudCredito_cargo_conyugue').val('');
                $('#GestionSolicitudCredito_direccion_empresa_conyugue').val('');
                $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').val('');
                $('#GestionSolicitudCredito_nacionalidad_conyugue').val('');

                //validateCasado();
                break;
            case 'Casado sin separación de bienes':
            case 'Casado':
            case 'Union Libre':
            case 'Union_hecho':
                //validateSoltero();
                if ($('#GestionSolicitudCredito_apellido_paterno_conyugue').val() == '') {
                    $('#GestionSolicitudCredito_apellido_paterno_conyugue_error').show();
                    $('#GestionSolicitudCredito_apellido_paterno_conyugue').focus().addClass('error');
                    error++;
                }
                if ($('#GestionSolicitudCredito_nombres_conyugue').val() == '') {
                    $('#GestionSolicitudCredito_nombres_conyugue_error').show();
                    $('#GestionSolicitudCredito_nombres_conyugue').focus().addClass('error');
                    error++;
                }
                if ($('#GestionSolicitudCredito_cedula_conyugue').val() == '') {
                    $('#GestionSolicitudCredito_cedula_conyugue_error').show();
                    $('#GestionSolicitudCredito_cedula_conyugue').focus().addClass('error');
                    error++;
                }
                if ($('#GestionSolicitudCredito_fecha_nacimiento_conyugue').val() == '') {
                    $('#GestionSolicitudCredito_fecha_nacimiento_conyugue_error').show();
                    $('#GestionSolicitudCredito_fecha_nacimiento_conyugue').focus().addClass('error');
                    error++;
                }
                if(conyugue_trabaja == ''){
                    $('#GestionSolicitudCredito_conyugue_trabaja_error').show();
                    $('#GestionSolicitudCredito_conyugue_trabaja').focus().addClass('error');
                    error++;
                }
                if(conyugue_trabaja == 1 && (tipo == 2 ||  tipo == 7 || tipo == 4 || tipo == 5)){
                    if ($('#GestionSolicitudCredito_empresa_trabajo_conyugue').val() == '') {
                        $('#GestionSolicitudCredito_empresa_trabajo_conyugue_error').show();
                        $('#GestionSolicitudCredito_empresa_trabajo_conyugue').focus().addClass('error');
                        error++;
                    }
                    if ($('#GestionSolicitudCredito_sector_conyugue').val() == '') {
                        $('#GestionSolicitudCredito_sector_conyugue_error').show();
                        $('#GestionSolicitudCredito_sector_conyugue').focus().addClass('error');
                        error++;
                    }
                    if ($('#GestionSolicitudCredito_telefono_trabajo_conyugue').val() == '') {
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').show();
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue').focus().addClass('error');
                        error++;
                    }
                    if ($('#GestionSolicitudCredito_tiempo_trabajo_conyugue').val() == '') {
                        $('#GestionSolicitudCredito_tiempo_trabajo_conyugue_error').show();
                        $('#GestionSolicitudCredito_tiempo_trabajo_conyugue').focus().addClass('error');
                        error++;
                    }
                    if ($('#GestionSolicitudCredito_meses_trabajo_conyugue').val() == '') {
                        $('#GestionSolicitudCredito_meses_trabajo_conyugue_error').show();
                        $('#GestionSolicitudCredito_meses_trabajo_conyugue').focus().addClass('error');
                        error++;
                    }
                    if ($('#GestionSolicitudCredito_cargo_conyugue').val() == '') {
                        $('#GestionSolicitudCredito_cargo_conyugue_error').show();
                        $('#GestionSolicitudCredito_cargo_conyugue').focus().addClass('error');
                        error++;
                    }
                    if ($('#GestionSolicitudCredito_direccion_empresa_conyugue').val() == '') {
                        $('#GestionSolicitudCredito_direccion_empresa_conyugue_error').show();
                        $('#GestionSolicitudCredito_direccion_empresa_conyugue').focus().addClass('error');
                        error++;
                    }
                    if ($('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').val() == '') {
                        $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue_error').show();
                        $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').focus().addClass('error');
                        error++;
                    }

                }
                // VALIDACION QUE CEDULAS NO SEAN IGUALES DE PRINCIPAL Y CONYUGUE
                if($('#GestionSolicitudCredito_cedula').val() == $('#GestionSolicitudCredito_cedula_conyugue').val()){
                    alert('Cédula de Deudor y Cónyugue son iguales, por favor corregir');
                    error++;
                }
                break;
        }
        return error;
    }
    function validateCasado() {
        console.log('enter val casa');
        $("#GestionSolicitudCredito_apellido_paterno_conyugue").rules("add", "required");
        $("#GestionSolicitudCredito_apellido_materno_conyugue").rules("add", "required");
        $("#GestionSolicitudCredito_nombres_conyugue").rules("add", "required");
        $("#GestionSolicitudCredito_cedula_conyugue").rules("add", "required");
        $("#GestionSolicitudCredito_fecha_nacimiento_conyugue").rules("add", "required");
        $("#GestionSolicitudCredito_telefono_trabajo_conyugue").rules("add", "required");
        $("#GestionSolicitudCredito_tiempo_trabajo_conyugue").rules("add", "required");
        $("#GestionSolicitudCredito_meses_trabajo_conyugue").rules("add", "required");
        $("#GestionSolicitudCredito_cargo_conyugue").rules("add", "required");
        $("#GestionSolicitudCredito_direccion_empresa_conyugue").rules("add", "required");
        $("#GestionSolicitudCredito_tipo_relacion_laboral_conyugue").rules("add", "required");
        $("#Cotizador_year").rules("add", "required");
    }
    function validateSoltero() {
        $("#intoptions").rules("add", "required");
    }
    function sendSol() {
        $('#confirm').show();
        $('#finalizar').hide();
    }
    function confirmar() {
        $('#confirm').hide();
        $('#send-asesor').show();
    }
    function countChar(str){
        var arr = str.split("");
        var i;
        var count = 0;
        var cer = 0;
        for (i = 0; i < arr.length; i++) {
            if(arr[i] == '0'){
                count++;
            }
            if(i == 1 && arr[1] == '0'){
                cer++;
            }
        }
        //console.log('count: '+count);
        if (count > 7 || cer > 0){
            return false;
        }else{
            return true;
        }
    }
    function send() {
        //console.log('enter send');
        $('#gestion-solicitud-credito-form').validate({
            rules: {
                'GestionSolicitudCredito[modelo]': {required: true},
                'GestionSolicitudCredito[valor]': {required: true},
                'GestionSolicitudCredito[monto_financiar]': {required: true},
                'GestionSolicitudCredito[entrada]': {required: true},
                'GestionSolicitudCredito[year]': {required: true},
                'GestionSolicitudCredito[plazo]': {required: true},
                'GestionSolicitudCredito[taza]': {required: true},
                'GestionSolicitudCredito[cuota_mensual]': {required: true},
                'GestionSolicitudCredito[apellido_paterno]': {required: true},
                'GestionSolicitudCredito[nombres]': {required: true},
                'GestionSolicitudCredito[cedula]': {required: true},
                'GestionSolicitudCredito[estado_civil]': {required: true},
                'GestionSolicitudCredito[fecha_nacimiento]': {required: true},
                'GestionSolicitudCredito[empresa_trabajo]': {required: true},
                'GestionSolicitudCredito[sector]': {required: true},
                'GestionSolicitudCredito[telefonos_trabajo]': {required: true},
                'GestionSolicitudCredito[tiempo_trabajo]': {required: true},
                'GestionSolicitudCredito[cargo]': {required: true},
                'GestionSolicitudCredito[direccion_empresa]': {required: true},
                'GestionSolicitudCredito[tipo_relacion_laboral]': {required: true},
                'GestionSolicitudCredito[email]': {required: true},
                'GestionSolicitudCredito[actividad_empresa]': {required: true},
                'GestionSolicitudCredito[ciudad_domicilio]': {required: true},
                'GestionSolicitudCredito[barrio]': {required: true},
                <?php if($grupo_id == 3 || $grupo_id == 4)
                echo "'GestionSolicitudCredito[manzana]': {required: true},";
                 ?>
                'GestionSolicitudCredito[calle]': {required: true},
                'GestionSolicitudCredito[telefono_residencia]': {required: true},
                'GestionSolicitudCredito[sueldo_mensual]': {required: true},
                //'GestionSolicitudCredito[banco1]': {required: true},
                //'GestionSolicitudCredito[cuenta_ahorros1]':{required: true},
                'GestionSolicitudCredito[celular]': {required: true},
                'GestionSolicitudCredito[referencia_personal1]': {required: true},
                'GestionSolicitudCredito[parentesco1]': {required: true},
                'GestionSolicitudCredito[telefono_referencia1]': {required: true},
                //'GestionSolicitudCredito[referencia_personal2]': {required: true},
                //'GestionSolicitudCredito[parentesco2]': {required: true},
                //'GestionSolicitudCredito[telefono_referencia2]': {required: true},
                'GestionSolicitudCredito[habita]': {required: true},
                'GestionSolicitudCredito[numero]': {required: true}
            },
            messages: {},
            submitHandler: function (form) {
                var error = 0;
                console.log('enter submit');
                var estado_civil = $('#GestionSolicitudCredito_estado_civil').val();
                var telefono_trabajo = $('#GestionSolicitudCredito_telefonos_trabajo').val();
                var conyugue_trabaja = $('#GestionSolicitudCredito_conyugue_trabaja').val();
                //alert(conyugue_trabaja);
                if(countChar(telefono_trabajo) == false){
                    $('#telefonos_trabajo_error').show();
                    $('#GestionSolicitudCredito_telefonos_trabajo').focus();
                    return false;
                }
                if(estado_civil == 'Casado' || estado_civil == 'Casado sin separación de bienes'){
                   if(countChar($('#GestionSolicitudCredito_telefono_trabajo_conyugue').val()) == false){
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').show();
                        $('#GestionSolicitudCredito_telefono_trabajo_conyugue').focus();
                        return false;
                    } 
                }
                if($('#img-firma-cont').val() == ''){
                    alert("Por favor ingrese la firma del cliente");  
                    return false;  
                }
                switch (estado_civil) {
                    case 'Soltero':
                    case 'Viudo':
                    case 'Divorciado':
                    case 'Casado con separación de bienes':
                        var cadena = $('#GestionSolicitudCredito_telefonos_trabajo').val();
                            var letra = cadena.charAt(1);
                            if(letra == '0'){
                               error++; 
                               $('#telefonos_trabajo_error').show();
                               $('#GestionSolicitudCredito_telefonos_trabajo').focus().addClass('error');
                            }
                        var sueldo_soltero = formatnumber($('#GestionSolicitudCredito_sueldo_mensual').val());
                        if(sueldo_soltero < 300){
                            $('#GestionSolicitudCredito_sueldo_mensual_error').show();
                            $('#GestionSolicitudCredito_sueldo_mensual').focus().addClass('error');
                            error++;
                            return false;
                        }
                        //validateCasado();
                        break;
                    case 'Casado sin separación de bienes':
                    case 'Casado':
                    case 'Union Libre':
                    case 'Union_hecho':
                        //validateSoltero();
                        if ($('#GestionSolicitudCredito_apellido_paterno_conyugue').val() == '') {
                            $('#GestionSolicitudCredito_apellido_paterno_conyugue_error').show();
                            $('#GestionSolicitudCredito_apellido_paterno_conyugue').focus().addClass('error');
                            error++;
                        }
                        if ($('#GestionSolicitudCredito_nombres_conyugue').val() == '') {
                            $('#GestionSolicitudCredito_nombres_conyugue_error').show();
                            $('#GestionSolicitudCredito_nombres_conyugue').focus().addClass('error');
                            error++;
                        }
                        if ($('#GestionSolicitudCredito_cedula_conyugue').val() == '') {
                            $('#GestionSolicitudCredito_cedula_conyugue_error').show();
                            $('#GestionSolicitudCredito_cedula_conyugue').focus().addClass('error');
                            error++;
                        }
                        if ($('#GestionSolicitudCredito_fecha_nacimiento_conyugue').val() == '') {
                            $('#GestionSolicitudCredito_fecha_nacimiento_conyugue_error').show();
                            $('#GestionSolicitudCredito_fecha_nacimiento_conyugue').focus().addClass('error');
                            error++;
                        }
                        if(conyugue_trabaja == ''){
                            $('#GestionSolicitudCredito_conyugue_trabaja_error').show();
                            $('#GestionSolicitudCredito_conyugue_trabaja').focus().addClass('error');
                            error++;
                        }
                        if(conyugue_trabaja == 1){
                            if ($('#GestionSolicitudCredito_empresa_trabajo_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_empresa_trabajo_conyugue_error').show();
                                $('#GestionSolicitudCredito_empresa_trabajo_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_sector_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_sector_conyugue_error').show();
                                $('#GestionSolicitudCredito_sector_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_telefono_trabajo_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').show();
                                $('#GestionSolicitudCredito_telefono_trabajo_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_tiempo_trabajo_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_tiempo_trabajo_conyugue_error').show();
                                $('#GestionSolicitudCredito_tiempo_trabajo_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_meses_trabajo_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_meses_trabajo_conyugue_error').show();
                                $('#GestionSolicitudCredito_meses_trabajo_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_cargo_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_cargo_conyugue_error').show();
                                $('#GestionSolicitudCredito_cargo_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_direccion_empresa_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_direccion_empresa_conyugue_error').show();
                                $('#GestionSolicitudCredito_direccion_empresa_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue_error').show();
                                $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').focus().addClass('error');
                                error++;
                            }
                            if ($('#GestionSolicitudCredito_sueldo_mensual_conyugue').val() == '') {
                                $('#GestionSolicitudCredito_sueldo_mensual_conyugue_error').show();
                                $('#GestionSolicitudCredito_sueldo_mensual_conyugue').focus().addClass('error');
                                error++;
                            }
                        }
                        var sueldo_soltero = formatnumber($('#GestionSolicitudCredito_sueldo_mensual').val());
                        if(sueldo_soltero < 300){
                            $('#GestionSolicitudCredito_sueldo_mensual_error').show();
                            $('#GestionSolicitudCredito_sueldo_mensual').focus().addClass('error');
                            error++;
                            return false;
                        }
                        if(conyugue_trabaja == 1){
                            var sueldo_casado = formatnumber($('#GestionSolicitudCredito_sueldo_mensual_conyugue').val());
                            if(sueldo_casado < 300){
                                $('#GestionSolicitudCredito_sueldo_mensual_conyugue_error2').show();
                                $('#GestionSolicitudCredito_sueldo_mensual_conyugue').focus().addClass('error');
                                errot++;
                                return false;
                            }
                        }
                        break;
                }
                var fechaNac = $('#GestionSolicitudCredito_fecha_nacimiento').val();
                var fechaActual = new Date().toJSON().slice(0, 10);
                //console.log('Fecha Actual: '+fechaActual);
                var diferencia = restaFechas(fechaNac, fechaActual);
                //console.log('dias: '+diferencia);
                if (diferencia < 6480) {
                    alert('El cliente debe ser mayor de 18 años');
                    return false;
                }
                
                var tipo_propiedad = $('#GestionSolicitudCredito_habita').val();
                /*if(tipo_propiedad == 'Propia'){
                    var avaluo_propiedad = formatnumber($('#GestionSolicitudCredito_avaluo_propiedad').val())
                    if(avaluo_propiedad < 9999){
                        $('#GestionSolicitudCredito_avaluo_propiedad_error').show();
                        $('#GestionSolicitudCredito_avaluo_propiedad').focus().addClass('error');
                        return false;
                    }
                }*/
                //console.log('enter submit');
                //if (confirm('Desea grabar los datos ingresados y enviar la solicitud al Asesor de Crédito?')) {
                var dataform = $("#gestion-solicitud-credito-form").serialize();
                var count = 0;
                var cedulaConyugue = $('#GestionSolicitudCredito_cedula_conyugue').val();
                console.log('valor cedula: ' + cedulaConyugue);
                if (cedulaConyugue != '' && error == 0) {
                    var validateCedula = CedulaValida(cedulaConyugue);
                    if (validateCedula) {
                        $.ajax({
                            url: '<?php echo $url; ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            datatype: "json",
                            type: 'POST',
                            data: dataform,
                            success: function (data) {
                                //var returnedData = JSON.parse(data);
                                //alert(returnedData.result);
                                $('#bg_negro').hide();
                                $('#finalizar').hide();
                                $('#generatepdf').show();
                                $('#continue').show();
                                $('#send-asesor').hide();
                                //$('#GestionFinanciamiento_ipdfid').val(returnedData.id);
                            }
                        });
                    } else {
                        alert('Cédula inválida de cónyugue');
                        $('#GestionSolicitudCredito_cedula_conyugue').focus();
                    }
                } else if (error == 0) {
                    console.log('enter no cony');
                    $.ajax({
                        url: '<?php echo $url; ?>',
                        beforeSend: function (xhr) {
                            $('#bg_negro').show();  // #bg_negro must be defined somewhere
                        },
                        datatype: "json",
                        type: 'POST',
                        data: dataform,
                        success: function (data) {
                            //var returnedData = JSON.parse(data);
                            //alert(returnedData.result);
                            $('#bg_negro').hide();
                            $('#finalizar').hide();
                            $('#generatepdf').show();
                            $('#continue').show();
                            $('#send-asesor').hide();
                            //$('#GestionFinanciamiento_ipdfid').val(returnedData.id);
                        }
                    });
                }

                //}
            }
        });
    }

    function getvalortotal() {
    
        var avaluo = $('#GestionSolicitudCredito_avaluo_propiedad').val(); 
        console.log('avaluo: '+avaluo);
        var valorcomercial1 = $("[name='GestionSolicitudCredito[direccion_valor_comercial1]']").val();
        var valorcomercial2 = $("[name='GestionSolicitudCredito[direccion_valor_comercial2]']").val();
        var vehiculovalor1 = $("[name='GestionSolicitudCredito[vehiculo_valor1]']").val();
        var vehiculovalor2 = $("[name='GestionSolicitudCredito[vehiculo_valor2]']").val();
        var valorinversion = $("[name='GestionSolicitudCredito[valor_inversion]']").val();
        var valoriotrosactivos1 = $("[name='GestionSolicitudCredito[valor_otros_activos1]']").val();
        var valoriotrosactivos2 = $("[name='GestionSolicitudCredito[valor_otros_activos2]']").val();
        avaluo = formatnumber(avaluo);
        valorcomercial1 = formatnumber(valorcomercial1);
        valorcomercial2 = formatnumber(valorcomercial2);
        vehiculovalor1 = formatnumber(vehiculovalor1);
        vehiculovalor2 = formatnumber(vehiculovalor2);
        valorinversion = formatnumber(valorinversion);
        valoriotrosactivos1 = formatnumber(valoriotrosactivos1);
        valoriotrosactivos2 = formatnumber(valoriotrosactivos2);
        var valuetotal = $("[name='GestionSolicitudCredito[total_activos]']").val();
        valueft = formatnumber(valuetotal);
        total = avaluo + valorcomercial1 + valorcomercial2 + vehiculovalor1 + vehiculovalor2 + valorinversion + valoriotrosactivos1 + valoriotrosactivos2;
        total = format2(total, '$');

        $("[name='GestionSolicitudCredito[total_activos]']").val(total);
    }

    function getIngresosTotal(){
        var sueldo_mensual = formatnumber($('#GestionSolicitudCredito_sueldo_mensual').val());
        //console.log('sueldo mensual tot: '+sueldo_mensual);
        var sueldo_mensual_conyugue = formatnumber($('#GestionSolicitudCredito_sueldo_mensual_conyugue').val());
        var otros_ingresos = formatnumber($('#GestionSolicitudCredito_otros_ingresos').val());
        total = sueldo_mensual + sueldo_mensual_conyugue +  otros_ingresos;
        total = format2(total, '$');
        $('#GestionSolicitudCredito_total_ingresos').val(total); 
    }

    function getEgresosTotal(){
        var arriendo = formatnumber($('#GestionSolicitudCredito_gastos_arriendo').val());
        var gastos_alimentacion_otros = formatnumber($('#GestionSolicitudCredito_gastos_alimentacion_otros').val());
        var gastos_educacion = formatnumber($('#GestionSolicitudCredito_gastos_educacion').val());
        var gastos_prestamos = formatnumber($('#GestionSolicitudCredito_gastos_prestamos').val());
        var gastos_tarjetas_credito = formatnumber($('#GestionSolicitudCredito_gastos_tarjetas_credito').val());
        total = arriendo + gastos_alimentacion_otros + gastos_educacion + gastos_prestamos + gastos_tarjetas_credito;
        total = format2(total, '$');
        $('#GestionSolicitudCredito_total_egresos').val(total);
    }

    function getIngresosTotalLoad(){
        var sueldo_mensual = ($('#GestionSolicitudCredito_sueldo_mensual').val());
        alert('sueldo mensual conyugye: '+sueldo_mensual);
        var sueldo_mensual_conyugue = formatnumber($('#GestionSolicitudCredito_sueldo_mensual_conyugue').val());
        
        var otros_ingresos = formatnumber($('#GestionSolicitudCredito_otros_ingresos').val());
        total = sueldo_mensual + sueldo_mensual_conyugue +  otros_ingresos;
        total = format2(total, '$');
        $('#GestionSolicitudCredito_total_ingresos').val(total); 
    }


    function formatnumber(precioanterior) {
        if (precioanterior == '' || typeof precioanterior == "undefined") {
            return 0;
        } else {
            precioanterior = precioanterior.replace(',', '');
            precioanterior = precioanterior.replace('.', ',');
            precioanterior = precioanterior.replace('$', '');
            precioanterior = parseInt(precioanterior);
            return precioanterior;
        }

    }
    function CedulaValida(cedula) {

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
    function format2(n, currency) {
        return currency + " " + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    }
    function validateNumbers(c) {
        var d = (document.all) ? c.keyCode : c.which;
        if (d < 48 || d > 57) {
            if (d == 8) {
                return true
            } else {
                return false
            }
        }
        return true
    }
</script>
<div class="container">
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita.png" alt="" /></span> Cita</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
            <li role="presentation" class="active"><a aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion_on.png" alt="" /></span> Negociación</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="form">
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'gestion-solicitud-credito-form',
                        'enableAjaxValidation' => false,
                        'htmlOptions' => array(
                            'onsubmit' => "return false;", /* Disable normal form submit */
                            'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                        ),
                    ));
                    ?>
                    <?php 
                    if(Yii::app()->user->getState('cargo_id') == 74){ // si es asesora de credito
                        echo '<a href="'. Yii::app()->createUrl('gestionSolicitudCredito/admin').'" class="btn btn-xs btn-cat306 btn-cat btn-success btn-danger" ><< Regresar</a>';
                    }
                    if(Yii::app()->user->getState('cargo_id') == 87){ // asesora de credito fyi automundo
                        echo '<a href="'. Yii::app()->createUrl('gestionSolicitudCredito/fyi').'" class="btn btn-xs btn-cat306 btn-cat btn-success btn-danger" ><< Regresar</a>';
                    }

                    else{
                        echo '<a href="'. Yii::app()->createUrl('site/negociacion', array('id' => $id_informacion)).'" class="btn btn-xs btn-cat306 btn-cat btn-success btn-danger" >Regresar</a>';
                        
                    }
                    ?>
                    
                    <br><br>
                    <p class="note">Campos con <span class="required">*</span> son requeridos.</p>
                    <?php //echo $form->errorSummary($model); ?>

                    <div class="row">
                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'concesionario');  ?>
                            <?php //echo $form->textField($model, 'concesionario', array('class' => 'form-control', 'value' => 'Asiauto Mariana de Jesús', 'disabled' => 'true'));  ?>
                            <?php //echo $form->error($model, 'concesionario');  ?>
                            <label for="GestionSolicitudCredito_concesionario">Concesionario</label>
                            <input class="form-control" value="<?php echo $nombre_concesionario; ?>" disabled="disabled" name="GestionSolicitudCredito[concesionarioh]" id="GestionSolicitudCredito_concesionarioh" type="text">       
                        </div>

                        <div class="col-md-3">
                            <?php //echo $form->labelEx($model, 'vendedor');  ?>
                            <?php //echo $form->textField($model, 'vendedor', array('class' => 'form-control'));  ?>
                            <?php //echo $form->error($model, 'vendedor'); ?>
                            <label for="GestionSolicitudCredito_concesionario">Vendedor</label>
                            <input class="form-control" value="<?php echo $this->getResponsable($id_responsable); ?>" disabled="disabled" name="GestionSolicitudCredito[vendedorh]" id="GestionSolicitudCredito_vendedorh" type="text">       
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'fecha'); ?>
                            <?php echo $form->textField($model, 'fecha', array('class' => 'form-control', 'value' => date("d") . "/" . date("m") . "/" . date("Y"))); ?>
                            <?php echo $form->error($model, 'fecha'); ?></div>
                        <div class="col-md-3"></div>
                        <input type="hidden" name="GestionSolicitudCredito[concesionario]" id="GestionSolicitudCredito_concesionario" value="<?php echo $dealer_id; ?>"/>
                        <input type="hidden" name="GestionSolicitudCredito[vendedor]" id="GestionSolicitudCredito_vendedor" value="<?php echo $id_responsable; ?>"/>
                        <input type="hidden" name="GestionSolicitudCredito[id_informacion]" id="GestionSolicitudCredito_id_informacion" value="<?php echo $id_informacion; ?>"/>
                        <input type="hidden" name="GestionSolicitudCredito[id_vehiculo]" id="GestionSolicitudCredito_id_vehiculo" value="<?php echo $id_vehiculo; ?>"/>
                    </div>
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Confirmación</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Esta seguro de grabar los datos ingresados?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary">Grabar cambios</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    
                    <div class="row">
                        <h1 class="tl_seccion_rf">Seleccione Cotización</h1>
                    </div>
                    <div class="row">
                        <?php
                        $cri5 = new CDbCriteria;
                        $cri5->condition = "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo}";
                        $fin1 = GestionFinanciamiento::model()->find($cri5);
                        
                        $cri6 = new CDbCriteria;
                        $cri6->condition = "id_financiamiento={$fin1->id}";
                        $fin2count = GestionFinanciamientoOp::model()->count($cri6);
                        $data = '';
                        $count = 2;
                        if($fin2count > 0):
                            $fin2 = GestionFinanciamientoOp::model()->findAll($cri6);
                            foreach ($fin2 as $key => $value) {
                                $data .= '<option value="second-'.$value['id'].'">Opción '.$count.': $ '.$value['cuota_mensual'].'</option>';
                                $count++;
                            }
                        endif;
                        ?>
                        <div class="col-md-4">
                            <select name="" id="GestionSolicitudCredito_select_cotizacion" class="form-control">
                                <option value="">--Seleccione una opción--</option>
                                <option value="first-<?php echo $fin1->id ?>"><?php echo 'Opción 1: $ '.$fin1->cuota_mensual; ?></option>
                                <?php echo $data; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <h1 class="tl_seccion_rf">Datos del Vehículo</h1>
                    </div>  
                    <?php
                    $vec = GestionFinanciamiento::model()->findAll(array('condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo}", 'order' => 'id DESC', 'limit' => 1));
                    // echo '<pre>';
                    //  print_r($vec);
                    //  echo '</pre>';
                    ?>
                    <?php foreach ($vec as $value) { ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'modelo'); ?>
                                <?php echo $form->textField($model, 'modelo', array('class' => 'form-control', 'value' => $modelo, 'readonly' => true)); ?>
                                <?php echo $form->error($model, 'modelo'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'valor'); ?>
                                <?php echo $form->textField($model, 'valor', array('class' => 'form-control', 'value' => $value['precio_vehiculo'])); ?>
                                <?php echo $form->error($model, 'valor'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'monto_financiar'); ?>
                                <?php echo $form->textField($model, 'monto_financiar', array('class' => 'form-control', 'value' => ($credito == 1) ? $value['valor_financiamiento'] : 0)); ?>
                                <?php echo $form->error($model, 'monto_financiar'); ?>
                            </div>
                            <div class="col-md-2">
                                <label for="">Tipo de Vehículo</label>
                                <input type="text" value="<?php echo $this->getTipoVehiculo($id_modelo); ?>" readoonly="readonly" class="form-control" name="GestionSolicitudCredito[tipo_producto]">
                            </div>
<!--                            <div class="col-md-2">
                                <br />
                                <a href="<?php echo Yii::app()->createUrl('gestionVehiculo/negociacion', array('id_informacion' => $value['id_informacion'], 'id_vehiculo' => $value['id_vehiculo'])); ?>" class="btn btn-primary btn-xs">Modificar</a>
                            </div>-->

                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'entrada'); ?>
                                <?php echo $form->textField($model, 'entrada', array('class' => 'form-control', 'value' => ($credito == 1) ? $value['cuota_inicial'] : 0)); ?>
                                <?php echo $form->error($model, 'entrada'); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'year'); ?>
                                <?php echo $form->dropDownList($model, 'year', array('' => '-Seleccione-', '2014' => '2014', '2015' => '2015', '2016' => '2016', '2017' => '2017', '2018' => '2018'), array('class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'year'); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'plazo'); ?>
                                <?php echo $form->textField($model, 'plazo', array('class' => 'form-control', 'value' => ($credito == 1) ? $value['plazos'] : 0)); ?>
                                <?php echo $form->error($model, 'plazo'); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'taza'); ?>
                                <?php echo $form->textField($model, 'taza', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control', 'value' => ($credito == 1) ? $value['tasa'] : 0)); ?>
                                <?php echo $form->error($model, 'taza'); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'cuota_mensual'); ?>
                                <?php echo $form->textField($model, 'cuota_mensual', array('size' => 25, 'maxlength' => 25, 'class' => 'form-control', 'value' => ($credito == 1) ? $value['cuota_mensual'] : 0)); ?>
                                <?php echo $form->error($model, 'cuota_mensual'); ?>
                            </div>

                        </div>
                        <?php //} ?>
                        <div class="row">
                            <h1 class="tl_seccion_rf">Datos del Solicitante</h1>
                        </div>
                        <?php
                        $sql = "SELECT gi.* FROM gestion_informacion gi "
                                . "INNER JOIN ";
                        $criteria2 = new CDbCriteria(array(
                            'condition' => "id={$id_informacion}"
                        ));
                        $inf = GestionInformacion::model()->findAll($criteria2);
                        ?>
                        <?php foreach ($inf as $val) { ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'apellido_paterno'); ?>
                                    <?php echo $form->textField($model, 'apellido_paterno', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'value' => ucfirst($val['apellidos']))); ?>
                                    <?php echo $form->error($model, 'apellido_paterno'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'apellido_materno'); ?>
                                    <?php echo $form->textField($model, 'apellido_materno', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'apellido_materno'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'nombres'); ?>
                                    <?php echo $form->textField($model, 'nombres', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control', 'value' => ucfirst($val['nombres']))); ?>
                                    <?php echo $form->error($model, 'nombres'); ?>
                                </div>

                            </div>
                            <div class="row">

                                <?php if ($val['cedula'] != '') { ?>
                                    <div class="col-md-3">
                                        <?php echo $form->labelEx($model, 'cedula'); ?>
                                        <?php echo $form->textField($model, 'cedula', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control', 'value' => $val['cedula'])); ?>
                                        <?php echo $form->error($model, 'cedula'); ?>
                                    </div>
                                <?php } ?>
                                <?php if ($val['ruc'] != '') { ?>
                                    <div class="col-md-3">
                                        <?php echo $form->labelEx($model, 'ruc'); ?>
                                        <?php echo $form->textField($model, 'ruc', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control', 'value' => $val['ruc'])); ?>
                                        <?php echo $form->error($model, 'ruc'); ?>
                                    </div>
                                <?php } ?>
                                <?php if ($val['pasaporte'] != '') { ?>
                                    <div class="col-md-3">
                                        <?php echo $form->labelEx($model, 'pasaporte'); ?>
                                        <?php echo $form->textField($model, 'pasaporte', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control', 'value' => $val['pasaporte'])); ?>
                                        <?php echo $form->error($model, 'pasaporte'); ?>
                                    </div>
                                <?php } ?>

                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'fecha_nacimiento'); ?>
                                    <?php //if($valid_cedula){ ?>
                                        <?php //echo $form->textField($model, 'fecha_nacimiento', array('size' => 60, 'maxlength' => 75, 'class' => 'form-control','readonly' => 'true','value' => $vartrf['anionacimiento'] .'-'. $vartrf['mesnacimiento'] .'-'. $vartrf['dianacimiento'])); ?>
                                    <?php// }else{ ?>
                                        <?php echo $form->textField($model, 'fecha_nacimiento', array('size' => 60, 'maxlength' => 75, 'class' => 'form-control','readonly' => 'true')); ?>
                                    <?php //} ?>
                                    <?php echo $form->error($model, 'fecha_nacimiento'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'nacionalidad'); ?>
                                    <?php //echo $form->dropDownList($model, 'nacionalidad', array('' => '--Seleccione--', 'ecuador' => 'Ecuador', 'colombia' => 'Colombia', 'peru' => 'Perú'), array('class' => 'form-control'));  ?>
                                    <select name="GestionSolicitudCredito[nacionalidad]" id="GestionSolicitudCredito_nacionalidad" class="form-control">
                                        <option value="EC">Ecuador</option>
                                        <option value="CO">Colombia</option>
                                        <option value="PE">Perú</option>
                                        <option value="VE">Venezuela</option>
                                        <option value="CU">Cuba</option>
                                        <option value="AF">Afganistán</option>
                                        <option value="AL">Albania</option>
                                        <option value="DE">Alemania</option>
                                        <option value="AD">Andorra</option>
                                        <option value="AO">Angola</option>
                                        <option value="AI">Anguila</option>
                                        <option value="AQ">Antártida</option>
                                        <option value="AG">Antigua y Barbuda</option>
                                        <option value="AN">Antiguas Antillas Holandesas</option>
                                        <option value="SA">Arabia Saudí</option>
                                        <option value="DZ">Argelia</option>
                                        <option value="AR">Argentina</option>
                                        <option value="AM">Armenia</option>
                                        <option value="AW">Aruba</option>
                                        <option value="AU">Australia</option>
                                        <option value="AT">Austria</option>
                                        <option value="PS">Autoridad Palestina</option>
                                        <option value="AZ">Azerbaiyán</option>
                                        <option value="BD">Bangladesh</option>
                                        <option value="BB">Barbados</option>
                                        <option value="BY">Belarús</option>
                                        <option value="BE">Bélgica</option>
                                        <option value="BZ">Belice</option>
                                        <option value="BJ">Benín</option>
                                        <option value="BM">Bermudas</option>
                                        <option value="BO">Bolivia</option>
                                        <option value="BQ">Bonaire</option>
                                        <option value="BA">Bosnia y Herzegovina</option>
                                        <option value="BW">Botsuana</option>
                                        <option value="BR">Brasil</option>
                                        <option value="BN">Brunéi</option>
                                        <option value="BG">Bulgaria</option>
                                        <option value="BF">Burkina Faso</option>
                                        <option value="BI">Burundi</option>
                                        <option value="BT">Bután</option>
                                        <option value="CV">Cabo Verde</option>
                                        <option value="KH">Camboya</option>
                                        <option value="CM">Camerún</option>
                                        <option value="CA">Canadá</option>
                                        <option value="TD">Chad</option>
                                        <option value="CL">Chile</option>
                                        <option value="CN">China</option>
                                        <option value="CY">Chipre</option>
                                        <option value="KM">Comoras</option>
                                        <option value="CD">Congo (RDC)</option>
                                        <option value="KP">Corea del Norte</option>
                                        <option value="KR">Corea del Sur</option>
                                        <option value="CR">Costa Rica</option>
                                        <option value="HR">Croacia (Hrvatska)</option>
                                        
                                        <option value="CW">Curazao</option>
                                        <option value="DK">Dinamarca</option>
                                        <option value="DM">Dominica</option>
                                        <option value="EG">Egipto</option>
                                        <option value="SV">El Salvador</option>
                                        <option value="AE">Emiratos Árabes Unidos</option>
                                        <option value="ER">Eritrea</option>
                                        <option value="SK">Eslovaquia</option>
                                        <option value="SI">Eslovenia</option>
                                        <option value="ES">España</option>
                                        <option value="US">Estados Unidos</option>
                                        <option value="EE">Estonia</option>
                                        <option value="ET">Etiopía</option>
                                        <option value="MK">Ex-República Yugoslava de Macedonia</option>
                                        <option value="PH">Filipinas</option>
                                        <option value="FI">Finlandia</option>
                                        <option value="FR">Francia</option>
                                        <option value="GA">Gabón</option>
                                        <option value="GM">Gambia</option>
                                        <option value="GE">Georgia</option>
                                        <option value="GS">Georgia del Sur e Islas Sandwich del Sur</option>
                                        <option value="GH">Ghana</option>
                                        <option value="GI">Gibraltar</option>
                                        <option value="GD">Granada</option>
                                        <option value="GR">Grecia</option>
                                        <option value="GL">Groenlandia</option>
                                        <option value="GP">Guadalupe</option>
                                        <option value="GU">Guam</option>
                                        <option value="GT">Guatemala</option>
                                        <option value="GF">Guayana Francesa</option>
                                        <option value="GG">Guernsey</option>
                                        <option value="GN">Guinea</option>
                                        <option value="GQ">Guinea Ecuatorial</option>
                                        <option value="GW">Guinea-Bissau</option>
                                        <option value="GY">Guyana</option>
                                        <option value="HT">Haití</option>
                                        <option value="HN">Honduras</option>
                                        <option value="HK">Hong Kong RAE</option>
                                        <option value="HU">Hungría</option>
                                        <option value="IN">India</option>
                                        <option value="ID">Indonesia</option>
                                        <option value="IQ">Irak</option>
                                        <option value="IR">Irán</option>
                                        <option value="IE">Irlanda</option>
                                        <option value="AC">Isla Ascensión</option>
                                        <option value="BV">Isla Bouvet</option>
                                        <option value="CX">Isla Christmas</option>
                                        <option value="IM">Isla de Man</option>
                                        <option value="NF">Isla Norfolk</option>
                                        <option value="IS">Islandia</option>
                                        <option value="AX">Islas Åland</option>
                                        <option value="KY">Islas Caimán</option>
                                        <option value="CC">Islas Cocos</option>
                                        <option value="CK">Islas Cook</option>
                                        <option value="FO">Islas Feroe</option>
                                        <option value="FJ">Islas Fiji</option>
                                        <option value="HM">Islas Heard y McDonald</option>
                                        <option value="FK">Islas Malvinas</option>
                                        <option value="MP">Islas Marianas del Norte</option>
                                        <option value="MH">Islas Marshall</option>
                                        <option value="UM">Islas menores alejadas de los Estados Unidos</option>
                                        <option value="PN">Islas Pitcairn</option>
                                        <option value="SB">Islas Salomón</option>
                                        <option value="TC">Islas Turcas y Caicos</option>
                                        <option value="VG">Islas Vírgenes Británicas</option>
                                        <option value="VI">Islas Vírgenes, EE.UU.</option>
                                        <option value="IL">Israel</option>
                                        <option value="IT">Italia</option>
                                        <option value="JM">Jamaica</option>
                                        <option value="SJ">Jan Mayen</option>
                                        <option value="JP">Japón</option>
                                        <option value="JE">Jersey</option>
                                        <option value="JO">Jordania</option>
                                        <option value="KZ">Kazajistán</option>
                                        <option value="KE">Kenia</option>
                                        <option value="KG">Kirguistán</option>
                                        <option value="KI">Kiribati</option>
                                        <option value="XK">Kosovo</option>
                                        <option value="KW">Kuwait</option>
                                        <option value="LA">Laos</option>
                                        <option value="BS">Las Bahamas</option>
                                        <option value="LS">Lesoto</option>
                                        <option value="LV">Letonia</option>
                                        <option value="LB">Líbano</option>
                                        <option value="LR">Liberia</option>
                                        <option value="LY">Libia</option>
                                        <option value="LI">Liechtenstein</option>
                                        <option value="LT">Lituania</option>
                                        <option value="LU">Luxemburgo</option>
                                        <option value="MO">Macao RAE</option>
                                        <option value="MG">Madagascar</option>
                                        <option value="MY">Malasia</option>
                                        <option value="MW">Malawi</option>
                                        <option value="MV">Maldivas</option>
                                        <option value="ML">Malí</option>
                                        <option value="MT">Malta</option>
                                        <option value="MA">Marruecos</option>
                                        <option value="MQ">Martinica</option>
                                        <option value="MU">Mauricio</option>
                                        <option value="MR">Mauritania</option>
                                        <option value="YT">Mayotte</option>
                                        <option value="MX">México</option>
                                        <option value="FM">Micronesia</option>
                                        <option value="MD">Moldova</option>
                                        <option value="MC">Mónaco</option>
                                        <option value="MN">Mongolia</option>
                                        <option value="ME">Montenegro</option>
                                        <option value="MS">Montserrat</option>
                                        <option value="MZ">Mozambique</option>
                                        <option value="MM">Myanmar</option>
                                        <option value="NA">Namibia</option>
                                        <option value="NR">Nauru</option>
                                        <option value="NP">Nepal</option>
                                        <option value="NI">Nicaragua</option>
                                        <option value="NE">Níger</option>
                                        <option value="NG">Nigeria</option>
                                        <option value="NU">Niue</option>
                                        <option value="NO">Noruega</option>
                                        <option value="NC">Nueva Caledonia</option>
                                        <option value="NZ">Nueva Zelanda</option>
                                        <option value="OM">Omán</option>
                                        <option value="NL">Países Bajos</option>
                                        <option value="PK">Pakistán</option>
                                        <option value="PW">Palaos</option>
                                        <option value="PA">Panamá</option>
                                        <option value="PG">Papúa Nueva Guinea</option>
                                        <option value="PY">Paraguay</option>
                                        <option value="PF">Polinesia Francesa</option>
                                        <option value="PL">Polonia</option>
                                        <option value="PT">Portugal</option>
                                        <option value="PR">Puerto Rico</option>
                                        <option value="QA">Qatar</option>
                                        <option value="BH">Reino de Baréin</option>
                                        <option value="UK">Reino Unido</option>
                                        <option value="CF">República Centroafricana</option>
                                        <option value="CZ">República Checa</option>
                                        <option value="CI">República de Côte d'Ivoire</option>
                                        <option value="CG">República del Congo</option>
                                        <option value="DO">República Dominicana</option>
                                        <option value="RE">Reunión</option>
                                        <option value="RW">Ruanda</option>
                                        <option value="RO">Rumania</option>
                                        <option value="RU">Rusia</option>
                                        <option value="XS">Saba</option>
                                        <option value="KN">Saint Kitts y Nevis</option>
                                        <option value="WS">Samoa</option>
                                        <option value="AS">Samoa Americana</option>
                                        <option value="BL">San Bartolomé</option>
                                        <option value="XE">San Eustaquio</option>
                                        <option value="SM">San Marino</option>
                                        <option value="MF">San Martín</option>
                                        <option value="PM">San Pedro y Miquelón</option>
                                        <option value="VC">San Vicente y las Granadinas</option>
                                        <option value="SH">Santa Elena, Ascensión y Tristán de Acuña</option>
                                        <option value="LC">Santa Lucía</option>
                                        <option value="VA">Santa Sede (Ciudad del Vaticano)</option>
                                        <option value="ST">Santo Tomé y Príncipe</option>
                                        <option value="SN">Senegal</option>
                                        <option value="RS">Serbia</option>
                                        <option value="YU">Serbia, Montenegro</option>
                                        <option value="SC">Seychelles</option>
                                        <option value="SL">Sierra Leona</option>
                                        <option value="SG">Singapur</option>
                                        <option value="SX">Sint Maarten</option>
                                        <option value="SY">Siria</option>
                                        <option value="SO">Somalia</option>
                                        <option value="LK">Sri Lanka</option>
                                        <option value="SZ">Suazilandia</option>
                                        <option value="ZA">Sudáfrica</option>
                                        <option value="SD">Sudán</option>
                                        <option value="SE">Suecia</option>
                                        <option value="CH">Suiza</option>
                                        <option value="SR">Surinam</option>
                                        <option value="TH">Tailandia</option>
                                        <option value="TW">Taiwán</option>
                                        <option value="TZ">Tanzania</option>
                                        <option value="TJ">Tayikistán</option>
                                        <option value="IO">Territorio Británico del Océano Índico</option>
                                        <option value="TF">Tierras Australes y Antárticas Francesas</option>
                                        <option value="TP">Timor Oriental</option>
                                        <option value="TL">Timor-Leste</option>
                                        <option value="TG">Togo</option>
                                        <option value="TK">Tokelau</option>
                                        <option value="TO">Tonga</option>
                                        <option value="TT">Trinidad y Tobago</option>
                                        <option value="TA">Tristán da Cunha</option>
                                        <option value="TN">Túnez</option>
                                        <option value="TM">Turkmenistán</option>
                                        <option value="TR">Turquía</option>
                                        <option value="TV">Tuvalu</option>
                                        <option value="UA">Ucrania</option>
                                        <option value="UG">Uganda</option>
                                        <option value="UY">Uruguay</option>
                                        <option value="UZ">Uzbekistán</option>
                                        <option value="VU">Vanuatu</option>
                                        
                                        <option value="VN">Vietnam</option>
                                        <option value="WF">Wallis y Futuna</option>
                                        <option value="YE">Yemen</option>
                                        <option value="DJ">Yibuti</option>
                                        <option value="ZM">Zambia</option>
                                        <option value="ZW">Zimbabue</option>
                                    </select>
                                    <?php echo $form->error($model, 'nacionalidad'); ?>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'estado_civil'); ?>
                                    <?php
                                    echo $form->dropDownList($model, 'estado_civil', array(
                                        '' => '--Seleccione una opción--',
                                        'Casado' => 'Casado/a',
                                        'Casado sin separación de bienes' => 'Casado/a sin separación de bienes',
                                        'Casado con separación de bienes' => 'Casado/a con separación de bienes',
                                        'Soltero' => 'Soltero/a',
                                        'Viudo' => 'Viudo/a',
                                        'Divorciado' => 'Divorciado/a',
                                        'Union Libre' => 'Union Libre', 'Union_hecho' => 'Union de Hecho'), array('class' => 'form-control'));
                                    ?>
                                    <?php echo $form->error($model, 'estado_civil'); ?>
                                </div>
                                <div class="col-md-3 conyugue_trabaja" style="display:none;">
                                    <label for="">Cónyugue Trabaja</label>
                                    <?php echo $form->dropDownList($model, 'trabaja_conyugue', array('' => '--Seleccione--', '1' => 'Si' , '0' => "No"), array('class' => 'form-control', 'id' => 'GestionSolicitudCredito_conyugue_trabaja')); ?>
                                    
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_conyugue_trabaja_error" style="display: none;">Seleccione una opción.</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'numero_cargas'); ?>
                                    <?php echo $form->dropDownList($model, 'numero_cargas', array('' => '--Seleccione--', '1' => '1' , '2' => "2", '3' => "3",
                                     '4' => "4", '5' => "6", '7' => "7", '8' => "8", '9' => "9", '10' => "10"), array('class' => 'form-control', 'options' => array('1' => array('selected' => true)))); ?>
                                    <?php echo $form->error($model, 'numero_cargas'); ?>
                                </div>
                            </div>
                    
                        <?php } ?>
                        <!--<div class="row">
                            <div class="offset6 col-md-3">
                                <button class="btn btn-danger btn-xs" onclick="createsc6()">Grabar</button>
                            </div>
                        </div>-->
                        <div class="row">
                            <h1 class="tl_seccion_rf">Empleo/Actividad Actual del Solicitante</h1>
                        </div> 
                        <div class="row">
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'empresa_trabajo'); ?>
                                <?php echo $form->textField($model, 'empresa_trabajo', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control','value' => $vartrf['nombreempleador'])); ?>
                                <?php echo $form->error($model, 'empresa_trabajo'); ?></div>
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'sector'); ?>
                                <?php echo $form->dropDownList($model, 'sector', array('' => '--Seleccione--', 'publico' => 'Público' , 'privado' => "Privado"), array('class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'sector'); ?>
                            </div>    
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'telefonos_trabajo'); ?>
                                <?php echo $form->textField($model, 'telefonos_trabajo', array('size' => 60, 'maxlength' => 9, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)','value' => $vartrf['telefonoempleador'])); ?>
                                <label class="error" id="telefonos_trabajo_error" style="display: none;">Ingrese un número vállido.</label>
                                <?php echo $form->error($model, 'telefonos_trabajo'); ?></div>
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'tiempo_trabajo'); ?>
                                <?php //echo $form->textField($model, 'tiempo_trabajo', array('class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php
                                echo $form->dropDownList($model, 'tiempo_trabajo', array(
                                    '' => '--Seleccione--',
                                    '0' => 'Menos de 1 año',
                                    '1' => '1 año',
                                    '2' => '2 años',
                                    '3' => '3 años',
                                    '4' => '4 años',
                                    '5' => '5 años',
                                    '6' => '6 años',
                                    '7' => '7 años',
                                    '8' => '8 años',
                                    '9' => '9 años',
                                    '10' => '10 años',
                                    '11' => '11 años',
                                    '12' => '12 años',
                                    '13' => '13 años',
                                    '14' => '14 años',
                                    '15' => '15 años',
                                    '16' => '16 años',
                                    '17' => '17 años',
                                    '18' => '18 años',
                                    '19' => '19 años',
                                    '20' => '20 años',
                                    '21' => '21 años',
                                    '22' => '22 años',
                                    '23' => '23 años',
                                    '24' => '24 años',
                                    '25' => '25 años',
                                    '26' => '26 años',
                                    '27' => '27 años',
                                    '28' => '28 años',
                                    '29' => '29 años',
                                    '30' => '30 años',
                                    '31' => 'Más de 30 años',
                                        ), array('class' => 'form-control','options' => array($years => array('selected' => true))));
                                ?>
                                <?php echo $form->error($model, 'tiempo_trabajo'); ?>
                            </div>
                            <div class="col-md-2">
                                <label for="GestionSolicitudCredito_meses_trabajo" class="required">Meses de Trabajo <span class="required">*</span></label>
                                <?php
                                echo $form->dropDownList($model, 'meses_trabajo', array(
                                    '' => '--Seleccione--',
                                    '1' => '1 mes',
                                    '2' => '2 meses',
                                    '3' => '3 meses',
                                    '4' => '4 meses',
                                    '5' => '5 meses',
                                    '6' => '6 meses',
                                    '7' => '7 meses',
                                    '8' => '8 meses',
                                    '9' => '9 meses',
                                    '10' => '10 meses',
                                    '11' => '11 meses'
                                        ), array('class' => 'form-control','id' => 'GestionSolicitudCredito_meses_trabajo','options' => array($meses_resto => array('selected' => true))));
                                ?>
                                
                            </div>
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'cargo'); ?>
                                <?php echo $form->textField($model, 'cargo', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control','value' => $vartrf['cargo'])); ?>
                                <?php echo $form->error($model, 'cargo'); ?>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'direccion_empresa'); ?>
                                <?php echo $form->textField($model, 'direccion_empresa', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control','value' => $vartrf['direccionempleador'])); ?>
                                <?php echo $form->error($model, 'direccion_empresa'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'tipo_relacion_laboral'); ?>
                                <?php
                                echo $form->dropDownList($model, 'tipo_relacion_laboral', array(
                                    '' => '--Seleccione actividad--',
                                    'Independiente Negocio Propio' => 'Independiente Negocio Propio',
                                    'Dependiente' => 'Dependiente',
                                    'Jubilado No Trabaja' => 'Jubilado/a No Trabaja'
                                        ), array('class' => 'form-control'));
                                ?>
                                <?php echo $form->error($model, 'tipo_relacion_laboral'); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'email'); ?>
                                <?php echo $form->textField($model, 'email', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'value' => $val['email'])); ?>
                                <?php echo $form->error($model, 'email'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'actividad_empresa'); ?>
                                <?php echo $form->textField($model, 'actividad_empresa', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control','value' => $vartrf['actividadempleador'])); ?>
                                <?php echo $form->error($model, 'actividad_empresa'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'email_laboral'); ?>
                                <?php echo $form->textField($model, 'email_laboral', array('size' => 100, 'maxlength' => 100, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'email_laboral'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset6 col-md-3">
                                <button class="btn btn-danger btn-xs" onclick="createsc()">Grabar</button>
                            </div>
                        </div>
                        <div class="conyugue">
                            <div class="row">
                                <h1 class="tl_seccion_rf">Datos del Cónyugue</h1>
                            </div> 

                            <div class="row">
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'apellido_paterno_conyugue'); ?>
                                    <?php echo $form->textField($model, 'apellido_paterno_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control', 'value' => $vartrf['nombreconyuge'])); ?>
                                    <?php echo $form->error($model, 'apellido_paterno_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_apellido_paterno_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'apellido_materno_conyugue'); ?>
                                    <?php echo $form->textField($model, 'apellido_materno_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'apellido_materno_conyugue'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'nombres_conyugue'); ?>
                                    <?php echo $form->textField($model, 'nombres_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'nombres_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_nombres_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'cedula_conyugue'); ?>
                                    <?php echo $form->textField($model, 'cedula_conyugue', array('size' => 60, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)', 'value' => $vartrf['conyugecedula'])); ?>
                                    <?php echo $form->error($model, 'cedula_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_cedula_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'ruc_conyugue'); ?>
                                    <?php echo $form->textField($model, 'ruc_conyugue', array('size' => 60, 'maxlength' => 13, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'ruc_conyugue'); ?>
                                    
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'pasaporte_conyugue'); ?>
                                    <?php echo $form->textField($model, 'pasaporte_conyugue', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'pasaporte_conyugue'); ?>
                                    
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'fecha_nacimiento_conyugue'); ?>
                                    <?php echo $form->textField($model, 'fecha_nacimiento_conyugue', array('size' => 60, 'maxlength' => 85, 'class' => 'form-control','readonly' => 'true')); ?>
                                    <?php echo $form->error($model, 'fecha_nacimiento_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_fecha_nacimiento_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'nacionalidad_conyugue'); ?>
                                    <?php //echo $form->textField($model, 'nacionalidad_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control'));  ?>
                                    <?php 
                                    echo $form->dropDownList($model, 'nacionalidad_conyugue', array(
                                        '' => '--Seleccione nacionalidad--',
                                        "EC"=>'Ecuador',
                                        "CO"=>'Colombia',
                                        "PE"=>'Perú',
                                        "VE"=>'Venezuela',
                                        "CU"=>'Cuba',
                                        "AF"=>'Afganistán',
                                        "AL"=>'Albania',
                                        "DE"=>'Alemania',
                                        "AD"=>'Andorra',
                                        "AO"=>'Angola',
                                        "AI"=>'Anguila',
                                        "AQ"=>'Antártida',
                                        "AG"=>'Antigua y Barbuda',
                                        "AN"=>'Antiguas Antillas Holandesas',
                                        "SA"=>'Arabia Saudí',
                                        "DZ"=>'Argelia',
                                        "AR"=>'Argentina',
                                        "AM"=>'Armenia',
                                        "AW"=>'Aruba',
                                        "AU"=>'Australia',
                                        "AT"=>'Austria',
                                        "PS"=>'Autoridad Palestina',
                                        "AZ"=>'Azerbaiyán',
                                        "BD"=>'Bangladesh',
                                        "BB"=>'Barbados',
                                        "BY"=>'Belarús',
                                        "BE"=>'Bélgica',
                                        "BZ"=>'Belice',
                                        "BJ"=>'Benín',
                                        "BM"=>'Bermudas',
                                        "BO"=>'Bolivia',
                                        "BQ"=>'Bonaire',
                                        "BA"=>'Bosnia y Herzegovina',
                                        "BW"=>'Botsuana',
                                        "BR"=>'Brasil',
                                        "BN"=>'Brunéi',
                                        "BG"=>'Bulgaria',
                                        "BF"=>'Burkina Faso',
                                        "BI"=>'Burundi',
                                        "BT"=>'Bután',
                                        "CV"=>'Cabo Verde',
                                        "KH"=>'Camboya',
                                        "CM"=>'Camerún',
                                        "CA"=>'Canadá',
                                        "TD"=>'Chad',
                                        "CL"=>'Chile',
                                        "CN"=>'China',
                                        "CY"=>'Chipre',

                                        "KM"=>'Comoras',
                                        "CD"=>'Congo (RDC)',
                                        "KP"=>'Corea del Norte',
                                        "KR"=>'Corea del Sur',
                                        "CR"=>'Costa Rica',
                                        "HR"=>'Croacia (Hrvatska)',
                                        
                                        "CW"=>'Curazao',
                                        "DK"=>'Dinamarca',
                                        "DM"=>'Dominica',

                                        "EG"=>'Egipto',
                                        "SV"=>'El Salvador',
                                        "AE"=>'Emiratos Árabes Unidos',
                                        "ER"=>'Eritrea',
                                        "SK"=>'Eslovaquia',
                                        "SI"=>'Eslovenia',
                                        "ES"=>'España',
                                        "US"=>'Estados Unidos',
                                        "EE"=>'Estonia',
                                        "ET"=>'Etiopía',
                                        "MK"=>'Ex-República Yugoslava de Macedonia',
                                        "PH"=>'Filipinas',
                                        "FI"=>'Finlandia',
                                        "FR"=>'Francia',
                                        "GA"=>'Gabón',
                                        "GM"=>'Gambia',
                                        "GE"=>'Georgia',
                                        "GS"=>'Georgia del Sur e Islas Sandwich del Sur',
                                        "GH"=>'Ghana',
                                        "GI"=>'Gibraltar',
                                        "GD"=>'Granada',
                                        "GR"=>'Grecia',
                                        "GL"=>'Groenlandia',
                                        "GP"=>'Guadalupe',
                                        "GU"=>'Guam',
                                        "GT"=>'Guatemala',
                                        "GF"=>'Guayana Francesa',
                                        "GG"=>'Guernsey',
                                        "GN"=>'Guinea',
                                        "GQ"=>'Guinea Ecuatorial',
                                        "GW"=>'Guinea-Bissau',
                                        "GY"=>'Guyana',
                                        "HT"=>'Haití',
                                        "HN"=>'Honduras',
                                        "HK"=>'Hong Kong RAE',
                                        "HU"=>'Hungría',
                                        "IN"=>'India',
                                        "ID"=>'Indonesia',
                                        "IQ"=>'Irak',
                                        "IR"=>'Irán',
                                        "IE"=>'Irlanda',
                                        "AC"=>'Isla Ascensión',
                                        "BV"=>'Isla Bouvet',
                                        "CX"=>'Isla Christmas',
                                        "IM"=>'Isla de Man',
                                        "NF"=>'Isla Norfolk',
                                        "IS"=>'Islandia',
                                        "AX"=>'Islas Åland',
                                        "KY"=>'Islas Caimán',
                                        "CC"=>'Islas Cocos',
                                        "CK"=>'Islas Cook',
                                        "FO"=>'Islas Feroe',
                                        "FJ"=>'Islas Fiji',
                                        "HM"=>'Islas Heard y McDonald',
                                        "FK"=>'Islas Malvinas',
                                        "MP"=>'Islas Marianas del Norte',
                                        "MH"=>'Islas Marshall',
                                        "UM"=>'Islas menores alejadas de los Estados Unidos',
                                        "PN"=>'Islas Pitcairn',
                                        "SB"=>'Islas Salomón',
                                        "TC"=>'Islas Turcas y Caicos',
                                        "VG"=>'Islas Vírgenes Británicas',
                                        "VI"=>'Islas Vírgenes, EE.UU.',
                                        "IL"=>'Israel',
                                        "IT"=>'Italia',
                                        "JM"=>'Jamaica',
                                        "SJ"=>'Jan Mayen',
                                        "JP"=>'Japón',
                                        "JE"=>'Jersey',
                                        "JO"=>'Jordania',
                                        "KZ"=>'Kazajistán',
                                        "KE"=>'Kenia',
                                        "KG"=>'Kirguistán',
                                        "KI"=>'Kiribati',
                                        "XK"=>'Kosovo',
                                        "KW"=>'Kuwait',
                                        "LA"=>'Laos',
                                        "BS"=>'Las Bahamas',
                                        "LS"=>'Lesoto',
                                        "LV"=>'Letonia',
                                        "LB"=>'Líbano',
                                        "LR"=>'Liberia',
                                        "LY"=>'Libia',
                                        "LI"=>'Liechtenstein',
                                        "LT"=>'Lituania',
                                        "LU"=>'Luxemburgo',
                                        "MO"=>'Macao RAE',
                                        "MG"=>'Madagascar',
                                        "MY"=>'Malasia',
                                        "MW"=>'Malawi',
                                        "MV"=>'Maldivas',
                                        "ML"=>'Malí',
                                        "MT"=>'Malta',
                                        "MA"=>'Marruecos',
                                        "MQ"=>'Martinica',
                                        "MU"=>'Mauricio',
                                        "MR"=>'Mauritania',
                                        "YT"=>'Mayotte',
                                        "MX"=>'México',
                                        "FM"=>'Micronesia',
                                        "MD"=>'Moldova',
                                        "MC"=>'Mónaco',
                                        "MN"=>'Mongolia',
                                        "ME"=>'Montenegro',
                                        "MS"=>'Montserrat',
                                        "MZ"=>'Mozambique',
                                        "MM"=>'Myanmar',
                                        "NA"=>'Namibia',
                                        "NR"=>'Nauru',
                                        "NP"=>'Nepal',
                                        "NI"=>'Nicaragua',
                                        "NE"=>'Níger',
                                        "NG"=>'Nigeria',
                                        "NU"=>'Niue',
                                        "NO"=>'Noruega',
                                        "NC"=>'Nueva Caledonia',
                                        "NZ"=>'Nueva Zelanda',
                                        "OM"=>'Omán',
                                        "NL"=>'Países Bajos',
                                        "PK"=>'Pakistán',
                                        "PW"=>'Palaos',
                                        "PA"=>'Panamá',
                                        "PG"=>'Papúa Nueva Guinea',
                                        "PY"=>'Paraguay',
                                        "PF"=>'Polinesia Francesa',
                                        "PL"=>'Polonia',
                                        "PT"=>'Portugal',
                                        "PR"=>'Puerto Rico',
                                        "QA"=>'Qatar',
                                        "BH"=>'Reino de Baréin',
                                        "UK"=>'Reino Unido',
                                        "CF"=>'República Centroafricana',
                                        "CZ"=>'República Checa',
                                        "CI"=>'República de CIvoire',
                                        "CG"=>'República del Congo',
                                        "DO"=>'República Dominicana',
                                        "RE"=>'Reunión',
                                        "RW"=>'Ruanda',
                                        "RO"=>'Rumania',
                                        "RU"=>'Rusia',
                                        "XS"=>'Saba',
                                        "KN"=>'Saint Kitts y Nevis',
                                        "WS"=>'Samoa',
                                        "AS"=>'Samoa Americana',
                                        "BL"=>'San Bartolomé',
                                        "XE"=>'San Eustaquio',
                                        "SM"=>'San Marino',
                                        "MF"=>'San Martín',
                                        "PM"=>'San Pedro y Miquelón',
                                        "VC"=>'San Vicente y las Granadinas',
                                        "SH"=>'Santa Elena, Ascensión y Tristán de Acuña',
                                        "LC"=>'Santa Lucía',
                                        "VA"=>'Santa Sede (Ciudad del Vaticano)',
                                        "ST"=>'Santo Tomé y Príncipe',
                                        "SN"=>'Senegal',
                                        "RS"=>'Serbia',
                                        "YU"=>'Serbia, Montenegro',
                                        "SC"=>'Seychelles',
                                        "SL"=>'Sierra Leona',
                                        "SG"=>'Singapur',
                                        "SX"=>'Sint Maarten',
                                        "SY"=>'Siria',
                                        "SO"=>'Somalia',
                                        "LK"=>'Sri Lanka',
                                        "SZ"=>'Suazilandia',
                                        "ZA"=>'Sudáfrica',
                                        "SD"=>'Sudán',
                                        "SE"=>'Suecia',
                                        "CH"=>'Suiza',
                                        "SR"=>'Surinam',
                                        "TH"=>'Tailandia',
                                        "TW"=>'Taiwán',
                                        "TZ"=>'Tanzania',
                                        "TJ"=>'Tayikistán',
                                        "IO"=>'Territorio Británico del Océano Índico',
                                        "TF"=>'Tierras Australes y Antárticas Francesas',
                                        "TP"=>'Timor Oriental',
                                        "TL"=>'Timor-Leste',
                                        "TG"=>'Togo',
                                        "TK"=>'Tokelau',
                                        "TO"=>'Tonga',
                                        "TT"=>'Trinidad y Tobago',
                                        "TA"=>'Tristán da Cunha',
                                        "TN"=>'Túnez',
                                        "TM"=>'Turkmenistán',
                                        "TR"=>'Turquía',
                                        "TV"=>'Tuvalu',
                                        "UA"=>'Ucrania',
                                        "UG"=>'Uganda',
                                        "UY"=>'Uruguay',
                                        "UZ"=>'Uzbekistán',
                                        "VU"=>'Vanuatu',
                                        "VN"=>'Vietnam',
                                        "WF"=>'Wallis y Futuna',
                                        "YE"=>'Yemen',
                                        "DJ"=>'Yibuti',
                                        "ZM"=>'Zambia',
                                        "ZW"=>'Zimbabue',
                                            ), array('class' => 'form-control','id' => 'GestionSolicitudCredito_meses_trabajo'));
                                    
                                     ?>
                                    
                                    <?php echo $form->error($model, 'nacionalidad_conyugue'); ?>
                                </div>

                            </div>
                            <div class="row">
                                <div class="offset6 col-md-3">
                                    <button class="btn btn-danger btn-xs" onclick="createsc6()">Grabar</button>
                                </div>
                            </div>
                            <div class="row">
                                <h1 class="tl_seccion_rf">Empleo/Actividad Actual del Cónyugue</h1>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <?php echo $form->labelEx($model, 'empresa_trabajo_conyugue'); ?>
                                    <?php echo $form->textField($model, 'empresa_trabajo_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'empresa_trabajo_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_empresa_trabajo_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                                <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'sector_conyugue'); ?>
                                <?php echo $form->dropDownList($model, 'sector_conyugue', array('' => '--Seleccione--', 'publico' => 'Público' , 'privado' => "Privado"), array('class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'sector_conyugue'); ?>
                                <label for="" generated="true" class="error" id="GestionSolicitudCredito_sector_conyugue_error" style="display: none;">Este campo es requerido.</label>
                            </div>  
                                <div class="col-md-2">
                                    <?php echo $form->labelEx($model, 'telefono_trabajo_conyugue'); ?>
                                    <?php echo $form->textField($model, 'telefono_trabajo_conyugue', array('size' => 50, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'telefono_trabajo_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_telefono_trabajo_conyugue_error" style="display: none;">Ingrese un teléfono válido.</label>
                                </div>

                                <div class="col-md-2">
                                    <?php echo $form->labelEx($model, 'tiempo_trabajo_conyugue'); ?>
                                    <?php //echo $form->textField($model, 'tiempo_trabajo', array('class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php
                                    echo $form->dropDownList($model, 'tiempo_trabajo_conyugue', array(
                                        '' => '--Seleccione--',
                                        '0' => 'Menos de 1 año',
                                        '1' => '1 año',
                                        '2' => '2 años',
                                        '3' => '3 años',
                                        '4' => '4 años',
                                        '5' => '5 años',
                                        '6' => '6 años',
                                        '7' => '7 años',
                                        '8' => '8 años',
                                        '9' => '9 años',
                                        '10' => '10 años',
                                        '11' => '11 años',
                                        '12' => '12 años',
                                        '13' => '13 años',
                                        '14' => '14 años',
                                        '15' => '15 años',
                                        '16' => '16 años',
                                        '17' => '17 años',
                                        '18' => '18 años',
                                        '19' => '19 años',
                                        '20' => '20 años',
                                        '21' => '21 años',
                                        '22' => '22 años',
                                        '23' => '23 años',
                                        '24' => '24 años',
                                        '25' => '25 años',
                                        '26' => '26 años',
                                        '27' => '27 años',
                                        '28' => '28 años',
                                        '29' => '29 años',
                                        '30' => '30 años',
                                        '31' => 'Más de 30 años'
                                            ), array('class' => 'form-control'));
                                    ?>
                                    <?php echo $form->error($model, 'tiempo_trabajo_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_tiempo_trabajo_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                                <div class="col-md-2">
                                    <label for="GestionSolicitudCredito_meses_trabajo" class="required">Meses de Trabajo</label>
                                    <?php
                                    echo $form->dropDownList($model, 'meses_trabajo_conyugue', array(
                                        '' => '--Seleccione--',
                                        '1' => '1 mes',
                                        '2' => '2 meses',
                                        '3' => '3 meses',
                                        '4' => '4 meses',
                                        '5' => '5 meses',
                                        '6' => '6 meses',
                                        '7' => '7 meses',
                                        '8' => '8 meses',
                                        '9' => '9 meses',
                                        '10' => '10 meses',
                                        '11' => '11 meses'
                                            ), array('class' => 'form-control','id' => 'GestionSolicitudCredito_meses_trabajo_conyugue'));
                                    ?>
                                    
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_meses_trabajo_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                                <div class="col-md-2">
                                    <?php echo $form->labelEx($model, 'cargo_conyugue'); ?>
                                    <?php echo $form->textField($model, 'cargo_conyugue', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'cargo_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_cargo_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php echo $form->labelEx($model, 'direccion_empresa_conyugue'); ?>
                                    <?php echo $form->textField($model, 'direccion_empresa_conyugue', array('size' => 60, 'maxlength' => 120, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'direccion_empresa_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_direccion_empresa_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                                <div class="col-md-4">
                                    <?php echo $form->labelEx($model, 'tipo_relacion_laboral_conyugue'); ?>
                                    <?php
                                    echo $form->dropDownList($model, 'tipo_relacion_laboral_conyugue', array(
                                        '' => '--Seleccione actividad--',
                                        'Independiente Negocio Propio' => 'Independiente Negocio Propio',
                                        'Dependiente' => 'Dependiente',
                                        'Jubilado No Trabaja' => 'Jubilado No Trabaja'), array('class' => 'form-control'));
                                    ?>
                                    <?php echo $form->error($model, 'tipo_relacion_laboral_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_tipo_relacion_laboral_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>

                            </div>
                            <div class="row">
                                <div class="offset6 col-md-3">
                                    <button class="btn btn-danger btn-xs" onclick="createsc2()">Grabar</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <h1 class="tl_seccion_rf">Domicilio Actual</h1>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'habita'); ?>
                                <?php
                                echo $form->dropDownList($model, 'habita', array(
                                    '' => '--Seleccione--',
                                    'Propia' => 'Propia',
                                    'Rentada' => 'Rentada',
                                    'Vive con Familiares' => 'Vive con Familiares'
                                        ), array('class' => 'form-control'));
                                ?>
                                <?php echo $form->error($model, 'habita'); ?>
                            </div>
                            <div class="col-md-3" id="cont-arriendo" style="display: none;">
                                <?php echo $form->labelEx($model, 'valor_arriendo'); ?>
                                <?php echo $form->textField($model, 'valor_arriendo', array('size' => 50, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'valor_arriendo'); ?>
                            </div>
                            <div class="col-md-3" id="cont-avaluo" style="display: none;">
                                <?php echo $form->labelEx($model, 'avaluo_propiedad'); ?>
                                <?php echo $form->textField($model, 'avaluo_propiedad', array('size' => 60, 'maxlength' => 14, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'avaluo_propiedad'); ?>
                                <label for="" generated="true" class="error" id="GestionSolicitudCredito_avaluo_propiedad_error" style="display: none;">Ingrese un valor correcto</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <?php //echo $form->labelEx($model, 'email');  ?>
                                <label class="" for="">Provincia Domicilio </label>
                                <?php
                                $criteria = new CDbCriteria(array(
                                    'order' => 'nombre'
                                ));
                                $provincias = CHtml::listData(Provincias::model()->findAll($criteria), "id_provincia", "nombre");
                                ?>
                                <?php
                                $proDomicilio = $this->getProvinciaIdDomicilio($id_informacion);
                                //echo $proDomicilio;
                                echo $form->dropDownList($model, 'provincia_domicilio', $provincias, array('empty' => '---Seleccione una provincia---', 'class' => 'form-control', 'options' => array($proDomicilio => array('selected' => true))));
                                ?>
                                <?php
                                /* $this->widget('ext.select2.ESelect2', array(
                                  'model' => $model,
                                  'attribute' => 'provincia_domicilio',
                                  'data' => $provincias
                                  )); */
                                ?>
                                <?php echo $form->error($model, 'provincia_domicilio'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php //echo $form->labelEx($model, 'celular'); ?>
                                <label class="" for="">Ciudad Domicilio </label>
                                <div id="info3" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                <?php
                                $ctr = new CDbCriteria;
                                $ctr->condition = "id_provincia={$proDomicilio}";
                                $ciudades = CHtml::listData(TblCiudades::model()->findAll($ctr), "id_ciudad", "nombre");
                                echo $form->dropDownList($model, 'ciudad_domicilio', $ciudades, array('' => '---Seleccione una ciudad---', 'class' => 'form-control', 'options' => array($val['ciudad_domicilio'] => array('selected' => true))));
                                ?>
                                <?php
                                /* $this->widget('ext.select2.ESelect2', array(
                                  'name' => 'GestionInformacion[ciudad_domicilio]',
                                  'id' => 'GestionInformacion_ciudad_domicilio',
                                  'data' => array(
                                  '' => '--Seleccione una ciudad--'
                                  ),
                                  )); */
                                ?>
                                <?php echo $form->error($model, 'ciudad_domicilio'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'calle'); ?>
                                <?php echo $form->textField($model, 'calle', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control', 'value' => $val['direccion'])); ?>
                                <?php echo $form->error($model, 'calle'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php // echo $form->labelEx($model, 'interseccion', array('class' => 'required')); ?>
                                <label class="required" for="GestionSolicitudCredito_manzana">Intersección <span class="required">*</span></label>
                                <?php echo $form->textField($model, 'interseccion', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'interseccion'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?php // echo $form->labelEx($model, 'numero', array('class' => 'required')); ?>
                                <label class="required" for="GestionSolicitudCredito_manzana">Número <span class="required">*</span></label>
                                <?php echo $form->textField($model, 'numero', array('size' => 60, 'maxlength' => 40, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'numero'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php // echo $form->labelEx($model, 'barrio', array('class' => 'required')); ?>
                                <label class="required" for="GestionSolicitudCredito_manzana">Barrio <span class="required">*</span></label>
                                <?php echo $form->textField($model, 'barrio', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'barrio'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php // echo $form->labelEx($model, 'manzana', array('class' => 'required')); ?>
                                <label class="required" for="GestionSolicitudCredito_manzana">Manzana
                                    <?php if($grupo_id == 3 || $grupo_id == 4)
                                    echo '<span class="required">*</span>' ?>
                                 
                             </label>
                                <?php echo $form->textField($model, 'manzana', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'manzana'); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'referencia_domicilio'); ?>
                                <?php echo $form->textField($model, 'referencia_domicilio', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'referencia_domicilio'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'telefono_residencia'); ?>
                                <?php echo $form->textField($model, 'telefono_residencia', array('size' => 50, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)', 'value' => $val['telefono_casa'])); ?>
                                <?php echo $form->error($model, 'telefono_residencia'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'celular'); ?>
                                <?php echo $form->textField($model, 'celular', array('size' => 10, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)', 'value' => $val['celular'])); ?>
                                <?php echo $form->error($model, 'celular'); ?>
                            </div>

                        </div>
                    <?php } ?> 
                    <div class="row">
                        <div class="offset6 col-md-3">
                            <button class="btn btn-danger btn-xs" onclick="createsc7()">Grabar</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h1 class="tl_seccion_rf tl_seccion_rft">Ingresos Mensuales Familiares</h1>
                            <div class="col-md-12">
                                <?php echo $form->labelEx($model, 'sueldo_mensual'); ?>
                                <?php echo $form->textField($model, 'sueldo_mensual', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'sueldo_mensual'); ?>
                            <label for="" generated="true" class="error" id="GestionSolicitudCredito_sueldo_mensual_error" style="display: none;">Sueldo mensual debe ser mayor a $ 300</label>
                            </div>
                            <div class="col-md-12">
                                <?php echo $form->labelEx($model, 'sueldo_mensual_conyugue'); ?>
                                <?php echo $form->textField($model, 'sueldo_mensual_conyugue', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'sueldo_mensual_conyugue'); ?>
                            <label for="" generated="true" class="error" id="GestionSolicitudCredito_sueldo_mensual_conyugue_error" style="display: none;">Este campo es requerido.</label>
                            <label for="" generated="true" class="error" id="GestionSolicitudCredito_sueldo_mensual_conyugue_error2" style="display: none;">Sueldo mensual debe ser mayor a $ 300</label>
                            </div>
                            <div class="col-md-12">
                                <?php echo $form->labelEx($model, 'otros_ingresos'); ?>
                                <?php echo $form->textField($model, 'otros_ingresos', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'otros_ingresos'); ?>
                            </div>
                            <div class="col-md-12">
                                <?php echo $form->labelEx($model, 'descripcion_otros_ingresos'); ?>
                                <?php echo $form->textField($model, 'descripcion_otros_ingresos', array('size' => 20, 'maxlength' => 100, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'descripcion_otros_ingresos'); ?>
                                <label for="" generated="true" class="error" id="GestionSolicitudCredito_descripcion_otros_ingresos_error" style="display: none;">Ingrese la descripción de otros ingresos</label>
                            </div>
                            <div class="col-md-12">
                                <?php echo $form->labelEx($model, 'total_ingresos'); ?>
                                <?php echo $form->textField($model, 'total_ingresos', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'total_ingresos'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h1 class="tl_seccion_rf tl_seccion_rft">Gastos Mensuales Familiares</h1>
                            <div class="col-md-12">
                                <?php echo $form->labelEx($model, 'gastos_arriendo'); ?>
                                <?php echo $form->textField($model, 'gastos_arriendo', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'gastos_arriendo'); ?>
                            </div>
                            <div class="col-md-12">
                                <?php echo $form->labelEx($model, 'gastos_alimentacion_otros'); ?>
                                <?php echo $form->textField($model, 'gastos_alimentacion_otros', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'gastos_alimentacion_otros'); ?>
                            </div>
                            <div class="col-md-12">
                                <?php echo $form->labelEx($model, 'gastos_educacion'); ?>
                                <?php echo $form->textField($model, 'gastos_educacion', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'gastos_educacion'); ?>
                            </div>
                            <div class="col-md-12">
                                <?php echo $form->labelEx($model, 'gastos_prestamos'); ?>
                                <?php echo $form->textField($model, 'gastos_prestamos', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'gastos_prestamos'); ?>
                            </div>
                            <div class="col-md-12">
                                <?php echo $form->labelEx($model, 'gastos_tarjetas_credito'); ?>
                                <?php echo $form->textField($model, 'gastos_tarjetas_credito', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'gastos_tarjetas_credito'); ?>
                            </div>
                            <div class="col-md-12">
                                <?php echo $form->labelEx($model, 'total_egresos'); ?>
                                <?php echo $form->textField($model, 'total_egresos', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'total_egresos'); ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="offset6 col-md-3">
                            <button class="btn btn-danger btn-xs" onclick="createsc3()">Grabar</button>
                        </div>
                    </div>
                    <div class="row">
                        <h1 class="tl_seccion_rf">Referencias Bancarias</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'banco1'); ?>
                            <?php
                            $criteria2 = new CDbCriteria(array(
                                'order' => 'nombre'
                            ));

                            $bancos = CHtml::listData(GestionBancos::model()->findAll($criteria2), "id", "nombre");
                            ?>
                            <?php
                            echo $form->dropDownList($model, 'banco1', $bancos, array('class' => 'form-control', 'empty' => '---Seleccione---'));
                            ?>
                            <?php echo $form->error($model, 'banco1'); ?>
                        </div>
                        <div class="otro-bn-1" style="display: none;">
                            <div class="col-md-3">
                                <label for="">Otra institución</label>
                                <input type="text" name="GestionSolicitudCredito[otro1]" id="GestionSolicitudCredito_otro1" class="form-control"/>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'cuenta_ahorros1'); ?>
                            <?php echo $form->textField($model, 'cuenta_ahorros1', array('size' => 50, 'maxlength' => 12, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'cuenta_ahorros1'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'cuenta_corriente1'); ?>
                            <?php echo $form->textField($model, 'cuenta_corriente1', array('size' => 50, 'maxlength' => 12, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'cuenta_corriente1'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'banco2'); ?>
                            <?php
                            echo $form->dropDownList($model, 'banco2', $bancos, array('class' => 'form-control', 'empty' => '---Seleccione---'));
                            ?>
                            <?php echo $form->error($model, 'banco2'); ?>
                        </div>
                        <div class="otro-bn-2" style="display: none;">
                            <div class="col-md-3">
                                <label for="">Otra institución</label>
                                <input type="text" name="GestionSolicitudCredito[otro2]" id="GestionSolicitudCredito_otro2" class="form-control"/>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'cuenta_ahorros2'); ?>
                            <?php echo $form->textField($model, 'cuenta_ahorros2', array('size' => 50, 'maxlength' => 12, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'cuenta_ahorros2'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'cuenta_corriente2'); ?>
                            <?php echo $form->textField($model, 'cuenta_corriente2', array('size' => 50, 'maxlength' => 12, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'cuenta_corriente2'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <h1 class="tl_seccion_rf">Referencias Personales</h1>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'referencia_personal1'); ?>
                            <?php echo $form->textField($model, 'referencia_personal1', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'referencia_personal1'); ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'parentesco1'); ?>
                            <?php
                            echo $form->dropDownList($model, 'parentesco1', array(
                                '' => '-Seleccione-',
                                'Padre' => 'Padre',
                                'Madre' => 'Madre',
                                'Suegro' => 'Suegro/a',
                                'Cunado' => 'Cuñado/a',
                                'Hijo' => 'Hijo',
                                'Hermano' => 'Hermano',
                                'Primo' => 'Primo/a',
                                'Tio' => 'Tio/a',
                                'Abuelo' => 'Abuelo/a',
                                'Amigo' => 'Amigo/a'), array('class' => 'form-control'));
                            ?>
                            <?php echo $form->error($model, 'parentesco1'); ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'telefono_referencia1'); ?>
                            <?php echo $form->textField($model, 'telefono_referencia1', array('size' => 60, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                            <?php echo $form->error($model, 'telefono_referencia1'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'referencia_personal2'); ?>
                            <?php echo $form->textField($model, 'referencia_personal2', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'referencia_personal2'); ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'parentesco2'); ?>
                            <?php
                            echo $form->dropDownList($model, 'parentesco2', array(
                                '' => '-Seleccione-',
                                'Padre' => 'Padre',
                                'Madre' => 'Madre',
                                'Suegro' => 'Suegro/a',
                                'Cunado' => 'Cuñado/a',
                                'Hijo' => 'Hijo',
                                'Hermano' => 'Hermano',
                                'Primo' => 'Primo/a',
                                'Tio' => 'Tio/a',
                                'Abuelo' => 'Abuelo/a',
                                'Amigo' => 'Amigo/a'), array('class' => 'form-control'));
                            ?>
                            <?php echo $form->error($model, 'parentesco2'); ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'telefono_referencia2'); ?>
                            <?php echo $form->textField($model, 'telefono_referencia2', array('size' => 60, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                            <?php echo $form->error($model, 'telefono_referencia2'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset6 col-md-3">
                            <button class="btn btn-danger btn-xs" onclick="createsc4()">Grabar</button>
                        </div>
                    </div>
                    <div class="row">
                        <h1 class="tl_seccion_rf">Activos y Propiedades</h1>
                    </div>

                    <div class="row activos">
                        <div class="col-md-3">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <!--<input type="checkbox" value="Local" name="activos[]" id="">-->
                                        <?php echo $form->checkBox($model,'tipo_activo1',array('value' => 'Local', 'name' => 'activos[]')); ?>
                                        Local
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <!--<input type="checkbox" value="Finca" name="activos[]" id="">-->
                                        <?php echo $form->checkBox($model,'tipo_activo1',array('value' => 'Finca', 'name' => 'activos[]')); ?>
                                        Finca
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <!--<input type="checkbox" value="Casa" name="activos[]" id="">-->
                                        <?php echo $form->checkBox($model,'tipo_activo1',array('value' => 'Casa', 'name' => 'activos[]')); ?>
                                        Casa
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <!--<input type="checkbox" value="Dpto" name="activos[]" id="">-->
                                        <?php echo $form->checkBox($model,'tipo_activo1',array('value' => 'Dpto', 'name' => 'activos[]')); ?>
                                        Dpto
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <!--<input type="checkbox" value="Lote" name="activos[]" id="">-->
                                        <?php echo $form->checkBox($model,'tipo_activo1',array('value' => 'Lote', 'name' => 'activos[]')); ?>
                                        Lote
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="">Dirección</label>
                            <!--<input type="text" class="form-control" name="GestionSolicitudCredito[direccion_activo1]"/>-->
                            <?php echo $form->textField($model, 'direccion_activo1', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                            
                        </div>
                        <div class="col-md-3">
                            <label for="">Sector</label>
                            <!--<input type="text" class="form-control" name="GestionSolicitudCredito[direccion_sector1]"/>-->
                            <?php echo $form->textField($model, 'direccion_sector1', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div><div class="col-md-3">
                            <label for="">Valor Comercial</label>
                            <!--<input type="text" maxlength="14" class="form-control" id="GestionSolicitudCreditodireccion_valor_comercial1" name="GestionSolicitudCredito[direccion_valor_comercial1]" onkeypress="return validateNumbers(event)"/>-->
                            <?php echo $form->textField($model, 'direccion_valor_comercial1', array('size' => 60, 'maxlength' => 40, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                        </div>
                    </div>
                    <hr />
                    <div class="row activos">
                        <div class="col-md-3">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <!--<input type="checkbox" value="Casa2" name="activos[]" id="">-->
                                        <?php echo $form->checkBox($model,'tipo_activo2',array('value' => 'Casa', 'name' => 'activos2[]')); ?>
                                        Casa
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <!--<input type="checkbox" value="Finca" name="activos[]" id="">-->
                                        <?php echo $form->checkBox($model,'tipo_activo2',array('value' => 'Dpto', 'name' => 'activos2[]')); ?>
                                        Dpto
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <!--<input type="checkbox" value="Terreno" name="activos[]" id="">-->
                                        <?php echo $form->checkBox($model,'tipo_activo2',array('value' => 'Terreno', 'name' => 'activos2[]')); ?>
                                        Terreno
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="">Dirección</label>
                            <!--<input type="text" class="form-control" name="GestionSolicitudCredito[direccion_activo2]"/>-->
                            <?php echo $form->textField($model, 'direccion_activo2', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                        <div class="col-md-3">
                            <label for="">Sector</label>
                            <!--<input type="text" class="form-control" name="GestionSolicitudCredito[direccion_sector2]"/>-->
                            <?php echo $form->textField($model, 'direccion_sector2', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div><div class="col-md-3">
                            <label for="">Valor Comercial</label>
                            <!--<input type="text" maxlength="14" class="form-control" id="GestionSolicitudCreditodireccion_valor_comercial2" name="GestionSolicitudCredito[direccion_valor_comercial2]"  onkeypress="return validateNumbers(event)"/>-->
                            <?php echo $form->textField($model, 'direccion_valor_comercial2', array('size' => 60, 'maxlength' => 40, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <?php
                        $criteria4 = new CDbCriteria(array(
                            'condition' => "id_informacion={$id_informacion}",
                            'limit' => 1
                        ));
                        $art = GestionConsulta::model()->findAll($criteria4);
                        foreach ($art as $c):
                            if ($c['preg1_sec5'] == 0 ): // SI TIENE VEHICULO
                                $params = explode('@', $c['preg1_sec2']);
                                //print_r($params);
                                $modelo_auto = $params[1] . ' ' . $params[2];
                                ?>
                                <div class="col-md-3">
                                    <label for="">Vehículo: Marca</label>
                                    <input type="text" name="GestionSolicitudCredito[vehiculo_marca1]" class="form-control" value="<?php echo $c['preg1_sec1']; ?>"/>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Modelo</label>
                                    <input type="text" name="GestionSolicitudCredito[vehiculo_modelo1]" class="form-control" value="<?php echo $modelo_auto; ?>"/>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Año</label>
                                    <input type="text" name="GestionSolicitudCredito[vehiculo_year1]" class="form-control" value="<?php echo $c['preg1_sec3']; ?>"/>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Valor Comercial</label>
                                    <input type="text" maxlength="14" id="GestionSolicitudCreditovehiculo_valor1" name="GestionSolicitudCredito[vehiculo_valor1]" class="form-control" onkeypress="return validateNumbers(event)"/>
                                </div>
                            <?php elseif($c['preg1_sec5'] == 1 || empty($c['preg1_sec5'])): ?>
                                <div class="col-md-3">
                                    <label for="">Vehículo: Marca</label>
                                    <?php echo $form->textField($model, 'vehiculo_marca1', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                                    <!--<input type="text" name="GestionSolicitudCredito[vehiculo_marca2]" class="form-control"/>-->
                                </div>
                                <div class="col-md-3">
                                    <label for="">Modelo</label>
                                    <?php echo $form->textField($model, 'vehiculo_modelo1', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                                    <!--<input type="text" name="GestionSolicitudCredito[vehiculo_modelo2]" class="form-control"/>-->
                                </div>
                                <div class="col-md-3">
                                    <label for="">Año</label>
                                    <?php echo $form->textField($model, 'vehiculo_year1', array('size' => 60, 'maxlength' => 10, 'class' => 'form-control')); ?>
                                    <!--<input type="text" name="GestionSolicitudCredito[vehiculo_year2]" class="form-control"/>-->
                                </div>
                                <div class="col-md-3">
                                    <label for="">Valor Comercial</label>
                                    <?php echo $form->textField($model, 'vehiculo_valor1', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                                    <!--<input type="text" id="GestionSolicitudCreditovehiculo_valor2" maxlength="14" name="GestionSolicitudCredito[vehiculo_valor2]" class="form-control" onkeypress="return validateNumbers(event)"/>-->
                                </div>
                            <?php endif; ?>    
                        <?php endforeach;?>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Vehículo: Marca</label>
                            <?php echo $form->textField($model, 'vehiculo_marca2', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                            <!--<input type="text" name="GestionSolicitudCredito[vehiculo_marca2]" class="form-control"/>-->
                        </div>
                        <div class="col-md-3">
                            <label for="">Modelo</label>
                            <?php echo $form->textField($model, 'vehiculo_modelo2', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                            <!--<input type="text" name="GestionSolicitudCredito[vehiculo_modelo2]" class="form-control"/>-->
                        </div>
                        <div class="col-md-3">
                            <label for="">Año</label>
                            <?php echo $form->textField($model, 'vehiculo_year2', array('size' => 60, 'maxlength' => 10, 'class' => 'form-control')); ?>
                            <!--<input type="text" name="GestionSolicitudCredito[vehiculo_year2]" class="form-control"/>-->
                        </div>
                        <div class="col-md-3">
                            <label for="">Valor Comercial</label>
                            <?php echo $form->textField($model, 'vehiculo_valor2', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                            <!--<input type="text" id="GestionSolicitudCreditovehiculo_valor2" maxlength="14" name="GestionSolicitudCredito[vehiculo_valor2]" class="form-control" onkeypress="return validateNumbers(event)"/>-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Tipo de inversión</label>
<!--                            <input type="text" name="GestionSolicitudCredito[tipo_inversion]" class="form-control"/>-->
                            <?php echo $form->textField($model, 'tipo_inversion', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="">Institución</label>
                            <!--<input type="text" name="GestionSolicitudCredito[institucion_inversion]" class="form-control"/>-->
                            <?php echo $form->textField($model, 'institucion_inversion', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="">Valor</label>
                            <!--<input type="text" id="GestionSolicitudCreditovalor_inversion" maxlength="14" name="GestionSolicitudCredito[valor_inversion]" class="form-control"/>-->
                            <?php echo $form->textField($model, 'valor_inversion', array('size' => 60, 'maxlength' => 14, 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Otros activos</label>
                            <!--<input type="text" name="GestionSolicitudCredito[otros_activos1]" class="form-control"/>-->
                            <?php echo $form->textField($model, 'otros_activos', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="">Descripción</label>
                            <!--<input type="text" name="GestionSolicitudCredito[descripcion1]" class="form-control"/>-->
                            <?php echo $form->textField($model, 'descripcion1', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="">Valor</label>
                            <!--<input type="text" id="GestionSolicitudCreditovalor_otros_activos1" maxlength="14" name="GestionSolicitudCredito[valor_otros_activos1]" class="form-control" onkeypress="return validateNumbers(event)"/>-->
                            <?php echo $form->textField($model, 'valor_otros_activos1', array('size' => 60, 'maxlength' => 14, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Otros activos</label>
                            <!--<input type="text" name="GestionSolicitudCredito[otros_activos2]" class="form-control"/>-->
                            <?php echo $form->textField($model, 'otros_activos2', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="">Descripción</label>
                            <!--<input type="text" name="GestionSolicitudCredito[descripcion2]" class="form-control"/>-->
                            <?php echo $form->textField($model, 'descripcion2', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="">Valor</label>
                            <!--<input type="text" id="GestionSolicitudCreditovalor_otros_activos2" maxlength="14" name="GestionSolicitudCredito[descripcion2]" class="form-control" onkeypress="return validateNumbers(event)"/>-->
                            <?php echo $form->textField($model, 'valor_otros_activos2', array('size' => 60, 'maxlength' => 14, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Total</label>
                            <?php echo $form->textField($model, 'total_activos', array('size' => 60, 'maxlength' => 50, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset6 col-md-3">
                            <button class="btn btn-danger btn-xs" onclick="createsc5()">Grabar</button>
                        </div>
                    </div>
                    <div class="row">
                        <h1 class="tl_seccion_rf">Firma del Cliente</h1>
                    </div>
                    <div class="row">
                        <?php
                        $firma = GestionFirma::model()->count(array('condition' => "id_informacion={$id_informacion} AND tipo = 2"));
                        if ($firma > 0):
                            $fr = GestionFirma::model()->find(array('condition' => "id_informacion={$id_informacion} AND tipo = 2"));
                            $imgfr = $fr->firma;
                            ?>

                        <div class="row">
                                <div class="col-md-5">
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/upload/firma/<?php echo $imgfr; ?>" alt="" width="200" height="100">
                                    <input type="hidden" id="img-firma-cont" value="<?php echo $imgfr; ?>">
                                    <hr>
                                    Firma Cliente
                                </div>
                                
                            </div>
                        <?php else: ?>
                            <div id="inline1" style="width:800px;display: none;height: 400px;">
                                <!--div class="row">
                                    <h1 class="tl_seccion_rf">Ingreso de firma</h1>
                                </div-->
                                <div class="row">
                                    <div class="col-md-12"> 
                                            <script type="text/javascript">
                                                  function UploadPic() {
                                                        alert('upload pic send');
                                                        // generate the image data
                                                        var data = document.getElementById("colors_sketch2").toDataURL("image/png");
                                                        //console.log('data: '+data);
                                                        var output = data.replace(/^data:image\/(png|jpg);base64,/, "");
                                                        // Sending the image data to Server
                                                        if (confirm("Antes de continuar, esta seguro que ha realizado su firma correctamente?")) {
                                                            $.ajax({
                                                                type: 'POST',
                                                                url: '<?php echo Yii::app()->createUrl("/site/grabarFirma") ?>',
                                                                // tipo 2 cuando es firma para solicitud de credito
                                                                data: {imageData: output, id_informacion: "<?php echo $id_informacion; ?>", tipo: 2},
                                                                success: function (msg) {
                                                                    if (msg == 1) {
                                                                        alert('Firma cargada exitosamente.');
                                                                        //var ur = 'https://www.kia.com.ec/intranet/ventas/index.php/gestionTestDrive/create/'+<?php echo $_GET['id'] ?>+'?id_informacion='+<?php echo $id_informacion; ?>;
                                                                        //$(location).attr('href', ur)
                                                                        $('#cont-firma').hide();
                                                                        $.ajax({
                                                                            type: 'POST',
                                                                            dataType: "json",
                                                                            url: '<?php echo Yii::app()->createUrl("/site/getFirma") ?>',
                                                                            data: {id_informacion: "<?php echo $id_informacion; ?>"},
                                                                            success: function (data) {
                                                                                //console.log('firma digital: '+data.firma);
                                                                                $('#img-firma').attr('src', '/intranet/usuario/upload/firma/' + data.firma);
                                                                                $('#img-firma-cont').val(data.firma);
                                                                                $('#cont-firma').hide();
                                                                                $('#cont-firma-img').show();
                                                                                //$('#cont-btn').show();
                                                                            }
                                                                        });

                                                                        $.fancybox.close();
                                                                    }
                                                                }
                                                            });

                                                        }
                                                    }                                     


                                            </script>
                                            <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/canvas/signature-pad.css" rel="stylesheet">
                                            <div id="signature-pad" class="m-signature-pad">
                                                <div class="m-signature-pad--body">
                                                      <canvas id="colors_sketch2"></canvas>
                                                </div>
                                                <div class="m-signature-pad--footer">
                                                    <div class="description">Firma arriba</div>
                                                    <button type="button" class="button clear btn" data-action="clear">Borrar</button>
                                                    <button type="button" class="button save" data-action="save" style="display:none;">Guardar</button>
                                                    <button type="button" class="button save btn btn-danger" onclick="UploadPic()" >Guardar</button>

                                                    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/canvas/signature_pad.js"></script>
                                                    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/canvas/app.js"></script>

                                                      <!--ORIGINLAES-->
                                                      <!--button type="button" class="button save" data-action="save" >Guardar</button>
                                                      <input type="button"  onclick="UploadPic()" class=" btn btn-info" value="Subir Firma"-->
                                                </div>
                                            </div>

                                        <!--canvas id="colors_sketch" width="800" height="300"></canvas-->
                                    </div>
                                </div>
                                <!--div class="row">
                                    <div class="col-md-8">
                                        <div class="tools">
                                            <!--<a href="#colors_sketch" data-download="png" class="btn btn-success">Descargar firma</a>-->
                                            <!--input type="button"  data-clear='true' class="reset-canvas btn btn-warning" value="Borrar Firma">
                                            <input type="button"  onclick="UploadPic()" class=" btn btn-info" value="Subir Firma">
                                        </div>
                                    </div>
                                </div-->
                            </div>
                            <div class="row">
                                <div class="col-md-4" id="cont-firma">
                                    <!--<a href="<?php echo Yii::app()->createUrl('site/signature/', array('id' => $id, 'id_informacion' => $id_informacion)); ?>" class="btn btn-xs btn-primary">Ingresar Firma</a>-->
                                    <a href="#inline1" class="fancybox btn btn-xs btn-primary">Ingresar Firma</a> 
                                </div>
                                <div class="col-md-5" id="cont-firma-img" style="display: none;">
                                    <img src="" alt="" width="200" height="100" id="img-firma">
                                    <input type="hidden" id="img-firma-cont" value="">
                                    <hr>
                                    Firma Cliente
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        
                    </div>
                    
                    <div class="row buttons">
                        <div class="col-md-4">
                            <input class="btn btn-danger" id="finalizar" type="submit" name="yt0" value="Crear" onclick="sendSol();">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <a class="btn btn-warning" id="confirm" style="display: none;" onclick="confirmar();">Confirmar</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <input class="btn btn-success" id="send-asesor" type="submit" style="display: none;" onclick="send();" value="Enviar">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/cotizacion/', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" class="btn btn-warning" id="generatepdf" style="display: none;" target="_blank">Solicitud de Crédito</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <?php 
                            if($cargo_id == 70 || $cargo_id == 71){
                                echo '<a href="'.Yii::app()->createUrl('site/negociacion/' . $id_informacion).'" class="btn btn-danger" id="continue">Agendar Seguimiento</a>';
                            }
                             ?>
                            
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>

                </div><!-- form -->
            </div>
        </div>
    </div>

</div>