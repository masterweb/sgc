$(document).ready(function () {

    $('#GestionFinanciamiento_tiempo_seguro').change(function () {
        var valorFin = $('#GestionFinanciamiento_entrada').val();
        if (valorFin != '') {
            calcFinanciamiento();
        }
    });
    $('#GestionFinanciamiento_plazo').change(function () {
        var valorFin = $('#GestionFinanciamiento_entrada').val();
        if (valorFin != '') {
            calcFinanciamiento();
        }
    });
    $('#GestionFinanciamiento_tiempo_seguro2').change(function () {
        var valorFin = $('#GestionFinanciamiento_entrada2').val();
        if (valorFin != '') {
            calcFinanciamiento2();
        }
    });
    $('#GestionFinanciamiento_plazo2').change(function () {
        var valorFin = $('#GestionFinanciamiento_entrada2').val();
        if (valorFin != '') {
            calcFinanciamiento2();
        }
    });

    $('#GestionFinanciamiento_tiempo_seguro3').change(function () {
        var valorFin = $('#GestionFinanciamiento_entrada3').val();
        if (valorFin != '') {
            calcFinanciamiento3();
        }
    });
    $('#GestionFinanciamiento_plazo3').change(function () {
        var valorFin = $('#GestionFinanciamiento_entrada3').val();
        if (valorFin != '') {
            calcFinanciamiento3();
        }
    });
    $('#GestionFinanciamiento_tiempo_seguro_contado').change(function () {
        var valorFin = $('#GestionFinanciamiento_tiempo_seguro_contado').val();
        calcFinanciamientoContado();
    });
    $('#GestionFinanciamiento_tiempo_seguro_contado2').change(function () {
        var valorFin = $('#GestionFinanciamiento_tiempo_seguro_contado2').val();
        calcFinanciamientoContado2();
    });
    $('#GestionFinanciamiento_tiempo_seguro_contado3').change(function () {
        var valorFin = $('#GestionFinanciamiento_tiempo_seguro_contado3').val();
        calcFinanciamientoContado3();
    });
//    $('#gestion-agendamiento-form').validate({
//        rules: {
//            'GestionAgendamiento[agendamiento]': {
//                required: true
//            },
//            'GestionAgendamiento[observaciones]': {
//                required: true
//            },
//            'GestionAgendamiento[categorizacion]': {
//                required: true
//            }
//        },
//        messages: {
//            'GestionAgendamiento[agendamiento]': {
//                required: 'Seleccione una fecha de agendamiento'
//            },
//            'GestionAgendamiento[categorizacion]': {
//                required: 'Seleccione una categoría'
//            }
//        },
//        submitHandler: function (form) {
//            var proximoSeguimiento = $('#GestionAgendamiento_agendamiento').val();
//            if (proximoSeguimiento != '') {
//                console.log('proximo: ' + proximoSeguimiento);
//                if ($('#GestionInformacion_check').val() != 2) {
//                    var cliente = '';
//                    var params = proximoSeguimiento.split("/");
//                    var fechaDate = params[0] + params[1] + params[2];
//                    var secDate = params[2].split(" ");
//                    var fechaStart = params[0] + params[1] + secDate[0];
//                    var start = secDate[1].split(":");
//                    var startTime = start[0] + start[1];
//                    var params2 = fechaDate.split(":");
//                    var endTime = parseInt(startTime) + 100;
//                    //console.log('start time:'+fechaStart+startTime);
//                    //console.log('fecha end:'+fechaStart+endTime);
//                    var href = '/intranet/ventas/index.php/gestionDiaria/ical?startTime=' + fechaStart + startTime + '&endTime=' + fechaStart + endTime + '&subject=Agendamiento Cita Cliente&desc=Cita con el cliente prospección&location=Por definir&to_name=' + cliente + '&conc=no';
//                    //var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente prospección';
//                    $('#event-download').attr('href', href);
//                    $('#calendar-content').show();
//                    $("#event-download").click(function () {
//                        $('#GestionInformacion_calendar').val(1);
//                        $('#calendar-content').hide();
//                        $('#GestionInformacion_check').val(2)
//                    });
//                    if ($('#GestionInformacion_calendar').val() == 1) {
//                        form.submit();
//                    } else {
//                        alert('Debes descargar agendamiento y luego dar click en Continuar');
//                    }
//                } else {
//                    form.submit();
//                }
//            }
//        }
//    });
    $('#GestionInformacion_celular').focus(function () {
        $('#celular2').hide();
    });
    $('#GestionInformacion_telefono_oficina').focus(function () {
        $('#telefono2').hide();
    });
    $('#GestionInformacion_telefono_casa').focus(function () {
        $('#casa2').hide();
        $('#telefono3').hide();
    });
    $('.tipo-venta').hide();
    $('.tipo-form-drop').hide();
    $('#GestionAgendamiento_categorizacion').change(function () {
        var value = $(this).attr('value');
        if (value != '') {
            $('#cont-obs-cat').show();
        } else
            $('#cont-obs-cat').hide();
    });
    $('.form-control').change(function () {
        var value = $(this).attr('value');
        if (value == 'Aprobado') {
            $('.cont-obs').hide();
        } else
            $('.cont-obs').show();
    });
    $('#Casos_tema').change(function () {
        var value = $(this).attr('value');
        if (value != 3) {
            $('.content-concec').hide();
            var dataModelo = '<option value="" selected="selected">--Escoja un Modelo--</option>\n\
                                  <option value="84">Picanto R</option>\n\
                                <option value="85">Rio R</option>\n\
                                <option value="24">Cerato Forte</option>\n\
                                <option value="90">Cerato R</option>\n\
                                <option value="89">Óptima Híbrido</option>\n\
                                <option value="88">Quoris</option>\n\
                                <option value="20">Carens R</option>\n\
                                <option value="11">Grand Carnival</option>\n\
                                <option value="21">Sportage Active</option>\n\
                                <option value="83">Sportage R</option>\n\
                                <option value="10">Sorento</option>\n\
                                <option value="25">K 2700 Cabina Simple</option>\n\
                                <option value="87">K 2700 Cabina Doble</option>\n\
                                <option value="86">K 3000</option>'
            var dataVersion = '<option value="" selected="selected">Escoja una versión</option>';
            $('#Casos_modelo').html(dataModelo);
            $('#Casos_version').html(dataVersion);
        }
        $.ajax({
            url: 'https://www.kia.com.ec/intranet/usuario/index.php/submenu/getsubmenus',
            dataType: "json",
            beforeSend: function (xhr) {
                $('#info').show();  // #info must be defined somehwere
            },
            data: {
                id: value
            },
            type: 'post',
            success: function (data) {
                //alert(data.options)
                $('#Casos_subtema').html(data.options);
                $('#info').hide();
            }
        });
    });
    $('#GestionVehiculo_modelo').change(function () {
        var value = $(this).attr('value');
        $.ajax({
            url: 'https://www.kia.com.ec/intranet/usuario/index.php/versiones/getversiones',
            beforeSend: function (xhr) {
                $('#info2').show();  // #info must be defined somehwere
            },
            dataType: "json",
            data: {
                id: value
            },
            type: 'post',
            success: function (data) {
                //alert(data.options)
                $('#GestionVehiculo_version').html(data.options);
                $('#info2').hide();
            }
        });
    });
    $('#GestionVehiculo_modelo2').change(function () {
        var value = $(this).attr('value');
        $.ajax({
            url: 'https://www.kia.com.ec/intranet/usuario/index.php/versiones/getversiones',
            beforeSend: function (xhr) {
                $('#info2').show();  // #info must be defined somehwere
            },
            dataType: "json",
            data: {
                id: value
            },
            type: 'post',
            success: function (data) {
                //alert(data.options)
                $('#GestionVehiculo_version2').html(data.options);
                $('#info2').hide();
            }
        });
    });
    $('#GestionNuevaCotizacion_fuente').change(function () {
        var value = $(this).attr('value');
        var datausado = '<option value="">--Seleccione--</option>\n\
<option value="Nuevo">Nuevo</option>\n\\n\
<option value="Usado">Usado</option>\n\
<option value="Exonerado Taxi">Exonerado Taxi</option>\n\
<option value="Exonerado Conadis">Exonerado Conadis</option>\n\
<option value="Exonerado Diplomatico">Exonerado Diplomático</option>';
        var dataprospeccion = '<option value="">--Seleccione--</option>\n\
<option value="Nuevo">Nuevo</option>';
        var data = '<option value="">--Seleccione--</option>\n\
<option value="Nuevo">Nuevo</option>\n\
<option value="Usado">Usado</option>';
        var datatrafico = '<option value="">--Seleccione--</option>\n\
<option value="Nuevo">Nuevo</option>\n\
<option value="Usado">Usado</option>\n\
';
        switch (value) {
            case 'showroom':
                $("#GestionNuevaCotizacion_tipo").html(datausado);
                $('.tipo-cont').show();
                $('#cont-ident').show();
                $('.motivo-cont').hide();
                $('.exh-cont').hide();
                $('.empresa-cont').hide();
                break;
            case 'otro':
                $('.tipo-cont').show();
                $('.otro-cont').show();
                $('.motivo-cont').hide();
                $('.empresa-cont').hide();
                break;
            case 'exonerados':
                $("#GestionNuevaCotizacion_tipo").html(data);
                $('.tipo-cont').show();
                $('.otro-cont').hide();
                $('.motivo-cont').show();
                $('.exh-cont').hide();
                $('.empresa-cont').hide();
                break;
            case 'prospeccion':
                $("#GestionNuevaCotizacion_tipo").html(dataprospeccion);
                $('.tipo-cont').show();
                $('#cont-ident').show();
                $('.exh-cont').hide();
                $('.motivo-cont').hide();
                $('.empresa-cont').hide();
                break;
            case 'trafico':
                $("#GestionNuevaCotizacion_tipo").html(datatrafico);
                $('.tipo-cont').show();
                $('#cont-ident').hide();
                $('.exh-cont').hide();
                $('.motivo-cont').hide();
                $('.empresa-cont').hide();
                break;
            case 'exhibicion':
                $("#GestionNuevaCotizacion_tipo").html(data);
                $('.tipo-cont').show();
                $('#cont-ident').show();
                $('.exh-cont').show();
                $('.motivo-cont').hide();
                $('.empresa-cont').hide();
                break;
            case 'web':
                $('.tipo-cont').hide();
                $('#cont-ident').show();
                $('.exh-cont').hide();
                $('.empresa-cont').hide();
                break;
            default:

                break;
        }
        /*if (value == 'otro') {
         $('.otro-cont').show();
         $('.motivo-cont').hide();
         } else if (value == 'exonerados') {
         $('.otro-cont').hide();
         $('.motivo-cont').show();
         } else {
         $('.motivo-cont').hide();
         }
         if (value == 'prospeccion' || value == 'trafico') {
         $('#cont-doc').hide();
         $('#cont-ident').hide();
         } else {
         $('#cont-doc').show();
         $('#cont-ident').show();
         }*/
    });

    $('#Casos_provincia').change(function () {
        var value = $(this).attr('value');
        $.ajax({
            url: 'https://www.kia.com.ec/intranet/usuario/index.php/dealercities/getcities',
            dataType: "json",
            beforeSend: function (xhr) {
                $('#info5').show();  // #info must be defined somehwere
            },
            data: {
                id: value
            },
            type: 'post',
            success: function (data) {
                //alert(data.options)
                $('#Casos_ciudad').html(data.options);
                $('#info5').hide();
            }
        });
    });
    $('#GestionInformacion_provincia_conc').change(function () {
        var value = $(this).attr('value');
        $.ajax({
            url: 'https://www.kia.com.ec/intranet/usuario/index.php/dealercities/getcities',
            dataType: "json",
            beforeSend: function (xhr) {
                $('#info5').show();  // #info must be defined somehwere
            },
            data: {
                id: value
            },
            type: 'post',
            success: function (data) {
                //alert(data.options)
                $('#GestionInformacion_ciudad_conc').html(data.options);
                $('#info5').hide();
            }
        });
    });

    $('#Casos_identificacion').change(function () {
        var value = $(this).attr('value');
        switch (value) {
            case 'ci':
                $('.cedula-cont').show();
                $('.ruc-cont').hide();
                $('#Casos_ruc').val('');
                $('.pasaporte-cont').hide();
                $('#Casos_pasaporte').val('');
                break;
            case 'ruc':
                $('.cedula-cont').hide();
                $('#Casos_cedula').val('');
                $('.ruc-cont').show();
                $('.pasaporte-cont').hide();
                $('#Casos_pasaporte').val('');
                break;
            case 'pasaporte':
                $('.cedula-cont').hide();
                $('#Casos_cedula').val('');
                $('.ruc-cont').hide();
                $('#Casos_ruc').val('');
                $('.pasaporte-cont').show();
                break;
            default:
        }
    });

    $('#Casos_subtema').change(function () {
        var value = $(this).attr('value');
        var tipoForm = '<option value="">--Tipo de caso--</option><option value="informativo">Informativo</option><option value="caso">Creación de Caso</option>';
        var tipoVenta = '<option value="">--Tipo Venta--</option><option value="venta">Venta</option><option value="postventa">Post Venta</option>';
        //alert(value);
        if (value == 2 || value == 3 || value == 6 || value == 18) {
            //alert('valor ic');
            $('.tipo-form-drop').show();
            $('.tipo-venta').show();
        } else {
            //$('#Casos_tipo_form').html(tipoForm);
            $('#Casos_tipo_venta').html(tipoVenta);
            //$('.tipo-venta').hide();
            $('.tipo-form-drop').hide();
        }

    });

    $('#Casos_ciudad').change(function () {
        var value = $(this).attr('value');
        $.ajax({
            url: 'https://www.kia.com.ec/intranet/usuario/index.php/dealers/getdealers',
            dataType: "json",
            beforeSend: function (xhr) {
                $('#info6').show();  // #info must be defined somehwere
            },
            data: {
                id: value
            },
            type: 'post',
            success: function (data) {
                //alert(data.options)
                $('#Casos_concesionario').html(data.options);
                $('#info6').hide();
            }
        });
    });
    $('#GestionInformacion_ciudad_conc').change(function () {
        var value = $(this).attr('value');
        $.ajax({
            url: 'https://www.kia.com.ec/intranet/usuario/index.php/dealers/getdealers',
            dataType: "json",
            beforeSend: function (xhr) {
                $('#info6').show();  // #info must be defined somehwere
            },
            data: {
                id: value
            },
            type: 'post',
            success: function (data) {
                //alert(data.options)
                $('#GestionInformacion_concesionario').html(data.options);
                $('#info6').hide();
            }
        });
    });
    $('#Casos_tipo_form').change(function () {
        var value = $(this).attr('value');
        if (value == 'caso') {
            $('.tipo-venta').show();
        } else {
            $('.tipo-venta').hide();
        }
    });
    $('#edit-btn').click(function () {
        //alert('click');
        $('#save-btn').show();
        $('#edit-btn').hide();// cancel button
        //$('#Casos_tema').attr("disabled",false);$('#Casos_subtema').attr("disabled",false);$('#Casos_provincia_domicilio').attr("disabled",false);
        //$('#Casos_ciudad_domicilio').attr("disabled",false);$('#Casos_tipo_venta').attr('disabled',false);
        //$('#Casos_provincia').attr("disabled",false);
        $(".highlight #precio_normal").removeAttr("readonly");
        //$("#Casos_tema").prop("disabled", false);
        //$("#Casos_subtema").prop("disabled", false);
        $(".highlight #precio_normal").css('background-color', 'white');
    });
    $('#save-btn').click(function () {
        //console.log('enter cancel button');

        $('#save-btn').hide();
        $('#edit-btn').show();
        //$(".highlight #precio_normal").attr('readonly', true);
        //$("#Casos_tema").prop("disabled", true);
        //$("#Casos_subtema").prop("disabled", true);
        $(".highlight #precio_normal").css('background-color', '#DCD8D9');
        // cogo precio anterior desde la base de datos
        var precio_accesorios_anterior = $('#precio_accesorios_anterior').val();
        var precioaccesorios = $('#precio_accesorios').val();
        // parseamos el valor a integer
        precioaccesorios = precioaccesorios.replace(',', '');
        precioaccesorios = precioaccesorios.replace('.', ',');
        precioaccesorios = precioaccesorios.replace('$', '');
        precioaccesorios = parseInt(precioaccesorios);
        var precionormal = $('#precio_normal').val();

        precionormal = precionormal.replace(',', '');
        precionormal = precionormal.replace('.', ',');
        precionormal = precionormal.replace('$', '');
        precionormal = parseInt(precionormal);
        //console.log('precio normal: '+precionormal);
        //console.log('precio accesorios: '+precioaccesorios);

        var difprecioaccesorios = precioaccesorios - precio_accesorios_anterior;
        console.log('diferencia: ' + difprecioaccesorios);
        precioaccesorios = precionormal + difprecioaccesorios;
        precioaccesorios = format2(precioaccesorios, '$');
        precionormal = format2(precionormal, '$');
        //console.log('precio normal: '+precionormal);
        $('#GestionFinanciamiento_precio_contado').val(precioaccesorios);
        $('#precio_accesorios').val(precioaccesorios);
        $('#precio_normal_anterior').val(precionormal);

        calcFinanciamientoContadoExo();
    });
    $('#Casos_provincia_domicilio').change(function () {
        var value = $(this).attr('value');
        //console.log(value)
        var data = '';
        $.ajax({
            url: 'https://www.kia.com.ec/intranet/usuario/index.php/TblCiudades/getciudades',
            dataType: "json",
            type: 'post',
            beforeSend: function (xhr) {
                $('#info4').show();  // #info must be defined somehwere
            },
            data: {
                id: value
            },
            success: function (data) {
                //alert(data.options)
                $('#Casos_ciudad_domicilio').html(data.options);
                $('#info4').hide();
            }
        });
        //alert(value)
        $('#Contactenos_ciudad').html(data);

    });

    $('#Casos_tipo_form').change(function () {
        var value = $(this).attr('value');
        if (value == 'caso') {
            var email = $('#Casos_email').val();
            var nombre = $('#Casos_nombres').val();
            var apellido = $('#Casos_apellidos').val();
            console.log(email);
            $('#email-data').html(email);
            $('#name-data').html(nombre + ' ' + apellido);
            $('.scr-caso').show();
            $('.scr-info').hide();
        } else {
            var nombre = $('#Casos_nombres').val();
            var apellido = $('#Casos_apellidos').val();
            $('#name-data-info').html(nombre + ' ' + apellido);
            $('.scr-info').show();
            $('.scr-caso').hide();
        }
    });

    $('#GestionAgendamiento_categorizacion').change(function () {
        sendCat();
    });
    $('#GestionAgendamiento_observaciones').change(function () {
        var value = $(this).attr('value');
        switch (value) {
            case 'Falta de tiempo':
                $('#cont-otro').hide();
                $('.agendamiento').show();
                addReglas();
                break;
            case 'Llamada de emergencia':
                $('#cont-otro').hide();
                $('.agendamiento').show();
                addReglas();
                break;
            case 'Busca solo precio':
                $('#cont-otro').hide();
                $('.agendamiento').hide();
                removeReglas();
                break;
            case 'Desiste':
                $('#cont-otro').hide();
                $('.agendamiento').hide();
                removeReglas();
                break;
            case 'Otro':
                $('#cont-otro').show();
                $('.agendamiento').hide();
                removeReglas();
                break;
        }
    });
    $('#gestion-agendamiento-form').validate({
        rules: {
            'GestionAgendamiento[observaciones]': {
                required: true
            }
        },
        messages: {
            'GestionAgendamiento[observaciones]': {
                required: 'Seleccione una opción'
            }
        },
        submitHandler: function (form) {
            var observaciones = $('#GestionAgendamiento_observaciones').val();
            switch (observaciones) {
                case 'Falta de tiempo':
                case 'Llamada de emergencia':
                    var proximoSeguimiento = $('#GestionAgendamiento_agendamiento').val();
                    //console.log(proximoSeguimiento);
                    var fechaSeguimiento = proximoSeguimiento.replace('/', '-');
                    fechaSeguimiento = fechaSeguimiento.replace('/', '-');
                    var fechaArray = fechaSeguimiento.split(' ');

                    //console.log('fecha seguimiento: '+fechaSeguimiento);
                    var categorizacion = $('#GestionAgendamiento_categorizacion').val();
                    var dias = 0;
                    switch (categorizacion) {
                        case 'Hot A (hasta 7 dias)':
                            dias = 7;
                            break;
                        case 'Hot B (hasta 15 dias)':
                            dias = 15;
                            break;
                        case 'Hot C (hasta 30 dias)':
                            dias = 30;
                            break;
                        case 'Warm (hasta 3 meses)':
                            dias = 60;
                            break;
                        case 'Cold (hasta 6 meses)':
                            dias = 180;
                            break;
                        case 'Very Cold(mas de 6 meses)':
                            dias = 181;
                            break;
                        default:
                    }
                    //console.log(proximoSeguimiento);
                    var fechaActual = new Date().toJSON().slice(0, 10);
                    //console.log('Fecha Actual: '+hoy);
                    var diferencia = restaFechas(fechaActual, fechaArray[0]);
                    if (diferencia <= dias) {
                        if (proximoSeguimiento != '') {
                            //console.log('proximo: '+proximoSeguimiento);
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
                                var href = '/intranet/usuario/index.php/gestionDiaria/ical?startTime=' + fechaStart + startTime + '&endTime=' + fechaStart + endTime + '&subject=Agendamiento Cita Cliente <?php echo $nombre_cliente; ?>&desc=Cita con el cliente paso consulta: <?php echo $nombre_cliente; ?>&location=Por definir&to_name=' + cliente + '&conc=<?php echo $nombreConcesionario; ?>';
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
                    } else {
                        alert('Seleccione una fecha menor o igual a la fecha de Categorización.');
                        return false;
                    }
                    break;
                case 'Busca solo precio':
                case 'Desiste':
                case 'Otro':
                    form.submit();
                    break;
                default :
                    break;
            }
        }
    });
});

function addReglas() {
    $("#GestionAgendamiento_agendamiento").rules("add", "required");
}
function removeReglas() {
    $("#GestionAgendamiento_agendamiento").rules("remove", "required");
}

function restaFechas(f1, f2)
{
    var aFecha1 = f1.split('-');
    var aFecha2 = f2.split('-');
    var fFecha1 = Date.UTC(aFecha1[0], aFecha1[1] - 1, aFecha1[2]);
    var fFecha2 = Date.UTC(aFecha2[0], aFecha2[1] - 1, aFecha2[2]);
    var dif = fFecha2 - fFecha1;
    var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
    return dias;
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

function validateCantNumbers(g) {
    var e = g;
    var c = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    var b = c.length;
    var d = e.length;
    var a;
    veces = 0;
    for (var f = 0; f < b; f++) {
        veces = 0;
        newLen = d;
        cadenaNueva = e;
        while (newLen >= 1) {
            a = cadenaNueva.indexOf(c[f]);
            if (a != -1) {
                cadenaNueva = cadenaNueva.substring(a + 1);
                veces++
            } else {
                a = a + 2;
                cadenaNueva = cadenaNueva.substring(a)
            }
            newLen = cadenaNueva.length
        }
        if (veces >= 4)
        {
            return false;
            break
        }
    }
}

function sendCat() {
    var dataform = $("#gestion-categorizacion-form").serialize();
    $.ajax({
        url: 'https://www.kia.com.ec/intranet/usuario/index.php/gestionAgendamiento/createCat',
        beforeSend: function (xhr) {
            $('#bg_negro').show();  // #bg_negro must be defined somewhere
        },
        type: 'POST',
        data: dataform,
        success: function (data) {
            $('#bg_negro').hide();
            location.reload();
        }
    });
}



