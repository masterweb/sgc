<?php
/* @var $this GestionConsultaController */
/* @var $model GestionConsulta */
/* @var $form CActiveForm */
$tipo = $_GET['tipo'];
$id = $_GET['id_informacion'];
$fuente = $_GET["fuente"];
?>
<?php
$id_asesor = Yii::app()->user->getId();
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
if ($cargo_id != 46) {
    $concesionarioid = $this->getConcesionarioDealerId($id_asesor);
    $nombreConcesionario = $this->getNameConcesionarioById($concesionarioid);
    $nombre_cliente = $this->getNombresInfo($id_informacion) . ' ' . $this->getApellidosInfo($id_informacion);
    $direccion_concesionario = $this->getConcesionarioDireccionById($concesionarioid);
}
?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.maskMoney.js" type="text/javascript"></script>
<style type="text/css">
    .fileinput .btn{  padding: 6px 14px;}
    @media (min-width: 992px){
        .col-md-3 { width: 22% !important;}
    }
    .fileinput-new > img{opacity: 0.3;}
    #info4, #info5, #info6 {top: 20px;}
    .radio, .checkbox{font-size: 15 px !important;}
    form{padding: 0 !important;}
</style>
<script type="text/javascript">
    $(document).ready(function () {
        numerar();
        $("input[name='colores[]']").click(function () {

        });
        $('#GestionConsulta_preg5').maskMoney({prefix: '$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: true});
        $('#GestionInformacion_fecha_cita').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                $(this).find('.xdsoft_date.xdsoft_weekend')
                        .addClass('xdsoft_disabled');
            },
            weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
        });
        $('#GestionAgendamiento_agendamiento').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                $(this).find('.xdsoft_date.xdsoft_weekend')
                        .addClass('xdsoft_disabled');
            },
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
            weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
        });
        $('#agendamiento2').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                $(this).find('.xdsoft_date.xdsoft_weekend')
                        .addClass('xdsoft_disabled');
            },
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
                    //$('.cont-accesorios').show();
                }
            });

        });
        $('#GestionConsulta_preg8').change(function () {
            var value = $("#GestionConsulta_preg8 option:selected").val();
            if (value == 'Otra') {
                $('.espec').show();
            } else {
                $('.espec').hide();
            }
        });
        $("input[name='necesidad[]']").click(function () {
            var value = $(this).val();
            if (value == 'Otra') {
                if ($(this).prop('checked')) {
                    $('.espec').show();
                } else {
                    $('.espec').hide();
                }
            }

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
        $('#GestionConsulta_preg7').change(function () {
            var value = $("#GestionConsulta_preg7 option:selected").val();
            console.log(value);
            switch (value) {
                case 'Hot A (hasta 7 dias)':
                    //console.log('enter 7')
                    $("#GestionAgendamiento_categorizacion option").removeAttr('selected');
                    $("#GestionAgendamiento_categorizacion option[value=1]").attr("selected", true);
                    break;
                case 'Hot B (hasta 15 dias)':
                    $("#GestionAgendamiento_categorizacion option").removeAttr('selected');
                    $("#GestionAgendamiento_categorizacion option[value=2]").attr("selected", true);
                    break;
                case 'Hot C(hasta 30 dias)':
                    $("#GestionAgendamiento_categorizacion option").removeAttr('selected');
                    $("#GestionAgendamiento_categorizacion option[value=3]").attr("selected", true);
                    break;
                case 'Warm (hasta 3 meses)':
                    $("#GestionAgendamiento_categorizacion option").removeAttr('selected');
                    $("#GestionAgendamiento_categorizacion option[value=4]").attr("selected", true);
                    break;
                case 'Cold (hasta 6 meses)':
                    $("#GestionAgendamiento_categorizacion option").removeAttr('selected');
                    $("#GestionAgendamiento_categorizacion option[value=5]").attr("selected", true);
                    break;
                case 'Very Cold(mas de 6 meses)':
                    $("#GestionAgendamiento_categorizacion option").removeAttr('selected');
                    $("#GestionAgendamiento_categorizacion option[value=6]").attr("selected", true);
                    break;
                default:
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
        $('#GestionConsulta_vec').change(function () {
            var value = $("#GestionConsulta_vec option:selected").val();
            if (value === '0') {// primer vehiculo
                $('#tipovehiculo').hide();
                $('.cont-fv').hide();
                $('.cont-fv .pos').remove();
                $('.cont-vec-new').hide();
                $('.cont-vec-new .pos').remove();
                //$("#GestionConsulta_preg3 option[value=0]").attr('selected', 'selected');
                //$("#GestionConsulta_preg3 option[value=1]").removeAttr('selected');
                $('#GestionConsulta_vec').removeClass('error');
            } else {
                $('#tipovehiculo').hide();
                $('.cont-fv').show();
                $('.cont-fv .pos').remove();
                $('.cont-fv label').first().prepend('<span class="pos"></span>')
                $('.cont-vec-new').show();
                $('.cont-vec-new .pos').remove();
                $('.cont-vec-new label').first().prepend('<span class="pos"></span>')
                //$("#GestionConsulta_preg3 option[value=1]").attr('selected', 'selected');
                //$("#GestionConsulta_preg3 option[value=0]").removeAttr('selected');$('#GestionConsulta_vec').removeClass('error');
            }
            numerar();
        });

        function numerar() {
            var totalLen = $('.pos').length;
            $("*[class*=pos]").each(
                    function (index) {
                        $(this).empty();
                        $(this).append((index + 1) + ".");
                        if (index === totalLen - 1) {
                            index = 0;
                        }
                    }
            );

        }
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
        $('#GestionProspeccionPr_pregunta').change(function () {
            var value = $("#GestionProspeccionPr_pregunta option:selected").val();
            switch (value) {
                case '3':
                    $('.cont-vec').show();
                    $('.cont-interesado').hide();
                    $('.cont-nocont').hide();
                    break;
                case '4':
                    $('.cont-vec').hide();
                    $('.cont-interesado').show();
                    $('.cont-nocont').hide();
                    break;
                case '5':
                    $('.cont-vec').hide();
                    $('.cont-interesado').hide();
                    $('.cont-nocont').show();
                    break;
                case '1':
                case '2':
                case '6':
                    $('.cont-vec').hide();
                    $('.cont-interesado').hide();
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

        $('#GestionConsulta_preg1_sec1').change(function () {
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
                    $('#Cotizador_modelo').html(data);
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

//        $('#Cotizador_modelo').change(function () {
//            var modelo = $(this).attr('value');
//            //alert(value);
//            $.ajax({
//                type: 'post',
//                url: '<?php echo Yii::app()->createUrl("site/getmodelsyears"); ?>',
//                beforeSend: function (xhr) {
//                    $('#info5').show();  // #info must be defined somehwere
//                },
//                //dataType: "json",
//                data: {modelo: modelo},
//                success: function (data) {
//                    //alert(data.options)
//                    $('#Cotizador_year').html(data);
//                    $('#info5').hide();
//                }
//            });
//        });
        $('#GestionConsulta_preg5').focus(function () {
            $('#consulta_preg5').hide();
        });
        $('#gestion-agendamiento-form').validate({
            rules: {
                'GestionAgendamiento[agendamiento]': {
                    required: true
                },
                'GestionAgendamiento[observaciones]': {
                    required: true
                },
                'GestionAgendamiento[categorizacion]': {
                    required: true
                }
            },
            messages: {
                'GestionAgendamiento[agendamiento]': {
                    required: 'Seleccione una fecha de agendamiento'
                },
                'GestionAgendamiento[categorizacion]': {
                    required: 'Seleccione una categoría'
                }
            },
            submitHandler: function (form) {
                var proximoSeguimiento = $('#GestionAgendamiento_agendamiento').val();
                if (proximoSeguimiento != '') {
                    console.log('proximo: ' + proximoSeguimiento);
                    if ($('#GestionInformacion_check').val() != 2) {
                        var cliente = '';
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
                        var href = '/intranet/usuario/index.php/gestionDiaria/ical?startTime=' + fechaStart + startTime + '&endTime=' + fechaStart + endTime + '&subject=Agendamiento Cita Cliente&desc=Cita con el cliente prospección&location=Por definir&to_name=' + cliente + '&conc=no';
                        //var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente prospección';
                        $('#event-download').attr('href', href);
                        $('#calendar-content').show();
                        $("#event-download").click(function () {
                            $('#GestionInformacion_calendar').val(1);
                            $('#calendar-content').hide();
                            $('#GestionInformacion_check').val(2)
                        });
                        if ($('#GestionInformacion_calendar').val() == 1) {
                            form.submit();
                        } else {
                            alert('Debes descargar agendamiento y luego dar click en Continuar');
                        }
                    } else {
                        form.submit();
                    }
                }
            }
        });
    });
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
    function sendInfo() {
        var fuente = '<?php echo $fuente; ?>';
        //console.log('enter send info');
        if (fuente != 'web') {
            var tipo = $('#GestionInformacion_tipo').val();
            var vec = $('#GestionConsulta_vec').val();
            if (vec == '') {
                $('#tipovehiculo').show();
                $('#tipovehiculo').focus();
                $('#GestionConsulta_vec').addClass('error');
                return false;
            }
            //alert('tipo: '+tipo);
            if (tipo == 'gestion') {
                console.log('enter gestion');
                if (vec === '1') {// ya posee vehiculo
                    console.log('enter posee');
                    $('#gestion-consulta-form').validate({
                        rules: {'GestionConsulta[preg1_sec1]': {required: true}, 'Cotizador[modelo]': {required: true}, 'Cotizador[year]': {required: true}, 'GestionConsulta[preg1_sec4]': {number: true}, 'GestionConsulta[preg5]': {required: true},
                            'GestionVehiculo[modelo]': {required: true}, 'GestionVehiculo[version]': {required: true},
                            'GestionConsulta[preg2]': {required: true}, 'GestionConsulta[preg3_sec1]': {required: true}, 'GestionConsulta[preg4]': {required: true},
                            'GestionConsulta[preg6]': {required: true}, 'GestionConsulta[preg7]': {required: true}, 'necesidad[]': {required: true}},
                        messages: {'GestionConsulta[preg1_sec1]': {required: 'Seleccione la marca'}, 'Cotizador[modelo]': {required: 'Seleccione el modelo'}, 'Cotizador[year]': {required: 'Seleccione el año'}, 'GestionConsulta[preg1_sec4]': {number: 'Ingrese sólo números'}, 'GestionConsulta[preg5]': {required: 'Ingrese el presupuesto'},
                            'GestionVehiculo[modelo]': {required: 'Seleccione modelo'}, 'GestionVehiculo[version]': {required: 'Seleccione versión'},
                            'GestionConsulta[preg2]': {required: 'Seleccione una opción'}, 'GestionConsulta[preg3_sec1]': {required: 'Seleccione una opción'}, 'GestionConsulta[preg4]': {required: 'Seleccione una opción'},
                            'GestionConsulta[preg6]': {required: 'Seleccione una forma de pago'}, 'GestionConsulta[preg7]': {required: 'Seleccione una opción'}, 'necesidad[]': {required: 'Seleccione una opción'}},
                        submitHandler: function (form) {
                            var pres = $('#GestionConsulta_preg5').val();
                            pres = pres.replace('.', '');
                            pres = pres.replace('$', '');
                            pres = parseInt(pres);
                            if (pres < 14990 || pres > 84990) {
                                $('#consulta_preg5').html('Ingrese un valor entre 14990 y 84990');
                                $('#consulta_preg5').show();
                                return false;
                            }
                            $('#finalizar').prop('disabled', true);
                            form.submit();
                        }
                    });
                } else if (vec === '0') { // primer vehiculo
                    console.log('primer vehiculo');
                    $('#gestion-consulta-form').validate({
                        rules: {'GestionConsulta[preg5]': {required: true},
                            'GestionVehiculo[modelo]': {required: true}, 'GestionVehiculo[version]': {required: true}, 'GestionConsulta[preg3_sec1]': {required: true}, 'GestionConsulta[preg4]': {required: true},
                            'GestionConsulta[preg6]': {required: true}, 'GestionConsulta[preg7]': {required: true}, 'necesidad[]': {required: true}},
                        messages: {'GestionConsulta[preg5]': {required: 'Ingrese el presupuesto'},
                            'GestionVehiculo[modelo]': {required: 'Seleccione modelo'}, 'GestionVehiculo[version]': {required: 'Seleccione versión'},
                            'GestionConsulta[preg3_sec1]': {required: 'Seleccione una opción'}, 'GestionConsulta[preg4]': {required: 'Seleccione una opción'},
                            'GestionConsulta[preg6]': {required: 'Seleccione una forma de pago'}, 'GestionConsulta[preg7]': {required: 'Seleccione una opción'}, 'necesidad[]': {required: 'Seleccione una opción'}},
                        submitHandler: function (form) {
                            var pres = $('#GestionConsulta_preg5').val();
                            pres = pres.replace('.', '');
                            pres = pres.replace('$', '');
                            pres = parseInt(pres);
                            if (pres < 14990 || pres > 84990) {
                                $('#consulta_preg5').html('Ingrese un valor entre 14990 y 84990');
                                $('#consulta_preg5').show();
                                return false;
                            }
                            $('#finalizar').prop('disabled', true);
                            form.submit();
                        }
                    });
                }
            } else {
                if (vec === '1') {// ya posee vehiculo
                    //console.log('enter posee');
                    $('#gestion-consulta-form').validate({
                        rules: {'GestionConsulta[preg1_sec1]': {required: true}, 'Cotizador[modelo]': {required: true}, 'Cotizador[year]': {required: true}, 'GestionConsulta[preg1_sec4]': {number: true}, 'GestionConsulta[preg5]': {required: true},
                            'GestionVehiculo[modelo]': {required: true}, 'GestionVehiculo[version]': {required: true},
                            'GestionConsulta[preg2]': {required: true}, 'GestionConsulta[preg3_sec1]': {required: true}, 'GestionConsulta[preg4]': {required: true},
                            'GestionConsulta[preg6]': {required: true}, 'GestionConsulta[preg7]': {required: true}, 'necesidad[]': {required: true}},
                        messages: {'GestionConsulta[preg1_sec1]': {required: 'Seleccione la marca'}, 'Cotizador[modelo]': {required: 'Seleccione el modelo'}, 'Cotizador[year]': {required: 'Seleccione el año'}, 'GestionConsulta[preg1_sec4]': {number: 'Ingrese sólo números'}, 'GestionConsulta[preg5]': {required: 'Ingrese el presupuesto'},
                            'GestionVehiculo[modelo]': {required: 'Seleccione modelo'}, 'GestionVehiculo[version]': {required: 'Seleccione versión'},
                            'GestionConsulta[preg2]': {required: 'Seleccione una opción'}, 'GestionConsulta[preg3_sec1]': {required: 'Seleccione una opción'}, 'GestionConsulta[preg4]': {required: 'Seleccione una opción'},
                            'GestionConsulta[preg6]': {required: 'Seleccione una forma de pago'}, 'GestionConsulta[preg7]': {required: 'Seleccione una opción'}, 'necesidad[]': {required: 'Seleccione una opción'}},
                        submitHandler: function (form) {
                            var pres = $('#GestionConsulta_preg5').val();
                            pres = pres.replace('.', '');
                            pres = pres.replace('$', '');
                            pres = parseInt(pres);
                            if (pres < 14990 || pres > 84990) {
                                $('#consulta_preg5').html('Ingrese un valor entre 14990 y 84990');
                                $('#consulta_preg5').show();
                                return false;
                            }
                            form.submit();
                        }
                    });
                } else if (vec === '0') { // primer vehiculo
                    console.log('primer vehiculo');
                    $('#gestion-consulta-form').validate({
                        rules: {'GestionConsulta[preg5]': {required: true},
                            'GestionVehiculo[modelo]': {required: true}, 'GestionVehiculo[version]': {required: true}, 'GestionConsulta[preg3_sec1]': {required: true}, 'GestionConsulta[preg4]': {required: true},
                            'GestionConsulta[preg6]': {required: true}, 'GestionConsulta[preg7]': {required: true}, 'necesidad[]': {required: true}},
                        messages: {'GestionConsulta[preg5]': {required: 'Ingrese el presupuesto'},
                            'GestionVehiculo[modelo]': {required: 'Seleccione modelo'}, 'GestionVehiculo[version]': {required: 'Seleccione versión'},
                            'GestionConsulta[preg3_sec1]': {required: 'Seleccione una opción'}, 'GestionConsulta[preg4]': {required: 'Seleccione una opción'},
                            'GestionConsulta[preg6]': {required: 'Seleccione una forma de pago'}, 'GestionConsulta[preg7]': {required: 'Seleccione una opción'}, 'necesidad[]': {required: 'Seleccione una opción'}},
                        submitHandler: function (form) {
                            var pres = $('#GestionConsulta_preg5').val();
                            pres = pres.replace('.', '');
                            pres = pres.replace('$', '');
                            pres = parseInt(pres);
                            if (pres < 14990 || pres > 84990) {
                                $('#consulta_preg5').html('Ingrese un valor entre 14990 y 84990');
                                $('#consulta_preg5').show();
                                return false;
                            }
                            form.submit();
                        }
                    });
                }
            }
        }else{
            $('#gestion-consulta-form').validate({
                submitHandler: function (form) {
                    form.submit();
                }
            });
        }
    }
</script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/jasny-bootstrap.css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jasny-bootstrap.js"></script>
<?php
$id_responsable = Yii::app()->user->getId();
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
//echo 'responsable id: '.$id_responsable;
if ($cargo_id != 46 && $cargo_adicional != 0 && $cargo_id != 70) {
    $dealer_id = $this->getDealerId($id_responsable);
    $city_id = $this->getCityId($dealer_id);
    $provincia_id = $this->getProvinciaId($city_id);
}
?>
<?php $this->widget('application.components.Notificaciones'); ?>
<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita.png" alt="" /></span> Cita</a></li>
        <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
        <li role="presentation" class="active"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
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
                    'id' => 'gestion-consulta-form',
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'enctype' => 'multipart/form-data',
                        'onsubmit' => "return false;", /* Disable normal form submit */
                        'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                    ),
                ));
                ?>
                <div class="highlight"><!-- Seguimiento -->
                    <div class="row">
                        <h1 class="tl_seccion_rf">Seguimiento por Consulta</h1>
                    </div>
                    <div class="form cont-seguimiento">
                        <div class="row">
                            <label for=""><span class="pos"></span> ¿El cliente vino interesado por el siguiente modelo?</label>
                            <div class="row">
                                <div class="col-md-5">                                    
                                    <select name="GestionConsulta[modelo_intersado]" id="GestionConsulta_modelo_interesado" class="form-control">
                                        <option value="">--Escoja un Modelo</option>
                                        <option value="Picanto R">Picanto R</option>
                                        <option value="Rio R 4p">Rio R 4p</option>
                                        <option value="Rio R 5p">Rio R 5p</option>
                                        <option value="Rio R Taxi">Rio R Taxi</option>
                                        <option value="Cerato Forte">Cerato Forte</option>
                                        <option value="Cerato Forte Koup">Cerato Koup</option>
                                        <option value="Cerato R">Cerato R</option>
                                        <option value="Optima Híbrido">Optima Híbrido</option>
                                        <option value="Quoris">Quoris</option>
                                        <option value="Soul">Soul Gasolina</option>
                                        <option value="Soul R">Soul R</option>
                                        <option value="Sportage GT">Sportage GT</option>
                                        <option value="Sportage Active">Sportage Active</option>
                                        <option value="Sportage R">Sportage R</option>
                                        <option value="Sorento">Sorento</option>
                                        <option value="Carens R">Carens R</option>
                                        <option value="Carnival R">Carnival R</option>
                                        <option value="K2700">K2700</option>
                                        <option value="K3000">K3000</option>                                        
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Seleccione tipo vehículo</label>
                                    <select name="GestionConsulta[vec]" id="GestionConsulta_vec" class="form-control">
                                        <option value="">--Seleccione--</option>
                                        <option value="1">Ya tiene vehículo</option>
                                        <option value="0">Primer vehículo</option>
                                    </select> 
                                    <label for="GestionConsulta_vec" generated="true" class="error" id="tipovehiculo" style="display: none;">Seleccione tipo vehículo</label>
                                </div>

                            </div>
                            <div class="cont-fv">
                                <label for=""><span class="pos"></span> ¿Qué clase de vehículo conduce en la actualidad?</label>

                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="">Marca</label>
                                        <?php
                                        $consulta = new GestionConsulta;
                                        $criteria = new CDbCriteria(array('group' => 'modelo', 'order' => 'id asc'));
                                        //$marcas = CHtml::listData(Marcas::model()->findAll($criteria), "marca", "marca");
                                        $marcas = array(
                                            '' => 'Seleccione',
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
                                        ?>
                                        <?php
                                        $this->widget('ext.select2.ESelect2', array(
                                            'model' => $consulta,
                                            'attribute' => 'preg1_sec1',
                                            'data' => $marcas
                                        ));
                                        ?>
                                        <?php echo $form->error($consulta, 'preg1_sec1'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="info4" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                        <label for="">Modelo</label>
                                        <?php
                                        $this->widget('ext.select2.ESelect2', array(
                                            'name' => 'Cotizador[modelo]',
                                            'id' => 'Cotizador_modelo',
                                            'data' => array(
                                                '' => '--Seleccione un modelo--'
                                            ),
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div id="info5" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                        <label for="">Año</label>
                                        <?php
                                        $this->widget('ext.select2.ESelect2', array(
                                            'name' => 'Cotizador[year]',
                                            'id' => 'Cotizador_year',
                                            'data' => array(
                                                '' => '--Seleccione el año--',
                                                '2015' => '2015',
                                                '2014' => '2014', '2013' => '2013', '2012' => '2012',
                                                '2011' => '2011', '2010' => '2010', '2009' => '2009',
                                                '2008' => '2008', '2007' => '2007', '2006' => '2006',
                                                '2005' => '2005', '2004' => '2004', '2003' => '2003',
                                                '2002' => '2002', '2001' => '2001', '2000' => '2000',
                                                '1999' => '1999', '1998' => '1998', '1997' => '1997',
                                                '1996' => '1996', '1995' => '1995', '1994' => '1994',
                                                '1993' => '1993', '1992' => '1992', '1991' => '1991',
                                                '1990' => '1990', '1989' => '1989', '1987' => '1987',
                                                '1986' => '1986', '1985' => '1985', '1984' => '1984',
                                                '1983' => '1983', '1982' => '1982', '1981' => '1981',
                                                '1980' => '1980', '1979' => '1979', '1978' => '1978',
                                                '1977' => '1977', '1976' => '1976', '1975' => '1975',
                                                '1974' => '1974', '1973' => '1973', '1972' => '1972',
                                                '1971' => '1971', '1970' => '1970', '1969' => '1969',
                                                '1968' => '1968', '1967' => '1967', '1966' => '1966',
                                                '1965' => '1965', '1964' => '1964', '1963' => '1963',
                                                '1962' => '1962', '1961' => '1961', '1960' => '1960',
                                                '1959' => '1959', '1958' => '1958', '1957' => '1957',
                                                '1956' => '1956', '1955' => '1955', '1954' => '1954',
                                                '1953' => '1953', '1952' => '1952', '1951' => '1951',
                                                '1950' => '1950'
                                            ),
                                        ));
                                        ?>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="kilometraje">Kilometraje</label>
<?php echo $form->textField($consulta, 'preg1_sec4', array('class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
                                        <label for="kilometraje" generated="true" class="error" style="display: none;">Este campo es requerido</label>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="row cont-vec-new">
                            <label for=""><span class="pos"></span> ¿Qué tiene pensado hacer con su vehículo actual?</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php echo $form->dropDownList($consulta, 'preg2', array('' => '--Seleccione--', '0' => 'Vender', '1' => 'Entrega de vehículo usado como parte de pago', '2' => 'Mantenerlo'), array('class' => 'form-control')); ?>
                                </div>
                            </div>
                            <div class="row cont-img" style="display: none;">
                                <div class="col-md-3">
<?php //echo $form->FileField($model, 'img', array('class' => 'form-control'));     ?>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 179px; height: 100px;">
                                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ext_1.jpg" alt="...">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="width: 179px; height: 100px;"></div>
                                        <div>
                                            <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
<?php //echo $form->FileField($consulta, 'preg2_sec1', array('class' => 'form-control'));     ?>
                                                <input class="form-control" name="GestionConsulta[img1]" id="GestionConsulta_img1" type="file">
                                            </span>
                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>
<?php echo $form->error($model, 'img'); ?>
                                </div>
                                <div class="col-md-3">
<?php //echo $form->FileField($model, 'img', array('class' => 'form-control'));     ?>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 179px; height: 100px;">
                                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ext_2.jpg" alt="...">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="width: 179px; height: 100px;"></div>
                                        <div>
                                            <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                <input class="form-control" name="GestionConsulta[img2]" id="GestionConsulta_img2" type="file">
                                            </span>
                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>
<?php echo $form->error($model, 'img'); ?>
                                </div>
                                <div class="col-md-3">
<?php //echo $form->FileField($model, 'img', array('class' => 'form-control'));     ?>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 179px; height: 100px;">
                                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ext_3.jpg" alt="...">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="width: 179px; height: 100px;"></div>
                                        <div>
                                            <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                <input class="form-control" name="GestionConsulta[img3]" id="GestionConsulta_img3" type="file">
                                            </span>
                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>
<?php echo $form->error($model, 'img'); ?>
                                </div>
                                <div class="col-md-3">
<?php //echo $form->FileField($model, 'img', array('class' => 'form-control'));     ?>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 179px; height: 100px;">
                                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/int_1.jpg" alt="...">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="width: 179px; height: 100px;"></div>
                                        <div>
                                            <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                <input class="form-control" name="GestionConsulta[img4]" id="GestionConsulta_img4" type="file">
                                            </span>
                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>
<?php echo $form->error($model, 'img'); ?>
                                </div>
                                <div class="col-md-3">
<?php //echo $form->FileField($model, 'img', array('class' => 'form-control'));     ?>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 179px; height: 100px;">
                                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/int_2.jpg" alt="...">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="width: 179px; height: 100px;"></div>
                                        <div>
                                            <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                <input class="form-control" name="GestionConsulta[img5]" id="GestionConsulta_img5" type="file">
                                            </span>
                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>
                                    <?php echo $form->error($model, 'img'); ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Porque el cliente no autorizó a subir fotos del vehículo?</label>
<?php echo $form->textField($consulta, 'preg2_sec2', array('class' => 'form-control')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label for=""><span class="pos"></span> ¿Para qué utilizará el nuevo vehículo?</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php echo $form->dropDownList($consulta, 'preg3', array('' => '--Seleccione--', '0' => 'Primer Vehículo del hogar', '1' => 'Segundo Vehículo del hogar', '2' => 'Renovación de vehículo'), array('class' => 'form-control', 'options' => array('5' => array('selected' => true)))); ?>
                                </div>
                            </div>
                            <div class="row cont-utl">
                                <div class="col-md-4">
<?php echo $form->dropDownList($consulta, 'preg3_sec1', array('' => '-Seleccione-', '0' => 'Familiar', '1' => 'Trabajo'), array('class' => 'form-control')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label for=""><span class="pos"></span> ¿Quién más participa en la decisión de compra?</label>
                            <div class="row">
                                <div class="col-md-3">
<?php echo $form->dropDownList($consulta, 'preg4', array('' => '-Seleccione-', '0' => 'Esposa/o', '1' => 'Familiar', '2' => 'Departamento de compras', '3' => 'Ninguno'), array('class' => 'form-control')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label for=""><span class="pos"></span> ¿Cuánto es el presupuesto que tiene previsto para su nuevo vehículo?</label>
                            <div class="row">
                                <div class="col-md-3">
<?php echo $form->textField($consulta, 'preg5', array('size' => 10, 'maxlength' => 11, 'class' => 'form-control')); ?>
                                    <label for="GestionConsulta_preg5" generated="true" class="error" id="consulta_preg5"></label>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <label for=""><span class="pos"></span> ¿Cuál sería su forma de pago para su nuevo vehículo?</label>
                            <input type="hidden" name="GestionInformacion[id_cotizacion]" id="GestionInformacion_id_cotizacion" value="<?php echo $id; ?>">
                            <input name="GestionInformacion[id_informacion]" id="GestionConsulta_id_informacion" type="hidden" value="<?php echo $id_informacion; ?>">
<?php $fin = $this->getFinanciamientoExo($id_informacion); ?>
<?php if ($fin == 'exonerados') { ?>
                                <div class="row">
                                    <div class="col-md-3">
                                        <select class="form-control" name="GestionConsulta[preg6]" id="GestionConsulta_preg6">
                                            <option value="0" selected="">Contado</option>
                                        </select>
                                    </div>

                                </div>
<?php } else { ?>
                                <div class="row">
                                    <div class="col-md-3">
    <?php echo $form->dropDownList($consulta, 'preg6', array('' => '-Seleccione-', '0' => 'Contado', '1' => 'Financiado'), array('class' => 'form-control')); ?>
                                    </div>
                                </div>
<?php } ?>

                        </div>
                        <div class="row">
                            <label for=""><span class="pos"></span> ¿En qué tiempo estima realizar su compra?</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <?php
                                    echo $form->dropDownList($consulta, 'preg7', array('' => '-Seleccione-',
                                        'Hot A (hasta 7 dias)' => 'Hasta 7 días',
                                        'Hot B (hasta 15 dias)' => 'Hasta 15 días',
                                        'Hot C (hasta 30 dias)' => 'Hasta 30 días',
                                        'Warm (hasta 3 meses)' => 'Hasta 3 meses',
                                        'Cold (hasta 6 meses)' => 'Hasta 6 meses',
                                        'Very Cold(mas de 6 meses)' => 'Más de 6 meses'), array('class' => 'form-control'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label for=""><span class="pos"></span> ¿Qué busca en su nuevo vehículo?</label>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label>
                                        Seguridad
                                        <input type="checkbox" value="Seguridad" name="necesidad[]">
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label>
                                        Confort
                                        <input type="checkbox" value="Confort" name="necesidad[]">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label>
                                        Desempeño
                                        <input type="checkbox" value="Desempeño" name="necesidad[]">
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label>
                                        Precio
                                        <input type="checkbox" value="Precio" name="necesidad[]">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label>
                                        Economía de Combustible
                                        <input type="checkbox" value="Seguridad" name="necesidad[]">
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label>
                                        Tecnología
                                        <input type="checkbox" value="Tecnología" name="necesidad[]">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <label for="necesidad[]" generated="true" class="error" style="display: none;">Seleccione una opción</label>
                        <div class="row espec" style="display: none;">
                            <label for="">Especifique</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="GestionConsulta[especifique]" id="GestionConsulta_especifique" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- End Seguimiento -->
                <br>
                <div class="highlight"><!-- Vehiculo recomendado -->
                    <div class="row">
                        <h1 class="tl_seccion_rf">Modelo a Cotizar</h1>
                    </div>
                    <div class="form vehicle-cont">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php $vehiculo = new GestionVehiculo; ?>
                                    <?php echo $form->labelEx($vehiculo, 'modelo'); ?>
                                    <?php
                                    $ve = GestionVehiculo::model()->count(array('condition' => "id_informacion = {$id}"));
                                    $id_modelo = 0;
                                    $id_version = 0;
                                    if ($ve > 0) {
                                        $vec = GestionVehiculo::model()->find(array('condition' => "id_informacion = {$id}"));
                                        $id_modelo = $vec->modelo;
                                        $id_version = $vec->version;
                                        $nombre_version = $this->getVersion($id_version);
                                    }
                                    echo $form->dropDownList($vehiculo, 'modelo', array(
                                        "" => "--Escoja un Modelo--",
                                        "84" => "Picanto R",
                                        "85" => "Rio R",
                                        //"91" => "Rio Taxi",
                                        "24" => "Cerato Forte",
                                        "94" => "Cerato Koup",
                                        "90" => "Cerato R",
                                        "89" => "Óptima R",
                                        "88" => "Quoris",
                                        "20" => "Carens R",
                                        "11" => "Grand Carnival",
                                        "80" => "Soul",
                                        "93" => "Soul EV",
                                        "21" => "Sportage Active",
                                        "95" => "Sportage GT",
                                        "83" => "Sportage R",
                                        "96" => "Sportage R CKD",
                                        //"10" => "Sorento",
                                        //"25" => "K 2700 Cabina Simple",
                                        //"87" => "K 2700 Cabina Doble",
                                        "86" => "K 3000"), array('class' => 'form-control', 'options' => array($id_modelo => array('selected' => true))));
                                    ?>
                                    <?php echo $form->error($vehiculo, 'modelo'); ?>
                                </div>
                                <div class="col-md-6">
                                    <div id="info2" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                                    <?php echo $form->labelEx($vehiculo, 'version'); ?>
                                    <?php
                                    if ($ve > 0) {
                                        echo $form->dropDownList($vehiculo, 'version', array(
                                            $id_version => $nombre_version), array('class' => 'form-control'));
                                    } else {
                                        echo $form->dropDownList($vehiculo, 'version', array('' => 'Escoja una versión'), array('class' => 'form-control'));
                                    }
                                    ?>
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
                            <div class="row">
                                <div class="col-md-6 cont-accesorios" style="display: none;">
                                    <label for="">Accesorios</label>
                                    <div class="well well-sm">
                                        <ul class="list-accesorios">
                                            <li><u>Dispositivo GPS</u></li>
                                            <li><u>Aros</u></li>
                                            <li><u>Vidrios Eléctricos</u></li>
                                            <li><u>Radio Táctil</u></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row buttons">
                            <div class="col-md-8">
                                <input type="hidden" name="GestionInformacion2[calendar]" id="GestionInformacion_calendar2" value="0">
                                <input type="hidden" name="GestionInformacion2[check]" id="GestionInformacion_check2" value="1">
                                <input type="hidden" name="GestionInformacion[fuente]" id="GestionInformacion_fuente" value="<?php echo $_GET['fuente'];?>">
                                <input name="GestionInformacion[paso]" id="GestionInformacion_paso" type="hidden" value="3-4">
                                <input name="GestionInformacion[tipo]" id="GestionInformacion_tipo" type="hidden" value="<?php
                                if (isset($_GET['tipo'])) {
                                    echo $_GET['tipo'];
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
                                <input type="hidden" name="tipo" value="<?= $tipo ?>">
                                <input type="hidden" name="fuente" value="<?= $_GET['fuente'] ?>">
<?php echo CHtml::submitButton($model->isNewRecord ? 'Continuar' : 'Grabar', array('class' => 'btn btn-danger', 'id' => 'finalizar', 'onclick' => 'sendInfo();')); ?>
                                <input class="btn btn-primary" style="display: none;" onclick=";" type="submit" name="yt0"  id="continuar" value="Abandonar">
                            </div>
                            <div class="col-md-2">
                                <div id="calendar-content2" style="display: none;">
                                    <a href="" class="btn btn-primary" id="event-download2">Descargar Evento</a>
                                </div>
                            </div>

                        </div>
                    </div>
<?php $this->endWidget(); ?>
                </div><!-- End Vehiculo recomendado -->
                <!--                <br />
                                <div class="highlight">
                                    <div class="row">
                                        <h1 class="tl_seccion_green">Categorización</h1>
                                    </div>
                                    <div class="form">
                <?php $agendamiento = new GestionAgendamiento; ?>
                
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'action' => Yii::app()->createUrl('gestionAgendamiento/createCat'),
                    'id' => 'gestion-categorizacion-form',
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'onsubmit' => "return false;", /* Disable normal form submit */
                        'onkeypress' => " if(event.keyCode == 13){ sendCat(); } " /* Do ajax call when user presses enter key */
                    ),
                ));
                ?>
                <?php //echo $form->errorSummary($agendamiento);    ?>
                                        <div class="row">
                                            <div class="col-md-4">
                <?php echo $form->labelEx($agendamiento, 'categorizacion'); ?>
                <?php
                //$categorizacion = $this->getCategorizacion($id);
                //echo $categorizacion;
                echo $form->dropDownList($agendamiento, 'categorizacion', array(
                    '' => '-Seleccione categoría-',
                    'Hot A (hasta 7 dias)' => 'Hot A(hasta 7 dias)',
                    'Hot B (hasta 15 dias)' => 'Hot B(hasta 15 dias)',
                    'Hot C (hasta 30 dias)' => 'Hot C(hasta 30 dias)',
                    'Warm (hasta 3 meses)' => 'Warm(hasta 3 meses)',
                    'Cold (hasta 6 meses)' => 'Warm(hasta 6 meses)',
                    'Very Cold(mas de 6 meses)' => 'Very Cold(mas de 6 meses)'), array('class' => 'form-control'));
                ?>
<?php echo $form->error($agendamiento, 'categorizacion'); ?>
                                            </div>
                                            
                                        </div>
                                        <div class="row buttons">
                                            <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                                            <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                                            <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="7">
                                            <input type="hidden" name="GestionAgendamiento[id_informacion]" id="GestionAgendamiento_id_informacion" value="<?php echo $id; ?>">
                                            <div class="col-md-2">
                <?php echo CHtml::submitButton($agendamiento->isNewRecord ? 'Cambiar' : 'Save', array('class' => 'btn btn-danger', 'onclick' => 'sendCat();')); ?>
                                            </div>
                                        </div>
<?php $this->endWidget(); ?>
                                    </div> END FORM  
                                    
                                </div>  END OF HIGHLIGHT 
                                <br />
                                <div class="highlight">
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
                <?php //echo $form->errorSummary($agendamiento);    ?>
                                        <div class="row">
                                            <div class="col-md-4" style="display: none;">
                <?php echo $form->labelEx($agendamiento, 'categorizacion'); ?>
                <?php
                //$categorizacion = $this->getCategorizacion($id);
                echo $form->dropDownList($agendamiento, 'categorizacion', array(
                    '' => '-Seleccione categoría-',
                    'Hot A (hasta 7 dias)' => 'Hot A(hasta 7 dias)',
                    'Hot B (hasta 15 dias)' => 'Hot B(hasta 15 dias)',
                    'Hot C (hasta 30 dias)' => 'Hot C(hasta 30 dias)',
                    'Warm (hasta 3 meses)' => 'Warm(hasta 3 meses)',
                    'Cold (hasta 6 meses)' => 'Warm(hasta 6 meses)',
                    'Very Cold(mas de 6 meses)' => 'Very Cold(mas de 6 meses)'), array('class' => 'form-control',));
                ?>
                <?php echo $form->error($agendamiento, 'categorizacion'); ?>
                                            </div>
                                            <div class="col-md-4">
                <?php echo $form->labelEx($agendamiento, 'observaciones'); ?>
<?php echo $form->dropDownList($agendamiento, 'observaciones', array('' => '--Seleccione--', 'Falta de tiempo' => 'Falta de tiempo', 'Llamada de emergencia' => 'Llamada de emergencia', 'Busca solo precio' => 'Busca solo precio', 'Desiste' => 'Desiste', 'Otro' => 'Otro'), array('class' => 'form-control')); ?>
                <?php echo $form->error($agendamiento, 'observaciones'); ?>
                                            </div>
                                            <div class="col-md-4">
                <?php echo $form->labelEx($agendamiento, 'agendamiento'); ?>
<?php echo $form->textField($agendamiento, 'agendamiento', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
<?php echo $form->error($agendamiento, 'agendamiento'); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div id="cont-obs-cat" style="display: none;">
                                                    <label for="">Observación de Categorización</label>
                                                    <input type="text" class="form-control" name="GestionAgendamiento[observacion_categorizacion]" id="GestionAgendamiento_observacion_categorizacion"/>
                                                </div>
                                            </div>
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
                                            <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="4">
                                            <input type="hidden" name="GestionAgendamiento[id_informacion]" id="GestionAgendamiento_id_informacion" value="<?php echo $id; ?>">
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
                                    </div> END FORM  
                                    <div class="row">
                <?php
                $crit5 = new CDbCriteria(array('condition' => "id_informacion={$id} AND paso = 7"));
                $agen5 = GestionAgendamiento::model()->count($crit5);

                $ag5 = GestionAgendamiento::model()->findAll($crit5);
                if ($agen5 > 0) {
                    ?>
                                                        <div class="col-md-8">
                                                            <h4 class="text-danger">Historial</h4>
                                                        </div>
                                                        <div class="col-md-8">
                    <?php
                }
                foreach ($ag5 as $a) {
                    ?>
                                                                <div class="row">
                                                                <div class="col-md-4"><strong>Fecha Agendamiento: </strong><?php echo $a['agendamiento']; ?></div>
                                                                <div class="col-md-4"><strong>Motivo: </strong><?php echo $a['observaciones']; ?></div>
                                                                <div class="col-md-4"><strong>Categorización: </strong><?php echo $a['categorizacion']; ?></div>
                                                                </div>
<?php } ?>
                                        </div>
                                    </div>
                                </div>  END OF HIGHLIGHT 
                                <br />-->
            </div>
            <br />
            <br />
<?= $this->renderPartial('//layouts/rgd/links'); ?>
        </div>
    </div>
</div>
