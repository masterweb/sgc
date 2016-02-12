<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.maskMoney.js" type="text/javascript"></script>
<?php
/* @var $this GestionSolicitudCreditoController */
/* @var $model GestionSolicitudCredito */
/* @var $form CActiveForm */
//echo 'id vehiculo: '.$id_vehiculo;
$id_asesor = Yii::app()->user->getId();
$dealer_id = $this->getConcesionarioDealerId($id_asesor);
$id_responsable = $this->getResponsableId($id_informacion);
$id_modelo = $this->getIdModelo($id_vehiculo);
//$nombre_modelo = $this->getVersion($id_vehiculo);
//$id_version = $this->getVersion($id_vehiculo);
//echo 'id repsonsable:'.$id_asesor;
$modelo = $this->getNombreModelo($id_informacion, $id_vehiculo);
$credito = $this->getFinanciamiento($id_informacion);
$nombre_concesionario = $this->getNameConcesionarioById($dealer_id);
//echo 'nombre concesionario : '.$nombre_concesionario;
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<style type="text/css">
    .tl_seccion_rf_s {
        background-color: #aa1f2c;
        padding: 5px 28px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 18px;
        color: #fff;
        font-weight: bold;
        margin-top: 20px;
        width: 100%;
    }
    .activos label{font-size: 0.7em !important;}
    .activos .checkbox{margin-bottom: -6px;}
</style>
<script type="text/javascript">
    $(document).ready(function () {
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

        $('#GestionSolicitudCredito_apellido_paterno_conyugue').keyup(function () {
            $('#GestionSolicitudCredito_apellido_paterno_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_apellido_paterno_conyugue_error').hide();
        });
        $('#GestionSolicitudCredito_telefonos_trabajo').keyup(function(){
            $('#telefonos_trabajo_error').hide();
        });
        $('#GestionSolicitudCredito_nombres_conyugue').keyup(function () {
            $('#GestionSolicitudCredito_nombres_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_nombres_conyugue_error').hide();
        });
        $('#GestionSolicitudCredito_cedula_conyugue').keyup(function () {
            $('#GestionSolicitudCredito_cedula_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_cedula_conyugue_error').hide();
        });
        $('#GestionSolicitudCredito_fecha_nacimiento_conyugue').click(function () {
            $('#GestionSolicitudCredito_fecha_nacimiento_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_fecha_nacimiento_conyugue_error').hide();
        });
        $('#GestionSolicitudCredito_empresa_trabajo_conyugue').keyup(function () {
            $('#GestionSolicitudCredito_empresa_trabajo_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_empresa_trabajo_conyugue_error').hide();
        });
        $('#GestionSolicitudCredito_telefono_trabajo_conyugue').keyup(function () {
            $('#GestionSolicitudCredito_telefono_trabajo_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_telefono_trabajo_conyugue_error').hide();
        });
        $('#GestionSolicitudCredito_tiempo_trabajo_conyugue').click(function () {
            $('#GestionSolicitudCredito_tiempo_trabajo_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_tiempo_trabajo_conyugue_error').hide();
        });
        $('#GestionSolicitudCredito_meses_trabajo_conyugue').click(function () {
            $('#GestionSolicitudCredito_meses_trabajo_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_meses_trabajo_conyugue_error').hide();
        });
        $('#GestionSolicitudCredito_cargo_conyugue').keyup(function () {
            $('#GestionSolicitudCredito_cargo_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_cargo_conyugue_error').hide();
        });
        $('#GestionSolicitudCredito_direccion_empresa_conyugue').keyup(function () {
            $('#GestionSolicitudCredito_direccion_empresa_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_direccion_empresa_conyugue_error').hide();
        });
        $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').keyup(function () {
            $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_tipo_relacion_laboral_conyugue_error').hide();
        });
        $('#GestionSolicitudCredito_sueldo_mensual_conyugue').keyup(function () {
            $('#GestionSolicitudCredito_sueldo_mensual_conyugue').removeClass('error');
            $('#GestionSolicitudCredito_sueldo_mensual_conyugue_error').hide();
        });
        $('#GestionSolicitudCreditovehiculo_valor2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCreditovalor_inversion').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCreditodireccion_valor_comercial1').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCreditodireccion_valor_comercial2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCreditovehiculo_valor1').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCreditovalor_otros_activos1').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCreditovalor_otros_activos2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_avaluo_propiedad').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_valor_arriendo').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_sueldo_mensual').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_sueldo_mensual_conyugue').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionSolicitudCredito_otros_ingresos').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        var valor = parseInt($('#GestionSolicitudCredito_valor').val());
        var valorformat = format2(valor, '$');
        $('#GestionSolicitudCredito_valor').val(valorformat);

        var financiar = parseInt($('#GestionSolicitudCredito_monto_financiar').val());
        var financiarformat = format2(financiar, '$');
        $('#GestionSolicitudCredito_monto_financiar').val(financiarformat);

        var entrada = parseInt($('#GestionSolicitudCredito_entrada').val());
        var entradaformat = format2(entrada, '$');
        $('#GestionSolicitudCredito_entrada').val(entradaformat);

        var cuotamensual = parseInt($('#GestionSolicitudCredito_cuota_mensual').val());
        var cuotamensualformat = format2(cuotamensual, '$');
        $('#GestionSolicitudCredito_cuota_mensual').val(cuotamensualformat);

        $("[name='GestionSolicitudCredito[direccion_valor_comercial1]']").keyup(function () {
            getvalortotal();
        });

        $("[name='GestionSolicitudCredito[direccion_valor_comercial2]']").keyup(function () {
            getvalortotal();
        });

        $("[name='GestionSolicitudCredito[vehiculo_valor2]']").keyup(function () {
            getvalortotal();
        });


        $("[name='GestionSolicitudCredito[valor_inversion]']").keyup(function () {
            getvalortotal();
        });

        $("[name='GestionSolicitudCredito[valor_otros_activos1]']").keyup(function () {
            getvalortotal();
        });

        $("[name='GestionSolicitudCredito[valor_otros_activos2]']").keyup(function () {
            getvalortotal();
        });

        $("#GestionSolicitudCredito_fecha_nacimiento").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '1950:1996'
        });
        $("#GestionSolicitudCredito_fecha_nacimiento_conyugue").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '1950:1996'
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
                    break;
                case 'Casado sin separación de bienes':
                case 'Casado':
                case 'Union Libre':
                    //validateSoltero();    
                    $('.conyugue').slideDown();
                    break;
            }

        });
        $('#GestionSolicitudCredito_habita').change(function () {
            var value = $(this).attr('value');
            //alert(value);
            switch (value) {
                case 'Rentada':
                    $('#cont-arriendo').show();
                    $('#cont-avaluo').hide();
                    break;
                case 'Propia':
                    $('#cont-arriendo').hide();
                    $('#cont-avaluo').show();
                    break;
                case 'Vive con Familiares':
                    $('#cont-arriendo').hide();
                    $('#cont-avaluo').hide();
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
                    $('#GestionInformacion_ciudad_domicilio').html(data);
                    $('#info3').hide();
                }
            });
            //alert(value)
            $('#GestionInformacion_ciudad_domicilio').html(data);

        });
    });
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
    function confirm() {
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
        if (count > 4 || cer > 0){
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
                'GestionSolicitudCredito[telefonos_trabajo]': {required: true},
                'GestionSolicitudCredito[tiempo_trabajo]': {required: true},
                'GestionSolicitudCredito[cargo]': {required: true},
                'GestionSolicitudCredito[direccion_empresa]': {required: true},
                'GestionSolicitudCredito[tipo_relacion_laboral]': {required: true},
                'GestionSolicitudCredito[email]': {required: true},
                'GestionSolicitudCredito[actividad_empresa]': {required: true},
                'GestionSolicitudCredito[ciudad_domicilio]': {required: true},
                'GestionSolicitudCredito[barrio]': {required: true},
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
                        //validateCasado();
                        break;
                    case 'Casado sin separación de bienes':
                    case 'Casado':
                    case 'Union Libre':
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
                        if ($('#GestionSolicitudCredito_empresa_trabajo_conyugue').val() == '') {
                            $('#GestionSolicitudCredito_empresa_trabajo_conyugue_error').show();
                            $('#GestionSolicitudCredito_empresa_trabajo_conyugue').focus().addClass('error');
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
                            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionSolicitudCredito/createAjax"); ?>',
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
                        url: '<?php echo Yii::app()->createAbsoluteUrl("gestionSolicitudCredito/createAjax"); ?>',
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
        var valorcomercial1 = $("[name='GestionSolicitudCredito[direccion_valor_comercial1]']").val();
        var valorcomercial2 = $("[name='GestionSolicitudCredito[direccion_valor_comercial2]']").val();
        var vehiculovalor2 = $("[name='GestionSolicitudCredito[vehiculo_valor2]']").val();
        var valorinversion = $("[name='GestionSolicitudCredito[valor_inversion]']").val();
        var valoriotrosactivos1 = $("[name='GestionSolicitudCredito[valor_otros_activos1]']").val();
        var valoriotrosactivos2 = $("[name='GestionSolicitudCredito[valor_otros_activos2]']").val();
        valorcomercial1 = formatnumber(valorcomercial1);
        valorcomercial2 = formatnumber(valorcomercial2);
        vehiculovalor2 = formatnumber(vehiculovalor2);
        valorinversion = formatnumber(valorinversion);
        valoriotrosactivos1 = formatnumber(valoriotrosactivos1);
        valoriotrosactivos2 = formatnumber(valoriotrosactivos2);
        var valuetotal = $("[name='GestionSolicitudCredito[total_activos]']").val();
        valueft = formatnumber(valuetotal);
        total = valorcomercial1 + valorcomercial2 + vehiculovalor2 + valorinversion + valoriotrosactivos1 + valoriotrosactivos2;
        total = format2(total, '$');

        $("[name='GestionSolicitudCredito[total_activos]']").val(total);
    }

    function formatnumber(precioanterior) {
        if (precioanterior == '') {
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
                    <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/negociacion/<?= $id_informacion ?>" class="btn btn-xs btn-cat306 btn-cat btn-success btn-danger" >Regresar</a>
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
                    $criteria = new CDbCriteria(array(
                        'condition' => "id_informacion={$id_informacion}",
                        'order' => 'id DESC',
                        'limit' => 1
                    ));
                    $vec = GestionFinanciamiento::model()->findAll($criteria);
                    /* echo '<pre>';
                      print_r($vec);
                      echo '</pre>'; */
                    ?>
                    <?php foreach ($vec as $value) { ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'modelo'); ?>
                                <?php echo $form->textField($model, 'modelo', array('class' => 'form-control', 'value' => $modelo)); ?>
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
                                <?php echo $form->dropDownList($model, 'year', array('' => '-Seleccione-', '2014' => '2014', '2015' => '2015', '2016' => '2016', '2017' => '2017'), array('class' => 'form-control')); ?>
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
                                    <?php echo $form->textField($model, 'fecha_nacimiento', array('size' => 60, 'maxlength' => 75, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'fecha_nacimiento'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'nacionalidad'); ?>
                                    <?php //echo $form->dropDownList($model, 'nacionalidad', array('' => '--Seleccione--', 'ecuador' => 'Ecuador', 'colombia' => 'Colombia', 'peru' => 'Perú'), array('class' => 'form-control'));  ?>
                                    <select name="GestionSolicitudCredito[nacionalidad]" id="GestionSolicitudCredito_nacionalidad" class="form-control">
                                        <option value="EC">Ecuador</option>
                                        <option value="CO">Colombia</option>
                                        <option value="PE">Perú</option>
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
                                        <option value="CU">Cuba</option>
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
                                        <option value="VE">Venezuela</option>
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
                                        'Union Libre' => 'Union Libre'), array('class' => 'form-control'));
                                    ?>
                                    <?php echo $form->error($model, 'estado_civil'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <h1 class="tl_seccion_rf">Empleo/Actividad Actual del Solicitante</h1>
                        </div> 
                        <div class="row">
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'empresa_trabajo'); ?>
                                <?php echo $form->textField($model, 'empresa_trabajo', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'empresa_trabajo'); ?></div>
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'telefonos_trabajo'); ?>
                                <?php echo $form->textField($model, 'telefonos_trabajo', array('size' => 60, 'maxlength' => 9, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)', 'value' => $val['telefono_oficina'])); ?>
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
                                    '8' => 'Más de 7 años',
                                        ), array('class' => 'form-control'));
                                ?>
                                <?php echo $form->error($model, 'tiempo_trabajo'); ?>
                            </div>
                            <div class="col-md-2">
                                <label for="GestionSolicitudCredito_meses_trabajo" class="required">Meses de Trabajo <span class="required">*</span></label>
                                <select class="form-control" name="GestionSolicitudCredito[meses_trabajo]" id="GestionSolicitudCredito_meses_trabajo">
                                    <option value="" selected="selected">--Seleccione--</option>
                                    <option value="1">1 mes</option>
                                    <option value="2">2 meses</option>
                                    <option value="3">3 meses</option>
                                    <option value="4">4 meses</option>
                                    <option value="5">5 meses</option>
                                    <option value="6">6 meses</option>
                                    <option value="7">7 meses</option>
                                    <option value="8">8 meses</option>
                                    <option value="9">9 meses</option>
                                    <option value="10">10 meses</option>
                                    <option value="11">11 meses</option>
                                    <option value="12">12 meses</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <?php echo $form->labelEx($model, 'cargo'); ?>
                                <?php echo $form->textField($model, 'cargo', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'cargo'); ?>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'direccion_empresa'); ?>
                                <?php echo $form->textField($model, 'direccion_empresa', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
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
                                <?php echo $form->textField($model, 'actividad_empresa', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'actividad_empresa'); ?>
                            </div>
                        </div>
                        <div class="conyugue">
                            <div class="row">
                                <h1 class="tl_seccion_rf">Datos del Cónyugue</h1>
                            </div> 

                            <div class="row">
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'apellido_paterno_conyugue'); ?>
                                    <?php echo $form->textField($model, 'apellido_paterno_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
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
                                    <?php echo $form->textField($model, 'cedula_conyugue', array('size' => 60, 'maxlength' => 10, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                    <?php echo $form->error($model, 'cedula_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_cedula_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'fecha_nacimiento_conyugue'); ?>
                                    <?php echo $form->textField($model, 'fecha_nacimiento_conyugue', array('size' => 60, 'maxlength' => 85, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'fecha_nacimiento_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_fecha_nacimiento_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                                <div class="col-md-3">
                                    <?php echo $form->labelEx($model, 'nacionalidad_conyugue'); ?>
                                    <?php //echo $form->textField($model, 'nacionalidad_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control'));  ?>
                                    <select name="GestionSolicitudCredito[nacionalidad_conyugue]" id="GestionSolicitudCredito_nacionalidad_conyugue" class="form-control">
                                        <option value="EC">Ecuador</option>
                                        <option value="CO">Colombia</option>
                                        <option value="PE">Perú</option>
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
                                        <option value="CU">Cuba</option>
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
                                        <option value="VE">Venezuela</option>
                                        <option value="VN">Vietnam</option>
                                        <option value="WF">Wallis y Futuna</option>
                                        <option value="YE">Yemen</option>
                                        <option value="DJ">Yibuti</option>
                                        <option value="ZM">Zambia</option>
                                        <option value="ZW">Zimbabue</option>
                                    </select>
                                    <?php echo $form->error($model, 'nacionalidad_conyugue'); ?>
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
                                        '1' => '1 año',
                                        '2' => '2 años',
                                        '3' => '3 años',
                                        '4' => '4 años',
                                        '5' => '5 años',
                                        '6' => '6 años',
                                        '7' => '7 años',
                                            ), array('class' => 'form-control'));
                                    ?>
                                    <?php echo $form->error($model, 'tiempo_trabajo_conyugue'); ?>
                                    <label for="" generated="true" class="error" id="GestionSolicitudCredito_tiempo_trabajo_conyugue_error" style="display: none;">Este campo es requerido.</label>
                                </div>
                                <div class="col-md-2">
                                    <label for="GestionSolicitudCredito_meses_trabajo" class="required">Meses de Trabajo</label>
                                    <select class="form-control" name="GestionSolicitudCredito[meses_trabajo_conyugue]" id="GestionSolicitudCredito_meses_trabajo_conyugue">
                                        <option value="" selected="selected">--Seleccione--</option>
                                        <option value="1">1 mes</option>
                                        <option value="2">2 meses</option>
                                        <option value="3">3 meses</option>
                                        <option value="4">4 meses</option>
                                        <option value="5">5 meses</option>
                                        <option value="6">6 meses</option>
                                        <option value="7">7 meses</option>
                                        <option value="8">8 meses</option>
                                        <option value="9">9 meses</option>
                                        <option value="10">10 meses</option>
                                        <option value="11">11 meses</option>
                                        <option value="12">12 meses</option>
                                    </select>
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
                                <?php echo $form->labelEx($model, 'barrio'); ?>
                                <?php echo $form->textField($model, 'barrio', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'barrio'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'calle'); ?>
                                <?php echo $form->textField($model, 'calle', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control', 'value' => $val['direccion'])); ?>
                                <?php echo $form->error($model, 'calle'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $form->labelEx($model, 'numero'); ?>
                                <?php echo $form->textField($model, 'numero', array('size' => 60, 'maxlength' => 15, 'class' => 'form-control')); ?>
                                <?php echo $form->error($model, 'numero'); ?>
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
                        <h1 class="tl_seccion_rf">Ingresos</h1>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'sueldo_mensual'); ?>
                            <?php echo $form->textField($model, 'sueldo_mensual', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                            <?php echo $form->error($model, 'sueldo_mensual'); ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'sueldo_mensual_conyugue'); ?>
                            <?php echo $form->textField($model, 'sueldo_mensual_conyugue', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                            <?php echo $form->error($model, 'sueldo_mensual_conyugue'); ?>
                            <label for="" generated="true" class="error" id="GestionSolicitudCredito_sueldo_mensual_conyugue_error" style="display: none;">Este campo es requerido.</label>
                        </div>
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'otros_ingresos'); ?>
                            <?php echo $form->textField($model, 'otros_ingresos', array('size' => 20, 'maxlength' => 11, 'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                            <?php echo $form->error($model, 'otros_ingresos'); ?>
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
                        <h1 class="tl_seccion_rf">Activos y Propiedades</h1>
                    </div>

                    <div class="row activos">
                        <div class="col-md-3">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="Local" name="activos[]" id="">
                                        Local
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="Finca" name="activos[]" id="">
                                        Finca
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="Casa" name="activos[]" id="">
                                        Casa
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="Dpto" name="activos[]" id="">
                                        Dpto
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="Lote" name="activos[]" id="">
                                        Lote
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="">Dirección</label>
                            <input type="text" class="form-control" name="GestionSolicitudCredito[direccion_activo1]"/>
                        </div>
                        <div class="col-md-3">
                            <label for="">Sector</label>
                            <input type="text" class="form-control" name="GestionSolicitudCredito[direccion_sector1]"/>
                        </div><div class="col-md-3">
                            <label for="">Valor Comercial</label>
                            <input type="text" maxlength="14" class="form-control" id="GestionSolicitudCreditodireccion_valor_comercial1" name="GestionSolicitudCredito[direccion_valor_comercial1]" onkeypress="return validateNumbers(event)"/>
                        </div>
                    </div>
                    <hr />
                    <div class="row activos">
                        <div class="col-md-3">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="Casa2" name="activos[]" id="">
                                        Casa
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="Finca" name="activos[]" id="">
                                        Dpto
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="Terreno" name="activos[]" id="">
                                        Terreno
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="">Dirección</label>
                            <input type="text" class="form-control" name="GestionSolicitudCredito[direccion_activo2]"/>
                        </div>
                        <div class="col-md-3">
                            <label for="">Sector</label>
                            <input type="text" class="form-control" name="GestionSolicitudCredito[direccion_sector2]"/>
                        </div><div class="col-md-3">
                            <label for="">Valor Comercial</label>
                            <input type="text" maxlength="14" class="form-control" id="GestionSolicitudCreditodireccion_valor_comercial2" name="GestionSolicitudCredito[direccion_valor_comercial2]"  onkeypress="return validateNumbers(event)"/>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <?php
                        $criteria4 = new CDbCriteria(array(
                            'condition' => "id_informacion={$id_informacion}"
                        ));
                        $art = GestionConsulta::model()->findAll($criteria4);
                        foreach ($art as $c):
                            if ($c['preg1_sec5'] == 0): // SI TIENE VEHICULO
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
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Vehículo: Marca</label>
                            <input type="text" name="GestionSolicitudCredito[vehiculo_marca2]" class="form-control"/>
                        </div>
                        <div class="col-md-3">
                            <label for="">Modelo</label>
                            <input type="text" name="GestionSolicitudCredito[vehiculo_modelo2]" class="form-control"/>
                        </div>
                        <div class="col-md-3">
                            <label for="">Año</label>
                            <input type="text" name="GestionSolicitudCredito[vehiculo_year2]" class="form-control"/>
                        </div>
                        <div class="col-md-3">
                            <label for="">Valor Comercial</label>
                            <input type="text" id="GestionSolicitudCreditovehiculo_valor2" maxlength="14" name="GestionSolicitudCredito[vehiculo_valor2]" class="form-control" onkeypress="return validateNumbers(event)"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Tipo de inversión</label>
                            <input type="text" name="GestionSolicitudCredito[tipo_inversion]" class="form-control"/>
                        </div>
                        <div class="col-md-4">
                            <label for="">Institución</label>
                            <input type="text" name="GestionSolicitudCredito[institucion_inversion]" class="form-control"/>
                        </div>
                        <div class="col-md-4">
                            <label for="">Valor</label>
                            <input type="text" id="GestionSolicitudCreditovalor_inversion" maxlength="14" name="GestionSolicitudCredito[valor_inversion]" class="form-control"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Otros activos</label>
                            <input type="text" name="GestionSolicitudCredito[otros_activos1]" class="form-control"/>
                        </div>
                        <div class="col-md-4">
                            <label for="">Descripción</label>
                            <input type="text" name="GestionSolicitudCredito[descripcion1]" class="form-control"/>
                        </div>
                        <div class="col-md-4">
                            <label for="">Valor</label>
                            <input type="text" id="GestionSolicitudCreditovalor_otros_activos1" maxlength="14" name="GestionSolicitudCredito[valor_otros_activos1]" class="form-control" onkeypress="return validateNumbers(event)"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Otros activos</label>
                            <input type="text" name="GestionSolicitudCredito[otros_activos2]" class="form-control"/>
                        </div>
                        <div class="col-md-4">
                            <label for="">Descripción</label>
                            <input type="text" name="GestionSolicitudCredito[descripcion2]" class="form-control"/>
                        </div>
                        <div class="col-md-4">
                            <label for="">Valor</label>
                            <input type="text" id="GestionSolicitudCreditovalor_otros_activos2" maxlength="14" name="GestionSolicitudCredito[valor_otros_activos2]" class="form-control" onkeypress="return validateNumbers(event)"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Total</label>
                            <input type="text" name="GestionSolicitudCredito[total_activos]" class="form-control"/>
                        </div>
                    </div>
                    <div class="row buttons">
                        <div class="col-md-4">
                            <input class="btn btn-danger" id="finalizar" type="submit" name="yt0" value="Crear" onclick="sendSol();">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <a class="btn btn-warning" id="confirm" style="display: none;" onclick="confirm();">Confirmar</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <input class="btn btn-success" id="send-asesor" type="submit" style="display: none;" onclick="send();" value="Enviar a Asesor de Crédito">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/cotizacion/', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" class="btn btn-warning" id="generatepdf" style="display: none;" target="_blank">Solicitud de Crédito</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" class="btn btn-danger" id="continue" style="display: none;">Continuar</a>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>

                </div><!-- form -->
            </div>
        </div>
    </div>

</div>