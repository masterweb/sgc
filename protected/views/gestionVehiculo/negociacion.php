<?php $this->widget('application.components.Notificaciones'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.maskMoney.js" type="text/javascript"></script>
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//echo 'id informacion: '.$id_informacion.'<br>';
//echo 'id vehiculo: '.$id_vehiculo;
$id_modelo = $this->getIdModelo($id_vehiculo);
//echo 'id modelo: '.$id_modelo;
$tipo = $this->getFinanciamiento($id_informacion, $id_vehiculo); 
$id_version = $this->getIdVersion($id_vehiculo);
$fuente = $this->getFuenteContacto($id_informacion);
$urlConsulta = Yii::app()->createUrl('gestionVehiculo/create/' . $id_informacion);
if($fuente == 'web'){
    $urlConsulta = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $id_informacion, 'tipo' => 'gestion', 'fuente' => 'web'));
}
?>
<style type="text/css">
    #sum-accesorios-val2{color:#AA1F2C};
    #btn-ot{padding-left: 0px !important;}
    .ag-btn{padding-left: 0px !important;}
</style>
<script type="text/javascript">
    var acc1 = new Array();
    var acc2 = new Array();
    var acc3 = new Array();
    var acco1 = new Array();
    var acco2 = new Array();
    var acco3 = new Array();
    var accsum1 = 0;
    var accsum2 = 0;
    var accsum3 = 0;
    var preciovec1;
    var preciovec2;
    var preciovec3;
    var tipo_financiamiento = <?php echo $tipo; ?>;
    $(document).ready(function () {
<?php if ($tipo == 1): // credito            ?>
    //$('.cont-contado').hide();
    $('#GestionFinanciamiento_tipo_financiamiento').val(1);
    var precio_vehiculo1 = formatnumber($('#GestionFinanciamiento_precio').val());
    precio_vehiculo1 = precio_vehiculo1 * 0.20;
    $('#GestionFinanciamiento_entrada').val(format2(precio_vehiculo1,'$'));calcFinanciamiento();
    var precio_vehiculo2 = formatnumber($('#GestionFinanciamiento_precio2').val());
    precio_vehiculo2 = precio_vehiculo1 * 0.20;
    $('#GestionFinanciamiento_entrada2').val(format2(precio_vehiculo1,'$'));
    var precio_vehiculo3 = formatnumber($('#GestionFinanciamiento_precio3').val());
    precio_vehiculo3 = precio_vehiculo1 * 0.20;
    $('#GestionFinanciamiento_entrada3').val(format2(precio_vehiculo1,'$'));
            //$('.cont-financ').show();
<?php else: ?>
            //$('.cont-contado').show();
            $('#GestionFinanciamiento_tipo_financiamiento').val(0);
            //$('.cont-financ').hide();
<?php endif; ?>
    //$('#btn-acc').prop('disabled',true);
    
    
    $('#GestionFinanciamiento_entrada').keyup(function () {
        calcFinanciamiento();
    });
    $('#GestionFinanciamiento_entrada2').keyup(function () {
        calcFinanciamiento2();
    });
    $('#GestionFinanciamiento_entrada3').keyup(function () {
        calcFinanciamiento3();
    });
    $('#GestionFinanciamiento_porcentaje_entrada').keyup(function (){
        var porcentaje_entrada = $('#GestionFinanciamiento_porcentaje_entrada').val();
        if(porcentaje_entrada.length >= 2){
            porcentaje_entrada = porcentaje_entrada / 100;
            var valor_entrada = formatnumber($('#GestionFinanciamiento_precio').val()) * porcentaje_entrada;
            //alert(valor_entrada);
            $('#GestionFinanciamiento_entrada').val(format2(valor_entrada,'$'));calcFinanciamiento();
        }
    });
    $('#GestionFinanciamiento_porcentaje_entrada2').keyup(function (){
        var porcentaje_entrada = $('#GestionFinanciamiento_porcentaje_entrada2').val();
        if(porcentaje_entrada.length >= 2){
            porcentaje_entrada = porcentaje_entrada / 100;
            var valor_entrada = formatnumber($('#GestionFinanciamiento_precio2').val()) * porcentaje_entrada;
            //alert(valor_entrada);
            $('#GestionFinanciamiento_entrada2').val(format2(valor_entrada,'$'));calcFinanciamiento2();
        }
    });
    $('#GestionFinanciamiento_porcentaje_entrada3').keyup(function (){
        var porcentaje_entrada = $('#GestionFinanciamiento_porcentaje_entrada3').val();
        if(porcentaje_entrada.length >= 2){
            porcentaje_entrada = porcentaje_entrada / 100;
            var valor_entrada = formatnumber($('#GestionFinanciamiento_precio3').val()) * porcentaje_entrada;
            //alert(valor_entrada);
            $('#GestionFinanciamiento_entrada3').val(format2(valor_entrada,'$'));calcFinanciamiento3();
        }
    });
//    $('#GestionFinanciamiento_seguro').keyup(function () {
//        calcSeguro();
//    });
//    $('#GestionFinanciamiento_seguro2').keyup(function () {
//        calcSeguro2();
//    });
//    $('#GestionFinanciamiento_seguro3').keyup(function () {
//        calcSeguro3();
//    });
    $('#GestionFinanciamiento_precio').keyup(function () {
        //if($('#GestionFinanciamiento_entrada').val() != ''){
            //calcFinanciamiento();
        //}
    });
    $('#GestionFinanciamiento_precio2').keyup(function () {
        //if($('#GestionFinanciamiento_entrada2').val() != ''){
        //    calcFinanciamiento2();
       //}
    });
    $('#GestionFinanciamiento_precio3').keyup(function () {
        //if($('#GestionFinanciamiento_entrada3').val() != ''){
        //    calcFinanciamiento3();
        //}
    });
    $('#GestionFinanciamiento_precio_contado').keyup(function () {
        calcFinanciamientoContado();
    });
    $('#GestionFinanciamiento_precio_contado2').keyup(function () {
        calcFinanciamientoContado2();
    });
    $('#GestionFinanciamiento_precio_contado3').keyup(function () {
        calcFinanciamientoContado3();
    });
//    $('#GestionFinanciamiento_valor_financiamiento').keyup(function () {
//        calcFinanciamiento();
//    });
    
    // SUMAR VALORES DE ACCESORIOS INGRESADOS MANUALMENTE
    $('#valor_otro_accesorios1').keyup(function () {$('.error-accesorio1').hide();getvalortotal(1);});
    $('#valor_otro_accesorios2').keyup(function () {$('.error-accesorio2').hide();getvalortotal(2);});
    $('#valor_otro_accesorios3').keyup(function () {$('.error-accesorio3').hide();getvalortotal(3);});
    $('#otro_accesorios_nombre1').keyup(function () {$('.error-nombre1').hide();getvalortotal(3);});
    $('#otro_accesorios_nombre2').keyup(function () {$('.error-nombre2').hide();getvalortotal(3);});
    $('#otro_accesorios_nombre3').keyup(function () {$('.error-nombre3').hide();getvalortotal(3);});
    /*$('#btngenerate').click(function(){
        $('#btngenerate').hide();
    });*/
    var finanprecio = parseInt($('#GestionFinanciamiento_precio').val());
    var finanprecioformat = format2(finanprecio, '$');
    $('#GestionFinanciamiento_precio').val(finanprecioformat);

    var finanprecio2 = parseInt($('#GestionFinanciamiento_precio2').val());
    var finanprecioformat2 = format2(finanprecio2, '$');
        $('#GestionFinanciamiento_precio2').val(finanprecioformat2);
    
    var finanprecio3 = parseInt($('#GestionFinanciamiento_precio3').val());
    var finanprecioformat3 = format2(finanprecio3, '$');
        $('#GestionFinanciamiento_precio3').val(finanprecioformat3);

    var precionormal = parseInt($('#precio_normal').val());
    var precioformat = format2(precionormal, '$');
    $('#precio_normal').val(precioformat);
    var precioaccesorios = parseInt($('#precio_accesorios').val());
    if(isNaN(precioaccesorios)){
        var precioformatacc = format2(0, '$');
        $('#precio_accesorios').val(precioformatacc);
    }else{
        var precioformatacc = format2(precioaccesorios, '$');
        $('#precio_accesorios').val(precioformatacc);
    }
    var precioContado = parseInt($('#GestionFinanciamiento_precio_contado').val());
    var precioContado = format2(precioContado, '$');
    $('#GestionFinanciamiento_precio_contado').val(precioContado);

    var precioContado2 = parseInt($('#GestionFinanciamiento_precio_contado2').val());
    var precioContado2 = format2(precioContado2, '$');
    $('#GestionFinanciamiento_precio_contado2').val(precioContado2);

    var precioContado3 = parseInt($('#GestionFinanciamiento_precio_contado3').val());
    var precioContado3 = format2(precioContado3, '$');
    $('#GestionFinanciamiento_precio_contado3').val(precioContado3);
    
    
        $('#btngenerate').click(function () {
            var optionscont = $('#options-cont').val();
            switch(optionscont){
                case '2':
                    $('#cont-edit1').show();$('#cont-edit2').show();$('#cont-edit3').show();
                break;
                case '3':
                    $('#cont-edit1').show();$('#cont-edit2').show();$('#cont-edit3').show();
                break;
                case '4':
                    $('#cont-edit1').show();$('#cont-edit2').show();$('#cont-edit3').show();
                break;    
            }
            $('#btngenerate').hide();
            $('#contpdf').hide();
            $('#btnsendprof').show();$('#btnagendamiento').show();
            $('#btnmodprof').show();
            //$('#select-cot').show();
        });
        $('#GestionFinanciamiento_entrada').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_entrada2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_entrada3').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_precio').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_precio2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});   
        $('#GestionFinanciamiento_precio3').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_valor_financiamiento').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_valor_financiamiento2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_valor_financiamiento3').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_precio_contado').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_precio_contado2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});   
        $('#GestionFinanciamiento_precio_contado3').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true}); 
        $('#GestionFinanciamiento_precio_contado_total').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_precio_contado_total2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});   
        $('#GestionFinanciamiento_precio_contado_total3').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true}); 
        $('#GestionFinanciamiento_seguro_contado').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_seguro_contado2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_seguro_contado3').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#valor_otro_accesorios1').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#valor_otro_accesorios2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#valor_otro_accesorios3').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#precio_accesorios2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#precio_normal').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_seguro').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_tasa').maskMoney({thousands: '.', decimal: ',', affixesStay: true});
        $('#GestionFinanciamiento_seguro2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_tasa2').maskMoney({thousands: '.', decimal: ',', affixesStay: true});
        $('#GestionFinanciamiento_seguro3').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_tasa3').maskMoney({thousands: '.', decimal: ',', affixesStay: true});
        $('#GestionFinanciamiento_cuota_mensual').maskMoney({prefix: '$ ',thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_cuota_mensual2').maskMoney({prefix: '$ ',thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_cuota_mensual3').maskMoney({prefix: '$ ',thousands: ',', decimal: '.', affixesStay: true});
        
        //$('#GestionFinanciamiento_valor_financiamiento').maskMoney({prefix:'$ ', allowNegative: true, thousands:',', decimal:'.', affixesStay: true});
        //$('#GestionFinanciamiento_cuota_mensual').maskMoney({prefix:'$ ', allowNegative: true, thousands:',', decimal:'.', affixesStay: true});

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
        
        $('#GestionFinanciamiento_tipo').change(function () {
            var valorFin = $('#GestionFinanciamiento_tipo').val();
            var idinfo = $('#GestionFinanciamiento_id_informacion').val();
            var idvehiculo = $('#GestionFinanciamiento_id_vehiculo').val();
            console.log('id informacion: ' + idinfo);
            if (valorFin == 'Contado') { // cambiar a contado
                // make a update to table gestion_consulta with value 0 in column preg6
                // refresh page
                $.ajax({
                    url: '<?php echo Yii::app()->createAbsoluteUrl("gestionConsulta/setFinanciamiento"); ?>',
                    type: 'POST',
                    data: {idInformacion: idinfo, tipo: 0, idVehiculo: idvehiculo},
                    success: function (data) {
                        $('#bg_negro').show();
                        location.reload();
                    }
                });
                //$('.cont-financ').hide();
                //$('.cont-contado').show();
            } else { // cambiar a credito
                // make a update to table gestion_consulta with value 1 in column preg6
                // refresh page
                $.ajax({
                    url: '<?php echo Yii::app()->createAbsoluteUrl("gestionConsulta/setFinanciamiento"); ?>',
                    type: 'POST',
                    data: {idInformacion: idinfo, tipo: 1, idVehiculo: idvehiculo},
                    success: function (data) {
                        $('#bg_negro').show();
                        location.reload();
                    }
                });
                //$('.cont-financ').show();
                //$('.cont-contado').hide();
            }
        });

        $("input[name='accesorios[]']").click(function () {
            var kit = $('.kit').val();
            var accesorio2 = $(this).val();
            var idacc = $(this).prop('id');
            var id = idacc.split("-");
            var precioanterior = formatnumber($('#precio_accesorios').val());
            var precioanterior2 = $('#precio_accesorios2').val();
            console.log('precio anterior2: '+precioanterior2);
            var valorFin = $('#GestionFinanciamiento_entrada').val();
            var valorFin2 = $('#GestionFinanciamiento_entrada2').val();
            var valorFin3 = $('#GestionFinanciamiento_entrada3').val();
            var counter = $('#options-cont').val();// valor del contador de formularios
            var flag = $('#GestionFinanciamiento_flag').val();// saber si se ha generado una proforma
            // valor del contador de proformas
            var savecounter = 0; // numero de formulario a editar original
            // si no se ha generado una proforma, continua el proceso normal
            
            // ya se ha generado una proforma
            if(flag == 1){
                savecounter = $('#options-cont').val();// guardamos el valor del contador de formularios
                // asignamos al contador de formularios el valor del click edit() del formulario
                counter = $('#GestionFinanciamiento_mod').val();
            }
            
            var tipoFinanciamiento = $('#GestionFinanciamiento_tipo_financiamiento').val();
            var accesorioscont = $('#accesorioscont').val();
            var stracc1 = '';var stracc2 = '';var stracc3 = '';
            
            if(kit && $('.kit').prop('checked') && counter == 2){
                
            }
            if ($(this).prop('checked')) {
                var precionuevo = parseInt(precioanterior2) + parseInt(accesorio2);
                console.log('precio nuevo: '+precionuevo);
                var accesorios_sum = parseInt(precioanterior) + parseInt(accesorio2);
                $('#precio_accesorios').val(format2(accesorios_sum, '$'));
                $('#precio_accesorios2').val(precionuevo);
                switch (counter) {
                    case '2':
                        acc1.length = 0;
                        //console.log('enter 2');
                        $('#GestionFinanciamiento_precio').val(format2(precionuevo, '$'));
                        $('#GestionFinanciamiento_precio_contado').val(format2(precionuevo, '$'));
                        //console.log('precio nuevo: '+precionuevo);
                        preciovec1 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado();
                        }else{
                           if (valorFin != '') {
                               calcFinanciamiento();
                           } 
                        }
                        
                        for (var i = 1; i <= accesorioscont; i++) {
                            if ($('#accesorio-' + i).prop('checked')) {
                                //console.log('Accesorio '+i+', checked');
                                acc1.push(i);
                                sat = $('#accesorio-' + i).val();
                                param = sat.split('-');
                                stracc1 += sat + '@';
                                if (param[1] != 'Kit Satelital') {
                                    //$('#accesorio-' + i).attr('checked', false);
                                    //$('#accspan-' + i).removeClass('label-price');
                                }
                            }
                        }
                        //console.log('string accesorios3: '+stracc3);
                        
                        $('#GestionFinanciamiento_acc1').val(stracc1);
                        console.log('ARRAY ACCESORIOS 1: '+acc1);
                        if(flag == 1){// si se ha generado una proforma
                            // volver a poner el valor del contador original
                            $('#options-cont').val(savecounter);
                        }
                        break;
                    case '3':
                        acc2.length = 0;
                        //console.log('enter chk');
                        $('#GestionFinanciamiento_precio2').val(format2(precionuevo, '$'));
                        $('#GestionFinanciamiento_precio_contado2').val(format2(precionuevo, '$'));
                        preciovec2 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado2();
                        }else{
                           if (valorFin2 != '') {
                               calcFinanciamiento2();
                           } 
                        }
                        for (var i = 1; i <= accesorioscont; i++) {
                            if ($('#accesorio-' + i).prop('checked')) {
                                //console.log('Accesorio '+i+', checked');
                                acc2.push(i);
                                sat = $('#accesorio-' + i).val();
                                param = sat.split('-');
                                stracc2 += sat + '@';
                                if (param[1] != 'Kit Satelital') {
                                    //$('#accesorio-' + i).attr('checked', false);
                                    //$('#accspan-' + i).removeClass('label-price');
                                }
                            }
                        }
                        //console.log('string accesorios3: '+stracc3);
                        $('#GestionFinanciamiento_acc2').val(stracc2);
                        console.log('ARRAY ACCESORIOS 2: '+acc2);
                        if(flag == 1){
                            // volver a poner el valor del contador original
                            $('#options-cont').val(savecounter);
                        }
                        break;
                    case '4':
                        acc3.length = 0;
                        $('#GestionFinanciamiento_precio3').val(format2(precionuevo, '$'));
                        $('#GestionFinanciamiento_precio_contado3').val(format2(precionuevo, '$'));
                        preciovec3 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado3();
                        }else{
                           if (valorFin3 != '') {
                               calcFinanciamiento3();
                           } 
                        }
                        for (var i = 1; i <= accesorioscont; i++) {
                            if ($('#accesorio-' + i).prop('checked')) {
                                //console.log('Accesorio '+i+', checked');
                                acc3.push(i);
                                sat = $('#accesorio-' + i).val();
                                param = sat.split('-');
                                stracc3 += sat + '@';
                                if (param[1] != 'Kit Satelital') {
                                    //$('#accesorio-' + i).attr('checked', false);
                                    //$('#accspan-' + i).removeClass('label-price');
                                }
                            }
                        }
                        //console.log('string accesorios3: '+stracc3);
                        $('#GestionFinanciamiento_acc3').val(stracc3);
                        console.log('ARRAY ACCESORIOS 3: '+acc3);
                        if(flag == 1){
                            // volver a poner el valor del contador original
                            $('#options-cont').val(savecounter);
                        }
                        break;
                }
                //$('#GestionFinanciamiento_precio_contado').val(format2(precionuevo, '$'));
                $('#accspan-' + id[1]).addClass('label-price');

            } else {
                var precionuevo = parseInt(precioanterior2) - parseInt(accesorio2);
                console.log('precio nuevo: '+precionuevo);
                var accesorios_sum = parseInt(precioanterior) - parseInt(accesorio2);
                
                $('#precio_accesorios').val(format2(accesorios_sum, '$'));
                $('#precio_accesorios2').val(precionuevo);
                switch (counter) {
                    case '2':
                        acc1.length = 0;
                        $('#GestionFinanciamiento_precio').val(format2(precionuevo, '$'));
                        $('#GestionFinanciamiento_precio_contado').val(format2(precionuevo, '$'));
                        preciovec1 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado();
                        }else{
                            if (valorFin != '') {
                                calcFinanciamiento();
                            }
                        }
                        /*for (var i = 1; i <= accesorioscont; i++) {
                            if ($('#accesorio-' + i).prop('checked')) {
                                //console.log('Accesorio '+i+', checked');
                                acc1.push(i);
                                sat = $('#accesorio-' + i).val();
                                param = sat.split('-');
                                stracc1 += sat + '@';
                                if (param[1] != 'Kit Satelital') {
                                    //$('#accesorio-' + i).attr('checked', false);
                                    //$('#accspan-' + i).removeClass('label-price');
                                }
                            }
                        }*/
                        //console.log('string accesorios3: '+stracc3);
                        $('#GestionFinanciamiento_acc1').val(stracc1);
                        console.log('ARRAY ACCESORIOS 1: '+acc1);
                        if(flag == 1){
                            // volver a poner el valor del contador original
                            $('#options-cont').val(savecounter);
                        }
                        break;
                    case '3':
                        acc2.length = 0;
                        //console.log('enter no check');
                        $('#GestionFinanciamiento_precio2').val(format2(precionuevo, '$'));
                        $('#GestionFinanciamiento_precio_contado2').val(format2(precionuevo, '$'));
                        preciovec2 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado2();
                        }else{
                            if (valorFin2 != '') {
                                calcFinanciamiento2();
                            }
                        }
                        
                        for (var i = 1; i <= accesorioscont; i++) {
                            if ($('#accesorio-' + i).prop('checked')) {
                                //console.log('Accesorio '+i+', checked');
                                acc2.push(i);
                                sat = $('#accesorio-' + i).val();
                                param = sat.split('-');
                                stracc2 += sat + '@';
                                //console.log('stringn 3: '+stracc2);
                                if (param[1] != 'Kit Satelital') {
                                    //$('#accesorio-' + i).attr('checked', false);
                                    //$('#accspan-' + i).removeClass('label-price');
                                }
                            }
                        }
                        $('#GestionFinanciamiento_acc2').val(stracc2);
                        console.log('ARRAY ACCESORIOS 2: '+acc2);
                        if(flag == 1){
                            // volver a poner el valor del contador original
                            $('#options-cont').val(savecounter);
                        }
                        break;
                    case '4':
                        acc3.length = 0;
                        $('#GestionFinanciamiento_precio3').val(format2(precionuevo, '$'));
                        $('#GestionFinanciamiento_precio_contado3').val(format2(precionuevo, '$'));
                        preciovec3 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado3();
                        }else{
                           if (valorFin3 != '') {
                                calcFinanciamiento3();
                            } 
                        }
                        
                        for (var i = 1; i <= accesorioscont; i++) {
                            if ($('#accesorio-' + i).prop('checked')) {
                                //console.log('Accesorio '+i+', checked');
                                acc3.push(i);
                                sat = $('#accesorio-' + i).val();
                                console.log(sat);
                                param = sat.split('-');
                                stracc3 += sat + '@';
                                if (param[1] != 'Kit Satelital') {
                                    //$('#accesorio-' + i).attr('checked', false);
                                    //$('#accspan-' + i).removeClass('label-price');
                                }
                            }
                        }
                        //console.log('string accesorios3: '+stracc3);
                        $('#GestionFinanciamiento_acc3').val(stracc3);
                        console.log('ARRAY ACCESORIOS 3: '+acc3);
                        if(flag == 1){
                            // volver a poner el valor del contador original
                            $('#options-cont').val(savecounter);
                        }
                        break;
                }
                //$('#GestionFinanciamiento_precio').val(format2(precionuevo, '$'));
                //$('#GestionFinanciamiento_precio2').val(format2(precionuevo, '$'));
                //$('#GestionFinanciamiento_precio3').val(format2(precionuevo, '$'));
                $('#GestionFinanciamiento_precio_contado').val(format2(precionuevo, '$'));
                $('#accspan-' + id[1]).removeClass('label-price');
            }
        });
        

    });
    function format2(n, currency) {
        return currency + " " + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    }
    
    function getvalortotal(num_acc){
        options_cont = $('#options-cont').val();
        if($('#cont-acc2').val() == 1 || $('#cont-acc3').val() == 1 || $('#cont-acc4').val() == 1){
            $('#btn-acc').prop('disabled', false);
        }
        switch(options_cont){
            case '2':
                precioaccesorios = formatnumber($('#precio_accesorios').val());
                var precioaccres = precioaccesorios;
                value = 0;
                str_acc_op = '';
                for(var i = 1; i <= 3 ; i++){
                    value += formatnumber($('#valor_otro_accesorios'+i).val());
                }
                value = format2(value, '$');
                $("#sum-accesorios2").val(value);
                $("#desc-accesorios2").val(str_acc_op);
                $('#sum-accesorios-val2').html(value);
                break;
            case '3':
                precioaccesorios = formatnumber($('#precio_accesorios').val());
                var precioaccres = precioaccesorios;
                value = 0;
                for(var i = 1; i <= 3 ; i++){
                    value += formatnumber($('#valor_otro_accesorios'+i).val());
                }
                value = format2(value, '$');
                $("#sum-accesorios3").val(value);
                //$("#desc-accesorios3").val($('#otro_accesorios_nombre1').val());
                $('#sum-accesorios-val2').html(value);
                break;
            case '4':
                precioaccesorios = formatnumber($('#precio_accesorios').val());
                var precioaccres = precioaccesorios;
                value = 0;
                for(var i = 1; i <= 3 ; i++){
                    value += formatnumber($('#valor_otro_accesorios'+i).val());
                }
                value = format2(value, '$');
                $("#sum-accesorios4").val(value);
                //$("#desc-accesorios4").val($('#otro_accesorios_nombre1').val());
                $('#sum-accesorios-val2').html(value);
                break;    
        }
        
        
            
    }
    function formatnumber(precioanterior) {
        console.log('format: '.precioanterior);
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
    function sco(){
        tipo_finan = $('#GestionFinanciamiento_tipo_financiamiento').val();
        options_cont = $('#options-cont').val();
        //console.log('options cont: '+options_cont);
        switch(options_cont){
            case '2':
                sumaccres = $('#sum-accesorios-res2').val();
                accmanuales = formatnumber($('#sum-accesorios2').val());
                //console.log('accmanuales: '+accmanuales);
                precioaccesorios = formatnumber($('#precio_accesorios').val());
                //console.log('precio accesorios: '+precioaccesorios);
                precioaccesorios2 = parseInt($('#precio_accesorios2').val());
                if(tipo_finan == 1){
                    precio = formatnumber($('#GestionFinanciamiento_precio').val());
                }else{
                    precio = formatnumber($('#GestionFinanciamiento_precio_contado').val());
                }
                
                if($('#cont-acc2').val() == 1){
                    sumaccres = formatnumber(sumaccres);
                    precioaccesorios = precioaccesorios - sumaccres;
                }  

                tacc = accmanuales + precioaccesorios;
                //precio_finan = precio + accmanuales;
                precio_finan = tacc + formatnumber($('#precio_normal').val());
                $('#precio_accesorios2').val(precio_finan);
                tacc = format2(tacc, '$');precio_finan = format2(precio_finan, '$');
                $("#precio_accesorios").val(tacc);
                
                $('#btn-accd').show();
                $('#cont-acc2').val(1);
                $('#btn-acc').prop('disabled',true);
                $('#sum-accesorios-res2').val($('#sum-accesorios2').val());
                if(tipo_finan == 1){
                    $('#GestionFinanciamiento_precio').val(precio_finan);
                }else{
                    $('#GestionFinanciamiento_precio_contado').val(precio_finan);
                }
                str_acc_op = '';
                str_acc_total = '';
                error = 0;
                for(var i = 1; i < $('#cont-otro2').val() ; i++){
                    if($('#otro_accesorios_nombre'+i).val() ==  ''){
                        $('.error-nombre'+i).show();$('#otro_accesorios_nombre'+i).focus();
                        error++;
                        return false;
                    }
                    if($('#valor_otro_accesorios'+i).val() ==  ''){
                        $('.error-accesorio'+i).show();$('#valor_otro_accesorios'+i).focus();
                        error++;
                        return false;
                    }
                    
                }
                for(var i = 1; i < $('#cont-otro2').val() ; i++){
                    // cadena de accesorios a mano, precio + nombre accesorio
                    str_acc_total += formatnumber($('#valor_otro_accesorios'+i).val())+'-'+$('#otro_accesorios_nombre'+i).val()+ '@';
                    str_acc_op += $('#otro_accesorios_nombre'+i).val() + '@';
                }
                
                $('#desc-accesorios2').val(str_acc_op);
                $('#sum-accesorios-total2').val(str_acc_total);
                accsum1 = tacc;
                console.log('ACC01: '+accsum1);
                // si la cuota de entrada no esta vacio 
                if($('#GestionFinanciamiento_entrada').val() != ''){
                    calcFinanciamiento();
                }
                
                break;
            case '3':
                console.log('enter sum cae 3');
                sumaccres = $('#sum-accesorios-res3').val();
                accmanuales = formatnumber($('#sum-accesorios3').val());
                precioaccesorios = formatnumber($('#precio_accesorios').val());
                precioaccesorios2 = parseInt($('#precio_accesorios3').val());
                if(tipo_finan == 1){
                    precio = formatnumber($('#GestionFinanciamiento_precio2').val());
                }else{
                    precio = formatnumber($('#GestionFinanciamiento_precio_contado2').val());
                }
                if($('#cont-acc3').val() == 1){
                    sumaccres = formatnumber(sumaccres);
                    precioaccesorios = precioaccesorios - sumaccres;
                }
                //console.log('enter sco');       

                tacc = accmanuales + precioaccesorios;
                //precio_finan = precio + accmanuales;
                precio_finan = tacc + formatnumber($('#precio_normal').val());
                //console.log('precio finan: '+precio_finan);
                $('#precio_accesorios2').val(precio_finan);
                tacc = format2(tacc, '$');precio_finan = format2(precio_finan, '$');
                //console.log('tacc: '+tacc);
                $("#precio_accesorios").val(tacc);
                
                $('#btn-accd').show();
                $('#cont-acc3').val(1);
                $('#sum-accesorios-res3').val($('#sum-accesorios3').val());
                if(tipo_finan == 1){
                    $('#GestionFinanciamiento_precio2').val(precio_finan);
                }else{
                    $('#GestionFinanciamiento_precio_contado2').val(precio_finan);
                }
                str_acc_op = '';
                str_acc_total = '';
                error = 0;
                for(var i = 1; i < $('#cont-otro3').val() ; i++){
                    if($('#otro_accesorios_nombre'+i).val() ==  ''){
                        $('.error-nombre'+i).show();$('#otro_accesorios_nombre'+i).focus();
                        error++;
                        return false;
                    }
                    if($('#valor_otro_accesorios'+i).val() ==  ''){
                        $('.error-accesorio'+i).show();$('#valor_otro_accesorios'+i).focus();
                        error++;
                        return false;
                    }
                    
                }
                for(var i = 1; i < $('#cont-otro3').val()  ; i++){
                    // cadena de accesorios a mano, precio + nombre accesorio
                    str_acc_total += formatnumber($('#valor_otro_accesorios'+i).val())+'-'+$('#otro_accesorios_nombre'+i).val()+ '@';
                    str_acc_op += $('#otro_accesorios_nombre'+i).val() + '@';
                }
                $('#desc-accesorios3').val(str_acc_op);
                $('#sum-accesorios-total3').val(str_acc_total);
                console.log('str_acc_op3: '+str_acc_op);
                accsum2 = tacc;
                // si la cuota de entrada no esta vacio 
                if($('#GestionFinanciamiento_entrada2').val() != ''){
                    calcFinanciamiento2();
                }
                
                console.log('ACC02: '+accsum2);
                $('#btn-acc').prop('disabled',true);
                break;
            case '4':
                sumaccres = $('#sum-accesorios-res4').val();
                accmanuales = formatnumber($('#sum-accesorios4').val());
                precioaccesorios = formatnumber($('#precio_accesorios').val());
                precioaccesorios2 = parseInt($('#precio_accesorios4').val());
                if(tipo_finan == 1){
                    precio = formatnumber($('#GestionFinanciamiento_precio3').val());
                }else{
                    precio = formatnumber($('#GestionFinanciamiento_precio_contado3').val())
                }
                if($('#cont-acc3').val() == 1){
                    sumaccres = formatnumber(sumaccres);
                    precioaccesorios = precioaccesorios - sumaccres;
                }
                //console.log('enter sco');       

                tacc = accmanuales + precioaccesorios;
                //precio_finan = precio + accmanuales;
                precio_finan = tacc + formatnumber($('#precio_normal').val());
                //console.log('precio finan: '+precio_finan);
                $('#precio_accesorios2').val(precio_finan);
                tacc = format2(tacc, '$');precio_finan = format2(precio_finan, '$');
                console.log('tacc: '+tacc);
                $("#precio_accesorios").val(tacc);
                $('#btn-accd').show();
                $('#cont-acc4').val(1);
                $('#sum-accesorios-res4').val($('#sum-accesorios4').val());
                if(tipo_finan == 1){
                    $('#GestionFinanciamiento_precio3').val(precio_finan);
                }else{
                    $('#GestionFinanciamiento_precio_contado3').val(precio_finan);
                }
                str_acc_op = '';
                str_acc_total = '';
                error = 0;
                for(var i = 1; i < $('#cont-otro4').val() ; i++){
                    if($('#otro_accesorios_nombre'+i).val() ==  ''){
                        $('.error-nombre'+i).show();$('#otro_accesorios_nombre'+i).focus();
                        error++;
                        return false;
                    }
                    if($('#valor_otro_accesorios'+i).val() ==  ''){
                        $('.error-accesorio'+i).show();$('#valor_otro_accesorios'+i).focus();
                        error++;
                        return false;
                    }
                    
                }
                for(var i = 1; i < $('#cont-otro4').val()  ; i++){
                    // cadena de accesorios a mano, precio + nombre accesorio
                    str_acc_total += formatnumber($('#valor_otro_accesorios'+i).val())+'-'+$('#otro_accesorios_nombre'+i).val()+ '@';
                    str_acc_op += $('#otro_accesorios_nombre'+i).val() + '@';
                }
                $('#desc-accesorios4').val(str_acc_op);
                $('#sum-accesorios-total4').val(str_acc_total);
                accsum3 = tacc;
                // si la cuota de entrada no esta vacio 
                if($('#GestionFinanciamiento_entrada3').val() != ''){
                    calcFinanciamiento3();
                }
                $('#btn-acc').prop('disabled',true);
                console.log('ACC03: '+accsum3);
                break;    
        }
        
        
    }
    
    function scod(){
        tipo_finan = $('#GestionFinanciamiento_tipo_financiamiento').val();
        options_cont = $('#options-cont').val();
        $('.error-nombre1').hide();$('.error-nombre2').hide();$('.error-nombre3').hide();
        $('.error-accesorio1').hide();$('.error-accesorio2').hide();$('.error-accesorio3').hide();
        switch(options_cont){
            case '2':
                sumaccres = formatnumber($('#sum-accesorios-res2').val());
                accmanuales = formatnumber($('#sum-accesorios2').val());
                console.log('accmanuales: '+accmanuales);
                precioaccesorios = formatnumber($('#precio_accesorios').val());
                console.log('precio accesorios: '+precioaccesorios);
                precioaccesorios2 = parseInt($('#precio_accesorios2').val());
                if(tipo_finan == 1){
                    precio = formatnumber($('#GestionFinanciamiento_precio').val());
                }else{
                    precio = formatnumber($('#GestionFinanciamiento_precio_contado').val());
                }
                
                if($('#cont-acc2').val() == 1){
                    precioaccesorios = precioaccesorios - sumaccres;
                }
                tacc = 0;
                precio_finan = precio - accmanuales;
                console.log('precio finan: '+precio_finan);
                $('#precio_accesorios2').val(precio_finan);
                tacc = format2(tacc, '$');precio_finan = format2(precio_finan, '$');
                $("#precio_accesorios").val(format2(precioaccesorios,'$'));
                $('#btn-accd').hide();
                $('#cont-acc2').val(1);
                $('#sum-accesorios-res2').val('');
                $('#sum-accesorios2').val('');
                if(tipo_finan == 1){
                    $('#GestionFinanciamiento_precio').val(precio_finan);
                }else{
                    $('#GestionFinanciamiento_precio_contado').val(precio_finan);
                }
                for(var i = 1; i < $('#cont-otro2').val() ; i++){
                    $('#valor_otro_accesorios'+i).val('');
                    $('#otro_accesorios_nombre'+i).val('');
                }
                $('#sum-accesorios-val2').html('');
                $('#desc-accesorios2').val('');
                $('#sum-accesorios-total2').val('');
                accsum1 = tacc;
                // si la cuota de entrada no esta vacio 
                if($('#GestionFinanciamiento_entrada').val() != ''){
                    calcFinanciamiento();
                }
                
                console.log('ACC01: '+accsum1);
            break;
            case '3':
                sumaccres = formatnumber($('#sum-accesorios-res3').val());
                accmanuales = formatnumber($('#sum-accesorios3').val());
                precioaccesorios = formatnumber($('#precio_accesorios').val());
                precioaccesorios2 = parseInt($('#precio_accesorios3').val());
                if(tipo_finan == 1){
                    precio = formatnumber($('#GestionFinanciamiento_precio2').val());
                }else{
                    precio = formatnumber($('#GestionFinanciamiento_precio_contado2').val());
                }
                
                if($('#cont-acc3').val() == 1){
                    precioaccesorios = precioaccesorios - sumaccres;
                }
                tacc = 0;
                precio_finan = precio - accmanuales;
                console.log('precio finan: '+precio_finan);
                $('#precio_accesorios2').val(precio_finan);
                tacc = format2(tacc, '$');precio_finan = format2(precio_finan, '$');
                $("#precio_accesorios").val(format2(precioaccesorios,'$'));
                $('#btn-accd').hide();
                $('#cont-acc3').val(1);
                $('#sum-accesorios-res3').val('');
                $('#sum-accesorios3').val('');
                if(tipo_finan == 1){
                    $('#GestionFinanciamiento_precio2').val(precio_finan);
                }else{
                    $('#GestionFinanciamiento_precio_contado2').val(precio_finan);
                }
                for(var i = 1; i < $('#cont-otro3').val() ; i++){
                    $('#valor_otro_accesorios'+i).val('');
                    $('#otro_accesorios_nombre'+i).val('');
                }
                $('#sum-accesorios-val2').html('');
                $('#desc-accesorios3').val('');
                $('#sum-accesorios-total3').val('');
                accsum2 = tacc;
                // si la cuota de entrada no esta vacio 
                if($('#GestionFinanciamiento_entrada2').val() != ''){
                    calcFinanciamiento2();
                }
                break;
            case '4':
                sumaccres = formatnumber($('#sum-accesorios-res4').val());
                accmanuales = formatnumber($('#sum-accesorios4').val());
                precioaccesorios = formatnumber($('#precio_accesorios').val());
                precioaccesorios2 = parseInt($('#precio_accesorios4').val());
                if(tipo_finan == 1){
                    precio = formatnumber($('#GestionFinanciamiento_precio3').val());
                }else{
                    precio = formatnumber($('#GestionFinanciamiento_precio_contado3').val());
                }
                
                if($('#cont-acc4').val() == 1){
                    precioaccesorios = precioaccesorios - sumaccres;
                }
                tacc = 0;
                precio_finan = precio - accmanuales;
                console.log('precio finan: '+precio_finan);
                $('#precio_accesorios2').val(precio_finan);
                tacc = format2(tacc, '$');precio_finan = format2(precio_finan, '$');
                $("#precio_accesorios").val(format2(precioaccesorios,'$'));
                $('#btn-accd').hide();
                $('#cont-acc4').val(1);
                $('#sum-accesorios-res4').val('');
                $('#sum-accesorios4').val('');
                if(tipo_finan == 1){
                    $('#GestionFinanciamiento_precio3').val(precio_finan);
                }else{
                    $('#GestionFinanciamiento_precio_contado3').val(precio_finan);
                }
                for(var i = 1; i < $('#cont-otro4').val() ; i++){
                    $('#valor_otro_accesorios'+i).val('');
                    $('#otro_accesorios_nombre'+i).val('');
                }
                $('#sum-accesorios-val2').html('');
                $('#desc-accesorios4').val('');
                $('#sum-accesorios-total4').val('');
                accsum3 = tacc;
                // si la cuota de entrada no esta vacio 
                if($('#GestionFinanciamiento_entrada3').val() != ''){
                    calcFinanciamiento3();
                }
                break;    
        }
    }
    
    function ot(){
        options_cont = $('#options-cont').val();
        cont_otro = $('#cont-otro').val();
        cotr = $('#options-cont-otro').val();
        if (cotr == 3) {
            $('#btn-ot').removeClass('btn-success').prop( "disabled", true );
        }
        $('.btn-canc-otro').show();
        $('.cont-opt-acc' + cotr).show();
        if (cotr <= 4) {
            cotr++;
            $('#options-cont-otro').val(cotr);
            switch(options_cont){
                case '2':
                    $('#cont-otro2').val(cotr);
                break;
                case '3':
                    $('#cont-otro3').val(cotr);
                break;
                case '4':
                    $('#cont-otro4').val(cotr);
                break;

            }
        }
    }
    function otcanc(){
        options_cont = $('#options-cont').val();
        cont_otro = $('#cont-otro').val();
        cotr = $('#options-cont-otro').val();
        if (cotr != 3) {
            $('#btn-ot').removeClass('btn-danger').addClass('btn-success').prop( "disabled", false );
        }
        cotr--;
        if (cotr > 1) {
            $('.cont-opt-acc' + cotr).hide();
            $('#options-cont-otro').val(cotr);
            switch(options_cont){
                case '2':
                    $('#cont-otro2').val(cotr);
                break;
                case '3':
                    $('#cont-otro3').val(cotr);
                break;
                case '4':
                    $('#cont-otro4').val(cotr);
                break;

            }
        }
        if ($('#options-cont-otro').val() == 2) {
            $('.btn-canc-otro').hide();
        }
    }
    function op(){
        //acc1.length = 0;
        //acc2.length = 0;
        //acc3.length = 0;
        console.log('enter op');
        var counter = $('#options-cont').val();
        var accesorioscont = $('#accesorioscont').val();
        var precionormal = $('#precio_normal').val();
        var flag = $('#GestionFinanciamiento_flag').val();
        var tipoFinanc = $('#GestionFinanciamiento_tipo_financiamiento').val();
        var stracc1 = '';
        var stracc2 = '';
        var stracc3 = '';
        console.log('counter: '+counter);
        switch (counter) {
            case '2':
                $('#btn-accd').hide();
                $('#total-acc1').val($('#precio_accesorios').val());
                $('#precio_accesorios2').val(formatnumber(precionormal));
                console.log('enter case 2');
                cont_otro = $('#cont-otro3').val();
                for (i = 1; i < 4; i++) {
                    $('#otro_accesorios_nombre'+i).val('');
                    $('#valor_otro_accesorios'+i).val('');
                    if(i < cont_otro){
                        $('.cont-opt-acc'+i).show();
                    }else{
                        $('.cont-opt-acc'+i).hide();
                    }
                }
                cont_otro_n = $('#cont-otro3').val();
                if(cont_otro_n == 2){
                    $('#btn-ot').prop('disabled', false);$('.btn-canc-otro').hide();
                    $('#btn-ot').addClass('btn-success');$('#options-cont-otro').val(2);
                }
                acc1.length = 0;
                console.log('accsum2: '+accsum2);
                if(accsum2 == 0){
                    $('#precio_accesorios').val(format2(parseInt(accsum2),'$'));
                }else{
                    $('#precio_accesorios').val(accsum2);
                }
                
                if(flag == 0){ // Se llena por primera vez
                    for (var i = 1; i <= accesorioscont; i++) {
                        if ($('#accesorio-' + i).prop('checked')) {
                            console.log('Accesorio checked '+i+', checked');
                            acc1.push(i);
                            sat = $('#accesorio-' + i).val();
                            param = sat.split('-');
                            stracc1 += sat + '@';
                            //if (param[1] != 'Kit Satelital') {
                                //console.log('enter kit1');
                                $('#accesorio-' + i).attr('checked', false);
                                $('#accspan-' + i).removeClass('label-price');
                            //}
                            if(tipoFinanc ==  0 && param[1] == 'Kit Satelital'){
                                $('#accesorio-' + i).attr('checked', false);
                                $('#accspan-' + i).removeClass('label-price');
                            }
                        }
                    }
                    $('#GestionFinanciamiento_acc1').val(stracc1);
                }
                $('#GestionFinanciamiento_precio').attr('disabled', true);
                $('#GestionFinanciamiento_entrada').attr('disabled', true);
                $('#GestionFinanciamiento_tiempo_seguro').attr('disabled', true);
                $('#GestionFinanciamiento_plazo').attr('disabled', true);
                $('#valor_otro_accesorios1').val('');$('#sum-accesorios-val2').html('');$('#otro_accesorios_nombre1').val('');
                
                console.log('array accesorios op 1: '+acc1);
                break;
            case '3':
                $('#btn-accd').hide();
                $('#total-acc2').val($('#precio_accesorios').val());
                $('#precio_accesorios2').val(formatnumber(precionormal));
                console.log('enter case 3');
                cont_otro = $('#cont-otro4').val();
                for (i = 1; i < 4; i++) {
                    $('#otro_accesorios_nombre'+i).val('');
                    $('#valor_otro_accesorios'+i).val('');
                    if(i < cont_otro){
                        $('.cont-opt-acc'+i).show();
                    }else{
                        $('.cont-opt-acc'+i).hide();
                    }
                }
                cont_otro_n = $('#cont-otro4').val();
                if(cont_otro_n == 2){
                    $('#btn-ot').prop('disabled', false);$('.btn-canc-otro').hide();
                    $('#btn-ot').addClass('btn-success');$('#options-cont-otro').val(2);
                }
                acc2.length = 0;
                console.log('ACCSUM3: '+accsum3);
                if(accsum3 == 0){
                    $('#precio_accesorios').val(format2(parseInt(accsum3),'$'));
                }else{
                    $('#precio_accesorios').val(accsum3);
                }
                
                //console.log('llenar array de accesorios 2');
                if(flag == 0){ // Se llena por primera vez
                    for (var i = 1; i <= accesorioscont; i++) {
                        if ($('#accesorio-' + i).prop('checked')) {
                            //console.log('Accesorio '+i+', checked');
                            acc2.push(i);
                            sat = $('#accesorio-' + i).val();
                            param = sat.split('-');
                            stracc2 += sat + '@';
                            //if (param[1] != 'Kit Satelital') {
                                $('#accesorio-' + i).attr('checked', false);
                                $('#accspan-' + i).removeClass('label-price');
                            //}
                            if(tipoFinanc ==  0 && param[1] == 'Kit Satelital'){
                                $('#accesorio-' + i).attr('checked', false);
                                $('#accspan-' + i).removeClass('label-price');
                            }
                        }
                    }
                }
                $('#GestionFinanciamiento_acc2').val(stracc2);
                $('#GestionFinanciamiento_precio2').attr('disabled', true);
                $('#GestionFinanciamiento_entrada2').attr('disabled', true);
                $('#GestionFinanciamiento_tiempo_seguro2').attr('disabled', true);
                $('#GestionFinanciamiento_plazo2').attr('disabled', true);
                $('#valor_otro_accesorios1').val('');$('#sum-accesorios-val2').html('');$('#otro_accesorios_nombre1').val('');
                
                console.log('array accesorios op 2: '+acc2);
                break;
            case '4':
                $('#btn-accd').hide();
                $('#total-acc3').val($('#precio_accesorios').val());
                $('#precio_accesorios2').val(formatnumber(precionormal));
                acc3.length = 0;
                console.log('enter case 4');
                cont_otro = $('#cont-otro4').val();
                for (i = 1; i < cont_otro; i++) { 
                    $('#otro_accesorios_nombre'+i).val('');
                    $('#valor_otro_accesorios'+i).val('');
                    $('.cont-opt-acc'+i).show();
                }
                cont_otro_n = $('#cont-otro4').val();
                if(cont_otro_n == 2){
                    $('#btn-ot').prop('disabled', false);$('.btn-canc-otro').hide();
                    $('#btn-ot').addClass('btn-success');$('#options-cont-otro').val(2);
                }
                if(flag == 0){ // Se llena por primera vez
                    for (var i = 1; i <= accesorioscont; i++) {
                        if ($('#accesorio-' + i).prop('checked')) {
                            //console.log('Accesorio '+i+', checked');
                            acc3.push(i);
                            sat = $('#accesorio-' + i).val();
                            param = sat.split('-');
                            stracc3 += sat + '@';
                            //if (param[1] != 'Kit Satelital') {
                                $('#accesorio-' + i).attr('checked', false);
                                $('#accspan-' + i).removeClass('label-price');
                            //}
                            if(tipoFinanc ==  0 && param[1] == 'Kit Satelital'){
                                $('#accesorio-' + i).attr('checked', false);
                                $('#accspan-' + i).removeClass('label-price');
                            }
                        }
                    }
                    $('#GestionFinanciamiento_acc3').val(stracc3);
                    $('#valor_otro_accesorios1').val('');$('#sum-accesorios-val2').html('');$('#otro_accesorios_nombre1').val('');
                    
                    console.log('array accesorios op 3: '+acc3);
                }
                break;
        }
        if (counter == 3) {
            $('#btn-opt').removeClass('btn-success').addClass('btn-danger');
        }
        $('.btn-canc').show();
        $('.cont-options' + counter).show();
        if (counter <= 3) {
            counter++;
            $('#options-cont').val(counter);
        }
    }
    function opcanc() {
        //console.log('accesorios1 opcanc: ' + acc1);
        //console.log('accesorios2 opcanc: ' + acc2);
        //console.log('accesorios3 opcanc: ' + acc3);
        var counter = $('#options-cont').val();
        var accesorioscont = $('#accesorioscont').val();
        var precionormal = $('#precio_normal').val();
        var tipoFinanc = $('#GestionFinanciamiento_tipo_financiamiento').val();
        var flag = $('#GestionFinanciamiento_flag').val();
        //console.log('COUNTER OP CANC: ' + counter);
        switch (counter) {
            case '3': // volcar datos al primer cotizador, checks 
                // vaciar los campos de los accesorios manuales con su descripcion

                cont_otro = $('#cont-otro2').val();
                for (i = 1; i < 4; i++) {
                    
                    if(i < cont_otro){
                        $('.cont-opt-acc'+i).show();
                    }else{
                        $('.cont-opt-acc'+i).hide();
                    }
                }
                $('#GestionFinanciamiento_precio').attr('disabled', false);
                $('#GestionFinanciamiento_entrada').attr('disabled', false);
                $('#GestionFinanciamiento_tiempo_seguro').attr('disabled', false);
                $('#GestionFinanciamiento_plazo').attr('disabled', false);
                for (var j = 1; j <= accesorioscont; j++) {
                    sat = $('#accesorio-' + j).val();
                    param = sat.split('-');
                    if (param[1] != 'Kit Satelital') {
                        //console.log('enter checked false: '+ j);
                        $('#accesorio-' + j).attr('checked', false);
                        $('#accspan-' + j).removeClass('label-price');
                    }
                    if(tipoFinanc ==  0 && param[1] == 'Kit Satelital'){
                        $('#accesorio-' + j).attr('checked', false);
                        $('#accspan-' + j).removeClass('label-price');
                    }
                }
                console.log('array accesorios cancelar 1: '+acc1.toString());
                var lt = acc1.length;
                console.log('long of acc1: '+lt);
                for (var i = 0; i <= lt; i++) {
                    $('#accesorio-' + acc1[i]).attr('checked', true);
                    $('#accspan-' + acc1[i]).addClass('label-price');
                }
                
                //acc1.length = 0;
                if(tipoFinanc == 1){
                    finaciamiento = formatnumber($('#GestionFinanciamiento_precio').val()) - formatnumber($('#precio_normal').val());
                    $('#precio_accesorios').val(format2(finaciamiento, '$'));
                    $('#valor_otro_accesorios1').val(accsum1);$('#sum-accesorios-val2').html(accsum1);
                }else{
                    finaciamiento = formatnumber($('#GestionFinanciamiento_precio_contado').val()) - formatnumber($('#precio_normal').val());
                    $('#precio_accesorios').val(format2(finaciamiento, '$'));
                }
                
                break;
            case '4':// volcar datos al segundo cotizador, checks 
                cont_otro = $('#cont-otro3').val();
                for (i = 1; i < 4; i++) {
                    if(i < cont_otro){
                        $('.cont-opt-acc'+i).show();
                    }else{
                        $('.cont-opt-acc'+i).hide();
                    }
                }
                $('#GestionFinanciamiento_precio2').attr('disabled', false);
                $('#GestionFinanciamiento_entrada2').attr('disabled', false);
                $('#GestionFinanciamiento_tiempo_seguro2').attr('disabled', false);
                $('#GestionFinanciamiento_plazo2').attr('disabled', false);
                for (var j = 1; j <= accesorioscont; j++) {
                    sat = $('#accesorio-' + j).val();
                    param = sat.split('-');
                    if (param[1] != 'Kit Satelital') {
                        $('#accesorio-' + j).attr('checked', false);
                        $('#accspan-' + j).removeClass('label-price');
                    }
                    if(tipoFinanc ==  0 && param[1] == 'Kit Satelital'){
                        $('#accesorio-' + j).attr('checked', false);
                        $('#accspan-' + j).removeClass('label-price');
                    }
                }
                console.log('array accesorios cancelar 2: '+acc2.toString());
                var lt = acc2.length;
                for (var i = 0; i <= lt; i++) {
                    $('#accesorio-' + acc2[i]).attr('checked', true);
                    $('#accspan-' + acc2[i]).addClass('label-price');
                }
                //acc2.length = 0;
                
                if(tipoFinanc == 1){
                    finaciamiento = formatnumber($('#GestionFinanciamiento_precio2').val()) - formatnumber($('#precio_normal').val());
                    console.log('acc02: '+accsum2);
                    $('#precio_accesorios').val(format2(finaciamiento, '$'));
                    $('#valor_otro_accesorios1').val(accsum2);$('#sum-accesorios-val2').html(accsum2);
                }else{
                    finaciamiento = formatnumber($('#GestionFinanciamiento_precio_contado2').val()) - formatnumber($('#precio_normal').val());
                    $('#precio_accesorios').val(format2(finaciamiento, '$'));
                }
                
                break;
        }
        if (counter != 3) {
            $('#btn-opt').removeClass('btn-danger').addClass('btn-success');
        }
        counter--;
        if (counter > 1) {
            $('.cont-options' + counter).hide();
            $('#options-cont').val(counter);
        }
        if ($('#options-cont').val() == 2) {
            $('.btn-canc').hide();
        }
    }

    function modProforma() {
        var stracc1;
        var stracc2;
        var stracc3;
        var accesorioscont = $('#accesorioscont').val();
        //var numCot = $('#numero_cotizaciones').val();
        //alert(numCot);
        //if(numCot == '0'){
        //    alert('Debe seleccionar el número de cotizaciones');
        //    return false;
        //}
        //$('#options-cont').val(numCot);
        var optionscont = $('#options-cont').val();
        switch (optionscont) {
            case '2':
//                for (var i = 1; i <= accesorioscont; i++) {
//                    if ($('#accesorio-' + i).prop('checked')) {
//                        //console.log('Accesorio '+i+', checked');
//                        sat = $('#accesorio-' + i).val();
//                        stracc1 += sat + '@';
//                    } else {
//                        stracc1 = '';
//                    }
//                }
                $('#GestionFinanciamiento_valor_financiamiento').removeAttr('disabled');
                $('#GestionFinanciamiento_entrada').removeAttr('disabled');
                $('#GestionFinanciamiento_precio').removeAttr('disabled');
                $('#GestionFinanciamiento_tasa').removeAttr('disabled');
                //$('#GestionFinanciamiento_plazo').removeAttr('disabled');
                $('#GestionFinanciamiento_seguro').removeAttr('disabled');
                $('#GestionFinanciamiento_cuota_mensual').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro').removeAttr('disabled');
                if ($('#GestionFinanciamiento_tipo_financiamiento').val() == 1)
                    $('.def').removeAttr('disabled');
                //$('#GestionFinanciamiento_acc1').val(stracc1);
                break;
            case '3':
                console.log('enter case3');

                $('#GestionFinanciamiento_valor_financiamiento2').removeAttr('disabled');
                $('#GestionFinanciamiento_entrada').removeAttr('disabled');
                $('#GestionFinanciamiento_entrada2').removeAttr('disabled');
                $('#GestionFinanciamiento_precio').removeAttr('disabled');
                $('#GestionFinanciamiento_precio2').removeAttr('disabled');
                $('#GestionFinanciamiento_tasa').removeAttr('disabled');
                $('#GestionFinanciamiento_tasa2').removeAttr('disabled');
                //$('#GestionFinanciamiento_plazo').removeAttr('disabled');
                $('#GestionFinanciamiento_seguro').removeAttr('disabled');
                $('#GestionFinanciamiento_cuota_mensual').removeAttr('disabled');
                $('#GestionFinanciamiento_seguro2').removeAttr('disabled');
                $('#GestionFinanciamiento_cuota_mensual2').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo2').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro2').removeAttr('disabled');
                //$('#GestionFinanciamiento_acc2').val(stracc2);
                break;
            case '4':

                $('#GestionFinanciamiento_valor_financiamiento3').removeAttr('disabled');
                $('#GestionFinanciamiento_entrada').removeAttr('disabled');
                $('#GestionFinanciamiento_entrada2').removeAttr('disabled');
                $('#GestionFinanciamiento_entrada3').removeAttr('disabled');
                $('#GestionFinanciamiento_precio').removeAttr('disabled');
                $('#GestionFinanciamiento_precio2').removeAttr('disabled');
                $('#GestionFinanciamiento_precio3').removeAttr('disabled');
                $('#GestionFinanciamiento_tasa').removeAttr('disabled');
                $('#GestionFinanciamiento_tasa2').removeAttr('disabled');
                $('#GestionFinanciamiento_tasa3').removeAttr('disabled');
                //$('#GestionFinanciamiento_plazo').removeAttr('disabled');
                $('#GestionFinanciamiento_seguro').removeAttr('disabled');
                $('#GestionFinanciamiento_cuota_mensual').removeAttr('disabled');
                $('#GestionFinanciamiento_seguro2').removeAttr('disabled');
                $('#GestionFinanciamiento_cuota_mensual2').removeAttr('disabled');
                $('#GestionFinanciamiento_seguro3').removeAttr('disabled');
                $('#GestionFinanciamiento_cuota_mensual3').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo2').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo3').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro2').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro3').removeAttr('disabled');
                //$('#GestionFinanciamiento_acc3').val(stracc3);
                break;
        }
        var dataform = $("#gestion-negociacion-form").serialize();
        var optionscont = $('#options-cont').val();
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionFinanciamiento/update"); ?>',
            beforeSend: function (xhr) {
                $('#bg_negro').show();  // #bg_negro must be defined somewhere
            },
            datatype: "json",
            type: 'POST',
            data: dataform,
            success: function (data) {
                var returnedData = JSON.parse(data);
                $('#bg_negro').hide();
                alert('Datos actualizados correctamente');
                $("#btnverprf").show();
            }
        });
    }
    function sendProforma() {
        var idInfo = $('#GestionFinanciamiento_id_informacion').val();
        var idVec = $('#GestionFinanciamiento_id_vehiculo').val();
        var fuente = $('#fuente').val();
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/sendProforma"); ?>',
            beforeSend: function (xhr) {
                $('#bg_negro').show();  // #bg_negro must be defined somewhere
            },
            datatype: "json",
            type: 'POST',
            data: {id_informacion: idInfo, id_vehiculo: idVec, fuente: fuente},
            success: function (data) {
                if(fuente == 'web'){
                    alert('Email enviado satisfactoriamente al cliente, el seguimiento se debera realizar en un plazo máximo de 48 horas');
                }else{
                    alert('Email enviado satisfactoriamente');
                }
                
                $('#btnsendprof').hide();//$('#btnagendamiento').hide();
                if(fuente == 'web'){
                    // redireccionar al RGD
                    urld = '<?php echo Yii::app()->createAbsoluteUrl("gestionInformacion/seguimiento"); ?>';
                    $(location).attr('href', urld);
                }
                $('#bg_negro').hide();
            }
        });
        
    }
    function send() {
    //console.log('accesorios1: ' + acc1);
    //console.log('accesorios2: ' + acc2);
    //console.log('accesorios3: ' + acc3);
        if (confirm('Está seguro de generar la proforma?')) { 
            
        var optionscont = $('#options-cont').val();
        $('#gestion-negociacion-form').validate({
            rules: {
                'GestionFinanciamiento1[precio]': {
                    required: true
                }, 'GestionFinanciamiento1[tasa]': {
                    required: true
                }, 'GestionFinanciamiento1[plazo]': {
                    required: true
                }, 
                'GestionFinanciamiento1[seguro]': {
                    required: true
                },
                'GestionFinanciamiento1[couta_mensual]': {
                    required: true
                }, 'GestionFinanciamiento1[valor_financiamiento]': {
                    required: true
                }, 'GestionFinanciamiento1[entrada]': {
                    required: true
                }
            },
            messages: {
                'GestionFinanciamiento1[precio]': {
                    required: 'Ingrese precio'
                }, 'GestionFinanciamiento1[tasa]': {
                    required: 'Ingrese tasa'
                }, 'GestionFinanciamiento1[plazo]': {
                    required: 'Ingrese plazo'
                }
                , 'GestionFinanciamiento1[seguro]': {
                    required: 'Ingrese seguro'
                }
                , 'GestionFinanciamiento1[couta_mensual]': {
                    required: 'Ingrese cuota mensual'
                }, 'GestionFinanciamiento1[valor_financiamiento]': {
                    required: 'Ingrese valor financiamiento'
                }, 'GestionFinanciamiento1[entrada]': {
                    required: 'Ingrese el valor de la entrada'
                }
            },
            submitHandler: function (form) {
                
                var stracc1;
                var stracc2;
                var stracc3;
                var accesorioscont = $('#accesorioscont').val();
                switch (optionscont) {
                    case '2':
                        $('#GestionFinanciamiento_valor_financiamiento').removeAttr('disabled');
                        $('#GestionFinanciamiento_entrada').removeAttr('disabled');
                        $('#GestionFinanciamiento_precio').removeAttr('disabled');
                        $('#GestionFinanciamiento_tasa').removeAttr('disabled');
                        //$('#GestionFinanciamiento_plazo').removeAttr('disabled');
                        //$('#GestionFinanciamiento_seguro').removeAttr('disabled');
                        $('#GestionFinanciamiento_cuota_mensual').removeAttr('disabled');
                        $('#GestionFinanciamiento_plazo').removeAttr('disabled');
                        $('#GestionFinanciamiento_tiempo_seguro').removeAttr('disabled');
                        if ($('#GestionFinanciamiento_tipo_financiamiento').val() == 1)
                            $('.def').removeAttr('disabled');
                        $('#total-acc1').val($('#precio_accesorios').val());
                        //$('#GestionFinanciamiento_acc1').val(stracc1);
                        break;
                    case '3':
                        $('#GestionFinanciamiento_valor_financiamiento2').removeAttr('disabled');
                        $('#GestionFinanciamiento_entrada').removeAttr('disabled');
                        $('#GestionFinanciamiento_entrada2').removeAttr('disabled');
                        $('#GestionFinanciamiento_precio').removeAttr('disabled');
                        $('#GestionFinanciamiento_precio2').removeAttr('disabled');
                        $('#GestionFinanciamiento_tasa').removeAttr('disabled');
                        $('#GestionFinanciamiento_tasa2').removeAttr('disabled');
                        //$('#GestionFinanciamiento_plazo').removeAttr('disabled');
                        $('#GestionFinanciamiento_seguro').removeAttr('disabled');
                        $('#GestionFinanciamiento_cuota_mensual').removeAttr('disabled');
                        $('#GestionFinanciamiento_seguro2').removeAttr('disabled');
                        $('#GestionFinanciamiento_cuota_mensual2').removeAttr('disabled');
                        $('#GestionFinanciamiento_plazo').removeAttr('disabled');
                        $('#GestionFinanciamiento_plazo2').removeAttr('disabled');
                        $('#GestionFinanciamiento_tiempo_seguro').removeAttr('disabled');
                        $('#GestionFinanciamiento_tiempo_seguro2').removeAttr('disabled');
                        $('#total-acc2').val($('#precio_accesorios').val());
                        //$('#GestionFinanciamiento_acc2').val(stracc2);
                        break;
                    case '4':
                        $('#GestionFinanciamiento_valor_financiamiento3').removeAttr('disabled');
                        $('#GestionFinanciamiento_entrada').removeAttr('disabled');
                        $('#GestionFinanciamiento_entrada2').removeAttr('disabled');
                        $('#GestionFinanciamiento_entrada3').removeAttr('disabled');
                        $('#GestionFinanciamiento_precio').removeAttr('disabled');
                        $('#GestionFinanciamiento_precio2').removeAttr('disabled');
                        $('#GestionFinanciamiento_precio3').removeAttr('disabled');
                        $('#GestionFinanciamiento_tasa').removeAttr('disabled');
                        $('#GestionFinanciamiento_tasa2').removeAttr('disabled');
                        $('#GestionFinanciamiento_tasa3').removeAttr('disabled');
                        //$('#GestionFinanciamiento_plazo').removeAttr('disabled');
                        $('#GestionFinanciamiento_seguro').removeAttr('disabled');
                        $('#GestionFinanciamiento_cuota_mensual').removeAttr('disabled');
                        $('#GestionFinanciamiento_seguro2').removeAttr('disabled');
                        $('#GestionFinanciamiento_cuota_mensual2').removeAttr('disabled');
                        $('#GestionFinanciamiento_seguro3').removeAttr('disabled');
                        $('#GestionFinanciamiento_cuota_mensual3').removeAttr('disabled');
                        $('#GestionFinanciamiento_plazo').removeAttr('disabled');
                        $('#GestionFinanciamiento_plazo2').removeAttr('disabled');
                        $('#GestionFinanciamiento_plazo3').removeAttr('disabled');
                        $('#GestionFinanciamiento_tiempo_seguro').removeAttr('disabled');
                        $('#GestionFinanciamiento_tiempo_seguro2').removeAttr('disabled');
                        $('#GestionFinanciamiento_tiempo_seguro3').removeAttr('disabled');
                        $('#total-acc3').val($('#precio_accesorios').val());
                        //$('#GestionFinanciamiento_acc3').val(stracc3);
                        break;
                }
                var dataform = $("#gestion-negociacion-form").serialize();
                var valorEntrada1 = $('#GestionFinanciamiento_entrada').val();
                var precioAccesorios = $('#precio_accesorios').val();

                var entrada = precioAccesorios / 5;
                if (valorEntrada1 < entrada) {
                    $('.error-entrada').show();
                    return false;
                } else {
                    $('.error-entrada').hide();
                }
                //console.log('before false');
                //return false;
                $.ajax({
                    url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/negociacionAjax"); ?>',
                    beforeSend: function (xhr) {
                        $('#bg_negro').show();  // #bg_negro must be defined somewhere
                    },
                    datatype: "json",
                    type: 'POST',
                    data: dataform,
                    success: function (data) {
                        var returnedData = JSON.parse(data);
                        var tipoFinanciamiento = $('#GestionFinanciamiento_tipo').val();

                        //alert(returnedData.result);
                        $('#bg_negro').hide();
                        $('#finalizar').hide();
                        $('#generatepdf').show();
                        $('#btnsendprof').show();
                        $('#GestionFinanciamiento_flag').val(1);
                        console.log('ARRAY ACCESORIOS1 LUEGO DE GRABAR: '+acc1);
                        console.log('ARRAY ACCESORIOS2 LUEGO DE GRABAR: '+acc2);
                        console.log('ARRAY ACCESORIOS3 LUEGO DE GRABAR: '+acc3);
                        if (tipoFinanciamiento == 'Crédito'){
                            $('#btn-continuar').show();
                            $(".def").attr('disabled', 'disabled');
                            $('#modificar1').show();
                            $('#modificar2').show();
                            $('#modificar3').show();
                            //alert('Id de Proforma: '+returnedData.id);
                            $('#GestionFinanciamiento_id_financiamiento').val(returnedData.id);
                        } else if(tipoFinanciamiento == 'Contado'){
                            $('#btn-continuar-ct').show();
                            $('#GestionFinanciamiento_id_financiamiento').val(returnedData.id);
                            $('#GestionFinanciamiento_pdfid').val(returnedData.id);
                            //alert('Id de Proforma: '+returnedData.id);
                        }
                        //$('#GestionFinanciamiento_ipdfid').val(returnedData.id);
                    }
                });
            }
        });
        }
    }
    function printpdf() {
        console.log('enter printpdf');
        var id_informacion = $('#GestionFinanciamiento_ipdfid').val();
        var url = '/intranet/ventas/index.php/gestionVehiculo/proforma?id_informacion=166&id_vehiculo=29&id_informacion=' + id_informacion;
        $(location).attr('href', url);
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

    function sendCalc() {
        var valorFin = $('#GestionFinanciamiento_entrada').val();
        if (valorFin != '') {
            calcFinanciamiento();
        }
    }
    contenido_textarea = "";
    num_caracteres_permitidos = 250;

    function valida_longitud() {
        num_caracteres = document.forms[0].GestionFinanciamiento_observaciones.value.length

        if (num_caracteres > num_caracteres_permitidos) {
            document.forms[0].GestionFinanciamiento_observaciones.value = contenido_textarea
        } else {
            contenido_textarea = document.forms[0].GestionFinanciamiento_observaciones.value
        }

        if (num_caracteres >= num_caracteres_permitidos) {
            //document.forms[0].caracteres.style.color = "#ff0000";
        } else {
            //document.forms[0].caracteres.style.color = "#000000";
        }

        //cuenta()
    }
    function valida_longitud2() {
        num_caracteres = document.forms[0].GestionFinanciamiento_observaciones2.value.length

        if (num_caracteres > num_caracteres_permitidos) {
            document.forms[0].GestionFinanciamiento_observaciones2.value = contenido_textarea
        } else {
            contenido_textarea = document.forms[0].GestionFinanciamiento_observaciones2.value
        }

        if (num_caracteres >= num_caracteres_permitidos) {
            //document.forms[0].caracteres.style.color = "#ff0000";
        } else {
            //document.forms[0].caracteres.style.color = "#000000";
        }

        //cuenta()
    }
    function valida_longitud3() {
        num_caracteres = document.forms[0].GestionFinanciamiento_observaciones3.value.length

        if (num_caracteres > num_caracteres_permitidos) {
            document.forms[0].GestionFinanciamiento_observaciones3.value = contenido_textarea
        } else {
            contenido_textarea = document.forms[0].GestionFinanciamiento_observaciones3.value
        }

        if (num_caracteres >= num_caracteres_permitidos) {
            //document.forms[0].caracteres.style.color = "#ff0000";
        } else {
            //document.forms[0].caracteres.style.color = "#000000";
        }

        //cuenta()
    }
    function cuenta() {
        document.forms[0].caracteres.value = document.forms[0].texto.value.length
    }
    function calcFinanciamientoContado() {
    //console.log('enter calc');
    //var valorEntrada1 = $('#GestionFinanciamiento_entrada3').attr('value');
    var valorVehiculo = $('#GestionFinanciamiento_precio_contado').val();
    //var plazo = $('#GestionFinanciamiento_plazo3').val();
    var seguro = $('#GestionFinanciamiento_tiempo_seguro_contado').val();
    //console.log('valor vehiculo contado: ' + valorVehiculo);

    valorVehiculo = valorVehiculo.replace(',', '');
    valorVehiculo = valorVehiculo.replace('.', ',');
    valorVehiculo = valorVehiculo.replace('$', '');
    //console.log('valor vehiculo ant: '+valorVehiculo);
    valorVehiculo = parseInt(valorVehiculo);
    //console.log('valor vehiculo: '+valorVehiculo);

    // valor del porcentaje del seguro
    var porcentajePrimaNeta;
    var porcentajeDerechos;
    switch (seguro) {
        case '0':
            porcentajePrimaNeta = 0;
            porcentajeDerechos = 0;
            break;
        case '1':
            porcentajePrimaNeta = 0.04;
            porcentajeDerechos = 0.0042;
            break;
        case '2':
            porcentajePrimaNeta = 0.0740;
            porcentajeDerechos = 0.00318;
            break;
        case '3':
            porcentajePrimaNeta = 0.1046;
            porcentajeDerechos = 0.0022;
            break;
        case '4':
        case '5':
            porcentajePrimaNeta = 0.13214;
            porcentajeDerechos = 0.0017;
            break;
        default:

    }
    //console.log('porcentaje prime neta: '+porcentajePrimaNeta);
    var primaNeta = valorVehiculo * parseFloat(porcentajePrimaNeta);
    //console.log('PRIMA NETA--: '+primaNeta);
    var superBancos = primaNeta * 0.035;
    var seguroCampesino = primaNeta * 0.00500;
    var derechosEmision = primaNeta * porcentajeDerechos;
    var subtotal = primaNeta + superBancos + seguroCampesino + derechosEmision;
    var iva = subtotal * 0.14;
    var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
    //console.log('----PRIMA TOTAL----: ' + primaTotal);

    var precioAccesorios = $('#precio_accesorios').val();
    precioAccesorios = precioAccesorios.replace(',', '');
    precioAccesorios = precioAccesorios.replace('.', ',');
    precioAccesorios = precioAccesorios.replace('$', '');
    precioAccesorios = parseInt(precioAccesorios);
    var entrada = precioAccesorios / 4;

    var valorSeguro = format2(primaTotal, '$');
    var valorTotal = valorVehiculo + primaTotal;
    valorTotal = format2(valorTotal, '$');

    $('#GestionFinanciamiento_precio_contado_total').val(valorTotal);
    //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
    $('#GestionFinanciamiento_seguro_contado').val(valorSeguro);
}
    function calcFinanciamientoContado2() {
        //var valorEntrada1 = $('#GestionFinanciamiento_entrada3').attr('value');
        var valorVehiculo = formatnumber($('#GestionFinanciamiento_precio_contado2').val());
        //var plazo = $('#GestionFinanciamiento_plazo3').val();
        var seguro = $('#GestionFinanciamiento_tiempo_seguro_contado2').val();

        // valor del porcentaje del seguro
        var porcentajePrimaNeta;
        var porcentajeDerechos;
        switch (seguro) {
            case '0':
                porcentajePrimaNeta = 0;
                porcentajeDerechos = 0;
                break;
            case '1':
                porcentajePrimaNeta = 0.04;
                porcentajeDerechos = 0.0042;
                break;
            case '2':
                porcentajePrimaNeta = 0.0740;
                porcentajeDerechos = 0.00318;
                break;
            case '3':
                porcentajePrimaNeta = 0.1046;
                porcentajeDerechos = 0.0022;
                break;
            case '4':
            case '5':
                porcentajePrimaNeta = 0.13214;
                porcentajeDerechos = 0.0017;
                break;
            default:

        }
        //console.log('porcentaje prime neta: '+porcentajePrimaNeta);
        var primaNeta = valorVehiculo * parseFloat(porcentajePrimaNeta);
        //console.log('PRIMA NETA--: '+primaNeta);
        var superBancos = primaNeta * 0.035;
        var seguroCampesino = primaNeta * 0.00500;
        var derechosEmision = primaNeta * porcentajeDerechos;
        var subtotal = primaNeta + superBancos + seguroCampesino + derechosEmision;
        var iva = subtotal * 0.14;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);

        var precioAccesorios = formatnumber($('#precio_accesorios').val());
        var entrada = precioAccesorios / 4;
        var valorSeguro = format2(primaTotal, '$');
        var valorTotal = valorVehiculo + primaTotal;
        valorTotal = format2(valorTotal, '$');
        //alert(valorTotal);
        $('#GestionFinanciamiento_precio_contado_total2').val(valorTotal);
        //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
        $('#GestionFinanciamiento_seguro_contado2').val(valorSeguro);
    }

    function calcFinanciamientoContado3() {
        //var valorEntrada1 = $('#GestionFinanciamiento_entrada3').attr('value');
        var valorVehiculo = formatnumber($('#GestionFinanciamiento_precio_contado3').val());
        //var plazo = $('#GestionFinanciamiento_plazo3').val();
        var seguro = $('#GestionFinanciamiento_tiempo_seguro_contado3').val();

        // valor del porcentaje del seguro
        var porcentajePrimaNeta;
        var porcentajeDerechos;
        switch (seguro) {
            case '0':
                porcentajePrimaNeta = 0;
                porcentajeDerechos = 0;
                break;
            case '1':
                porcentajePrimaNeta = 0.04;
                porcentajeDerechos = 0.0042;
                break;
            case '2':
                porcentajePrimaNeta = 0.0740;
                porcentajeDerechos = 0.00318;
                break;
            case '3':
                porcentajePrimaNeta = 0.1046;
                porcentajeDerechos = 0.0022;
                break;
            case '4':
            case '5':
                porcentajePrimaNeta = 0.13214;
                porcentajeDerechos = 0.0017;
                break;
            default:

        }
        //console.log('porcentaje prime neta: '+porcentajePrimaNeta);
        var primaNeta = valorVehiculo * parseFloat(porcentajePrimaNeta);
        //console.log('PRIMA NETA--: '+primaNeta);
        var superBancos = primaNeta * 0.035;
        var seguroCampesino = primaNeta * 0.00500;
        var derechosEmision = primaNeta * porcentajeDerechos;
        var subtotal = primaNeta + superBancos + seguroCampesino + derechosEmision;
        var iva = subtotal * 0.14;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);

        var precioAccesorios = formatnumber($('#precio_accesorios').val());
        var entrada = precioAccesorios / 4;

        var valorSeguro = format2(primaTotal, '$');
        var valorTotal = valorVehiculo + primaTotal;
        valorTotal = format2(valorTotal, '$');
        //alert(valorTotal);
        $('#GestionFinanciamiento_precio_contado_total3').val(valorTotal);
        //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
        $('#GestionFinanciamiento_seguro_contado3').val(valorSeguro);
    }
    function calcFinanciamiento3() {
        var valorEntrada1 = $('#GestionFinanciamiento_entrada3').attr('value');
        var valorVehiculo = $('#GestionFinanciamiento_precio3').val();
        var plazo = $('#GestionFinanciamiento_plazo3').val();
        var seguro = $('#GestionFinanciamiento_tiempo_seguro3').val();

        valorVehiculo = formatnumber(valorVehiculo);
        //console.log('valor vehiculo: '+valorVehiculo);

        // valor del porcentaje del seguro
        var porcentajePrimaNeta;
        var porcentajeDerechos;
        switch (seguro) {
            case '0':
                porcentajePrimaNeta = 0;
                porcentajeDerechos = 0;
                break;
            case '1':
                porcentajePrimaNeta = 0.04;
                porcentajeDerechos = 0.0042;
                break;
            case '2':
                porcentajePrimaNeta = 0.0740;
                porcentajeDerechos = 0.00318;
                break;
            case '3':
                porcentajePrimaNeta = 0.1046;
                porcentajeDerechos = 0.0022;
                break;
            case '4':
            case '5':
                porcentajePrimaNeta = 0.13214;
                porcentajeDerechos = 0.0017;
                break;
            default:

        }
        //console.log('porcentaje prime neta: '+porcentajePrimaNeta);
        var primaNeta = valorVehiculo * parseFloat(porcentajePrimaNeta);
        //console.log('PRIMA NETA--: '+primaNeta);
        var superBancos = primaNeta * 0.035;
        var seguroCampesino = primaNeta * 0.00500;
        var derechosEmision = primaNeta * porcentajeDerechos;
        var subtotal = primaNeta + superBancos + seguroCampesino + derechosEmision;
        var iva = subtotal * 0.14;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);
        var precioEntrada = valorEntrada1.replace(',', '');
        precioEntrada = precioEntrada.replace('.', ',');
        precioEntrada = precioEntrada.replace('$', '');
        precioEntrada = parseInt(precioEntrada);
        var precioAccesorios = formatnumber($('#precio_normal').val());
        var entrada = precioAccesorios / 4;
        if (precioEntrada < entrada) {
            $('.error-entrada3').show();$('#GestionFinanciamiento_entrada3').focus();
            return false;
        } else {
            $('.error-entrada3').hide();
            var valorFinanciamiento = precioAccesorios - precioEntrada;
            var valorFinanciamientoAnt = valorFinanciamiento;
            //console.log('valor fin: '+valorFinanciamiento);
            valorFinanciamiento += primaTotal + 475.75;
            valorFinanciamientoAnt += primaTotal + 475.75;
            valorFinanciamiento = format2(valorFinanciamiento, '$');
            var valorSeguro = format2(primaTotal, '$');
            //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
            $('#GestionFinanciamiento_seguro3').val(valorSeguro);
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/pago"); ?>',
                beforeSend: function (xhr) {
                    $('#bg_negro').show();  // #bg_negro must be defined somewhere
                },
                dataType: 'json',
                type: 'POST',
                data: {taza: 16.06, numpagos: 12, valorPrest: valorFinanciamientoAnt, plazo: plazo},
                success: function (data) {
                    var cuotamensual = parseInt(data.cuota);
                    cuotamensual = format2(cuotamensual, '$');
                    $('#GestionFinanciamiento_cuota_mensual3').val(cuotamensual);
                    var valorFin = parseInt(data.valorFinanciamiento);
                    valorFin = format2(valorFin, '$');
                    $('#GestionFinanciamiento_valor_financiamiento3').val(valorFin);
                    $('#bg_negro').hide();
                }
            });
        }
    }
    function calcFinanciamiento() {
    //console.log('enter calcfinanciammiento 1');
        var valorEntrada1 = $('#GestionFinanciamiento_entrada').attr('value');
        var valorVehiculo = $('#GestionFinanciamiento_precio').val();
        console.log('valor vehiculo: ' + valorVehiculo);
        var plazo = $('#GestionFinanciamiento_plazo').val();
        var seguro = $('#GestionFinanciamiento_tiempo_seguro').val();

        valorVehiculo = formatnumber(valorVehiculo);
                // valor del porcentaje del seguro
        var porcentajePrimaNeta;
        var porcentajeDerechos;
        switch (seguro) {
            case '0':
                porcentajePrimaNeta = 0;
                porcentajeDerechos = 0;
                break;
            case '1':
                porcentajePrimaNeta = 0.04;
                porcentajeDerechos = 0.0042;
                break;
            case '2':
                porcentajePrimaNeta = 0.0740;
                porcentajeDerechos = 0.00318;
                break;
            case '3':
                porcentajePrimaNeta = 0.1046;
                porcentajeDerechos = 0.0022;
                break;
            case '4':
            case '5':
                porcentajePrimaNeta = 0.13214;
                porcentajeDerechos = 0.0017;
                break;
            default:

        }
        //console.log('porcentaje prime neta: '+porcentajePrimaNeta);
        var primaNeta = valorVehiculo * parseFloat(porcentajePrimaNeta);
        //console.log('PRIMA NETA--: '+primaNeta);
        var superBancos = primaNeta * 0.035;
        var seguroCampesino = primaNeta * 0.00500;
        var derechosEmision = primaNeta * porcentajeDerechos;
        var subtotal = primaNeta + superBancos + seguroCampesino + derechosEmision;
        var iva = subtotal * 0.14;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);
        
        precioEntrada = formatnumber(valorEntrada1);
        //var precioAccesorios = $('#precio_accesorios').val();
        var precioAccesorios = formatnumber($('#precio_normal').val());
        var entrada = precioAccesorios / 5;
        if (precioEntrada < entrada) {
            $('.error-entrada').show();$('#GestionFinanciamiento_entrada').focus();
            return false;
        } else {
            $('.error-entrada').hide();
            var valorFinanciamiento = precioAccesorios - precioEntrada;
            var valorFinanciamientoAnt = valorFinanciamiento;
            //console.log('valor fin: '+valorFinanciamiento);
            valorFinanciamiento += primaTotal + 475.75;
            valorFinanciamientoAnt += primaTotal + 475.75;
            valorFinanciamiento = format2(valorFinanciamiento, '$');
            var valorSeguro = format2(primaTotal, '$');
            //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
            $('#GestionFinanciamiento_seguro').val(valorSeguro);
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/pago"); ?>',
                beforeSend: function (xhr) {
                    $('#bg_negro').show();  // #bg_negro must be defined somewhere
                },
                dataType: 'json',
                type: 'POST',
                data: {taza: 16.06, numpagos: 12, valorPrest: valorFinanciamientoAnt, plazo: plazo},
                success: function (data) {
                    var cuotamensual = parseInt(data.cuota);
                    cuotamensual = format2(cuotamensual, '$');
                    $('#GestionFinanciamiento_cuota_mensual').val(cuotamensual);
                    var valorFin = parseInt(data.valorFinanciamiento);
                    valorFin = format2(valorFin, '$');
                    $('#GestionFinanciamiento_valor_financiamiento').val(valorFin);
                    $('#bg_negro').hide();
                    console.log('VALOR ACCCESORIO 1 FINAN: '+acc1);
                }
            });
        }
    }
    function calcFinanciamiento2() {
        var valorEntrada1 = $('#GestionFinanciamiento_entrada2').attr('value');
        var valorVehiculo = $('#GestionFinanciamiento_precio2').val();
        var plazo = $('#GestionFinanciamiento_plazo2').val();
        var seguro = $('#GestionFinanciamiento_tiempo_seguro2').val();

        valorVehiculo = formatnumber(valorVehiculo);
        //console.log('valor vehiculo: '+valorVehiculo);

        // valor del porcentaje del seguro
        var porcentajePrimaNeta;
        var porcentajeDerechos;
        switch (seguro) {
            case '0':
                porcentajePrimaNeta = 0;
                porcentajeDerechos = 0;
                break;
            case '1':
                porcentajePrimaNeta = 0.04;
                porcentajeDerechos = 0.0042;
                break;
            case '2':
                porcentajePrimaNeta = 0.0740;
                porcentajeDerechos = 0.00318;
                break;
            case '3':
                porcentajePrimaNeta = 0.1046;
                porcentajeDerechos = 0.0022;
                break;
            case '4':
            case '5':
                porcentajePrimaNeta = 0.13214;
                porcentajeDerechos = 0.0017;
                break;
            default:

        }
        //console.log('porcentaje prime neta: '+porcentajePrimaNeta);
        var primaNeta = valorVehiculo * parseFloat(porcentajePrimaNeta);
        //console.log('PRIMA NETA--: '+primaNeta);
        var superBancos = primaNeta * 0.035;
        var seguroCampesino = primaNeta * 0.00500;
        var derechosEmision = primaNeta * porcentajeDerechos;
        var subtotal = primaNeta + superBancos + seguroCampesino + derechosEmision;
        var iva = subtotal * 0.14;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);
        var precioEntrada = valorEntrada1.replace(',', '');
        precioEntrada = precioEntrada.replace('.', ',');
        precioEntrada = precioEntrada.replace('$', '');
        precioEntrada = parseInt(precioEntrada);
        var precioAccesorios = formatnumber($('#precio_normal').val());
        var entrada = precioAccesorios / 4;
        if (precioEntrada < entrada) {
            $('.error-entrada2').show();$('#GestionFinanciamiento_entrada2').focus();
            return false;
        } else {
            $('.error-entrada2').hide();
            var valorFinanciamiento = precioAccesorios - precioEntrada;
            var valorFinanciamientoAnt = valorFinanciamiento;
            //console.log('valor fin: '+valorFinanciamiento);
            valorFinanciamiento += primaTotal + 475.75;
            valorFinanciamientoAnt += primaTotal + 475.75;
            valorFinanciamiento = format2(valorFinanciamiento, '$');
            var valorSeguro = format2(primaTotal, '$');
            //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
            $('#GestionFinanciamiento_seguro2').val(valorSeguro);
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/pago"); ?>',
                beforeSend: function (xhr) {
                    $('#bg_negro').show();  // #bg_negro must be defined somewhere
                },
                dataType: 'json',
                type: 'POST',
                data: {taza: 16.06, numpagos: 12, valorPrest: valorFinanciamientoAnt, plazo: plazo},
                success: function (data) {
                    var cuotamensual = parseInt(data.cuota);
                    cuotamensual = format2(cuotamensual, '$');
                    $('#GestionFinanciamiento_cuota_mensual2').val(cuotamensual);
                    var valorFin = parseInt(data.valorFinanciamiento);
                    valorFin = format2(valorFin, '$');
                    $('#GestionFinanciamiento_valor_financiamiento2').val(valorFin);
                    $('#bg_negro').hide();
                }
            });
        }
    }
    function edit(id){
        var tipoFinanciamiento = $('#GestionFinanciamiento_tipo_financiamiento').val(); 
        //console.log('tipo financiamiento: '+tipoFinanciamiento);
        var accesorioscont = $('#accesorioscont').val();
        var precioanterior = formatnumber($('#precio_accesorios2').val());
        var accesorio2 = 0;
        //console.log('valor id: '+id);
        switch(id){
            case 1:
                console.log('EDITAR ACCESORIOS 1: '+acc1);
                $('.cont-options1').addClass('cont-options1-after');
                $('.cont-options2').removeClass('cont-options1-after');
                $('.cont-options3').removeClass('cont-options1-after');
                $('#GestionFinanciamiento_mod').val(2);
                $('#GestionFinanciamiento_entrada').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo').removeAttr('disabled');


                // primero quitar los checks de los otros que esten seleccionados
                if(tipoFinanciamiento == 0){
                    for (var j = 1; j <= accesorioscont; j++) {
                        sat = $('#accesorio-' + j).val();
                        param = sat.split('-');
                        //console.log('enter checked false: '+ j);
                        $('#accesorio-' + j).attr('checked', false);
                        $('#accspan-' + j).removeClass('label-price');
                    }
                }else{// para credito no quitar check al Kit Satelital
                    for (var j = 1; j <= accesorioscont; j++) {
                        sat = $('#accesorio-' + j).val();
                        param = sat.split('-');
                        if (param[1] != 'Kit Satelital') {
                            //console.log('enter checked false: '+ j);
                            $('#accesorio-' + j).attr('checked', false);
                            $('#accspan-' + j).removeClass('label-price');
                        }
                    }
                }

                // poner checks a los elementos correspondientes
                var lt = acc1.length;
                for (var i = 0; i <= lt; i++) {
                    $('#accesorio-' + acc1[i]).attr('checked', true);
                    // llamar funcion de financiamiento
                    $('#accspan-' + acc1[i]).addClass('label-price');
                }

                for (var k = 0; k < lt; k++) {
                   pri = $('#accesorio-' + acc1[k]).val();
                    //console.log('val pri:'+pri);
                    params2 = pri.split('-');
                    if (params2[1] != 'Kit Satelital') {accesorio2 += parseInt(params2[0]);}
                }
                // sumar todo los valores de los checks al precio vehiculo
                finaciamiento = formatnumber($('#GestionFinanciamiento_precio').val()) - formatnumber($('#precio_normal').val());
                var precionuevo = parseInt(precioanterior) + parseInt(accesorio2);
                $('#precio_accesorios').val(format2(finaciamiento, '$'));
                if(tipoFinanciamiento == 0){ // TIPO CONTADO
                    secure = $('#GestionFinanciamiento_tiempo_seguro_contado').val();
                    if(secure != '' || secure != '0'){
                        calcFinanciamientoContado();
                    }
                }else{
                    $('#GestionFinanciamiento_precio').val(format2(precionuevo, '$'));
                    calcFinanciamiento();
                }
                break;
            case 2:
                console.log('EDITAR ACCESORIOS 2: '+acc2);
                $('#GestionFinanciamiento_mod').val(3);
                $('.cont-options2').addClass('cont-options1-after');
                $('.cont-options1').removeClass('cont-options1-after');
                $('.cont-options3').removeClass('cont-options1-after');
                $('#GestionFinanciamiento_entrada2').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro2').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo2').removeAttr('disabled');
                // primero quitar los checks de los otros que esten seleccionados
                if(tipoFinanciamiento == 0){
                    for (var j = 1; j <= accesorioscont; j++) {
                        sat = $('#accesorio-' + j).val();
                        param = sat.split('-');
                        //console.log('enter checked false: '+ j);
                        $('#accesorio-' + j).attr('checked', false);
                        $('#accspan-' + j).removeClass('label-price');
                    }
                }else{// para credito no quitar check al Kit Satelital
                    for (var j = 1; j <= accesorioscont; j++) {
                        sat = $('#accesorio-' + j).val();
                        param = sat.split('-');
                        if (param[1] != 'Kit Satelital') {
                            //console.log('enter checked false: '+ j);
                            $('#accesorio-' + j).attr('checked', false);
                            $('#accspan-' + j).removeClass('label-price');
                        }
                    }
                }
                // poner checks a los elementos correspondientes
                var lt = acc2.length;
                for (var i = 0; i <= lt; i++) {
                    $('#accesorio-' + acc2[i]).attr('checked', true);
                    // llamar funcion de financiamiento
                    $('#accspan-' + acc2[i]).addClass('label-price');
                }

                for (var k = 0; k < lt; k++) {
                   pri = $('#accesorio-' + acc2[k]).val();
                    console.log('val pri:'+pri);
                    params2 = pri.split('-');
                    if (params2[1] != 'Kit Satelital') {accesorio2 += parseInt(params2[0]);}
                }
                // sumar todo los valores de los checks al precio vehiculo

                var precionuevo = parseInt(precioanterior) + parseInt(accesorio2);
                finaciamiento = formatnumber($('#GestionFinanciamiento_precio2').val()) - formatnumber($('#precio_normal').val());
                $('#precio_accesorios').val(format2(finaciamiento, '$'));

                if(tipoFinanciamiento == 0){ // TIPO CONTADO
                    secure = $('#GestionFinanciamiento_tiempo_seguro_contado2').val();
                    if(secure != '' || secure != '0'){
                        calcFinanciamientoContado2();
                    }
                }else{
                    $('#GestionFinanciamiento_precio2').val(format2(precionuevo, '$'));
                    calcFinanciamiento2();
                }

                break;
            case 3:
                console.log('EDITAR ACCESORIOS 2: '+acc3);
                $('#GestionFinanciamiento_mod').val(4);
                $('.cont-options3').addClass('cont-options1-after');
                $('.cont-options1').removeClass('cont-options1-after');
                $('.cont-options2').removeClass('cont-options1-after');
                $('#GestionFinanciamiento_entrada3').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro3').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo3').removeAttr('disabled');
                // primero quitar los checks de los otros que esten seleccionados
                if(tipoFinanciamiento == 0){
                    for (var j = 1; j <= accesorioscont; j++) {
                        sat = $('#accesorio-' + j).val();
                        param = sat.split('-');
                        //console.log('enter checked false: '+ j);
                        $('#accesorio-' + j).attr('checked', false);
                        $('#accspan-' + j).removeClass('label-price');
                    }
                }else{// para credito no quitar check al Kit Satelital
                    for (var j = 1; j <= accesorioscont; j++) {
                        sat = $('#accesorio-' + j).val();
                        param = sat.split('-');
                        if (param[1] != 'Kit Satelital') {
                            //console.log('enter checked false: '+ j);
                            $('#accesorio-' + j).attr('checked', false);
                            $('#accspan-' + j).removeClass('label-price');
                        }
                    }
                }
                // poner checks a los elementos correspondientes
                var lt = acc3.length;
                for (var i = 0; i <= lt; i++) {
                    $('#accesorio-' + acc3[i]).attr('checked', true);
                    // llamar funcion de financiamiento
                    $('#accspan-' + acc3[i]).addClass('label-price');
                }

                for (var k = 0; k < lt; k++) {
                   pri = $('#accesorio-' + acc3[k]).val();
                    console.log('val pri:'+pri);
                    params2 = pri.split('-');
                    if (params2[1] != 'Kit Satelital') {accesorio2 += parseInt(params2[0]);}
                }
                // sumar todo los valores de los checks al precio vehiculo

                var precionuevo = parseInt(precioanterior) + parseInt(accesorio2);
                finaciamiento = formatnumber($('#GestionFinanciamiento_precio3').val()) - formatnumber($('#precio_normal').val());
                $('#precio_accesorios').val(format2(finaciamiento, '$'));

                if(tipoFinanciamiento == 0){ // TIPO CONTADO
                    secure = $('#GestionFinanciamiento_tiempo_seguro_contado3').val();
                    if(secure != '' || secure != '0'){
                        calcFinanciamientoContado3();
                    }
                }else{
                    $('#GestionFinanciamiento_precio3').val(format2(precionuevo, '$'));
                    calcFinanciamiento3();
                }

                break;
        }
    }
    function save(id){
        switch(id){
            case 1:
               calcFinanciamiento();$('.cont-options1').removeClass('cont-options1-after');
            case 2:
               calcFinanciamiento2();$('.cont-options2').removeClass('cont-options2-after'); 
            break;
            case 3:
               calcFinanciamiento3();$('.cont-options3').removeClass('cont-options3-after'); 
            break;
        }    
    }

    function deleter(id){
        console.log(id);
    }
    function calcSeguro(){
        console.log('enter calc seguro');
        var precioEntrada = formatnumber($('#GestionFinanciamiento_entrada').attr('value'));
        var precioAccesorios = formatnumber($('#GestionFinanciamiento_precio').val());
        
        var valorFinanciamiento = precioAccesorios - precioEntrada;
        var plazo = $('#GestionFinanciamiento_plazo').val();
        primaTotal = formatnumber($('#GestionFinanciamiento_seguro').val());
        var valorFinanciamientoAnt = valorFinanciamiento;
        valorFinanciamiento += primaTotal + 475.75;
        valorFinanciamientoAnt += primaTotal + 475.75;
        //valorFinanciamiento = format2(valorFinanciamiento, '$');
        //var valorSeguro = format2(primaTotal, '$');
        //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/pago"); ?>',
            beforeSend: function (xhr) {
                $('#bg_negro').show();  // #bg_negro must be defined somewhere
            },
            dataType: 'json',
            type: 'POST',
            data: {taza: 16.06, numpagos: 12, valorPrest: valorFinanciamientoAnt, plazo: plazo},
            success: function (data) {
                var cuotamensual = parseInt(data.cuota);
                cuotamensual = format2(cuotamensual, '$');
                $('#GestionFinanciamiento_cuota_mensual').val(cuotamensual);
                var valorFin = parseInt(data.valorFinanciamiento);
                valorFin = format2(valorFin, '$');
                $('#GestionFinanciamiento_valor_financiamiento').val(valorFin);
                $('#bg_negro').hide();
                console.log('VALOR ACCCESORIO 1 FINAN: '+acc1);
            }
        });
        
    }

    function calcSeguro2(){
        console.log('enter calc seguro2');
        var precioEntrada = formatnumber($('#GestionFinanciamiento_entrada2').attr('value'));
        var precioAccesorios = formatnumber($('#GestionFinanciamiento_precio2').val());
        
        var valorFinanciamiento = precioAccesorios - precioEntrada;
        var plazo = $('#GestionFinanciamiento_plazo2').val();
        primaTotal = formatnumber($('#GestionFinanciamiento_seguro2').val());
        var valorFinanciamientoAnt = valorFinanciamiento;
        valorFinanciamiento += primaTotal + 475.75;
        valorFinanciamientoAnt += primaTotal + 475.75;
        //valorFinanciamiento = format2(valorFinanciamiento, '$');
        //var valorSeguro = format2(primaTotal, '$');
        //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/pago"); ?>',
            beforeSend: function (xhr) {
                $('#bg_negro').show();  // #bg_negro must be defined somewhere
            },
            dataType: 'json',
            type: 'POST',
            data: {taza: 16.06, numpagos: 12, valorPrest: valorFinanciamientoAnt, plazo: plazo},
            success: function (data) {
                var cuotamensual = parseInt(data.cuota);
                cuotamensual = format2(cuotamensual, '$');
                $('#GestionFinanciamiento_cuota_mensual2').val(cuotamensual);
                var valorFin = parseInt(data.valorFinanciamiento);
                valorFin = format2(valorFin, '$');
                $('#GestionFinanciamiento_valor_financiamiento2').val(valorFin);
                $('#bg_negro').hide();
            }
        });
        
    }

    function calcSeguro3(){
        console.log('enter calc seguro3');
        var precioEntrada = formatnumber($('#GestionFinanciamiento_entrada3').attr('value'));
        var precioAccesorios = formatnumber($('#GestionFinanciamiento_precio3').val());
        
        var valorFinanciamiento = precioAccesorios - precioEntrada;
        var plazo = $('#GestionFinanciamiento_plazo3').val();
        primaTotal = formatnumber($('#GestionFinanciamiento_seguro3').val());
        var valorFinanciamientoAnt = valorFinanciamiento;
        valorFinanciamiento += primaTotal + 475.75;
        valorFinanciamientoAnt += primaTotal + 475.75;
        //valorFinanciamiento = format2(valorFinanciamiento, '$');
        //var valorSeguro = format2(primaTotal, '$');
        //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/pago"); ?>',
            beforeSend: function (xhr) {
                $('#bg_negro').show();  // #bg_negro must be defined somewhere
            },
            dataType: 'json',
            type: 'POST',
            data: {taza: 16.06, numpagos: 12, valorPrest: valorFinanciamientoAnt, plazo: plazo},
            success: function (data) {
                var cuotamensual = parseInt(data.cuota);
                cuotamensual = format2(cuotamensual, '$');
                $('#GestionFinanciamiento_cuota_mensual3').val(cuotamensual);
                var valorFin = parseInt(data.valorFinanciamiento);
                valorFin = format2(valorFin, '$');
                $('#GestionFinanciamiento_valor_financiamiento3').val(valorFin);
                $('#bg_negro').hide();
            }
        });
        
    }

</script>

<style type="text/css">
    label{font-size: 11pt;}
    .label-price{background-color: #5cb85c;}
</style>
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
                <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/update/' . $id_informacion); ?>"  aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <?php else: ?>
                <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <?php endif; ?>

            <li role="presentation"><a href="<?php echo $urlConsulta; ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/demostracion/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
            <li role="presentation" class="active"><a aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion_on.png" alt="" /></span> Negociación</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panels -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">

                <div class="row">
                    <h1 class="tl_seccion">Negociación</h1>
                </div>
                <div class="highlight">
                    <button type="button" class="btn btn-success btn-xs" onclick="history.go(-1);"><< Regresar</button>
                    <div class="form">
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'gestion-negociacion-form',
                            'enableAjaxValidation' => false,
                            'htmlOptions' => array(
                                'onsubmit' => "return false;", /* Disable normal form submit */
                                'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                            ),
                        ));
                        ?>

                        <div class="cont-proforma">
                            <div class="row">
                                <h1 class="tl_seccion">Datos de Cliente</h1>
                            </div>
                            <?php
                            $criteria = new CDbCriteria(array('condition' => "id = {$id_informacion}"));
                            $cl = GestionInformacion::model()->findAll($criteria);
                            ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr class="odd"><th>Nombres</th><td><?php echo ucfirst($cl[0]['nombres']); ?></td></tr> 
                                            <tr class="odd"><th>Apellidos</th><td><?php echo ucfirst($cl[0]['apellidos']); ?></td></tr>
                                            <?php if($cl[0]['cedula'] != ''): ?>
                                            <tr class="odd"><th>Cédula</th><td><?php echo $cl[0]['cedula']; ?></td></tr> 
                                            <?php endif; ?>
                                            <?php if($cl[0]['ruc'] != ''): ?>
                                            <tr class="odd"><th>RUC</th><td><?php echo $cl[0]['ruc']; ?></td></tr> 
                                            <?php endif; ?>
                                            <?php if($cl[0]['pasaporte'] != ''): ?>
                                            <tr class="odd"><th>Pasaporte</th><td><?php echo $cl[0]['pasaporte']; ?></td></tr> 
                                            <?php endif; ?>
                                            <tr class="odd"><th>Email</th><td><?php echo $cl[0]['email']; ?></td></tr> 
                                            <tr class="odd"><th>Celular</th><td><?php echo $cl[0]['celular']; ?></td></tr> 
                                            <tr class="odd"><th>Teléfono Domicilio</th><td><?php echo $cl[0]['telefono_casa']; ?></td></tr>
                                            <tr class="odd"><th>Dirección</th><td><?php echo $cl[0]['direccion']; ?></td></tr>
                                            <tr class="odd"><th>Modelo</th><td><?php echo $this->getModeloTestDrive($id_vehiculo); ?></td></tr>
                                            <tr class="odd"><th>Versión</th><td><?php echo $this->getVersionTestDrive($id_vehiculo); ?></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Tipo de pago</label>
                                    <select name="GestionFinanciamiento[tipo]" id="GestionFinanciamiento_tipo" class="form-control">
                                        <?php
                                        if ($tipo == 1) {
                                            echo '<option value="Contado">Contado</option>';
                                            echo '<option value="Crédito" selected>Crédito</option>';
                                        } else {
                                            echo '<option value="Contado" selected>Contado</option>';
                                            echo '<option value="Crédito">Crédito</option>';
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <?php  
                            $criteria3 = new CDbCriteria(array('condition' => "id_vehiculo = {$id_modelo} AND id_version = {$id_version} AND status = 1 AND opcional = 1"));
                            $cn3 = GestionAccesorios::model()->count($criteria3);
                            //echo 'cn: '.$cn;
                            $acc3 = GestionAccesorios::model()->findAll($criteria3);
                            ?>
                            <?php if($cn3 > 0): ?>
                            <div class="row">
                                <h1 class="tl_seccion">Accesorios Instalados</h1>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-default">
                                      <div class="panel-body">
                                          <?php foreach ($acc3 as $value3): ?>
                                          <p><?php echo $value3['accesorio'].' '.$value3['detalle']; ?></p>
                                          <?php endforeach; ?>
                                      </div>
                                  </div>     
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="row">
                                <h1 class="tl_seccion">Accesorios Opcionales</h1>
                            </div>
                            <?php
                            $criteria2 = new CDbCriteria(array('condition' => "id_vehiculo = {$id_modelo} AND id_version = {$id_version} AND status = 1 AND opcional = 0"));
                            $cn = GestionAccesorios::model()->count($criteria2);
                            //echo 'cn: '.$cn;
                            $acc = GestionAccesorios::model()->findAll($criteria2);
                            $limite1 = ceil($cn / 2);
                            //echo 'limit1: '.$limite1;
                            //$sql = 'SELECT * FROM gestion_accesorios WHERE id_vehiculo = ' . $id_modelo . ' AND status = 1 AND id_version = '.$id_version.' ORDER BY accesorio LIMIT ' . $limite1 . ',' . $cn . '';
                            //echo 'sql: '.$sql;
                            $rows1 = Yii::app()->db->createCommand('SELECT * FROM gestion_accesorios'
                                            . ' WHERE id_vehiculo = ' . $id_modelo . ' AND status = 1  AND opcional = 0 AND id_version = '.$id_version.' ORDER BY accesorio ASC LIMIT 0,' . $limite1 . '')
                                    ->queryAll();
                            $rows2 = Yii::app()->db->createCommand('SELECT * FROM gestion_accesorios'
                                            . ' WHERE id_vehiculo = ' . $id_modelo . ' AND status = 1  AND opcional = 0 AND id_version = '.$id_version.' ORDER BY accesorio ASC LIMIT ' . $limite1 . ',' . $cn . '')
                                    ->queryAll();

                            $count = 1;
                            ?>
                            <?php if ($cn > 0){ ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="col-md-6">
                                                    <?php foreach ($rows1 as $key => $value): ?>
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="<?php echo $value['precio'] . '-' . $value['accesorio']; ?>" name="accesorios[]" id="accesorio-<?php echo $count; ?>" <?php if ($value['codigo'] == 7 && $tipo == 1) { ?> <?php } ?>>
                                                                        <?php echo $value['accesorio']; ?>
                                                                        <input type="hidden" name="<?php echo $value['accesorio']; ?>" id="acc<?php echo $count; ?>" value="0"/>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="checkbox">
                                                                    <span class="label label-default <?php
                                                                    if ($value['codigo'] == 7 && $tipo == 1) {
                                                                        //echo 'label-price';
                                                                    }
                                                                    ?>" id="accspan-<?php echo $count; ?>"><?php echo 'USD. ' . $value['precio']; ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $count++;
                                                    endforeach;
                                                    ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php foreach ($rows2 as $key => $value): ?>
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="<?php echo $value['precio'] . '-' . $value['accesorio']; ?>" name="accesorios[]" id="accesorio-<?php echo $count; ?>" <?php if ($value['codigo'] == 7 && $tipo == 1) { ?> <?php } ?>>
                                                                        <?php echo $value['accesorio']; ?>
                                                                        <input type="hidden" name="<?php echo $value['accesorio']; ?>" id="acc<?php echo $count; ?>" value="0"/>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="checkbox">
                                                                    <span class="label label-default <?php
                                                                    if ($value['codigo'] == 7 && $tipo == 1) {
                                                                        //echo 'label-price';
                                                                    }
                                                                    ?>" id="accspan-<?php echo $count; ?>"><?php echo 'USD. ' . $value['precio']; ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $count++;
                                                    endforeach;
                                                    ?>
                                                </div>
                                                <hr />
                                                <div class="col-md-12">
                                                    <div class="row" style="display:none;">
                                                        <div class="col-md-1">
                                                            <div class="checkbox">
                                                                <label for="">
                                                                    <input type="checkbox" value="1" name="otro" id="otro" class="" checked=""/>Otro
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row otro_accesorio">
                                                         <div class="row">
                                                            <div class="col-md-1">
                                                            <div class="checkbox ag-btn"><button type="button" class="btn btn-success btn-xs" onclick="ot();" id="btn-ot">+ Agregar</button>
                                                            </div>
                                                            </div>
                                                            <div class="col-md-1 btn-canc-otro" style="display: none;">
                                                                <div class="checkbox"><button type="button" class="btn btn-info btn-inverse btn-xs" onclick="otcanc();">− Eliminar</button></div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row cont-opt-acc1">
                                                            <div class="col-md-4">
                                                                <label for="precio_normal">Accesorios</label>
                                                                <textarea name="otro_accesorios_nombre1" id="otro_accesorios_nombre1" cols="30" rows="3" draggable=""></textarea>
                                                                <label class="error error-nombre1" style="display:none;">Ingrese una descripción</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="precio_accesorios">Valor</label>
                                                                <input type="text" name="valor_otro_accesorios1" id="valor_otro_accesorios1" class="form-control" value="" data-symbol="$ " data-thousands="," data-decimal="." autocomplete="off">
                                                                <label class="error error-accesorio1" style="display:none;">Ingrese un valor</label>
                                                            </div>
                                                        </div>
                                                        <div class="row cont-opt-acc2" style="display: none;">
                                                            <div class="col-md-4">
                                                                <label for="precio_normal">Accesorios</label>
                                                                <textarea name="otro_accesorios_nombre2" id="otro_accesorios_nombre2" cols="30" rows="3" draggable=""></textarea>
                                                                <label class="error error-nombre2" style="display:none;">Ingrese una descripción</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="precio_accesorios">Valor</label>
                                                                <input type="text" name="valor_otro_accesorios2" id="valor_otro_accesorios2" class="form-control" value="" data-symbol="$ " data-thousands="," data-decimal="." autocomplete="off">
                                                                <label class="error error-accesorio2" style="display:none;">Ingrese un valor</label>
                                                            </div>
                                                        </div>
                                                        <div class="row cont-opt-acc3" style="display: none;">
                                                            <div class="col-md-4">
                                                                <label for="precio_normal">Accesorios</label>
                                                                <textarea name="otro_accesorios_nombre3" id="otro_accesorios_nombre3" cols="30" rows="3" draggable=""></textarea>
                                                                <label class="error error-nombre3" style="display:none;">Ingrese una descripción</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="precio_accesorios">Valor</label>
                                                                <input type="text" name="valor_otro_accesorios3" id="valor_otro_accesorios3" class="form-control" value="" data-symbol="$ " data-thousands="," data-decimal="." autocomplete="off">
                                                                <label class="error error-accesorio3" style="display:none;">Ingrese un valor</label>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-success btn-xs" onclick="sco();" id="btn-acc">Sumar Accesorios</button>
                                                                <input type="hidden" name="cont-acc2" id="cont-acc2" value="0" />
                                                                <input type="hidden" name="cont-acc3" id="cont-acc3" value="0" />
                                                                <input type="hidden" name="cont-acc4" id="cont-acc4" value="0" />
                                                                <input type="hidden" name="options-cont-otro" id="options-cont-otro" value="2" />
                                                                <input type="hidden" name="sum-accesorios2" id="sum-accesorios2" value=""/>
                                                                <input type="hidden" name="sum-accesorios-total2" id="sum-accesorios-total2" value=""/>
                                                                <input type="hidden" name="sum-accesorios-res2" id="sum-accesorios-res2" value=""/>
                                                                <input type="hidden" name="desc-accesorios2" id="desc-accesorios2" value=""/>
                                                                <input type="hidden" name="sum-accesorios3" id="sum-accesorios3" value=""/>
                                                                <input type="hidden" name="sum-accesorios-total3" id="sum-accesorios-total3" value=""/>
                                                                <input type="hidden" name="sum-accesorios-res3" id="sum-accesorios-res3" value=""/>
                                                                <input type="hidden" name="desc-accesorios3" id="desc-accesorios3" value=""/>
                                                                <input type="hidden" name="sum-accesorios4" id="sum-accesorios4" value=""/>
                                                                <input type="hidden" name="sum-accesorios-total4" id="sum-accesorios-total4" value=""/>
                                                                <input type="hidden" name="sum-accesorios-res4" id="sum-accesorios-res4" value=""/>
                                                                <input type="hidden" name="desc-accesorios4" id="desc-accesorios4" value=""/>
                                                                <input type="hidden" name="cont-otro2" id="cont-otro2" value="2"/>
                                                                <input type="hidden" name="cont-otro3" id="cont-otro3" value="2"/>
                                                                <input type="hidden" name="cont-otro4" id="cont-otro4" value="2"/>
                                                                <input type="hidden" name="total-acc1" id="total-acc1" value=''>
                                                                <input type="hidden" name="total-acc2" id="total-acc2" value=''>
                                                                <input type="hidden" name="total-acc3" id="total-acc3" value=''>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-info btn-inverse btn-xs" onclick="scod();" id="btn-accd" style="display:none;">Quitar Accesorios</button>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <b id="sum-accesorios-val2"></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <?php if ($tipo == 1 && $id_modelo != 90) { ?>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="670-Kit Satelital " name="accesorios[]" id="accesorio-3" checked="" disabled="" class="def">
                                                                        Kit Satelital                                                                     
                                                                        <input type="hidden" name="Kit Satelital " id="acc3" value="0">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="checkbox">
                                                                    <span class="label label-default label-price" id="accspan-3">USD. 670</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <p>Por el momento accesorios no disponibles para este vehículo.</p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>    
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="precio_accesorios">Valor Total Accesorios</label>
                                    <input type="text" name="precio_accesorios" id="precio_accesorios" class="form-control input-acc" value="" data-symbol="$ " data-thousands="." data-decimal=",">
                                    <input type="hidden" name="precio_accesorios_anterior" id="precio_accesorios_anterior" value=""/>
                                    <input type="hidden" name="precio_accesorios2" id="precio_accesorios2" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,0) ?>" data-symbol="$ " data-thousands="." data-decimal="," />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="precio_normal">Precio Normal</label>
                                    <input type="text" name="precio_normal" id="precio_normal" class="form-control" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,0) ?>">
                                </div>
                                
                            </div>
                            <br />
                            <div class="row">
                                    <h1 class="tl_seccion">Proforma</h1>
                                </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-warning" role="alert"><strong>Importante:</strong> Si usted edita manualmente alguno de los campos del cotizador, recuerde que el sistema ya no realizará ningún cálculo automático. 
                                        Por favor revisar que todos los valores estén correctos y que la información que va a ser enviada al cliente sea la correcta.</div>
                                </div>
                            </div>
                            <br />
                                <div class="row">
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success btn-xs" onclick="op();" id="btn-opt">+ Agregar opción</button>
                                        <input type="hidden" name="options-cont" id="options-cont" value="2" />
                                        <input type="hidden" name="options-cont-pass" id="options-cont-pass" value="2" />
                                        <input type="hidden" name="accesorioscont" id="accesorioscont" value="<?php echo $cn; ?>"/>
                                    </div>
                                    <div class="col-md-2 btn-canc" style="display: none;">
                                        <button type="button" class="btn btn-info btn-inverse btn-xs" onclick="opcanc();">− Eliminar opción</button>
                                    </div>
                                </div>
                            <?php if ($tipo == 1): // credito            ?>
<!-- ==================================INICIO COTIZACION A CREDITO===================================-->
                            <div class="cont-financ">
                                <div class="row">
                                    <div class="col-md-4 cont-options1">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="text-danger">Primera Opción</h4>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Entidad Financiera</label>
                                                <select name="GestionFinanciamiento1[entidad_financiera]" id="GestionFinanciamiento_entidad_financiera" class="form-control">
                                                    <option value="Banco del Austro">Banco del Austro</option>
                                                    <option value="Banco del Pichincha">Banco del Pichincha</option>
                                                    <option value="BPAC">BPAC</option>
                                                    <option value="Capital">Capital</option>
                                                    <option value="CFC" selected="true">CFC</option>
                                                    <option value="CPN">CPN</option>
                                                    <option value="Coop 29 de Octubre">Coop 29 de Octubre</option>
                                                    <option value="ISSA">ISSFA</option>
                                                    <option value="Originarsa">Originarsa</option>
                                                    <option value="Produbanco">Produbanco</option>
                                                    <option value="Unifinsa">Unifinsa</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Precio Vehículo</label>
                                                <input type="text" name="GestionFinanciamiento1[precio]" id="GestionFinanciamiento_precio" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,0) ?>" maxlength="11"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Valor de Entrada</label>
                                                <input type="text" maxlength="11" name="GestionFinanciamiento1[entrada]" id="GestionFinanciamiento_entrada" class="form-control" autocomplete="off"/>
                                                <label class="error error-entrada" style="display: none;">Ingrese un valor de entrada igual o superior al 20% del Precio Total</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Porcentaje de Entrada</label>
                                                <input type="text" name="GestionFinanciamiento[porcentaje]" class="form-control" maxlength="2" id="GestionFinanciamiento_porcentaje_entrada"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Tiempo de seguro</label>
                                                <select name="GestionFinanciamiento1[tiempo_seguro]" id="GestionFinanciamiento_tiempo_seguro" class="form-control">
                                                    <option value="5">5 años</option>
                                                    <option value="4">4 años</option>
                                                    <option value="3">3 años</option>
                                                    <option value="2">2 años</option>
                                                    <option value="1" selected="true">1 año</option>
                                                    <option value="0">Ninguno</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Plazo</label>
                                                <!--<input type="text" name="GestionFinanciamiento1[plazo]" id="GestionFinanciamiento_plazo" class="form-control" value="60" disabled=""/>-->
                                                <select name="GestionFinanciamiento1[plazo]" id="GestionFinanciamiento_plazo" class="form-control">
                                                    <option value="12">12 meses</option>
                                                    <option value="24">24 meses</option>
                                                    <option value="36">36 meses</option>
                                                    <option value="48">48 meses</option>
                                                    <option value="60" selected="">60 meses</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Valor Financiamiento</label>
                                                <input type="text" name="GestionFinanciamiento1[valor_financiamiento]" id="GestionFinanciamiento_valor_financiamiento" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Tasa</label>
                                                <input type="text" name="GestionFinanciamiento1[tasa]" id="GestionFinanciamiento_tasa" class="form-control" value="16,06" maxlength="5"/>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Seguro</label>
                                                <input type="text" name="GestionFinanciamiento1[seguro]" id="GestionFinanciamiento_seguro" class="form-control" maxlength="10"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Cuota Mensual Aproximada</label>
                                                <input type="text" name="GestionFinanciamiento1[cuota_mensual]" id="GestionFinanciamiento_cuota_mensual" class="form-control" maxlength="10"/>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Observaciones</label>
                                                <textarea name="GestionFinanciamiento1[observaciones]" id="GestionFinanciamiento_observaciones" cols="30" rows="7" onKeyDown="valida_longitud()" onKeyUp="valida_longitud()"></textarea>
                                            </div>
                                        </div>
                                        <div class="row" id="cont-edit1" style="display: none;">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-default btn-xs" id="edit1" onclick="edit(1);">Editar</button>
                                                <button type="button" class="btn btn-default btn-xs" id="save1" onclick="save(1);">Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 cont-options2" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="text-danger">Segunda Opción</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Entidad Financiera</label>
                                                <select name="GestionFinanciamiento2[entidad_financiera]" id="GestionFinanciamiento_entidad_financiera" class="form-control">
                                                    <option value="Banco del Austro">Banco del Austro</option>
                                                    <option value="Banco del Pichincha">Banco del Pichincha</option>
                                                    <option value="BPAC">BPAC</option>
                                                    <option value="Capital">Capital</option>
                                                    <option value="CFC" selected="true">CFC</option>
                                                    <option value="CPN">CPN</option>
                                                    <option value="Coop 29 de Octubre">Coop 29 de Octubre</option>
                                                    <option value="ISSA">ISSFA</option>
                                                    <option value="Originarsa">Originarsa</option>
                                                    <option value="Produbanco">Produbanco</option>
                                                    <option value="Unifinsa">Unifinsa</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Precio Vehículo</label>
                                                <input type="text" name="GestionFinanciamiento2[precio]" id="GestionFinanciamiento_precio2" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,0) ?>" maxlength="11"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Valor de Entrada</label>
                                                <input type="text" name="GestionFinanciamiento2[entrada]" id="GestionFinanciamiento_entrada2" class="form-control" onkeypress="return validateNumbers(event)" autocomplete="off"/>
                                                <label class="error error-entrada2" style="display: none;">Ingrese un valor de entrada igual o superior al 20% del Precio Total</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Porcentaje de Entrada</label>
                                                <input type="text" name="GestionFinanciamiento2[porcentaje]" class="form-control" maxlength="2" id="GestionFinanciamiento_porcentaje_entrada2"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Tiempo de seguro</label>
                                                <select name="GestionFinanciamiento2[tiempo_seguro]" id="GestionFinanciamiento_tiempo_seguro2" class="form-control">
                                                    <option value="5">5 años</option>
                                                    <option value="4">4 años</option>
                                                    <option value="3">3 años</option>
                                                    <option value="2">2 años</option>
                                                    <option value="1" selected="true">1 año</option>
                                                    <option value="0">Ninguno</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Plazo</label>
                                                <!--<input type="text" name="GestionFinanciamiento1[plazo]" id="GestionFinanciamiento_plazo" class="form-control" value="60" disabled=""/>-->
                                                <select name="GestionFinanciamiento2[plazo]" id="GestionFinanciamiento_plazo2" class="form-control">
                                                    <option value="12">12 meses</option>
                                                    <option value="24">24 meses</option>
                                                    <option value="36">36 meses</option>
                                                    <option value="48">48 meses</option>
                                                    <option value="60" selected="">60 meses</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Valor Financiamiento</label>
                                                <input type="text" name="GestionFinanciamiento2[valor_financiamiento]" id="GestionFinanciamiento_valor_financiamiento2" class="form-control" onkeypress="return validateNumbers(event)"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Tasa</label>
                                                <input type="text" name="GestionFinanciamiento2[tasa]" id="GestionFinanciamiento_tasa2" class="form-control" value="16,06" maxlength="5"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Seguro</label>
                                                <input type="text" name="GestionFinanciamiento2[seguro]" id="GestionFinanciamiento_seguro2" class="form-control" maxlength="10"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Cuota Mensual Aproximada</label>
                                                <input type="text" name="GestionFinanciamiento2[cuota_mensual]" id="GestionFinanciamiento_cuota_mensual2" class="form-control" onkeypress="return validateNumbers(event)" maxlength="10"/>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Observaciones</label>
                                                <textarea name="GestionFinanciamiento2[observaciones]" id="GestionFinanciamiento_observaciones2" cols="30" rows="7" onKeyDown="valida_longitud()" onKeyUp="valida_longitud()"></textarea>
                                            </div>
                                        </div>
                                        <div class="row" id="cont-edit2" style="display: none;">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-default btn-xs" id="edit2" onclick="edit(2);">Editar</button>
                                                <button type="button" class="btn btn-default btn-xs" id="save2" onclick="save(2);">Guardar</button>
                                                <!--<button type="button" class="btn btn-default btn-xs" id="delete2" onclick="deleter(2);">Borrar</button>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 cont-options3" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="text-danger">Tercera Opción</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Entidad Financiera</label>
                                                <select name="GestionFinanciamiento3[entidad_financiera]" id="GestionFinanciamiento_entidad_financiera" class="form-control">
                                                    <option value="Banco del Austro">Banco del Austro</option>
                                                    <option value="Banco del Pichincha">Banco del Pichincha</option>
                                                    <option value="BPAC">BPAC</option>
                                                    <option value="Capital">Capital</option>
                                                    <option value="CFC" selected="true">CFC</option>
                                                    <option value="CPN">CPN</option>
                                                    <option value="Coop 29 de Octubre">Coop 29 de Octubre</option>
                                                    <option value="ISSA">ISSFA</option>
                                                    <option value="Originarsa">Originarsa</option>
                                                    <option value="Produbanco">Produbanco</option>
                                                    <option value="Unifinsa">Unifinsa</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Precio Vehículo</label>
                                                <input type="text" name="GestionFinanciamiento3[precio]" id="GestionFinanciamiento_precio3" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,0) ?>" maxlength="11"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Valor de Entrada</label>
                                                <input type="text" name="GestionFinanciamiento3[entrada]" id="GestionFinanciamiento_entrada3" class="form-control" onkeypress="return validateNumbers(event)" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Porcentaje de Entrada</label>
                                                <input type="text" name="GestionFinanciamiento3[porcentaje]" class="form-control" maxlength="2" id="GestionFinanciamiento_porcentaje_entrada3"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Tiempo de seguro</label>
                                                <select name="GestionFinanciamiento3[tiempo_seguro]" id="GestionFinanciamiento_tiempo_seguro3" class="form-control">
                                                    <option value="5">5 años</option>
                                                    <option value="4">4 años</option>
                                                    <option value="3">3 años</option>
                                                    <option value="2">2 años</option>
                                                    <option value="1" selected="true">1 año</option>
                                                    <option value="0">Ninguno</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Plazo</label>
                                                <!--<input type="text" name="GestionFinanciamiento1[plazo]" id="GestionFinanciamiento_plazo" class="form-control" value="60" disabled=""/>-->
                                                <select name="GestionFinanciamiento3[plazo]" id="GestionFinanciamiento_plazo3" class="form-control">
                                                    <option value="12">12 meses</option>
                                                    <option value="24">24 meses</option>
                                                    <option value="36">36 meses</option>
                                                    <option value="48">48 meses</option>
                                                    <option value="60" selected="">60 meses</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Valor Financiamiento</label>
                                                <input type="text" name="GestionFinanciamiento3[valor_financiamiento]" id="GestionFinanciamiento_valor_financiamiento3" class="form-control" onkeypress="return validateNumbers(event)" />
                                                <label class="error error-entrada3" style="display: none;">Ingrese un valor de entrada igual o superior al 20% del Precio Total</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Tasa</label>
                                                <input type="text" name="GestionFinanciamiento3[tasa]" id="GestionFinanciamiento_tasa3" class="form-control" value="16,06" maxlength="5"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Seguro</label>
                                                <input type="text" name="GestionFinanciamiento3[seguro]" id="GestionFinanciamiento_seguro3" class="form-control" maxlength="10"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Cuota Mensual Aproximada</label>
                                                <input type="text" name="GestionFinanciamiento3[cuota_mensual]" id="GestionFinanciamiento_cuota_mensual3" class="form-control" onkeypress="return validateNumbers(event)" maxlength="10"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Observaciones</label>
                                                <textarea name="GestionFinanciamiento3[observaciones]" id="GestionFinanciamiento_observaciones3" cols="30" rows="7" onKeyDown="valida_longitud()" onKeyUp="valida_longitud()"></textarea>
                                            </div>
                                        </div>
                                        <div class="row" id="cont-edit3" style="display: none;">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-default btn-xs" id="edit3" onclick="edit(3);">Editar</button>
                                                <button type="button" class="btn btn-default btn-xs" id="save3" onclick="save(3);">Guardar</button>
                                                <!--<button type="button" class="btn btn-default btn-xs" id="delete3" onclick="deleter(3);">Borrar</button>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                
<!-- ==================================FIN COTIZACION A CREDITO======================================-->
                            <?php else: ?>    

<!-- ==================================INICIO COTIZACION CONTADO=====================================-->
                            <div class="cont-contado">
                                
                                <div class="row">
                                    <div class="col-md-4 cont-options1">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="text-danger">Primera Opción</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Precio Vehículo</label>
                                                <input type="text" name="GestionFinanciamiento1[precio_contado]" id="GestionFinanciamiento_precio_contado" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,1) ?>"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Tiempo de seguro</label>
                                                <select name="GestionFinanciamiento1[tiempo_seguro_contado]" id="GestionFinanciamiento_tiempo_seguro_contado" class="form-control">
                                                    <option value="">----Seleccione tiempo----</option>
                                                    <option value="0">Ninguno</option>
                                                    <option value="1">1 año</option>
                                                    <option value="2">2 años</option>
                                                    <option value="3">3 años</option>
                                                    <option value="4">4 años</option>
                                                    <option value="5">5 años</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Seguro</label>
                                                <input type="text" name="GestionFinanciamiento1[seguro_contado]" id="GestionFinanciamiento_seguro_contado" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Precio Total Vehículo Seguro</label>
                                                <input type="text" name="GestionFinanciamiento1[precio_contado_total]" id="GestionFinanciamiento_precio_contado_total" class="form-control" onkeypress="return validateNumbers(event)" value=""/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Observaciones</label>
                                                <textarea name="GestionFinanciamiento1[observaciones_contado]" id="GestionFinanciamiento_observaciones_contado" cols="30" rows="7"></textarea>
                                            </div>
                                        </div>
                                        <div class="row" id="cont-edit1" style="display: none;">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-default btn-xs" id="edit1" onclick="edit(1);">Editar</button>
                                                <button type="button" class="btn btn-default btn-xs" id="save1" onclick="save(1);">Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 cont-options2" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="text-danger">Segunda Opción</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Precio Vehículo</label>
                                                <input type="text" name="GestionFinanciamiento2[precio_contado]" id="GestionFinanciamiento_precio_contado2" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,1) ?>"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Tiempo de seguro</label>
                                                <select name="GestionFinanciamiento2[tiempo_seguro_contado]" id="GestionFinanciamiento_tiempo_seguro_contado2" class="form-control">
                                                    <option value="">----Seleccione tiempo----</option>
                                                    <option value="0">Ninguno</option>
                                                    <option value="1">1 año</option>
                                                    <option value="2">2 años</option>
                                                    <option value="3">3 años</option>
                                                    <option value="4">4 años</option>
                                                    <option value="5">5 años</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Seguro</label>
                                                <input type="text" name="GestionFinanciamiento2[seguro_contado]" id="GestionFinanciamiento_seguro_contado2" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Precio Total Vehículo Seguro</label>
                                                <input type="text" name="GestionFinanciamiento2[precio_contado_total]" id="GestionFinanciamiento_precio_contado_total2" class="form-control" onkeypress="return validateNumbers(event)" value=""/>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Observaciones</label>
                                                <textarea name="GestionFinanciamiento2[observaciones_contado]" id="GestionFinanciamiento_observaciones_contado2" cols="30" rows="7"></textarea>
                                            </div>
                                        </div>
                                        <div class="row" id="cont-edit2" style="display: none;">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-default btn-xs" id="edit2" onclick="edit(2);">Editar</button>
                                                <button type="button" class="btn btn-default btn-xs" id="save2" onclick="edit(2);">Guardar</button>
                                                <!--<button type="button" class="btn btn-default btn-xs" id="delete2" onclick="deleter(2);">Borrar</button>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 cont-options3" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="text-danger">Tercera Opción</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Precio Vehículo</label>
                                                <input type="text" name="GestionFinanciamiento3[precio_contado]" id="GestionFinanciamiento_precio_contado3" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,1) ?>"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Tiempo de seguro</label>
                                                <select name="GestionFinanciamiento3[tiempo_seguro_contado]" id="GestionFinanciamiento_tiempo_seguro_contado3" class="form-control">
                                                    <option value="">----Seleccione tiempo----</option>
                                                    <option value="0">Ninguno</option>
                                                    <option value="1">1 año</option>
                                                    <option value="2">2 años</option>
                                                    <option value="3">3 años</option>
                                                    <option value="4">4 años</option>
                                                    <option value="5">5 años</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Seguro</label>
                                                <input type="text" name="GestionFinanciamiento3[seguro_contado]" id="GestionFinanciamiento_seguro_contado3" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Precio Total Vehículo Seguro</label>
                                                <input type="text" name="GestionFinanciamiento3[precio_contado_total]" id="GestionFinanciamiento_precio_contado_total3" class="form-control" onkeypress="return validateNumbers(event)" value=""/>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Observaciones</label>
                                                <textarea name="GestionFinanciamiento3[observaciones_contado]" id="GestionFinanciamiento_observaciones_contado3" cols="30" rows="7"></textarea>
                                            </div>
                                        </div>
                                        <div class="row" id="cont-edit3" style="display: none;">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-default btn-xs" id="edit3" onclick="edit(3);">Editar</button>
                                                <button type="button" class="btn btn-default btn-xs" id="save3" onclick="edit(3);">Guardar</button>
                                                <!--<button type="button" class="btn btn-default btn-xs" id="delete3" onclick="deleter(3);">Borrar</button>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
<!-- ==================================FIN COTIZACION CONTADO========================================-->
                                
                            <?php endif; ?>
<!--                            <div class="row" id="select-cot" style="display: none;">
                                <div class="col-md-4">
                                    <label for="">Seleccione número de cotizaciones</label>
                                    <select name="numeroCotizaciones" id="numero_cotizaciones" class="form-control">
                                        <option value="0">--Seleccione un numero--</option>
                                        <option value="2">1</option>
                                        <option value="3">2</option>
                                        <option value="4">3</option>
                                    </select>
                                </div>
                            </div>-->

                            <div class="row buttons">
                                <div class="col-md-2">
                                    <input type="hidden" name="GestionFinanciamiento1[id_informacion]" id="GestionFinanciamiento_id_informacion" value="<?php echo $id_informacion; ?>" />
                                    <input type="hidden" name="GestionFinanciamiento1[id_vehiculo]" id="GestionFinanciamiento_id_vehiculo" value="<?php echo $id_vehiculo; ?>" />
                                    <input type="hidden" name="GestionFinanciamiento1[id_financiamiento]" id="GestionFinanciamiento_id_financiamiento" value="0" />
                                    <input type="hidden" name="GestionFinanciamiento1[flag]" id="GestionFinanciamiento_flag" value="0" />
                                    <input type="hidden" name="GestionFinanciamiento1[mod]" id="GestionFinanciamiento_mod" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acc1]" id="GestionFinanciamiento_acc1" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acc2]" id="GestionFinanciamiento_acc2" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acc3]" id="GestionFinanciamiento_acc3" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acco1]" id="GestionFinanciamiento_acco1" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acco2]" id="GestionFinanciamiento_acco2" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acco3]" id="GestionFinanciamiento_acco3" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[tipo_financiamiento]" id="GestionFinanciamiento_tipo_financiamiento" value="<?php echo $tipo; ?>" />
                                    <input class="btn btn-danger" id="finalizar" type="submit" name="yt0" value="Generar Proforma" onclick="send();">
                                    <input type="hidden" name="fuente" id="fuente" value="<?php echo $fuente; ?>" />
                                </div>

                            </div>
                            <div class="row">
                                <div id="generatepdf" style="display: none;">
                                    <div class="col-md-2" id="contpdf">
                                        <a href="<?php echo Yii::app()->createUrl('gestionVehiculo/proforma/', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" class="btn btn-warning" target="_blank" id="btngenerate">Ver Proforma</a>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-warning" onclick="modProforma();" id="btnmodprof" style="display: none;">Modificar Pdf</button>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-success" href="<?php echo Yii::app()->createUrl('site/proformaCliente/', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" target="_blank" id="btnverprf" style="display: none;">Ver Proforma</a>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-success" onclick="sendProforma();" id="btnsendprof" style="display: none;">Enviar proforma al cliente</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" class="btn btn-danger" id="btnagendamiento" style="display: none;">Agendar Seguimiento</a>
                        <a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/create/', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" class="btn btn-danger" style="display: none;" id="btn-continuar">Generar Solicitud de Crédito</a>
                        <a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" class="btn btn-danger" style="display: none;" id="btn-continuar-ct">Continuar</a>
                    </div>
                </div>
                <br />
                <?= $this->renderPartial('//layouts/rgd/links');?>
            </div>   
        </div>
    </div>
</div>
</div>