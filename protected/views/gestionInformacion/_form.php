<?php
/* @var $this GestionInformacionController */
/* @var $model GestionInformacion */
/* @var $form CActiveForm */
//die('id: '.$id);
?>
<?php
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
$grupo_id = (int) Yii::app()->user->getState('grupo_id');
// SI CARGO ID ES 72, JEFE BDC
$id_responsable = Yii::app()->user->getId();
//echo 'responsable id: '.$id_responsable;
$city_id = 0;
if($cargo_id != 72 && $cargo_adicional !=  85){
    $dealer_id = $this->getDealerId($id_responsable);
    //echo '<br>dealer id: '.$dealer_id.'<br />';
    //die();
    $city_id = $this->getCityId($dealer_id);
    //die('city id: '.$city_id);
    $provincia_id = $this->getProvinciaId($city_id);
    //echo 'provincia id: ' . $provincia_id;
}
if($cargo_adicional == 85){
    $dealer_id = $this->getDealerId($id_responsable);
    //echo '<br>dealer id: '.$dealer_id.'<br />';
    //die();
    $city_id = $this->getCityId($dealer_id);
    //die('city id: '.$city_id);
    $provincia_id = $this->getProvinciaId($city_id);
}
$cedula = $this->getCedulaCotizacion($id);
$ced = $this->getCedula($id);
$nombres = '';
$apellidos = '';
$direccion = '';
$email = '';
$celular = '';
$telefono_oficina = '';
$telefono_casa = '';
if (isset($id)) {
    $cotizacion = GestionNuevaCotizacion::model()->findByPk($id);
    $datos_cliente = explode(',', $cotizacion->datos_cliente);
    $nombres = $datos_cliente[2];
    $direccion = $datos_cliente[10];
    if (strlen($datos_cliente[12]) == 10) {
        $celular = $datos_cliente[12];
    }
    if (strlen($datos_cliente[12]) == 9) {
        $telefono_casa = $datos_cliente[12];
    }
}
if ($ced != '') {
    $criteria = new CDbCriteria(array(
        'condition' => "cedula={$cedula}"
    ));
    $c = GestionInformacion::model()->count($criteria);
    if ($c > 0) {
        $c = GestionInformacion::model()->find($criteria);
        $nombres = $c->nombres;
        $apellidos = $c->apellidos;
        $direccion = $c->direccion;
        $email = $c->email;
        $celular = $c->celular;
        $telefono_oficina = $c->telefono_casa;
        $telefono_casa = $c->telefono_casa;
    }
}
$tipo = $_GET['tipo'];

//echo '<br>Ciudad id: '.$city_id.', Provincia id: '.$provincia_id;
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/jasny-bootstrap.css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jasny-bootstrap.js"></script>
<style>
    form li{margin-left: -14px;}
    form {padding: 10px 0px 10px 0px !important;}
    .tl_seccion_rf{margin-left: 0px !important;margin-top: 10px !important;width: 100% !important;}
</style>
<script>
    $(document).ready(function () {
        $('#GestionInformacion_presupuesto').maskMoney({prefix: '$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: true});
        $('#GestionInformacion_celular').val('09');
        $('#GestionInformacion_fecha_cita').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                //$(this).find('.xdsoft_date.xdsoft_weekend')
                //        .addClass('xdsoft_disabled');
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
            var value = $('#GestionVehiculo_version option:selected');
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
        $('#intoptions').change(function () {
            var value = $(this).attr('value');
            //alert(value);
            if (value == 1) {
                $('.cont-interesado').show();
                $('.cont-int-price').hide();
            } else {
                $('.cont-interesado').hide();
                $('.cont-int-price').show();
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
            var codigo;
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
                    switch (value) {
                        case '1':
                        case '3':
                        case '7':
                        case '12':
                        case '15':
                        case '24':
                            codigo = '07';
                            break;
                        case '2':
                        case '5':
                        case '6':
                        case '18':
                        case '23':
                            codigo = '03';
                            break;
                        case '4':
                        case '8':
                        case '11':
                        case '16':
                        case '17':
                        case '22':
                            codigo = '06';
                            break;
                        case '9':
                        case '13':
                        case '14':
                            codigo = '05';
                            break;
                        case '10':
                        case '20':
                            codigo = '04';
                            break;
                        case '19':
                        case '21':
                            codigo = '02';
                            break;

                    }
                    $('#GestionInformacion_ciudad_domicilio').html(data);
                    $('#GestionInformacion_telefono_oficina').val(codigo);
                    $('#GestionInformacion_telefono_casa').val(codigo);
                    $('#info3').hide();
                }
            });
            //alert(value)
            $('#GestionInformacion_ciudad_domicilio').html(data);

        });
        $('#GestionProspeccionPr_pregunta').change(function () {
            var value = $("#GestionProspeccionPr_pregunta option:selected").val();
            switch (value) {
                case '3':// compro otro vehiculo
                    $('.cont-vec').show();
                    $('.cont-nocont').hide();
                    $('.cont-int-price').hide();
                    //validateVehiculo();
                    break;
                case '4':// si estoy interesado
                    $('.cont-vec').hide();
                    $('.cont-interesado').show();
                    $('.cont_encuentro').show();
                    $('.cont-conc').show();
                    $('.cont-nocont').hide();
                    //validateInteresado();
                    break;
                case '5':// no contesta
                    $('.cont-vec').hide();
                    $('.cont-nocont').show();
                    $('.cont-int-price').hide();
                    $('.cont-interesado').hide();
                    $('.cont-interesado').show();
                    $('.cont_encuentro').hide();
                    $('.cont-lugar').hide();
                    $('.cont-conc').hide();
                    break;
                case '1':// no estoy interesado
                case '2':// falta de dinero
                case '6':// telefono equivocado
                    $('.cont-vec').hide();
                    $('.cont-nocont').hide();
                    $('.cont-int-price').hide();
                    $('.cont-interesado').hide();
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

        $('#Cotizador_modelo').change(function (){
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
        $('#GestionInformacion_medio').change(function(){
            var option = $('#GestionInformacion_medio option:selected').val();
            switch (option) {
                case 'recomendaron':// compro otro vehiculo
                    $('#cont-recomendaron').show();
                    $('#cont-medio-prensa').hide();
                    $('#cont-medio-television').hide();
                    $('#medio_television_error').hide();$('#GestionInformacion_medio_television').removeClass('error');
                    $('#medio_prensa_error').hide();$('#GestionInformacion_medio_prensa').removeClass('error');
                    break;
                case 'prensa_escrita':// si estoy interesado
                    $('#cont-recomendaron').hide();$('#medio_television_error').hide();$('#GestionInformacion_medio_television').removeClass('error');
                    $('#cont-medio-prensa').show();
                    $('#cont-medio-television').hide();
                    break;
                case 'television':// no contesta
                    $('#cont-recomendaron').hide();$('#medio_prensa_error').hide();$('#GestionInformacion_medio_prensa').removeClass('error');
                    $('#cont-medio-prensa').hide();
                    $('#cont-medio-television').show();
                    break;
                default:
                    $('#cont-recomendaron').hide();
                    $('#cont-medio-prensa').hide();
                    $('#cont-medio-television').hide();
                    $('#medio_television_error').hide();$('#GestionInformacion_medio_television').removeClass('error');
                    $('#medio_prensa_error').hide();$('#GestionInformacion_medio_prensa').removeClass('error');
                    break;
            }
            if(option == 'recomendacion'){
                $('#cont-recomendaron').show();
            }else{
                $('#cont-recomendaron').hide();
            }
        });

        $('#GestionInformacion_considero').change(function(){
            var option = $('#GestionInformacion_considero option:selected').val();
            switch (option) {
                case 'recomendacion':// compro otro vehiculo
                    $('#cont-recomendaron2').show();
                    $('#recomendaron_error').hide();
                    break;
                default:
                    $('#cont-recomendaron2').hide();
                    break;
            }
            
        });
        $('#GestionInformacion_recomendaron').change(function(){
            var recom = $('#GestionInformacion_recomendaron option:selected').val();
            if(recom != ''){
                $('#recomendaron_error').hide();$('#GestionInformacion_recomendaron').removeClass('error');
            }
        });
        $('#GestionInformacion_medio_prensa').change(function(){
            var recom = $('#GestionInformacion_medio_prensa option:selected').val();
            if(recom != ''){
                $('#medio_prensa_error').hide();$('#GestionInformacion_medio_prensa').removeClass('error');
            }
        });
        $('#GestionInformacion_medio_television').change(function(){
            var recom = $('#GestionInformacion_medio_television option:selected').val();
            if(recom != ''){
                $('#medio_television_error').hide();$('#GestionInformacion_medio_television').removeClass('error');
            }
        });
        $('#GestionInformacion_considero_recomendaron').change(function(){
            var recom = $('#GestionInformacion_considero_recomendaron option:selected').val();
            if(recom != ''){
                $('#recomendaron_error2').hide();$('#GestionInformacion_considero_recomendaron').removeClass('error');
            }
        });
    });
    function validateVehiculo() {
        $("#Cotizador_modelo").rules("add", "required");
        console.log('after modelo');
        $("#Cotizador_year").rules("add", "required");
    }
    function validateInteresado() {
        $("#intoptions").rules("add", "required");
    }
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
        //console.log('tipo: '+tipo);
        if (tipo == 'gestion' || tipo == 'trafico') {
            console.log('enter gestion');
            $('#gestion-informacion-form').validate({
                rules: {'GestionInformacion[nombres]': {required: true}, 'GestionInformacion[apellidos]': {required: true},
                    'GestionInformacion[cedula]': {required: true, number: true, minlength: 10}, 'GestionInformacion[direccion]': {required: true},
                    'GestionInformacion[provincia_domicilio]': {required: true}, 'GestionInformacion[ciudad_domicilio]': {required: true},
                    'GestionInformacion[email]': {required: true, email: true}, 'GestionInformacion[celular]': {required: true, minlength: 10, number: true},
                    'GestionInformacion[medio]': {required: true},'GestionInformacion[considero]': {required: true},
                    'GestionInformacion[telefono_casa]': {required: true, minlength: 9, number: true}},
                messages: {'GestionInformacion[nombres]': {required: 'Ingrese los nombres'}, 'GestionInformacion[apellidos]': {required: 'Ingrese los apellidos'},
                    'GestionInformacion[cedula]': {required: 'Ingrese el número'}, 'GestionInformacion[direccion]': {required: 'Ingrese la dirección'},
                    'GestionInformacion[provincia_domicilio]': {required: 'Seleccione la provincia'}, 'GestionInformacion[ciudad_domicilio]': {required: 'Seleccione la ciudad'},
                    'GestionInformacion[email]': {required: 'Ingrese el email', email: 'Ingrese un email válido'}, 'GestionInformacion[celular]': {required: 'Ingrese el celular', minlength: 'Ingrese 10 dígitos', number: 'Ingrese números'},
                    'GestionInformacion[medio]': {required: 'Seleccione el medio'},'GestionInformacion[considero]': {required: 'Seleccione una opción'},
                    'GestionInformacion[telefono_casa]': {required: 'Ingrese el teléfono', minlength: 'Ingrese 9 dígitos', number: 'Ingrese números'}
                },
                submitHandler: function (form) {
                    var num_cel = $('#GestionInformacion_celular').val();
                    var num_tel = $('#GestionInformacion_telefono_oficina').val();
                    var num_casa = $('#GestionInformacion_telefono_casa').val();
                    var opt = $('#GestionInformacion_medio').val();
                    switch(opt){
                        case 'recomendacion':
                            var rec = $('#GestionInformacion_recomendaron').val();$('#GestionInformacion_recomendaron').addClass('error');
                            if(rec == ''){
                                $('#recomendaron_error').show();
                                return false;
                            }
                            break;
                        case 'television':
                            var rec = $('#GestionInformacion_medio_television').val();$('#GestionInformacion_medio_television').addClass('error');
                            if(rec == ''){
                                $('#medio_television_error').show();
                                return false;
                            }
                            break;
                        case 'prensa_escrita':
                            var rec = $('#GestionInformacion_medio_prensa').val();$('#GestionInformacion_medio_prensa').addClass('error');
                            if(rec == ''){
                                $('#medio_prensa_error').show();
                                return false;
                            }
                            break;    
                        default:
                            break;
                    }
                    var con = $('#GestionInformacion_considero').val();
                    switch(con){
                        case 'recomendacion':
                           var rec = $('#GestionInformacion_considero_recomendaron').val();$('#GestionInformacion_considero_recomendaron').addClass('error');
                            if(rec == ''){
                                $('#recomendaron_error2').show();
                                return false;
                            } 
                            break;
                        default:
                            break;
                    }
                    
                    if (num_cel.indexOf("0") != 0) {
                        $('#GestionInformacion_celular').after('<label for="celular2" generated="true" class="error" id="celular2">Ingrese correctamente su celular</label>')
                        //$('#telefono').val('');
                        return false;
                    }
                    if (num_cel.indexOf("9") != 1) {

                        $('#GestionInformacion_celular').after('<label for="celular2" generated="true" class="error" id="celular2">Ingrese correctamente su celular</label>')
                        //$('#telefono').val('');
                        return false;
                    }
                    if (num_tel.indexOf("0") != 0) {
                        $('#GestionInformacion_telefono_oficina').after('<label for="telefono2" generated="true" class="error" style="display: block;" id="telefono2">Ingrese el código provincial</label>')
                        //$('#telefono').val('');
                        return false;
                    }
                    $('#bg_negro').show();
                    /*if (num_casa.indexOf("0") != 0) {
                        $('#GestionInformacion_telefono_casa').after('<label for="casa2" generated="true" class="error" style="display: block;" id="telefono2">Ingrese el código provincial</label>')
                        //$('#telefono').val('');
                        return false;
                    }*/
                    $('#GestionInformacion_provincia_conc').removeAttr('disabled');
                    $('#GestionInformacion_ciudad_conc').removeAttr('disabled');
                    $('#GestionInformacion_concesionario').removeAttr('disabled');
                    form.submit();
                }
            });
        } else if (tipo == 'prospeccion') {
            //console.log('ENTER PROSPECCION');
            
            var observaciones = $('#GestionProspeccionPr_pregunta').val();
            console.log('observaciones: ' + observaciones);
            var num_cel = $('#GestionInformacion_celular').val();
            var num_tel = $('#GestionInformacion_telefono_oficina').val();
            var num_casa = $('#GestionInformacion_telefono_casa').val();
            if (num_cel.indexOf("0") != 0) {
                $('#GestionInformacion_celular').after('<label for="celular2" generated="true" class="error" id="celular2">Ingrese correctamente su celular</label>')
                //$('#telefono').val('');
                return false;
            }
            if (num_cel.indexOf("9") != 1) {

                $('#GestionInformacion_celular').after('<label for="celular2" generated="true" class="error" id="celular2">Ingrese correctamente su celular</label>')
                //$('#telefono').val('');
                return false;
            }
            var opt = $('#GestionInformacion_medio').val();
            switch(opt){
                case 'recomendaron':
                    var rec = $('#GestionInformacion_recomendaron').val();$('#GestionInformacion_recomendaron').addClass('error');
                    if(rec == ''){
                        $('#recomendaron_error').show();
                        return false;
                    }
                    break;
                case 'television':
                    var rec = $('#GestionInformacion_medio_television').val();$('#GestionInformacion_medio_television').addClass('error');
                    if(rec == ''){
                        $('#medio_television_error').show();
                        return false;
                    }
                    break;
                case 'prensa_escrita':
                    var rec = $('#GestionInformacion_medio_prensa').val();$('#GestionInformacion_medio_prensa').addClass('error');
                    if(rec == ''){
                        $('#medio_prensa_error').show();
                        return false;
                    }
                    break;    
                default:
                    break;
            }
            /*if (num_tel.indexOf("0") != 0) {
                $('#GestionInformacion_telefono_oficina').after('<label for="telefono2" generated="true" class="error" style="display: block;" id="telefono2">Ingrese el código provincial</label>')
                //$('#telefono').val('');
                return false;
            }*/
            /*if (num_casa.indexOf("0") != 0) {
                $('#GestionInformacion_telefono_casa').after('<label for="casa2" generated="true" class="error" style="display: block;" id="telefono2">Ingrese el código provincial</label>')
                //$('#telefono').val('');
                return false;
            }*/
            $('#GestionInformacion_provincia_conc').removeAttr('disabled');
            $('#GestionInformacion_ciudad_conc').removeAttr('disabled');
            $('#GestionInformacion_concesionario').removeAttr('disabled');
            switch (observaciones) {
                case '1':// no estoy interesado
                case '2':// falta de dinero
                case '6':// telefono equivocado  
                    //console.log('enter falta de dinero');
                    $('.cont-vec').hide();
                    $('.cont-ag').hide();
                    $('.cont-nocont').hide();
                    $('#gestion-informacion-form').validate({
                        rules: {'GestionProspeccionPr[pregunta]': {required: true},'GestionInformacion[nombres]': {required: true}, 'GestionInformacion[apellidos]': {required: true},
                            'GestionInformacion[cedula]': {required: true, number: true, minlength: 10}, 'GestionInformacion[email]': {required: true, email: true}, 'GestionInformacion[celular]': {required: true, minlength: 10, number: true},'GestionInformacion[medio]': {required: true},'GestionInformacion[considero]': {required: true},},
                        messages: {'GestionInformacion[nombres]': {required: 'Ingrese los nombres'}, 'GestionInformacion[apellidos]': {required: 'Ingrese los apellidos'},
                            'GestionInformacion[cedula]': {required: 'Ingrese la cédula', number: 'Ingrese sólo números', minlength: 'Ingrese 10 dígitos'}, 'GestionInformacion[email]': {required: 'Ingrese el email', email: 'Ingrese un email válido'},
                            'GestionInformacion[celular]': {required: 'Ingrese el celular', number: 'Ingrese solo números', number:'Ingrese números'},'GestionInformacion[medio]': {required: 'Seleccione el medio'},'GestionInformacion[considero]': {required: 'Seleccione una opción'}},
                        submitHandler: function (form) {
                            $('#bg_negro').show();
                            form.submit();
                        }
                    });
                    break;
                case '3':// compro otro vehiculo
                    $('.cont-vec').show();
                    $('.cont-ag').hide();
                    $('.cont-nocont').hide();
                    $('#gestion-informacion-form').validate({
                        rules: {'GestionProspeccionPr[pregunta]': {required: true},'GestionInformacion[nombres]': {required: true}, 'GestionInformacion[apellidos]': {required: true},
                            'GestionInformacion[cedula]': {required: true, number: true, minlength: 10}, 'GestionInformacion[email]': {required: true, email: true}, 'GestionProspeccionRp[marca]': {required: true}, 'GestionInformacion[celular]': {required: true, minlength: 10, number: true}, 'GestionProspeccionRp[modelo]': {required: true}, 'GestionProspeccionRp[year]': {required: true},'GestionInformacion[medio]': {required: true},'GestionInformacion[considero]': {required: true},},
                        messages: {'GestionInformacion[nombres]': {required: 'Ingrese los nombres'}, 'GestionInformacion[apellidos]': {required: 'Ingrese los apellidos'},
                            'GestionInformacion[cedula]': {required: 'Ingrese la cédula', number: 'Ingrese sólo números', minlength: 'Ingrese 10 dígitos'}, 'GestionInformacion[email]': {required: 'Ingrese el email', email: 'Ingrese un email válido'},
                            'GestionInformacion[celular]': {required: 'Ingrese el celular', number: 'Ingrese solo números'}, 'GestionProspeccionRp[marca]': {required: 'Ingrese la marca'}, 'GestionProspeccionRp[modelo]': {required: 'Ingrese el modelo'}, 'GestionProspeccionRp[year]': {required: 'Ingrese el año'},'GestionInformacion[medio]': {required: 'Seleccione el medio'},'GestionInformacion[considero]': {required: 'Seleccione una opción'}},
                        submitHandler: function (form) {
                            $('#bg_negro').show();
                            form.submit();
                        }
                    });
                    break;
                case '4':// si estoy interesado
                    $('.cont-vec').hide();
                    $('.cont-ag').show();
                    $('.cont-nocont').hide();
                    $('#gestion-informacion-form').validate({
                        rules: {'GestionProspeccionPr[pregunta]': {required: true},'GestionInformacion[nombres]': {required: true}, 'GestionInformacion[apellidos]': {required: true},
                            'GestionInformacion[cedula]': {required: true, number: true, minlength: 10}, 'GestionInformacion[email]': {required: true, email: true}, 'GestionInformacion[celular]': {required: true, minlength: 10, number: true}, 'GestionDiaria[agendamiento]': {required: true}, 'GestionProspeccionRp[lugar]': {required: true}, 'GestionProspeccionRp[agregar]': {required: true},'GestionInformacion[medio]': {required: true},'GestionInformacion[considero]': {required: true},},
                        messages: {'GestionInformacion[nombres]': {required: 'Ingrese los nombres'}, 'GestionInformacion[apellidos]': {required: 'Ingrese los apellidos'},
                            'GestionInformacion[cedula]': {required: 'Ingrese la cédula', number: 'Ingrese sólo números', minlength: 'Ingrese 10 dígitos'}, 'GestionInformacion[email]': {required: 'Ingrese el email', email: 'Ingrese un email válido'},
                            'GestionInformacion[celular]': {required: 'Ingrese el celular', number: 'Ingrese solo números', number:'Ingrese números'}, 'GestionDiaria[agendamiento]': {required: 'Ingrese agendamiento'}, 'GestionProspeccionRp[lugar]': {required: 'Seleccione lugar de encuentro'}, 'GestionProspeccionRp[agregar]': {required: 'Seleccione agregar'},'GestionInformacion[medio]': {required: 'Seleccione el medio'},'GestionInformacion[considero]': {required: 'Seleccione una opción'}},
                        submitHandler: function (form) {
                            var proximoSeguimiento = $('#agendamiento').val();
                            if (proximoSeguimiento != '') {
                                console.log('proximo: ' + proximoSeguimiento);
                                if ($('#GestionInformacion_check').val() != 2) {
                                    var lugarencuentro = $('#GestionProspeccion_lugar').val();
                                    switch (lugarencuentro) {
                                        case '0'://concesionario
                                            var lugarconc = $('#Casos_concesionario').val();
                                            var conc = 'si';
                                            break;
                                        case '1'://lugar de trabajo
                                            var lugarconc = $('#GestionProspeccion_ingreso_lugar').val();
                                            var conc = 'no';
                                            break;
                                        case '2'://domicilio
                                            var lugarconc = $('#GestionProspeccion_ingreso_lugar').val();
                                            var conc = 'no';
                                            break;
                                    }
                                    var cliente = $('#GestionInformacion_nombres').val() + ' ' + $('#GestionInformacion_apellidos').val();
                                    var params = proximoSeguimiento.split("/");
                                    var fechaDate = params[0] + params[1] + params[2];
                                    var secDate = params[2].split(" ");
                                    var fechaStart = params[0] + params[1] + secDate[0];
                                    var start = secDate[1].split(":");
                                    var startTime = start[0] + start[1];
                                    var params2 = fechaDate.split(":");
                                    var endTime = parseInt(startTime) + 100;
                                    //console.log('start time:'+fechaStart+startTime);
                                    //console.log('fecha end:'+fechaStart+endTime);
                                    var href = '/intranet/usuario/index.php/gestionDiaria/ical?startTime=' + fechaStart + startTime + '&endTime=' + fechaStart + endTime + '&subject=Cita con Cliente ' + cliente + ' en Concesionario&desc=Cita con el cliente Mariana de Jesus&location=' + lugarconc + '&to_name=' + cliente + '&conc=si';
                                    $('#event-download').attr('href', href);
                                    $('.calendar-content').show();
                                    $("#event-download").click(function () {
                                        $('#GestionInformacion_calendar').val(1);
                                        $('.calendar-content').hide();
                                        $('#GestionInformacion_check').val(2)
                                    });
                                    if ($('#GestionInformacion_calendar').val() == 1) {
                                        $('#bg_negro').show();
                                        form.submit();
                                    } else {
                                        alert('Debes descargar agendamiento y luego dar click en Continuar');
                                    }
                                } else {
                                    $('#bg_negro').show();
                                    form.submit();
                                }
                            } else {
                                form.submit();
                            }
                        }
                    });
                    break;
                case '5':// no contesta
                    $('.cont-vec').hide();
                    $('.cont-ag').hide();
                    $('.cont-nocont').show();
                    $('#gestion-informacion-form').validate({
                        rules: {'GestionProspeccionPr[pregunta]': {required: true},'GestionInformacion[nombres]': {required: true}, 'GestionInformacion[apellidos]': {required: true},
                            'GestionInformacion[cedula]': {required: true, number: true, minlength: 10}, 'GestionInformacion[email]': {required: true, email: true}, 'GestionDiaria[agendamiento2]': {required: true},'GestionInformacion[medio]': {required: true},'GestionInformacion[considero]': {required: true},},
                        messages: {'GestionInformacion[nombres]': {required: 'Ingrese los nombres'}, 'GestionInformacion[apellidos]': {required: 'Ingrese los apellidos'},
                            'GestionInformacion[celular]': {required: 'Ingrese el celular', number: 'Ingrese solo números', number:'Ingrese números'}, 'GestionInformacion[email]': {required: 'Ingrese el email', email: 'Ingrese un email válido'}, 'GestionDiaria[agendamiento2]': {required: 'Selecione Re Agendar'},'GestionInformacion[medio]': {required: 'Seleccione el medio'},'GestionInformacion[considero]': {required: 'Seleccione una opción'}},
                        submitHandler: function (form) {
                            var proximoSeguimiento = $('#agendamiento').val();
                            if (proximoSeguimiento != '') {
                                if ($('#GestionInformacion_check').val() != 2) {
                                    var cliente = $('#GestionInformacion_nombres').val() + ' ' + $('#GestionInformacion_apellidos').val();
                                    var params = proximoSeguimiento.split("/");
                                    var fechaDate = params[0] + params[1] + params[2];
                                    var secDate = params[2].split(" ");
                                    var fechaStart = params[0] + params[1] + secDate[0];
                                    var start = secDate[1].split(":");
                                    var startTime = start[0] + start[1];
                                    var params2 = fechaDate.split(":");
                                    var endTime = parseInt(startTime) + 100;
                                    //console.log('start time:'+fechaStart+startTime);
                                    //console.log('fecha end:'+fechaStart+endTime);
                                    var href = '/intranet/usuario/index.php/gestionDiaria/ical?startTime=' + fechaStart + startTime + '&endTime=' + fechaStart + endTime + '&subject=Cita con Cliente en Concesionario&desc=Cita con el cliente Mariana de Jesus&location=Por definir&to_name=' + cliente + '&conc=no';
                                    $('#event-download').attr('href', href);
                                    $('.calendar-content').show();
                                    $("#event-download").click(function () {
                                        $('#GestionInformacion_calendar').val(1);
                                        $('.calendar-content').hide();
                                        $('#GestionInformacion_check').val(2)
                                    });
                                    if ($('#GestionInformacion_calendar').val() == 1) {$('#bg_negro').show();
                                        form.submit();
                                    } else {
                                        alert('Debes descargar agendamiento y luego dar click en Continuar');
                                    }
                                } else {$('#bg_negro').show();
                                    form.submit();
                                }
                            }
                        }
                    });
                    break;
                case '15':
                    $('#gestion-informacion-form').validate({
                        rules: {'GestionProspeccionPr[pregunta]': {required: true},'GestionInformacion[nombres]': {required: true}, 'GestionInformacion[apellidos]': {required: true},
                            'GestionInformacion[cedula]': {required: true, number: true, minlength: 10}, 'GestionInformacion[email]': {required: true, email: true}, 'GestionInformacion[celular]': {required: true, minlength: 10, number: true}, 'GestionDiaria[agendamiento]': {required: true}, 'GestionProspeccionRp[lugar]': {required: true}, 'GestionProspeccionRp[agregar]': {required: true},'GestionInformacion[medio]': {required: true},'GestionInformacion[considero]': {required: true},},
                        messages: {'GestionInformacion[nombres]': {required: 'Ingrese los nombres'}, 'GestionInformacion[apellidos]': {required: 'Ingrese los apellidos'},
                            'GestionInformacion[cedula]': {required: 'Ingrese la cédula', number: 'Ingrese sólo números', minlength: 'Ingrese 10 dígitos'}, 'GestionInformacion[email]': {required: 'Ingrese el email', email: 'Ingrese un email válido'},
                            'GestionInformacion[celular]': {required: 'Ingrese el celular', number: 'Ingrese solo números', number:'Ingrese números'}, 'GestionDiaria[agendamiento]': {required: 'Ingrese agendamiento'}, 'GestionProspeccionRp[lugar]': {required: 'Seleccione lugar de encuentro'}, 'GestionProspeccionRp[agregar]': {required: 'Seleccione agregar'},'GestionInformacion[medio]': {required: 'Seleccione el medio'},'GestionInformacion[considero]': {required: 'Seleccione una opción'}},
                        submitHandler: function (form) {$('#bg_negro').show();
                            form.submit();
                        }
                    });
                    break;
                default:
                    break;
            }
        }
    }
    
</script>
<?php $this->widget('application.components.Notificaciones'); ?>
<div role="tabpanel">

    <!-- Nav tabs -->  
    <ul class="nav nav-tabs" role="tablist">
        <?php if ($tipo == 'prospeccion'): ?>
            <li role="presentation" class="active"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion_on.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita_on.png" alt="" /></span> Cita</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
        <?php else: ?>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita.png" alt="" /></span> Cita</a></li>
            <li role="presentation" class="active"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
        <?php endif; ?>
        <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
        <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
        <li role="presentation"><a aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
    </ul>


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
                        'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                    ),
                ));
                ?>
                <div class="highlight"><!--=========DATOS DEL CLIENTE Y CONCESIONARIO===============-->
                    <div class="row">
                        <h1 class="tl_seccion_rf">Datos del Cliente</h1>
                    </div>  
                    <div class="row"><p class="note">Campos con <span class="required">*</span> son requeridos.</p></div>

                    <?php echo $form->errorSummary($model); ?>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php //echo $form->labelEx($model, 'nombres', array('required' => 'required'));  ?>
                            <label class="" for="">Nombres <?php
                                if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <?php echo $form->textField($model, 'nombres', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control', 'value' => $nombres)); ?>
                            <?php echo $form->error($model, 'nombres'); ?>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php //echo $form->labelEx($model, 'apellidos');   ?>
                            <label class="" for="">Primer Apellido <?php
                                if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <?php echo $form->textField($model, 'apellidos', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control', 'value' => $apellidos)); ?>
                            <?php echo $form->error($model, 'apellidos'); ?>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php //echo $form->labelEx($model, 'apellidos');    ?>
                            <label class="" for="">Segundo Apellido</label>
                            <?php echo $form->textField($model, 'last_name', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control', 'value' => $apellidos)); ?>
                            <?php echo $form->error($model, 'last_name'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        $identificacion = $this->getIdentificacion($id);
//echo '--------- IDENT: '.$identificacion;
                        ?>
                        <?php if ($identificacion == 'ci'): ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php //echo $form->labelEx($model, 'cedula');   ?>
                                <label class="" for="">Cédula <?php
                                    if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                        echo '<span class="required">*</span>';
                                    }
                                    ?></label>
                                <?php //echo $form->textField($model, 'cedula', array('size' => 20, 'maxlength' => 10, 'class' => 'form-control'));   ?>
                                <input size="20" maxlength="10" class="form-control" name="GestionInformacion[cedula]" id="GestionInformacion_cedula" type="text" value="<?php
                                if (isset($id)) {
                                    echo $ced;
                                }
                                ?>">
                                       <?php echo $form->error($model, 'cedula'); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($identificacion == 'ruc'): ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php //echo $form->labelEx($model, 'cedula');   ?>
                                <label class="" for="">RUC <?php
                                    if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                        echo '<span class="required">*</span>';
                                    }
                                    ?></label>
                                <?php //echo $form->textField($model, 'cedula', array('size' => 20, 'maxlength' => 10, 'class' => 'form-control'));   ?>
                                <input size="20" maxlength="13" class="form-control" name="GestionInformacion[ruc]" id="GestionInformacion_ruc" type="text" value="<?php
                                if (isset($id)) {
                                    echo $this->getIdentificacionRuc($id);
                                }
                                ?>">
                                       <?php echo $form->error($model, 'ruc'); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($identificacion == 'pasaporte'): ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php //echo $form->labelEx($model, 'cedula');   ?>
                                <label class="" for="">Pasaporte <?php
                                    if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                        echo '<span class="required">*</span>';
                                    }
                                    ?></label>
                                <?php //echo $form->textField($model, 'cedula', array('size' => 20, 'maxlength' => 10, 'class' => 'form-control'));   ?>
                                <input size="20" maxlength="50" class="form-control" name="GestionInformacion[pasaporte]" id="GestionInformacion_pasaporte" type="text" value="<?php
                                if (isset($id)) {
                                    echo $this->getIdentificacionPasaporte($id);
                                }
                                ?>">
                                       <?php echo $form->error($model, 'pasaporte'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php //echo $form->labelEx($model, 'direccion');    ?>
                            <label class="" for="">Dirección <?php
                                if ($_GET['tipo'] == 'gestion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <?php echo $form->textField($model, 'direccion', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control', 'value' => $direccion)); ?>
                            <?php echo $form->error($model, 'direccion'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php //echo $form->labelEx($model, 'email');    ?>
                            <label class="" for="">Provincia Domicilio <?php
                                if ($_GET['tipo'] == 'gestion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <?php
                            $criteria = new CDbCriteria(array(
                                'order' => 'nombre'
                            ));
                            $provincias = CHtml::listData(Provincias::model()->findAll($criteria), "id_provincia", "nombre");
                            ?>
                            <?php
                            //echo $form->dropDownList($model, 'provincia_domicilio', $provincias,array('empty' => '---Seleccione una provincia---','class' => 'form-control'));
                            ?>
                            <?php
                            $this->widget('ext.select2.ESelect2', array(
                                'model' => $model,
                                'attribute' => 'provincia_domicilio',
                                'data' => array(
                                    '' => '--Seleccione provincia--',
                                    "1" => 'Azuay',
                                    "2" => 'Bolívar',
                                    "3" => 'Cañar',
                                    "4" => 'Carchi',
                                    "5" => 'Chimborazo',
                                    "6" => 'Cotopaxi',
                                    "7" => 'El Oro',
                                    "8" => 'Esmeraldas',
                                    "9" => 'Galápagos',
                                    "10" => 'Guayas',
                                    "11" => 'Imbabura',
                                    "12" => 'Loja',
                                    "13" => 'Los Ríos',
                                    "14" => 'Manabí',
                                    "15" => 'Morona Santiago',
                                    "16" => 'Napo',
                                    "17" => 'Orellana',
                                    "18" => 'Pastaza',
                                    "19" => 'Pichincha',
                                    "20" => 'Santa Elena',
                                    "22" => 'Sucumbíos',
                                    "21" => 'Tsachilas',
                                    "23" => 'Tungurahua',
                                    "24" => 'Zamora Chinchipe'
                                )
                            ));
                            ?>
                            <?php echo $form->error($model, 'provincia_domicilio'); ?>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php //echo $form->labelEx($model, 'celular');   ?>
                            <label class="" for="">Ciudad Domicilio <?php
                                if ($_GET['tipo'] == 'gestion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <div id="info3" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                            <?php //echo $form->dropDownList($model, 'ciudad_domicilio', array('' => '---Seleccione una ciudad---'),array('class' => 'form-control'));   ?>
                            <?php
                            $this->widget('ext.select2.ESelect2', array(
                                'name' => 'GestionInformacion[ciudad_domicilio]',
                                'id' => 'GestionInformacion_ciudad_domicilio',
                                'data' => array(
                                    '' => '--Seleccione una ciudad--'
                                ),
                            ));
                            ?>
                            <?php echo $form->error($model, 'ciudad_domicilio'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php //echo $form->labelEx($model, 'email');    ?>
                            <label class="" for="">Email <?php
                                if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <?php echo $form->textField($model, 'email', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control', 'value' => $email)); ?>
                            <?php echo $form->error($model, 'email'); ?>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php //echo $form->labelEx($model, 'celular');   ?>
                            <label class="" for="">Celular <?php
                                if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <?php echo $form->textField($model, 'celular', array('size' => 15, 'maxlength' => 10, 'class' => 'form-control', 'value' => $celular, 'onkeypress' => 'return validateNumbers(event)')); ?>
                            <?php echo $form->error($model, 'celular'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <input type="hidden" name="GestionInformacion[id_cotizacion]" id="GestionInformacion_id_cotizacion" value="<?php echo $id; ?>">
                            <?php //echo $form->labelEx($model, 'telefono_casa');   ?>
                            <label class="" for="">Teléfono Domicilio <?php
                                if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                    echo '<span class="required">*</span>';
                                }
                                ?></label>
                            <?php echo $form->textField($model, 'telefono_casa', array('size' => 15, 'maxlength' => 9, 'class' => 'form-control', 'value' => $telefono_casa, 'onkeypress' => 'return validateNumbers(event)')); ?>
                            <?php echo $form->error($model, 'telefono_casa'); ?>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php //echo $form->labelEx($model, 'telefono_oficina');     ?>
                            <label class="" for="">Teléfono Oficina <?php
                                /* if ($_GET['tipo'] == 'gestion') {
                                  echo '<span class="required">*</span>';
                                  } */
                                ?></label>
                            <?php echo $form->textField($model, 'telefono_oficina', array('size' => 15, 'maxlength' => 9, 'class' => 'form-control', 'value' => $telefono_oficina, 'onkeypress' => 'return validateNumbers(event)')); ?>
                            <?php echo $form->error($model, 'telefono_oficina'); ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="">¿Por qué medio tuvo información de nuestra marca que motivó su visita?</label>
                            <?php echo $form->dropDownList($model, 'medio', array('' => '--Seleccione el medio--',
                                'cine' => 'Cine',
                                'television' => 'Televisión',
                                'redessociales' => 'Redes sociales',
                                'pagina_web' => 'Página Web Kia',
                                'internet' => 'Internet',
                                'radio' => 'Radio',
                                'prensa_escrita' => 'Prensa escrita',
                                'recomendacion' => 'Le recomendaron',
                                //'le_gusta_marca_kia' => 'Le gusta la marca Kia'
                                ), 
                                    array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'telefono_casa'); ?>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12" id="cont-recomendaron" style="display:none;">
                            <label for="">Opciones Recomendaron</label>
                            <?php echo $form->dropDownList($model, 'recomendaron', array(
                                '' => '--Selecione opción--',
                                'calidad_de_producto' => 'Por Calidad de Producto',
                                'atencion' => 'Por Atención',
                                'diseno' => 'Por Diseño',
                                'facilidades_credito' => 'Por Facilidades de Crédito',
                                'garantia' => 'Por Garantía',
                            ), array('class' => 'form-control')); ?>
                            <label for="GestionInformacion_recomendaron" generated="true" class="error" id="recomendaron_error" style="display: none;">Seleccione una opción</label>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12" id="cont-medio-prensa" style="display:none;">
                            <label for="">Medio Prensa</label>
                            <?php 
                            //echo $provincia_id;
                            $medio_prensa = CHtml::listData(GestionMedios::model()->findAll(array('condition' => "id_provincia IN ({$provincia_id},100) AND tipo_medio = 1")),'medio', 'medio');
                            echo $form->dropDownList($model, 'medio_prensa', $medio_prensa, array('class' => 'form-control', 'empty' => 'Seleccione un medio')); 
                            ?>
                            <label for="GestionInformacion_medio_prensa" generated="true" class="error" id="medio_prensa_error" style="display: none;">Seleccione un medio de prensa</label>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12" id="cont-medio-television" style="display:none;">
                            <label for="">Medio Televisión</label>
                            <?php
                            $medio_television = CHtml::listData(GestionMedios::model()->findAll(array('condition' => "id_provincia IN ({$provincia_id},100) AND tipo_medio = 2")),'medio', 'medio');
                            echo $form->dropDownList($model, 'medio_television', $medio_television, array('class' => 'form-control', 'empty' => 'Seleccione un medio')); 
                            ?>
                            <label for="GestionInformacion_medio_television" generated="true" class="error" id="medio_television_error" style="display: none;">Seleccione un medio de televisión</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="">¿Por qué consideró la marca KIA?</label>
                            <?php echo $form->dropDownList($model, 'considero', array('' => '--Seleccione una opción--',
                                'garantia' => 'Garantía',
                                'diseno' => 'Diseño',
                                'precio' => 'Precio',
                                'recomendacion' => 'Le recomendaron',
                                'servicio' => 'Servicio',
                                'recompra' => 'Recompra'
                                ), 
                                    array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'telefono_casa'); ?>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12" id="cont-recomendaron2" style="display:none;">
                            <label for="">Opciones Recomendaron</label>
                            <?php echo $form->dropDownList($model, 'considero_recomendaron', array(
                                '' => '--Selecione opción--',
                                'calidad_de_producto' => 'Por Calidad de Producto',
                                'atencion' => 'Por Atención',
                                'diseno' => 'Por Diseño',
                                'facilidades_credito' => 'Por Facilidades de Crédito',
                                'garantia' => 'Por Garantía',
                            ), array('class' => 'form-control')); ?>
                            <label for="GestionInformacion_recomendaron" generated="true" class="error" id="recomendaron_error2" style="display: none;">Seleccione una opción</label>
                        </div>
                    </div>
                    <?php
                    if (isset($_GET['tipo']) && (isset($_GET['tipo_fuente']) == 'usado') && ($_GET['tipo'] == 'prospeccion') || (($_GET['tipo'] == 'trafico') && ($_GET['tipo_fuente']) == 'usado')) {
                        ?>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="" for="">Presupuesto </label>
                                <input size="15" maxlength="11" class="form-control" value="" onkeypress="return validateNumbers(event)" name="GestionInformacion[presupuesto]" id="GestionInformacion_presupuesto" type="text">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($_GET['tipo']) && $_GET['tipo'] == 'gestion'){ ?>
                        <div class="row">
                            <h1 class="tl_seccion_rf">Datos del Concesionario</h1>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php echo $form->labelEx($model, 'provincia_conc'); ?>
                                <?php
                                $criteria = new CDbCriteria(array(
                                    'condition' => "estado='s'",
                                    'order' => 'nombre'
                                ));
                                $provincias = CHtml::listData(Provincias::model()->findAll($criteria), "id_provincia", "nombre");
                                ?>
                                <?php 
                                if($city_id == 0){
                                    $disabled = false;
                                }else{
                                    $disabled = true;
                                }
                                echo $form->dropDownList($model, 'provincia_conc', $provincias, array('class' => 'form-control', 'empty' => 'Selecciona una provincia', 'options' => array($provincia_id => array('selected' => true)), 'disabled' => $disabled)); 
                                ?>
                                <?php //echo $form->textField($model,'provincia_conc',array('class' => 'form-control','value' => $provincia_id));   ?>
                                <?php echo $form->error($model, 'provincia_conc'); ?>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php echo $form->labelEx($model, 'ciudad_conc'); ?>
                                <div id="info5" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                <?php
                                $criteria2 = new CDbCriteria(array('condition' => "id={$city_id}", 'order' => 'name'));
                                $ciudades = CHtml::listData(Dealercities::model()->findAll($criteria2), "id", "name");
                                ?>
                                <?php echo $form->dropDownList($model, 'ciudad_conc', $ciudades, array('class' => 'form-control', 'options' => array($city_id => array('selected' => true)), 'disabled' => $disabled)); ?>
                                <?php echo $form->error($model, 'ciudad_conc'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php echo $form->labelEx($model, 'concesionario'); ?>
                                <div id="info6" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                <?php
                                $criteria3 = new CDbCriteria(array('condition' => "cityid={$city_id}", 'order' => 'name'));
                                $dealers = CHtml::listData(Dealers::model()->findAll($criteria3), "id", "name");
                                ?>
                                <?php //echo $form->dropDownList($model, 'concesionario', array('' => 'Concesionario'), array('class' => 'form-control'));  ?>
                                <?php echo $form->dropDownList($model, 'concesionario', $dealers, array('class' => 'form-control', 'options' => array($dealer_id => array('selected' => true)), 'disabled' => $disabled)); ?>
                                <?php echo $form->error($model, 'concesionario'); ?>
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
                                <input name="GestionInformacion[iden]" id="GestionInformacion_iden" type="hidden" value="<?php
                                if (isset($_GET['iden'])) {
                                    echo $_GET['iden'];
                                }
                                ?>">
                                <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET['tipo']; ?>">
                                <?php
                                if ($_GET['tipo'] == 'prospeccion'):
                                    echo '<input type="hidden" name="GestionInformacion[status]" id="GestionInformacion_status" value="prospeccion">';
                                else:
                                    echo '<input type="hidden" name="GestionInformacion[status]" id="GestionInformacion_status" value="primera_visita">';
                                endif;
                                ?>
                                <?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Grabar', array('class' => 'btn btn-danger', 'id' => 'finalizar', 'onclick' => 'sendInfo();')); ?>

                            </div>
                        </div>
                    <?php } ?>
                </div><!-- ==========END DATOS CLIENTE Y CONCESIONARIO=============-->
                <br>
                <?php
                if (isset($_GET['tipo']) && ($_GET['tipo'] == 'prospeccion') &&
                        (isset($_GET['tipo_fuente']) != 'usado')) { 
                    ?>
                    <?php if($cargo_id != 72){ // SI NO ES JEFE DE BDC ?>
                    <div style="display: none;">
                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'provincia_conc'); ?>
                                <?php
                                $criteria = new CDbCriteria(array(
                                    'condition' => "estado='s'",
                                    'order' => 'nombre'
                                ));
//                                if($cargo_id == 72){
//                                    $list_provincias = $this->getProvinciasGrupo($grupo_id);
//                                    $listItems = implode(',', $list_provincias);
//                                    $criteria = new CDbCriteria(array(
//                                        'condition' => "estado='s' AND id_provincia IN({$listItems})",
//                                        'order' => 'nombre'
//                                    ));
//                                }
                                $provincias = CHtml::listData(Provincias::model()->findAll($criteria), "id_provincia", "nombre");
                                ?>
                                <?php echo $form->dropDownList($model, 'provincia_conc', $provincias, array('class' => 'form-control', 'empty' => 'Selecciona una provincia', 'options' => array($provincia_id => array('selected' => true)), 'disabled' => true)); ?>
                                <?php echo $form->textField($model,'provincia_conc',array('class' => 'form-control','value' => $provincia_id));   ?>
                                <?php echo $form->error($model, 'provincia_conc'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'ciudad_conc'); ?>
                                <div id="info5" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                <?php
                                $criteria2 = new CDbCriteria(array('condition' => "id={$city_id}", 'order' => 'name'));
                                $ciudades = CHtml::listData(Dealercities::model()->findAll($criteria2), "id", "name");
                                ?>
                                <?php echo $form->dropDownList($model, 'ciudad_conc', $ciudades, array('class' => 'form-control', 'options' => array($city_id => array('selected' => true)), 'disabled' => true)); ?>
                                <?php echo $form->error($model, 'ciudad_conc'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'concesionario'); ?>
                                <div id="info6" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                <?php
                                $criteria3 = new CDbCriteria(array('condition' => "cityid={$city_id}", 'order' => 'name'));
                                $dealers = CHtml::listData(Dealers::model()->findAll($criteria3), "id", "name");
                                ?>
                                <?php echo $form->dropDownList($model, 'concesionario', array('' => 'Concesionario'), array('class' => 'form-control'));  ?>
                                <?php echo $form->dropDownList($model, 'concesionario', $dealers, array('class' => 'form-control', 'options' => array($dealer_id => array('selected' => true)), 'disabled' => true)); ?>
                                <?php echo $form->error($model, 'concesionario'); ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
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
                                        <option value="">--Seleccione--</option>
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
                                                    //$marcas = CHtml::listData(Marcas::model()->findAll($criteria), "marca", "marca");
                                                    $marcas = array(
                                                        '' => '--Seleccione--',
                                                        "ALFA ROMEO" => 'ALFA ROMEO',
                                                        "AUDI" => 'AUDI',
                                                        "BAW" => 'BAW',
                                                        "BMW" => 'BMW',
                                                        "BYD" => 'BYD',
                                                        "CHANA" => 'CHANA',
                                                        "CHANGHE" => 'CHANGHE',
                                                        "CHERY" => 'CHERY',
                                                        "CHEVROLET" => 'CHEVROLET',
                                                        "CHRYSLER" => 'CHRYSLER',
                                                        "CITROEN" => 'CITROEN',
                                                        "DAIHATSU" => 'DAIHATSU',
                                                        "DODGE" => 'DODGE',
                                                        "D.F.S.K. (DONGFENG)" => 'D.F.S.K. (DONGFENG)',
                                                        "FIAT" => 'FIAT',
                                                        "FORD" => 'FORD',
                                                        "GEELY" => 'GEELY',
                                                        "GREAT WALL" => 'GREAT WALL',
                                                        "HAFEI" => 'HAFEI',
                                                        "HONDA" => 'HONDA',
                                                        "HYUNDAI" => 'HYUNDAI',
                                                        "JAC MOTORS" => 'JAC MOTORS',
                                                        "JEEP" => 'JEEP',
                                                        "JINBEI" => 'JINBEI',
                                                        "JMC" => 'JMC',
                                                        "KIA" => 'KIA',
                                                        "LADA" => 'LADA',
                                                        "LAND ROVER" => 'LAND ROVER',
                                                        "LEXUS" => 'LEXUS',
                                                        "LIFAN" => 'LIFAN',
                                                        "MAHINDRA" => 'MAHINDRA',
                                                        "MAZDA" => 'MAZDA',
                                                        "MERCEDES BENZ" => 'MERCEDES BENZ',
                                                        "MITSUBISHI" => 'MITSUBISHI',
                                                        "NISSAN" => 'NISSAN',
                                                        "PEUGEOT" => 'PEUGEOT',
                                                        "PORSCHE" => 'PORSCHE',
                                                        "QMC" => 'QMC',
                                                        "RENAULT" => 'RENAULT',
                                                        "SAIC WULING" => 'SAIC WULING',
                                                        "SKODA" => 'SKODA',
                                                        "SSANGYONG" => 'SSANGYONG',
                                                        "TOYOTA" => 'TOYOTA',
                                                        "VOLKSWAGEN" => 'VOLKSWAGEN',
                                                        "VOLVO" => 'VOLVO',
                                                        "ZNA" => 'ZNA',
                                                        "ZOTYE" => 'ZOTYE',
                                                        "ZX AUTO" => 'ZX AUTO',
                                                        "DAEWOO" => 'DAEWOO'
                                                    );

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
                                    <div class="col-md-4 cont_encuentro">
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
                                                "" => '--Seleccione provincia--',
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

                            </div>
                            <div class="cont-int-price" style="display: none;">

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
                                <input name="GestionDiaria[id_informacion]" id="GestionDiaria_id_informacion" type="hidden" value="<?php //echo $id_informacion;            ?>">
                                <input name="GestionDiaria[id_vehiculo]" id="GestionDiaria_id_vehiculo" type="hidden" value="<?php //echo $id_vehiculo;             ?>">
                                <input name="GestionDiaria[primera_visita]" id="GestionDiaria_seguimiento" type="hidden" value="1">
                                <input name="GestionDiaria[seguimiento]" id="GestionDiaria_seguimiento" type="hidden" value="0">

                            </div>
                        </div>

                    </div><!-- End Seguimiento -->
                    <div class="row buttons">
                        <div class="col-md-7">
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
                            <?php
                            if ($_GET['tipo'] == 'prospeccion') {
                                echo '<a href="' . Yii::app()->request->baseUrl . '/images/LISTA-DE-PRECIOS-KIA-15-08-2017.pdf" class="btn btn-warning" type="submit" name="yt0" target="_blank">Lista de Precios</a>';
                            }
                            ?>

                            <a href="" class="btn btn-primary calendar-content" id="event-download" style="display: none;">Descargar Evento</a>

                            <input class="btn btn-primary" style="display: none;" onclick=";" type="submit" name="yt0"  id="continuar" value="Abandonar">
                        </div>
                        <div class="col-md-2">
                            <div id="calendar-content2" style="display: none;">
                                <a href="" class="btn btn-primary" id="event-download2">Descargar Evento</a>
                            </div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                <?php } elseif (isset($_GET['tipo']) && ($_GET['tipo'] == 'gestion') && (isset($_GET['fuente']) != 'web')) { ?>
                    <div class="row buttons">
                        <div class="col-md-2">
                            <input type="hidden" name="GestionInformacion[fuente]" id="GestionInformacion_fuente" value="<?php echo $fuente; ?>">
                            <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET['tipo']; ?>">
                            <input type="hidden" name="tipo_fuente" id="tipo_fuente" value="<?php //echo $_GET['tipo_fuente'];      ?>">
                            <input name="GestionInformacion[tipo]" id="GestionInformacion_tipo" type="hidden" value="<?php
                            if (isset($_GET['tipo'])) {
                                echo $_GET['tipo'];
                            }
                            ?>">
                            <input name="GestionInformacion[iden]" id="GestionInformacion_iden" type="hidden" value="<?php
                                if (isset($_GET['iden'])) {
                                    echo $_GET['iden'];
                                }
                                ?>">
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Grabar', array('class' => 'btn btn-danger', 'id' => 'finalizar', 'onclick' => 'sendInfo();')); ?>
                            <input class="btn btn-primary" style="display: none;" onclick=";" type="submit" name="yt0"  id="continuar" value="Abandonar">
                        </div>

                    </div>

                <?php } elseif (isset($_GET['tipo']) && (isset($_GET['tipo_fuente']) == 'usado')) { ?>

                    <div class="row buttons">
                        <div class="col-md-2">
                            <input type="hidden" name="GestionInformacion[fuente]" id="GestionInformacion_fuente" value="<?php echo $fuente; ?>">
                            <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET['tipo']; ?>">
                            <input name="GestionInformacion[paso]" id="GestionInformacion_paso" type="hidden" value="1-2">
                            <input name="GestionInformacion[tipo_form_web]" id="GestionInformacion_tipo_form_web" type="hidden" value="usado">
                            <input type="hidden" name="GestionProspeccionPr[pregunta]" id="GestionProspeccionPr_pregunta" value="15"/>
                            <input type="hidden" name="tipo_fuente" id="tipo_fuente" value="<?php //echo $_GET['tipo_fuente'];     ?>">
                            <input name="GestionInformacion[tipo]" id="GestionInformacion_tipo" type="hidden" value="<?php
                            if (isset($_GET['tipo'])) {
                                echo $_GET['tipo'];
                            }
                            ?>">
                            <input name="GestionInformacion[iden]" id="GestionInformacion_iden" type="hidden" value="<?php
                            if (isset($_GET['iden'])) {
                                echo $_GET['iden'];
                            }
                            ?>">
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Grabar', array('class' => 'btn btn-danger', 'id' => 'finalizar', 'onclick' => 'sendInfo();')); ?>
                            <input class="btn btn-primary" style="display: none;" onclick=";" type="submit" name="yt0"  id="continuar" value="Abandonar">
                        </div>
                    </div>
                <?php } ?>

                <?php $this->endWidget(); ?>
            </div><!-- form -->
        </div>
        <div role="tabpanel" class="tab-pane" id="profile"></div>
        <div role="tabpanel" class="tab-pane" id="settings"></div>
        <div role="tabpanel" class="tab-pane" id="messages"></div>
    </div>
</div>

<!-- Nav tabs -->  
<?php
$this->renderPartial('//layouts/rgd/footer', array('tipo' => $tipo));
?>
