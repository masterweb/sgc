<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.maskMoney.js" type="text/javascript"></script>
<?php
/* @var $this GestionInformacionController */
/* @var $model GestionInformacion */
/* @var $form CActiveForm */
//die('id: '.$id);
?>
<?php
$tipo_ex = $this->getTipoExo($id);
//echo $tipo_ex;
$id_responsable = Yii::app()->user->getId();
//echo 'responsable id: '.$id_responsable;
$dealer_id = $this->getDealerId($id_responsable);
//echo '<br>dealer id: '.$dealer_id;
$city_id = $this->getCityId($dealer_id);
$provincia_id = $this->getProvinciaId($city_id);
$cedula = $this->getCedulaCotizacion($id);
$ced = $this->getCedula($id);
$nombres = '';
$apellidos = '';
$direccion = '';
$email = '';
$celular = '';
$telefono_oficina = '';
$telefono_casa = '';
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
        $('#GestionInformacion_porcentaje_discapacidad').change(function () {
            var value = $(this).attr('value');
            switch (value) {
                case '6':
                    data = 'Porcentaje de beneficio 60%';
                    break;
                case '7':
                    data = 'Porcentaje de beneficio 70%';
                    break;
                case '8':
                    data = 'Porcentaje de beneficio 80%';
                    break;
                case '10':
                    data = 'Porcentaje de beneficio 100%';
                    break;
            }
            $('.info-dis').html(data);
        });
        $('#GestionInformacion_presupuesto').maskMoney({prefix: '$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: true});
        $('#GestionInformacion_celular').val('09');
        $('#GestionInformacion_fecha_cita').datetimepicker({
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
            var value = $(this).attr('value');
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
                case '3':
                    $('.cont-vec').show();
                    $('.cont-ag').hide();
                    $('.cont-nocont').hide();
                    break;
                case '4':
                    $('.cont-vec').hide();
                    $('.cont-ag').show();
                    $('.cont-nocont').hide();
                    break;
                case '5':
                    $('.cont-vec').hide();
                    $('.cont-ag').hide();
                    $('.cont-nocont').show();
                    break;
                case '1':
                case '2':
                case '6':
                    $('.cont-vec').hide();
                    $('.cont-ag').hide();
                    $('.cont-nocont').hide();
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

        $('#GestionInformacion_marca_usado').change(function () {
            var marca = $(this).attr('value');
            $.ajax({
                type: 'post',
                url: '<?php echo Yii::app()->createUrl("site/getmodelos"); ?>',
                beforeSend: function (xhr) {
                    $('#info4').show();  // #info must be defined somehwere
                },
                //dataType: "json",
                data: {marca: marca},
                success: function (data) {
                    //alert(data.options)
                    $('#Cotizador_modelo_usado').html(data);
                    $('#info4').hide();
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

        $('#Cotizador_modelo').change(function () {
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

    });
    function sendSeg() {
        var observaciones = $('#GestionProspeccionPr_pregunta').val();
        switch (observaciones) {
            case '3':

                break;
            default:
                break;
        }
    }

    //Hay que trabajar esta función para que sea el validador global en un archivo externo al cual se conecten todos los formularios
    function vallocal(mainform, lista_val){
        $(mainform).validate({
            submitHandler: function (mainform) {
                $('#GestionInformacion_provincia_conc').removeAttr('disabled');
                $('#GestionInformacion_ciudad_conc').removeAttr('disabled');
                $('#GestionInformacion_concesionario').removeAttr('disabled');
                var num_cel = $('#GestionInformacion_celular').val();
                var num_tel = $('#GestionInformacion_telefono_oficina').val();
                var num_casa = $('#GestionInformacion_telefono_casa').val();
                
                 $('#GestionInformacion_provincia_conc').removeAttr('disabled');
                    $('#GestionInformacion_ciudad_conc').removeAttr('disabled');
                    $('#GestionInformacion_concesionario').removeAttr('disabled');
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
                    if (num_tel.indexOf("0") != 0) {
                        $('#GestionInformacion_telefono_oficina').after('<label for="telefono2" generated="true" class="error" style="display: block;" id="telefono2">Ingrese el código provincial</label>')
                        //$('#telefono').val('');
                        return false;
                    }
                    if (num_casa.indexOf("0") != 0) {
                        $('#GestionInformacion_telefono_casa').after('<label for="casa2" generated="true" class="error" style="display: block;" id="telefono2">Ingrese el código provincial</label>')
                        //$('#telefono').val('');
                        return false;
                    }
                    else {
                        //mainform.submit();
                    }
            }
        });

        $.each( lista_val, function( i, val ) {
            var extraval = '';
            //alert(val[3]);
            if(typeof val[3] !== 'undefined'){ extraval = ', '+val[3]}
            $(val[2]+"[name*='"+val[0]+"']").rules("add", {required: true, messages: {required: val[1] + extraval}});
        });
    }
    function sendInfo() {
        //console.log('enter send info');
        var tipo = $('#GestionInformacion_tipo').val();
        //console.log('tipo: '+tipo);
        //return false;
        var tipo_fuente = $('#tipo_fuente').val();
        var lista_val = [            
            ['GestionVehiculo[modelo]', 'Campo obligatorio', 'select'],
            ['GestionVehiculo[version]', 'Campo obligatorio', 'select'],
            ['GestionInformacion[nombres]', 'Campo obligatorio', 'input'],
            ['GestionInformacion[apellidos]', 'Campo obligatorio', 'input'],
            ['GestionInformacion[direccion]', 'Campo obligatorio', 'input'],
            ['GestionInformacion[provincia_domicilio]', 'Campo obligatorio', 'select'],
            ['GestionInformacion[ciudad_domicilio]', 'Campo obligatorio', 'select'],
            ['GestionInformacion[email]', 'Campo obligatorio', 'input'],
            ['GestionInformacion[celular]', 'Campo obligatorio', 'input', "minlength: 'Ingrese 10 dígitos'"],
            ['GestionInformacion[telefono_casa]', 'Campo obligatorio', 'input']            
        ];
        if(tipo_fuente = 'exonerado' && tipo == 'gestion'){ 
            lista_val.push(['GestionInformacion[porcentaje_discapacidad]', 'Campo obligatorio', 'select']);
            //vallocal('#gestion-informacion-form', lista_val);
        }
        if (tipo == 'gestion' || tipo == 'trafico') {
            console.log('enter gestion o trafico');
            $('#gestion-informacion-form').validate({
                rules: {'GestionInformacion[nombres]': {required: true}, 'GestionInformacion[apellidos]': {required: true},
                    'GestionInformacion[cedula]': {required: true,number:true,minlength:10}, 'GestionInformacion[direccion]': {required: true},
                    'GestionInformacion[provincia_domicilio]': {required: true}, 'GestionInformacion[ciudad_domicilio]': {required: true},
                    'GestionInformacion[email]': {required: true, email: true}, 'GestionInformacion[celular]': {required: true, minlength: 10,number:true},
                    //'GestionInformacion[telefono_oficina]': {required: true, minlength: 9},
                    'GestionInformacion[marca_usado]': {required: true}, 'GestionInformacion[modelo_usado]': {required: true},
                    'GestionInformacion[telefono_casa]': {required: true, minlength: 9,number:true}},
                messages: {'GestionInformacion[nombres]': {required: 'Ingrese los nombres'}, 'GestionInformacion[apellidos]': {required: 'Ingrese los apellidos'},
                    'GestionInformacion[cedula]': {required: 'Ingrese la cédula',number:'Ingrese sólo números',minlength:'Ingrese 10 dígitos'}, 'GestionInformacion[direccion]': {required: 'Ingrese la dirección'},
                    'GestionInformacion[provincia_domicilio]': {required: 'Seleccione la provincia'}, 'GestionInformacion[ciudad_domicilio]': {required: 'Seleccione la ciudad'},
                    'GestionInformacion[email]': {required: 'Ingrese el email', email: 'Ingrese un email válido'}, 'GestionInformacion[celular]': {required: 'Ingrese el celular', minlength: 'Ingrese 10 dígitos',number:'Ingrese números'},
                    //'GestionInformacion[telefono_oficina]': {required: 'Ingrese el teléfono', minlength: 'Ingrese 9 dígitos'},
                    'GestionInformacion[telefono_casa]': {required: 'Ingrese el teléfono', minlength: 'Ingrese 9 dígitos', number:'Ingrese números'}
                },
                submitHandler: function (form) {
                    console.log('enter submit handler');
                    $('#GestionInformacion_provincia_conc').removeAttr('disabled');
                    $('#GestionInformacion_ciudad_conc').removeAttr('disabled');
                    $('#GestionInformacion_concesionario').removeAttr('disabled');
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
                    if (num_tel.indexOf("0") != 0) {
                        $('#GestionInformacion_telefono_oficina').after('<label for="telefono2" generated="true" class="error" style="display: block;" id="telefono2">Ingrese el código provincial</label>')
                        //$('#telefono').val('');
                        return false;
                    }
                    if (num_casa.indexOf("0") != 0) {
                        $('#GestionInformacion_telefono_casa').after('<label for="casa2" generated="true" class="error" style="display: block;" id="telefono2">Ingrese el código provincial</label>')
                        //$('#telefono').val('');
                        return false;
                    }
                    else {
                        $('#myModal').modal('show');
                        $('#closemodal').click(function(){
                            form.submit();
                        });
                    }

                }
            });
        }
        
    }
</script>
<?php $this->widget('application.components.Notificaciones'); ?>
<div class="container">
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
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">SGC</h4>
                            </div>
                            <div class="modal-body">
                                <h4>Tu proceso se está enviando a exonerados... Por favor espere...</h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" id="closemodal">Continuar</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
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

                        <?php //echo $form->errorSummary($model); ?>

                        <div class="row">
                            <div class="col-md-3">
                                <?php //echo $form->labelEx($model, 'nombres', array('required' => 'required')); ?>
                                <label class="" for="">Nombres <?php
                                    if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                        echo '<span class="required">*</span>';
                                    }
                                    ?></label>
                                <?php echo $form->textField($model, 'nombres', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control', 'value' => $nombres)); ?>
                                <?php echo $form->error($model, 'nombres'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php //echo $form->labelEx($model, 'apellidos');  ?>
                                <label class="" for="">Primer Apellido <?php
                                    if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                        echo '<span class="required">*</span>';
                                    }
                                    ?></label>
                                <?php echo $form->textField($model, 'apellidos', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control', 'value' => $apellidos)); ?>
                                <?php echo $form->error($model, 'apellidos'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php //echo $form->labelEx($model, 'apellidos');  ?>
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
                                <div class="col-md-3">
                                    <?php //echo $form->labelEx($model, 'cedula');   ?>
                                    <label class="" for="">Cédula <?php
                                        if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                            echo '<span class="required">*</span>';
                                        }
                                        ?></label>
                                    <?php //echo $form->textField($model, 'cedula', array('size' => 20, 'maxlength' => 10, 'class' => 'form-control'));   ?>
                                    <input size="20" maxlength="10" class="form-control" name="GestionInformacion[cedula]" id="GestionInformacion_cedula" type="text" value="<?php echo (isset($id)) ? $ced : ''; ?>" onkeypress="return validateNumbers(event);">
                                    <?php echo $form->error($model, 'cedula'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($identificacion == 'ruc'): ?>
                                <div class="col-md-3">
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
                                <div class="col-md-3">
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
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <?php //echo $form->labelEx($model, 'email');   ?>
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
                            <div class="col-md-3">
                                <?php //echo $form->labelEx($model, 'celular');  ?>
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
                            <div class="col-md-3">
                                <?php //echo $form->labelEx($model, 'email');   ?>
                                <label class="" for="">Email <?php
                                    if ($_GET['tipo'] == 'gestion' || $_GET['tipo'] == 'prospeccion') {
                                        echo '<span class="required">*</span>';
                                    }
                                    ?></label>
                                <?php echo $form->textField($model, 'email', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control', 'value' => $email)); ?>
                                <?php echo $form->error($model, 'email'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php //echo $form->labelEx($model, 'celular');  ?>
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
                            <div class="col-md-3">
                                <input type="hidden" name="GestionInformacion[id_cotizacion]" id="GestionInformacion_id_cotizacion" value="<?php echo $id; ?>">
                                <?php //echo $form->labelEx($model, 'telefono_casa');  ?>
                                <label class="" for="">Teléfono Domicilio <?php
                                    if ($_GET['tipo'] == 'gestion') {
                                        echo '<span class="required">*</span>';
                                    }
                                    ?></label>
                                <?php echo $form->textField($model, 'telefono_casa', array('size' => 15, 'maxlength' => 9, 'class' => 'form-control', 'value' => $telefono_casa, 'onkeypress' => 'return validateNumbers(event)')); ?>
                                <?php echo $form->error($model, 'telefono_casa'); ?>
                            </div>
                            <div class="col-md-3">
                                <?php //echo $form->labelEx($model, 'telefono_oficina');    ?>
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
                            <div class="col-md-3">
                                <label for="">Porcentaje de Discapacidad <?php
                                    if ($_GET['tipo'] == 'gestion') {
                                        echo '<span class="required">*</span>';
                                    }
                                    ?></label>
                                <?php
                                echo $form->dropDownList($model, 'porcentaje_discapacidad', array(
                                    '' => '--Seleccione un porcentaje--',
                                    '6' => 'Del 40% al 49%',
                                    '7' => 'Del 50% al 74%',
                                    '8' => 'Del 75% al 84%',
                                    '10' => 'Del 85% al 100%'
                                        ), array('class' => 'form-control', 'name' => 'GestionInformacion[porcentaje_discapacidad]', 'id' => 'GestionInformacion_porcentaje_discapacidad'));
                                ?>
                                <?php echo $form->error($model, 'porcentaje_discapacidad'); ?>
                            </div>
                            <div class="col-md-3">
                                <div class="info-dis"></div>
                            </div>
                        </div>
                        <div class="row cont-img">
                            <div class="col-md-8">
                                <label for="">Foto Carnet CONADIS</label>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                            <?php echo $form->FileField($model, 'img', array('class' => 'form-control')); ?>
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                    </div>
                                </div>
                                <?php echo $form->error($model, 'img'); ?>
                            </div>
                            <div class="col-md-4">
                                <div class="info"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>                                        
                                        <?php echo $form->checkBox($model, 'senae', array('value' => 1, 'uncheckValue' => 0)); ?> Declaración aduanera de importación "SENAE"
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div><!-- ==========END DATOS CLIENTE Y CONCESIONARIO=============-->
                    <br />
                    <div class="highlight"><!-- Vehiculo recomendado -->
                        <div class="row">
                            <h1 class="tl_seccion_rf">Vehículo de Interés</h1>
                        </div>
                        <div class="form vehicle-cont">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php $vehiculo = new GestionVehiculo; ?>
                                        <?php echo $form->labelEx($vehiculo, 'modelo'); ?>
                                        <?php
                                        echo $form->dropDownList($vehiculo, 'modelo', array(
                                            "" => "--Escoja un Modelo--",
                                            "84" => "Picanto R",
                                            "85" => "Rio R",
                                            //"91" => "Rio Taxi",
                                            "24" => "Cerato Forte",
                                            "90" => "Cerato R",
                                            //"89" => "Óptima Híbrido",
                                            "88" => "Quoris",
                                            "20" => "Carens R",
                                            "11" => "Grand Carnival",
                                            "21" => "Sportage Active",
                                            "83" => "Sportage R",
                                            //"10" => "Sorento",
                                            //"25" => "K 2700 Cabina Simple",
                                            //"87" => "K 2700 Cabina Doble",
                                            "86" => "K 3000"), array('class' => 'form-control'));
                                        ?>
                                        <?php echo $form->error($vehiculo, 'modelo'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="info2" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                        <?php echo $form->labelEx($vehiculo, 'versión'); ?>
                                        <?php echo $form->dropDownList($vehiculo, 'version', array('' => 'Escoja una versión'), array('class' => 'form-control')); ?>
                                        <?php echo $form->error($vehiculo, 'version'); ?>
                                    </div>
                                </div>
                                <div class="row" style="display: none;">
                                    <div class="col-md-6">
                                        <div id="info3" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                        <?php echo $form->labelEx($vehiculo, 'precio'); ?>
                                        <?php echo $form->textField($vehiculo, 'precio', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($vehiculo, 'precio'); ?>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                <label for="">Color de Preferencia</label>
                            </div>
                            <div class="row">
                                <div class="col-md-8 well well-sm">
                                    
                                    <div class="col-md-6">
                                        <ul class="list-accesorios">                                          
                                            <li><u><input type="checkbox" value="negro" name="colores[]" id="color-3"/><span class="color" style="background: rgb(0, 0, 0);"></span>Negro</u></li>
                                            <li><u><input type="checkbox" value="plomo" name="colores[]" id="color-5"/><span class="color" style="background: rgb(50, 52, 55);"></span>Plomo</u></li>
                                            <li><u><input type="checkbox" value="plata" name="colores[]" id="color-2"/><span class="color" style="background: rgb(205, 210, 216);"></span>Plata</u></li>
                                            <li><u><input type="checkbox" value="blanco" name="colores[]" id="color-1"/><span class="color" style="background: rgb(245, 245, 249);"></span>Blanco</u></li>                                               
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-accesorios">
                                            <li><u><input type="checkbox" value="rojo" name="colores[]" id="color-4"/><span class="color" style="background: rgb(213, 42, 44);"></span>Rojo</u></li>
                                            <li><u><input type="checkbox" value="vino" name="colores[]" id="color-2"/><span class="color" style="background: rgb(109, 20, 27);"></span>Vino</u></li>
                                            <li><u><input type="checkbox" value="azul" name="colores[]" id="color-3"/><span class="color" style="background: rgb(19, 17, 137);"></span>Azul</u></li>
                                        </ul>
                                    </div>                                   
                                </div>
                            </div>

                            </div>
                            <div class="row buttons">

                            </div>
                        </div>
                    </div><!-- End Vehiculo recomendado -->
                    <div style="display: none;">
                        <div class="row">
                            <h1 class="tl_seccion_rf">Datos del Concesionario</h1>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $form->labelEx($model, 'provincia_conc'); ?>
                                <?php
                                $criteria = new CDbCriteria(array(
                                    'condition' => "estado='s'",
                                    'order' => 'nombre'
                                ));
                                $provincias = CHtml::listData(Provincias::model()->findAll($criteria), "id_provincia", "nombre");
                                ?>
                                <?php echo $form->dropDownList($model, 'provincia_conc', $provincias, array('class' => 'form-control', 'empty' => 'Selecciona una provincia', 'options' => array($provincia_id => array('selected' => true)), 'disabled' => true)); ?>
                                <?php //echo $form->textField($model,'provincia_conc',array('class' => 'form-control','value' => $provincia_id));  ?>
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
                                <?php //echo $form->dropDownList($model, 'concesionario', array('' => 'Concesionario'), array('class' => 'form-control')); ?>
                                <?php echo $form->dropDownList($model, 'concesionario', $dealers, array('class' => 'form-control', 'options' => array($dealer_id => array('selected' => true)), 'disabled' => true)); ?>
                                <?php echo $form->error($model, 'concesionario'); ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <?php if (isset($_GET['tipo']) && (isset($_GET['tipo_fuente']) == 'usado')) { ?>

                        <div class="row buttons">
                            <div class="col-md-2">
                                <input type="hidden" name="GestionInformacion[fuente]" id="GestionInformacion_fuente" value="<?php echo $fuente; ?>">
                                <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET['tipo']; ?>">
                                <input name="GestionInformacion[paso]" id="GestionInformacion_paso" type="hidden" value="1-2">
                                <input name="GestionInformacion[tipo_form_web]" id="GestionInformacion_tipo_form_web" type="hidden" value="exonerados">
                                <input name="GestionInformacion[tipo_ex]" id="GestionInformacion_tipo_exb" type="hidden" value="<?php echo $tipo_ex; ?>">
                                <input type="hidden" name="GestionProspeccionPr[pregunta]" id="GestionProspeccionPr_pregunta" value="15"/>
                                <input type="hidden" name="tipo_fuente" id="tipo_fuente" value="<?php echo $_GET['tipo_fuente']; ?>">
                                <input name="GestionInformacion[tipo]" id="GestionInformacion_tipo" type="hidden" value="<?php echo isset($_GET['tipo']) ? $_GET['tipo'] : '' ?>">
                                <input name="GestionInformacion[iden]" id="GestionInformacion_iden" type="hidden" value="<?php echo isset($_GET['iden']) ? $_GET['iden'] : '' ?>">
                                <?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Grabar', array('class' => 'btn btn-danger', 'id' => 'finalizar', 'onclick' => 'sendInfo();')); ?>
                                <input class="btn btn-primary" style="display: none;" onclick=";" type="submit" name="yt0"  id="continuar" value="Continuar">
                            </div>
                        </div>
                    <?php } ?>

                    <?php $this->endWidget(); ?>
                </div><!-- form -->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">SGC</h4>
                            </div>
                            <div class="modal-body">
                                <h4>Tu proceso se está enviando a exonerados... Por favor espere...</h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" id="closemodal">Cerrar</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div>
            <div role="tabpanel" class="tab-pane" id="profile"></div>
            <div role="tabpanel" class="tab-pane" id="settings"></div>
            <div role="tabpanel" class="tab-pane" id="messages"></div>
        </div>
    </div>
</div>    
