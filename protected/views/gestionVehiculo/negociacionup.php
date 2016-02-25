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


$cri5 = new CDbCriteria;
$cri5->condition = "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo}";
$model = GestionFinanciamiento::model()->find($cri5);
//echo 'model financiamiento: '.$model->id;
//$id_financiamiento = $negociacion->id;
        
        
$id_modelo = $this->getIdModelo($id_vehiculo);
//echo 'id modelo: '.$id_modelo;
$tipo = $this->getFinanciamiento($id_informacion);
$id_version = $this->getIdVersion($id_vehiculo);
//echo 'id version: '.$id_version;
$criteria4 = new CDbCriteria(array('condition' => "id_financiamiento = {$model->id}"));
$fi = GestionFinanciamientoOp::model()->count($criteria4);
//echo 'fin: '.$fi;

// lista de accesorios principalde la primera opcion por defecto
$accesorios = GestionVehiculo::model()->find(array('condition' => "id=:match", 'params' => array(':match' => (int) $id_vehiculo)));
$stringAccesorios = $accesorios->accesorios;
//echo '646464646464'.$stringAccesorios;
$arrayAcc0 = $this->getListAccesorios($id_modelo, $id_version, $stringAccesorios);
//echo '<pre>';
//print_r($arrayAcc0);
//echo '</pre>';
//die();
//echo $stringAccesorios;
if ($fi == 1) {
    $fin1 = GestionFinanciamientoOp::model()->find(array('condition' => "id_financiamiento=:match AND num_cotizacion = 3", 'params' => array(':match' => (int) $model->id)));
    $stringAcc = $fin1->accesorios;
    $arrayAcc = $this->getListAccesorios($id_modelo, $id_version, $stringAcc);
}
if ($fi == 2) {
    $fin1 = GestionFinanciamientoOp::model()->find(array('condition' => "id_financiamiento=:match AND num_cotizacion = 3", 'params' => array(':match' => (int) $model->id)));
    $stringAcc = $fin1->accesorios;
    //echo 'accesorios 1: '.$stringAcc;
    $arrayAcc = $this->getListAccesorios($id_modelo, $id_version, $stringAcc);

    $fin2 = GestionFinanciamientoOp::model()->find(array('condition' => "id_financiamiento=:match AND num_cotizacion = 4", 'params' => array(':match' => (int) $model->id)));
    $stringAcc2 = $fin2->accesorios;
    $arrayAcc2 = $this->getListAccesorios($id_modelo, $id_version, $stringAcc2);
}
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script type="text/javascript">
<?php if ($fi == 1): ?>
        var acc1 = <?php echo json_encode($arrayAcc0); ?>;
        var acc2 = <?php echo json_encode($arrayAcc); ?>;
        var acc3 = new Array();
<?php endif; ?>
<?php if ($fi == 2): ?>
        var acc1 = <?php echo json_encode($arrayAcc0); ?>;
        var acc2 = <?php echo json_encode($arrayAcc); ?>;
        var acc3 = <?php echo json_encode($arrayAcc2); ?>;
<?php endif; ?>
<?php if ($fi == 0): ?>
        var acc1 = <?php echo json_encode($arrayAcc0); ?>;
        var acc2 = new Array();
        var acc3 = new Array();
<?php endif; ?>
    //var acc1 = new Array();
    var preciovec1;
    var preciovec2;
    var preciovec3;
    $(document).ready(function () {
<?php if ($fi == 1): ?>
            $('#options-cont').val(3);
            $('.cont-options2').show();
<?php endif; ?>
<?php if ($fi == 2): ?>
            $('#options-cont').val(4);
            $('.cont-options2').show();
            $('.cont-options3').show();
<?php endif; ?>
<?php if ($tipo == 1): // credito              ?>
            //$('.cont-contado').hide();
            $('#GestionFinanciamiento_tipo_financiamiento').val(1);
            //$('.cont-financ').show();
<?php else: ?>
            //$('.cont-contado').show();
            $('#GestionFinanciamiento_tipo_financiamiento').val(0);
            //$('.cont-financ').hide();
<?php endif; ?>

        $('#GestionFinanciamiento_entrada').keyup(function () {
            calcFinanciamiento();
        });
        $('#GestionFinanciamiento_entrada2').keyup(function () {
            calcFinanciamiento2();
        });
        $('#GestionFinanciamiento_entrada3').keyup(function () {
            calcFinanciamiento3();
        });

        $('#btngenerate').click(function () {
            $('#btngenerate').hide();
        });
        //VALORES PRIMERA OPCION FINANCIAMIENTO
        var valorentrada = parseInt($('#GestionFinanciamiento_entrada').val());
        if(valorentrada > 0){
            valorentrada = format2(valorentrada, '$');
        }else{
            valorentrada = format2(0, '$');
        }
        $('#GestionFinanciamiento_entrada').val(valorentrada);

        var valorfinanciamiento = parseInt($('#GestionFinanciamiento_valor_financiamiento').val()); 
        if(valorfinanciamiento > 0){
            valorfinanciamiento = format2(valorfinanciamiento, '$');
        }else{
            valorfinanciamiento = format2(0, '$');
        }
        $('#GestionFinanciamiento_valor_financiamiento').val(valorfinanciamiento);
        
        var valorseguro = parseInt($('#GestionFinanciamiento_seguro').val());    
        valorseguro = format2(valorseguro, '$');
        $('#GestionFinanciamiento_seguro').val(valorseguro);

        var cuotamensual = parseInt($('#GestionFinanciamiento_cuota_mensual').val());
        cuotamensual = format2(cuotamensual, '$');
        if(cuotamensual>0){
            cuotamensual = format2(cuotamensual, '$');
        }else{
            cuotamensual = format2(0, '$');
        }
        $('#GestionFinanciamiento_cuota_mensual').val(cuotamensual);

        //VALORES SEGUNDA OPCION FINANCIAMIENTO
        var valorentrada2 = parseInt($('#GestionFinanciamiento_entrada2').val());
        if (!isNaN(valorentrada2)) {
            valorentrada2 = format2(valorentrada2, '$');
            $('#GestionFinanciamiento_entrada2').val(valorentrada2);
        }
        var valorfinanciamiento2 = parseInt($('#GestionFinanciamiento_valor_financiamiento2').val());
        if (!isNaN(valorfinanciamiento2)) {
            valorfinanciamiento2 = format2(valorfinanciamiento2, '$');
            $('#GestionFinanciamiento_valor_financiamiento2').val(valorfinanciamiento2);
        }
        var valorseguro2 = parseInt($('#GestionFinanciamiento_seguro2').val());
        if (!isNaN(valorseguro2)) {
            valorseguro2 = format2(valorseguro2, '$');
            $('#GestionFinanciamiento_seguro2').val(valorseguro2);
        }
        var cuotamensual2 = parseInt($('#GestionFinanciamiento_cuota_mensual2').val());
        if (!isNaN(cuotamensual2)) {
            cuotamensual2 = format2(cuotamensual2, '$');
            $('#GestionFinanciamiento_cuota_mensual2').val(cuotamensual2);
        }
        //END VALORES SEGUNDA OPCION FINANCIAMIENTO

        //VALORES TERCERA OPCION FINANCIAMIENTO
        var valorentrada3 = parseInt($('#GestionFinanciamiento_entrada3').val());
        if (!isNaN(valorentrada3)) {
            valorentrada3 = format2(valorentrada3, '$');
            $('#GestionFinanciamiento_entrada3').val(valorentrada3);
        }
        var valorfinanciamiento3 = parseInt($('#GestionFinanciamiento_valor_financiamiento3').val());
        if (!isNaN(valorfinanciamiento3)) {
            valorfinanciamiento3 = format2(valorfinanciamiento3, '$');
            $('#GestionFinanciamiento_valor_financiamiento3').val(valorfinanciamiento3);
        }
        var valorseguro3 = parseInt($('#GestionFinanciamiento_seguro3').val());
        if (!isNaN(valorseguro3)) {
            valorseguro3 = format2(valorseguro3, '$');
            $('#GestionFinanciamiento_seguro3').val(valorseguro3);
        }
        var cuotamensual3 = parseInt($('#GestionFinanciamiento_cuota_mensual3').val());
        if (!isNaN(cuotamensual3)) {
            cuotamensual3 = format2(cuotamensual3, '$');
            $('#GestionFinanciamiento_cuota_mensual3').val(cuotamensual3);
        }
        //END VALORES TERCERA OPCION FINANCIAMIENTO
        
        //VALORES PRIMERA OPCION CONTADO
        var segurocontado1 = parseInt($('#GestionFinanciamiento_seguro_contado').val());
        if (!isNaN(segurocontado1)) {
            segurocontado1 = format2(segurocontado1, '$');
            $('#GestionFinanciamiento_seguro_contado').val(segurocontado1);
        }

        var finanprecio = parseInt($('#GestionFinanciamiento_precio').val());
        var finanprecioformat = format2(finanprecio, '$');
        $('#GestionFinanciamiento_precio').val(finanprecioformat);

        var finanprecio2 = parseInt($('#GestionFinanciamiento_precio2').val());
        var finanprecioformat2 = format2(finanprecio2, '$');
        $('#GestionFinanciamiento_precio2').val(finanprecioformat2);

        var finanprecio3 = parseInt($('#GestionFinanciamiento_precio3').val());
        var finanprecioformat3 = format2(finanprecio3, '$');
        $('#GestionFinanciamiento_precio3').val(finanprecioformat3);
        
        var precioContadoTotal = parseInt($('#GestionFinanciamiento_precio_contado_total').val());
        precioContadoTotal = format2(precioContadoTotal, '$');
        $('#GestionFinanciamiento_precio_contado_total').val(precioContadoTotal);
        
        var precioContadoTotal2 = parseInt($('#GestionFinanciamiento_precio_contado_total2').val());
        precioContadoTotal2 = format2(precioContadoTotal2, '$');
        $('#GestionFinanciamiento_precio_contado_total2').val(precioContadoTotal2);


        var precionormal = parseInt($('#precio_normal').val());
        var precioformat = format2(precionormal, '$');
        $('#precio_normal').val(precioformat);
        var precioaccesorios = parseInt($('#precio_accesorios').val());
        var precioformatacc = format2(precioaccesorios, '$');
        $('#precio_accesorios').val(precioformatacc);


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
            switch (optionscont) {
                case '2':
                    $('#cont-edit1').show();
                    $('#cont-edit2').show();
                    $('#cont-edit3').show();
                    break;
                case '3':
                    $('#cont-edit1').show();
                    $('#cont-edit2').show();
                    $('#cont-edit3').show();
                    break;
                case '4':
                    $('#cont-edit1').show();
                    $('#cont-edit2').show();
                    $('#cont-edit3').show();
                    break;
            }
            $('#contpdf').hide();
            $('#btnsendprof').show();
            $('#btnmodprof').show();
            //$('#select-cot').show();
        });

        $('#GestionFinanciamiento_entrada').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_entrada2').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});
        $('#GestionFinanciamiento_entrada3').maskMoney({prefix: '$ ', allowNegative: true, thousands: ',', decimal: '.', affixesStay: true});

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
            console.log('id informacion: ' + idinfo);
            if (valorFin == 'Contado') { // cambiar a contado
                // make a update to table gestion_consulta with value 0 in column preg6
                // refresh page
                $.ajax({
                    url: '<?php echo Yii::app()->createAbsoluteUrl("gestionConsulta/setFinanciamiento"); ?>',
                    type: 'POST',
                    data: {idInformacion: idinfo, tipo: 0},
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
                    data: {idInformacion: idinfo, tipo: 1},
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
            var accesorio2 = $(this).val();
            var idacc = $(this).prop('id');
            var id = idacc.split("-");
            var precioanterior = $('#precio_accesorios').val();
            var valorFin = $('#GestionFinanciamiento_entrada').val();
            var valorFin2 = $('#GestionFinanciamiento_entrada2').val();
            var valorFin3 = $('#GestionFinanciamiento_entrada3').val();
            var counter = $('#options-cont').val();// valor del contador de formularios
            //alert('val of counter: '+counter);
            var flag = $('#GestionFinanciamiento_flag').val();// saber si se ha generado una proforma
            // valor del contador de proformas
            var savecounter = 0; // numero de formulario a editar original
            // si no se ha generado una proforma, continua el proceso normal
            if (flag == 0) {

            }
            // ya se ha generado una proforma
            if (flag == 1) {
                savecounter = $('#options-cont').val();// guardamos el valor del contador de formularios
                // asignamos al contador de formularios el valor del click edit() del formulario
                counter = $('#GestionFinanciamiento_mod').val();
            }

            var tipoFinanciamiento = $('#GestionFinanciamiento_tipo_financiamiento').val();
            var accesorioscont = $('#accesorioscont').val();
            var stracc1 = '';
            var stracc2 = '';
            var stracc3 = '';

            precioanterior = precioanterior.replace(',', '');
            precioanterior = precioanterior.replace('.', ',');
            precioanterior = precioanterior.replace('$', '');
            precioanterior = parseInt(precioanterior);
            if ($(this).prop('checked')) {
                var precionuevo = parseInt(precioanterior) + parseInt(accesorio2);
                $('#precio_accesorios').val(format2(precionuevo, '$'));
                switch (counter) {
                    case '2':
                        acc1.length = 0;
                        //console.log('enter 2');
                        $('#GestionFinanciamiento_precio').val(format2(precionuevo, '$'));
                        $('#GestionFinanciamiento_precio_contado').val(format2(precionuevo, '$'));
                        //console.log('precio nuevo: '+precionuevo);
                        preciovec1 = format2(precionuevo, '$');
                        if (tipoFinanciamiento == 0) {// tipo contado
                            calcFinanciamientoContado();
                        } else {
                            if (valorFin != '') {
                                calcFinanciamiento();
                            }
                        }

                        for (var i = 1; i <= accesorioscont; i++) {
                            if ($('#accesorio-' + i).prop('checked')) {// si el input check esta true
                                //console.log('Accesorio '+i+', checked');
                                acc1.push(i);//suma un valor al arrar de accesorios1
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
                        console.log('ARRAY ACCESORIOS 1: ' + acc1);
                        if (flag == 1) {// si se ha generado una proforma
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
                        if (tipoFinanciamiento == 0) {// tipo contado
                            calcFinanciamientoContado2();
                        } else {
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
                        console.log('ARRAY ACCESORIOS 2: ' + acc2);
                        if (flag == 1) {
                            // volver a poner el valor del contador original
                            $('#options-cont').val(savecounter);
                        }
                        break;
                    case '4':
                        acc3.length = 0;
                        $('#GestionFinanciamiento_precio3').val(format2(precionuevo, '$'));
                        $('#GestionFinanciamiento_precio_contado3').val(format2(precionuevo, '$'));
                        preciovec3 = format2(precionuevo, '$');
                        if (tipoFinanciamiento == 0) {// tipo contado
                            calcFinanciamientoContado3();
                        } else {
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
                        console.log('ARRAY ACCESORIOS 3: ' + acc3);
                        if (flag == 1) {
                            // volver a poner el valor del contador original
                            $('#options-cont').val(savecounter);
                        }
                        break;
                }
                //$('#GestionFinanciamiento_precio_contado').val(format2(precionuevo, '$'));
                $('#accspan-' + id[1]).addClass('label-price');

            } else {
                var precionuevo = parseInt(precioanterior) - parseInt(accesorio2);
                $('#precio_accesorios').val(format2(precionuevo, '$'));
                switch (counter) {
                    case '2':
                        acc1.length = 0;
                        $('#GestionFinanciamiento_precio').val(format2(precionuevo, '$'));
                        $('#GestionFinanciamiento_precio_contado').val(format2(precionuevo, '$'));
                        preciovec1 = format2(precionuevo, '$');
                        if (tipoFinanciamiento == 0) {// tipo contado
                            calcFinanciamientoContado();
                        } else {
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
                        console.log('ARRAY ACCESORIOS 1: ' + acc1);
                        if (flag == 1) {
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
                        if (tipoFinanciamiento == 0) {// tipo contado
                            calcFinanciamientoContado2();
                        } else {
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
                        console.log('ARRAY ACCESORIOS 2: ' + acc2);
                        if (flag == 1) {
                            // volver a poner el valor del contador original
                            $('#options-cont').val(savecounter);
                        }
                        break;
                    case '4':
                        acc3.length = 0;
                        $('#GestionFinanciamiento_precio3').val(format2(precionuevo, '$'));
                        $('#GestionFinanciamiento_precio_contado3').val(format2(precionuevo, '$'));
                        preciovec3 = format2(precionuevo, '$');
                        if (tipoFinanciamiento == 0) {// tipo contado
                            calcFinanciamientoContado3();
                        } else {
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
                        console.log('ARRAY ACCESORIOS 3: ' + acc3);
                        if (flag == 1) {
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
    function op() {
        //acc1.length = 0;
        //acc2.length = 0;
        //acc3.length = 0;
        //console.log('enter op');
        var counter = $('#options-cont').val();
        var accesorioscont = $('#accesorioscont').val();
        var precionormal = $('#precio_normal').val();
        var flag = $('#GestionFinanciamiento_flag').val();
        var stracc1 = '';
        var stracc2 = '';
        var stracc3 = '';
        //console.log('COTIZACION: '+counter);
        switch (counter) {
            case '2':
                acc1.length = 0;
                $('#precio_accesorios').val(precionormal);
                //console.log('llenar array de accesorios 1');
                if (flag == 0) { // Se llena por primera vez
                    for (var i = 1; i <= accesorioscont; i++) {
                        if ($('#accesorio-' + i).prop('checked')) {
                            //console.log('Accesorio '+i+', checked');
                            acc1.push(i);
                            sat = $('#accesorio-' + i).val();
                            param = sat.split('-');
                            stracc1 += sat + '@';
                            if (param[1] != 'Kit Satelital') {
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
                console.log('array accesorios 1: ' + acc1);
                break;
            case '3':
                acc2.length = 0;
                $('#precio_accesorios').val(precionormal);
                //console.log('llenar array de accesorios 2');
                if (flag == 0) { // Se llena por primera vez
                    for (var i = 1; i <= accesorioscont; i++) {
                        if ($('#accesorio-' + i).prop('checked')) {
                            //console.log('Accesorio '+i+', checked');
                            acc2.push(i);
                            sat = $('#accesorio-' + i).val();
                            param = sat.split('-');
                            stracc2 += sat + '@';
                            if (param[1] != 'Kit Satelital') {
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
                console.log('array accesorios 2: ' + acc2);
                break;
            case '4':
                acc3.length = 0;
                if (flag == 0) { // Se llena por primera vez
                    for (var i = 1; i <= accesorioscont; i++) {
                        if ($('#accesorio-' + i).prop('checked')) {
                            //console.log('Accesorio '+i+', checked');
                            acc3.push(i);
                            sat = $('#accesorio-' + i).val();
                            param = sat.split('-');
                            stracc3 += sat + '@';
                            if (param[1] != 'Kit Satelital') {
                                $('#accesorio-' + i).attr('checked', false);
                                $('#accspan-' + i).removeClass('label-price');
                            }

                        }
                    }
                    $('#GestionFinanciamiento_acc3').val(stracc3);
                    console.log('array accesorios 3: ' + acc3);
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
        console.log('accesorios1 opcanc: ' + acc1);
        console.log('accesorios2 opcanc: ' + acc2);
        console.log('accesorios3 opcanc: ' + acc3);
        var counter = $('#options-cont').val();
        var accesorioscont = $('#accesorioscont').val();
        var precionormal = $('#precio_normal').val();
        var tipoFinanc = $('#GestionFinanciamiento_tipo_financiamiento').val();
        var flag = $('#GestionFinanciamiento_flag').val();
        console.log('counter en opcanc: ' + counter);
        switch (counter) {
            case '3': // volcar datos al primer cotizador, checks 
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
                }
                console.log('array accesorios cancelar 1: ' + acc1.toString());
                var lt = acc1.length;
                console.log('long of acc1: ' + lt);
                for (var i = 0; i <= lt; i++) {
                    $('#accesorio-' + acc1[i]).attr('checked', true);
                    $('#accspan-' + acc1[i]).addClass('label-price');
                }
                //acc1.length = 0;
                if (tipoFinanc == 1) {
                    $('#precio_accesorios').val($('#GestionFinanciamiento_precio').val());
                } else {
                    $('#precio_accesorios').val($('#GestionFinanciamiento_precio_contado').val());
                }

                break;
            case '4':// volcar datos al segundo cotizador, checks 
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
                }
                console.log('array accesorios cancelar 2: ' + acc2.toString());
                var lt = acc2.length;
                for (var i = 0; i <= lt; i++) {
                    $('#accesorio-' + acc2[i]).attr('checked', true);
                    $('#accspan-' + acc2[i]).addClass('label-price');
                }
                //acc2.length = 0;
                if (tipoFinanc == 1) {
                    $('#precio_accesorios').val($('#GestionFinanciamiento_precio2').val());
                } else {
                    $('#precio_accesorios').val($('#GestionFinanciamiento_precio_contado2').val());
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
        $.ajax({
            url: '/intranet/usuario/index.php/gestionFinanciamiento/updatefn',
            beforeSend: function (xhr) {
                //$('#bg_negro').show();  // #bg_negro must be defined somewhere
            },
            datatype: "json",
            type: 'POST',
            data: dataform,
            success: function (data) {
                //var returnedData = JSON.parse(data);
                $('#bg_negro').hide();
                alert('Datos actualizados correctamente');
                $("#btnverprf").show();
                <?php if($tipo == 1):// credito ?>
                    $('#btn-continuar').show();
                <?php else: ?> 
                    $("#btn-continuar-ct").show();
                <?php endif; ?>    
                $('#btnagendamiento').show();$("#btnsendprof").show();
                
            }
        });
    }
    function sendProforma() {
        var idInfo = $('#GestionFinanciamiento_id_informacion').val();
        var idVec = $('#GestionFinanciamiento_id_vehiculo').val();
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/sendProforma"); ?>',
            beforeSend: function (xhr) {
                $('#bg_negro').show();  // #bg_negro must be defined somewhere
            },
            datatype: "json",
            type: 'POST',
            data: {id_informacion: idInfo, id_vehiculo: idVec},
            success: function (data) {
                alert('Email enviado satisfactoriamente');
                $('#bg_negro').hide();
                $('#btnsendprof').show();
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
                    }, 'GestionFinanciamiento1[seguro]': {
                        required: true
                    }, 'GestionFinanciamiento1[couta_mensual]': {
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
                    }, 'GestionFinanciamiento1[seguro]': {
                        required: 'Ingrese seguro'
                    }, 'GestionFinanciamiento1[couta_mensual]': {
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
                            $('#GestionFinanciamiento_seguro').removeAttr('disabled');
                            $('#GestionFinanciamiento_cuota_mensual').removeAttr('disabled');
                            $('#GestionFinanciamiento_plazo').removeAttr('disabled');
                            $('#GestionFinanciamiento_tiempo_seguro').removeAttr('disabled');
                            if ($('#GestionFinanciamiento_tipo_financiamiento').val() == 1)
                                $('.def').removeAttr('disabled');
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
                    var valorEntrada1 = $('#GestionFinanciamiento_entrada').val();
                    var precioAccesorios = $('#precio_accesorios').val();

                    var entrada = precioAccesorios / 4;
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
                            $('#GestionFinanciamiento_flag').val(1);
                            console.log('ARRAY ACCESORIOS1 LUEGO DE GRABAR: ' + acc1);
                            console.log('ARRAY ACCESORIOS2 LUEGO DE GRABAR: ' + acc2);
                            console.log('ARRAY ACCESORIOS3 LUEGO DE GRABAR: ' + acc3);
                            if (tipoFinanciamiento == 'Crédito') {
                                $('#btn-continuar').show();
                                $(".def").attr('disabled', 'disabled');
                                $('#modificar1').show();
                                $('#modificar2').show();
                                $('#modificar3').show();
                                //alert('Id de Proforma: '+returnedData.id);
                                $('#GestionFinanciamiento_id_financiamiento').val(returnedData.id);
                            } else if (tipoFinanciamiento == 'Contado') {
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
        console.log('valor vehiculo contado: ' + valorVehiculo);

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
        var iva = subtotal * 0.12;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);

        var precioAccesorios = $('#precio_accesorios').val();
        precioAccesorios = precioAccesorios.replace(',', '');
        precioAccesorios = precioAccesorios.replace('.', ',');
        precioAccesorios = precioAccesorios.replace('$', '');
        precioAccesorios = parseInt(precioAccesorios);
        var entrada = precioAccesorios / 4;

        var valorSeguro = format2(primaTotal, '$');
        //console.log('valorseguro: '+valorSeguro);
        var valorTotal = valorVehiculo + primaTotal;
        valorTotal = format2(valorTotal, '$');

        $('#GestionFinanciamiento_precio_contado_total').val(valorTotal);
        //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
        $('#GestionFinanciamiento_seguro_contado').val(valorSeguro);
    }
    function calcFinanciamientoContado2() {
        //var valorEntrada1 = $('#GestionFinanciamiento_entrada3').attr('value');
        var valorVehiculo = $('#GestionFinanciamiento_precio_contado2').val();
        //var plazo = $('#GestionFinanciamiento_plazo3').val();
        var seguro = $('#GestionFinanciamiento_tiempo_seguro_contado2').val();

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
        var iva = subtotal * 0.12;
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
        //alert(valorTotal);
        $('#GestionFinanciamiento_precio_contado_total2').val(valorTotal);
        //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
        $('#GestionFinanciamiento_seguro_contado2').val(valorSeguro);
    }

    function calcFinanciamientoContado3() {
        //var valorEntrada1 = $('#GestionFinanciamiento_entrada3').attr('value');
        var valorVehiculo = $('#GestionFinanciamiento_precio_contado3').val();
        //var plazo = $('#GestionFinanciamiento_plazo3').val();
        var seguro = $('#GestionFinanciamiento_tiempo_seguro_contado3').val();

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
        var iva = subtotal * 0.12;
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
        var iva = subtotal * 0.12;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);
        var precioEntrada = valorEntrada1.replace(',', '');
        precioEntrada = precioEntrada.replace('.', ',');
        precioEntrada = precioEntrada.replace('$', '');
        precioEntrada = parseInt(precioEntrada);
        var precioAccesorios = $('#precio_accesorios').val();
        precioAccesorios = precioAccesorios.replace(',', '');
        precioAccesorios = precioAccesorios.replace('.', ',');
        precioAccesorios = precioAccesorios.replace('$', '');
        precioAccesorios = parseInt(precioAccesorios);
        var entrada = precioAccesorios / 4;
        if (precioEntrada < entrada) {
            $('.error-entrada3').show();
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
                data: {taza: 15, numpagos: 12, valorPrest: valorFinanciamientoAnt, plazo: plazo},
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
        var valorEntrada1 = $('#GestionFinanciamiento_entrada').attr('value');
        var valorVehiculo = $('#GestionFinanciamiento_precio').val();
        console.log('valor vehiculo: ' + valorVehiculo);
        var plazo = $('#GestionFinanciamiento_plazo').val();
        var seguro = $('#GestionFinanciamiento_tiempo_seguro').val();


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
        var iva = subtotal * 0.12;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);
        var precioEntrada = valorEntrada1.replace(',', '');
        precioEntrada = precioEntrada.replace('.', ',');
        precioEntrada = precioEntrada.replace('$', '');
        precioEntrada = parseInt(precioEntrada);
        var precioAccesorios = $('#precio_accesorios').val();
        precioAccesorios = precioAccesorios.replace(',', '');
        precioAccesorios = precioAccesorios.replace('.', ',');
        precioAccesorios = precioAccesorios.replace('$', '');
        precioAccesorios = parseInt(precioAccesorios);
        var entrada = precioAccesorios / 4;
        if (precioEntrada < entrada) {
            $('.error-entrada').show();
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
                data: {taza: 15, numpagos: 12, valorPrest: valorFinanciamientoAnt, plazo: plazo},
                success: function (data) {
                    var cuotamensual = parseInt(data.cuota);
                    cuotamensual = format2(cuotamensual, '$');
                    $('#GestionFinanciamiento_cuota_mensual').val(cuotamensual);
                    var valorFin = parseInt(data.valorFinanciamiento);
                    valorFin = format2(valorFin, '$');
                    $('#GestionFinanciamiento_valor_financiamiento').val(valorFin);
                    $('#bg_negro').hide();
                    console.log('VALOR ACCCESORIO 1 FINAN: ' + acc1);
                }
            });
        }
    }
    function calcFinanciamiento2() {
        var valorEntrada1 = $('#GestionFinanciamiento_entrada2').attr('value');
        var valorVehiculo = $('#GestionFinanciamiento_precio2').val();
        var plazo = $('#GestionFinanciamiento_plazo2').val();
        var seguro = $('#GestionFinanciamiento_tiempo_seguro2').val();

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
        var iva = subtotal * 0.12;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);
        var precioEntrada = valorEntrada1.replace(',', '');
        precioEntrada = precioEntrada.replace('.', ',');
        precioEntrada = precioEntrada.replace('$', '');
        precioEntrada = parseInt(precioEntrada);
        var precioAccesorios = $('#precio_accesorios').val();
        precioAccesorios = precioAccesorios.replace(',', '');
        precioAccesorios = precioAccesorios.replace('.', ',');
        precioAccesorios = precioAccesorios.replace('$', '');
        precioAccesorios = parseInt(precioAccesorios);
        var entrada = precioAccesorios / 4;
        if (precioEntrada < entrada) {
            $('.error-entrada2').show();
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
                data: {taza: 15, numpagos: 12, valorPrest: valorFinanciamientoAnt, plazo: plazo},
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
    function edit(id) {
        // get tipo financimiento tipo 1 credito, 0 contado
        var tipoFinanciamiento = $('#GestionFinanciamiento_tipo_financiamiento').val();
        //console.log('tipo financiamiento: '+tipoFinanciamiento);
        var accesorioscont = $('#accesorioscont').val();
        var precioanterior = $('#precio_normal').val();
        precioanterior = precioanterior.replace(',', '');
        precioanterior = precioanterior.replace('.', ',');
        precioanterior = precioanterior.replace('$', '');
        precioanterior = parseInt(precioanterior);
        var accesorio2 = 0;
        //console.log('valor id: '+id);
        switch (id) {
            case 1:
                console.log('EDITAR ACCESORIOS 1: ' + acc1);
                $('#options-cont').val(2);
                $('.cont-options1').addClass('cont-options1-after');
                $('.cont-options2').removeClass('cont-options1-after');
                $('.cont-options3').removeClass('cont-options1-after');
                $('#GestionFinanciamiento_mod').val(2);
                $('#GestionFinanciamiento_entrada').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo').removeAttr('disabled');

                // primero quitar los checks de los otros que esten seleccionados
                if (tipoFinanciamiento == 0) {
                    for (var j = 1; j <= accesorioscont; j++) {
                        sat = $('#accesorio-' + j).val();
                        param = sat.split('-');
                        //console.log('enter checked false: '+ j);
                        $('#accesorio-' + j).attr('checked', false);
                        $('#accspan-' + j).removeClass('label-price');
                    }
                } else {// para credito no quitar check al Kit Satelital
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
                    if (params2[1] != 'Kit Satelital') {
                        accesorio2 += parseInt(params2[0]);
                    }
                }
                // sumar todo los valores de los checks al precio vehiculo

                var precionuevo = parseInt(precioanterior) + parseInt(accesorio2);
                $('#precio_accesorios').val(format2(precionuevo, '$'));
                if (tipoFinanciamiento == 0) { // TIPO CONTADO
                    secure = $('#GestionFinanciamiento_tiempo_seguro_contado').val();
                    if (secure != '' || secure != '0') {
                        calcFinanciamientoContado();
                    }
                } else {
                    $('#GestionFinanciamiento_precio').val(format2(precionuevo, '$'));
                    calcFinanciamiento();
                }
                break;
            case 2:
                console.log('EDITAR ACCESORIOS 2: ' + acc2);
                $('#options-cont').val(3);
                $('#GestionFinanciamiento_mod').val(3);
                $('.cont-options2').addClass('cont-options1-after');
                $('.cont-options1').removeClass('cont-options1-after');
                $('.cont-options3').removeClass('cont-options1-after');
                $('#GestionFinanciamiento_entrada2').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro2').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo2').removeAttr('disabled');
                // primero quitar los checks de los otros que esten seleccionados
                if (tipoFinanciamiento == 0) {
                    for (var j = 1; j <= accesorioscont; j++) {
                        sat = $('#accesorio-' + j).val();
                        param = sat.split('-');
                        //console.log('enter checked false: '+ j);
                        $('#accesorio-' + j).attr('checked', false);
                        $('#accspan-' + j).removeClass('label-price');
                    }
                } else {// para credito no quitar check al Kit Satelital
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
                    console.log('val pri:' + pri);
                    params2 = pri.split('-');
                    if (params2[1] != 'Kit Satelital') {
                        accesorio2 += parseInt(params2[0]);
                    }
                }
                // sumar todo los valores de los checks al precio vehiculo

                var precionuevo = parseInt(precioanterior) + parseInt(accesorio2);
                $('#precio_accesorios').val(format2(precionuevo, '$'));

                if (tipoFinanciamiento == 0) { // TIPO CONTADO
                    secure = $('#GestionFinanciamiento_tiempo_seguro_contado2').val();
                    if (secure != '' || secure != '0') {
                        calcFinanciamientoContado2();
                    }
                } else {
                    $('#GestionFinanciamiento_precio2').val(format2(precionuevo, '$'));
                    calcFinanciamiento2();
                }

                break;
            case 3:
                console.log('EDITAR ACCESORIOS 3: ' + acc3);
                $('#options-cont').val(4);
                $('#GestionFinanciamiento_mod').val(4);
                $('.cont-options3').addClass('cont-options1-after');
                $('.cont-options1').removeClass('cont-options1-after');
                $('.cont-options2').removeClass('cont-options1-after');
                $('#GestionFinanciamiento_entrada3').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro3').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo3').removeAttr('disabled');
                // primero quitar los checks de los otros que esten seleccionados
                if (tipoFinanciamiento == 0) {
                    for (var j = 1; j <= accesorioscont; j++) {
                        sat = $('#accesorio-' + j).val();
                        param = sat.split('-');
                        //console.log('enter checked false: '+ j);
                        $('#accesorio-' + j).attr('checked', false);
                        $('#accspan-' + j).removeClass('label-price');
                    }
                } else {// para credito no quitar check al Kit Satelital
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
                    console.log('val pri:' + pri);
                    params2 = pri.split('-');
                    if (params2[1] != 'Kit Satelital') {
                        accesorio2 += parseInt(params2[0]);
                    }
                }
                // sumar todo los valores de los checks al precio vehiculo

                var precionuevo = parseInt(precioanterior) + parseInt(accesorio2);
                $('#precio_accesorios').val(format2(precionuevo, '$'));

                if (tipoFinanciamiento == 0) { // TIPO CONTADO
                    secure = $('#GestionFinanciamiento_tiempo_seguro_contado3').val();
                    if (secure != '' || secure != '0') {
                        calcFinanciamientoContado3();
                    }
                } else {
                    $('#GestionFinanciamiento_precio3').val(format2(precionuevo, '$'));
                    calcFinanciamiento3();
                }

                break;
        }
    }
    function save(id) {
        switch (id) {
            case 1:
                calcFinanciamiento();
                $('.cont-options1').removeClass('cont-options1-after');
            case 2:
                calcFinanciamiento2();
                $('.cont-options2').removeClass('cont-options2-after');
                break;
            case 3:
                calcFinanciamiento3();
                $('.cont-options3').removeClass('cont-options3-after');
                break;
        }
    }

    function deleter(id) {
        console.log(id)
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

            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionVehiculo/create/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
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
                                            <?php if ($cl[0]['cedula'] != ''): ?>
                                                <tr class="odd"><th>Cédula</th><td><?php echo $cl[0]['cedula']; ?></td></tr> 
                                            <?php endif; ?>
                                            <?php if ($cl[0]['ruc'] != ''): ?>
                                                <tr class="odd"><th>RUC</th><td><?php echo $cl[0]['ruc']; ?></td></tr> 
                                            <?php endif; ?>
                                            <?php if ($cl[0]['pasaporte'] != ''): ?>
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
                            <?php if ($cn3 > 0): ?>
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
                                            . ' WHERE id_vehiculo = ' . $id_modelo . ' AND status = 1  AND opcional = 0 AND id_version = ' . $id_version . ' ORDER BY accesorio ASC LIMIT 0,' . $limite1 . '')
                                    ->queryAll();
                            $rows2 = Yii::app()->db->createCommand('SELECT * FROM gestion_accesorios'
                                            . ' WHERE id_vehiculo = ' . $id_modelo . ' AND status = 1  AND opcional = 0 AND id_version = ' . $id_version . ' ORDER BY accesorio ASC LIMIT ' . $limite1 . ',' . $cn . '')
                                    ->queryAll();

                            $count = 1;
                            ?>
                            <?php if ($cn > 0): ?>

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
                                                                        <input type="checkbox" value="<?php echo $value['precio'] . '-' . $value['accesorio']; ?>" name="accesorios[]" id="accesorio-<?php echo $count; ?>" <?php if ($value['codigo'] == 7 && $tipo == 1) { ?> disabled="" class="def" checked=""<?php } ?>>
                                                                        <?php echo $value['accesorio']; ?>
                                                                        <input type="hidden" name="<?php echo $value['accesorio']; ?>" id="acc<?php echo $count; ?>" value="0"/>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="checkbox">
                                                                    <span class="label label-default <?php
                                                                    if ($value['codigo'] == 7 && $tipo == 1) {
                                                                        echo 'label-price';
                                                                    }
                                                                    //$this->getLabelAcc($value['precio'] . '-' . $value['accesorio'],$stringAccesorios);
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
                                                                        <input type="checkbox" value="<?php echo $value['precio'] . '-' . $value['accesorio']; ?>" name="accesorios[]" id="accesorio-<?php echo $count; ?>" <?php if ($value['codigo'] == 7 && $tipo == 1) { ?> checked="" disabled="" class="def" <?php } ?><?php //$this->getCheckedAcc($value['precio'] . '-' . $value['accesorio'],$stringAccesorios );   ?>>
                                                                        <?php echo $value['accesorio']; ?>
                                                                        <input type="hidden" name="<?php echo $value['accesorio']; ?>" id="acc<?php echo $count; ?>" value="0"/>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="checkbox">
                                                                    <span class="label label-default <?php
                                                                    if ($value['codigo'] == 7 && $tipo == 1) {
                                                                        echo 'label-price';
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
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
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="precio_normal">Precio Normal</label>
                                    <input type="text" name="precio_normal" id="precio_normal" class="form-control" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,$id_modelo); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="precio_accesorios">Precio con Accesorios</label>
                                    <input type="text" name="precio_accesorios" id="precio_accesorios" class="form-control input-acc" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,$id_modelo); ?>" data-symbol="$ " data-thousands="." data-decimal=",">
                                    <input type="hidden" name="precio_accesorios_anterior" id="precio_accesorios_anterior" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,$id_modelo); ?>"/>
                                </div>
                            </div>
                            <br />
                            <?php if ($tipo == 1): // credito             ?>
                                <!-- ==================================INICIO COTIZACION A CREDITO===================================-->
                                <div class="cont-financ">
                                    <div class="row">
                                        <h1 class="tl_seccion">Proforma</h1>
                                    </div>

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
                                                        <option value="CFC">CFC</option>
                                                        <option value="Banco del Pichincha">Banco del Pichincha</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Vehículo</label>
                                                    <input type="text" name="GestionFinanciamiento1[precio]" id="GestionFinanciamiento_precio" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $model->precio_vehiculo; ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Valor de Entrada</label>
                                                    <input type="text" name="GestionFinanciamiento1[entrada]" id="GestionFinanciamiento_entrada" class="form-control" value="<?php echo $model->cuota_inicial; ?>"/>
                                                    <label class="error error-entrada" style="display: none;">Ingrese un valor de entrada igual o superior al 25% del Precio Total</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Tiempo de seguro</label>
                                                    <select name="GestionFinanciamiento1[tiempo_seguro]" id="GestionFinanciamiento_tiempo_seguro" class="form-control">
                                                        <option value="5" <?php if($model->ts == 5){echo "selected";} ?>>5 años</option>
                                                        <option value="4" <?php if($model->ts == 4){echo "selected";} ?>>4 años</option>
                                                        <option value="3" <?php if($model->ts == 3){echo "selected";} ?>>3 años</option>
                                                        <option value="2" <?php if($model->ts == 2){echo "selected";} ?>>2 años</option>
                                                        <option value="1" <?php if($model->ts == 1){echo "selected";} ?>>1 año</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Plazo</label>
                                                    <!--<input type="text" name="GestionFinanciamiento1[plazo]" id="GestionFinanciamiento_plazo" class="form-control" value="60" disabled=""/>-->
                                                    <select name="GestionFinanciamiento1[plazo]" id="GestionFinanciamiento_plazo" class="form-control">
                                                        <option value="12" <?php if($model->plazos == 12){echo "selected";} ?>>12 meses</option>
                                                            <option value="24" <?php if($model->plazos == 24){echo "selected";} ?>>24 meses</option>
                                                            <option value="36" <?php if($model->plazos == 36){echo "selected";} ?>>36 meses</option>
                                                            <option value="48" <?php if($model->plazos == 48){echo "selected";} ?>>48 meses</option>
                                                            <option value="60" <?php if($model->plazos == 60){echo "selected";} ?>>60 meses</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Valor Financiamiento</label>
                                                    <input type="text" name="GestionFinanciamiento1[valor_financiamiento]" id="GestionFinanciamiento_valor_financiamiento" class="form-control" readonly="true" value="<?php echo $model->valor_financiamiento; ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Tasa</label>
                                                    <input type="text" name="GestionFinanciamiento1[tasa]" id="GestionFinanciamiento_tasa" class="form-control" value="16,06" disabled=""/>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Seguro</label>
                                                    <input type="text" name="GestionFinanciamiento1[seguro]" id="GestionFinanciamiento_seguro" class="form-control" readonly="true" value="<?php echo $model->seguro; ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Cuota Mensual Aproximada</label>
                                                    <input type="text" name="GestionFinanciamiento1[cuota_mensual]" id="GestionFinanciamiento_cuota_mensual" class="form-control" readonly="true" value="<?php echo $model->cuota_mensual; ?>"/>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Observaciones</label>
                                                    <textarea name="GestionFinanciamiento1[observaciones]" id="GestionFinanciamiento_observaciones" cols="30" rows="7" onKeyDown="valida_longitud()" onKeyUp="valida_longitud()"><?php echo $model->observaciones; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row" id="cont-edit1">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-default btn-xs" id="edit1" onclick="edit(1);">Editar</button>
                                                    <button type="button" class="btn btn-default btn-xs" id="save1" onclick="save(1);">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                            $fin1 = GestionFinanciamientoOp::model()->find(array('condition' => "id_financiamiento=:match AND num_cotizacion = 3", 'params' => array(':match' => (int) $model->id)));
                                        ?>
                                        <?php //if ($fin1) { ?>
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
                                                            <option value="CFC">CFC</option>
                                                            <option value="Banco del Pichincha">Banco del Pichincha</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Precio Vehículo</label>
                                                        <input type="text" name="GestionFinanciamiento2[precio]" id="GestionFinanciamiento_precio2" class="form-control" onkeypress="return validateNumbers(event)" value="<?php
                                                        if ($fi == 1 || $fi == 2) {
                                                            echo $fin1->precio_vehiculo;
                                                        } else {
                                                            echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,$id_modelo);
                                                        }
                                                        ?>"/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Valor de Entrada</label>
                                                        <input type="text" name="GestionFinanciamiento2[entrada]" id="GestionFinanciamiento_entrada2" class="form-control" onkeypress="return validateNumbers(event)" value="<?php
                                                        if ($fi == 1 || $fi == 2) {
                                                            echo $fin1->cuota_inicial;
                                                        }
                                                        ?>"/>
                                                        <label class="error error-entrada2" style="display: none;">Ingrese un valor de entrada igual o superior al 25% del Precio Total</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Tiempo de seguro</label>
                                                        <select name="GestionFinanciamiento2[tiempo_seguro]" id="GestionFinanciamiento_tiempo_seguro2" class="form-control">
                                                            <option value="5" <?php if($fin1 && $fin1->ts == 5){echo "selected";} ?>>5 años</option>
                                                            <option value="4" <?php if($fin1 && $fin1->ts == 4){echo "selected";} ?>>4 años</option>
                                                            <option value="3" <?php if($fin1 && $fin1->ts == 3){echo "selected";} ?>>3 años</option>
                                                            <option value="2" <?php if($fin1 && $fin1->ts == 2){echo "selected";} ?>>2 años</option>
                                                            <option value="1" <?php if($fin1 && $fin1->ts == 1){echo "selected";} ?>>1 año</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Plazo</label>
                                                        <!--<input type="text" name="GestionFinanciamiento1[plazo]" id="GestionFinanciamiento_plazo" class="form-control" value="60" disabled=""/>-->
                                                        <select name="GestionFinanciamiento2[plazo]" id="GestionFinanciamiento_plazo2" class="form-control">
                                                            <option value="12" <?php if($fin1 && $fin1->plazos == 12){echo "selected";} ?>>12 meses</option>
                                                            <option value="24" <?php if($fin1 && $fin1->plazos == 24){echo "selected";} ?>>24 meses</option>
                                                            <option value="36" <?php if($fin1 && $fin1->plazos == 36){echo "selected";} ?>>36 meses</option>
                                                            <option value="48" <?php if($fin1 && $fin1->plazos == 48){echo "selected";} ?>>48 meses</option>
                                                            <option value="60" <?php if($fin1 && $fin1->plazos == 60){echo "selected";} ?>>60 meses</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Valor Financiamiento</label>
                                                        <input type="text" name="GestionFinanciamiento2[valor_financiamiento]" id="GestionFinanciamiento_valor_financiamiento2" class="form-control" onkeypress="return validateNumbers(event)" readonly="true" value="<?php
                                                        if ($fi == 1 || $fi == 2 ) {
                                                            echo $fin1->saldo_financiar;
                                                        }
                                                        ?>"/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Tasa</label>
                                                        <input type="text" name="GestionFinanciamiento2[tasa]" id="GestionFinanciamiento_tasa2" class="form-control" value="16,06" disabled=""/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Seguro</label>
                                                        <input type="text" name="GestionFinanciamiento2[seguro]" id="GestionFinanciamiento_seguro2" class="form-control" readonly="true" value="<?php
                                                        if ($fi == 1 || $fi == 2) {
                                                            echo $fin1->seguro;
                                                        }
                                                        ?>"/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Cuota Mensual Aproximada</label>
                                                        <input type="text" name="GestionFinanciamiento2[cuota_mensual]" id="GestionFinanciamiento_cuota_mensual2" class="form-control" onkeypress="return validateNumbers(event)" readonly="true" value="<?php
                                                if ($fi == 1 || $fi == 2) {
                                                    echo $fin1->cuota_mensual;
                                                }
                                                ?>"/>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Observaciones</label>
                                                        <textarea name="GestionFinanciamiento2[observaciones]" id="GestionFinanciamiento_observaciones2" cols="30" rows="7" onKeyDown="valida_longitud()" onKeyUp="valida_longitud()"><?php echo $fin1->observaciones; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="row" id="cont-edit2">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-default btn-xs" id="edit2" onclick="edit(2);">Editar</button>
                                                        <button type="button" class="btn btn-default btn-xs" id="save2" onclick="save(2);">Guardar</button>
                                                        <!--<button type="button" class="btn btn-default btn-xs" id="delete2" onclick="deleter(2);">Borrar</button>-->
                                                    </div>
                                                </div>
                                            </div>
                                            <?php //} ?>
                                        <?php
                                            $fin2 = GestionFinanciamientoOp::model()->find(array('condition' => "id_financiamiento=:match AND num_cotizacion = 4", 'params' => array(':match' => (int) $model->id)));
                                            ?>
                                        <?php //if ($fin2) { ?>
                                        
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
                                                        <option value="CFC">CFC</option>
                                                        <option value="Banco del Pichincha">Banco del Pichincha</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Vehículo</label>
                                                    <input type="text" name="GestionFinanciamiento3[precio]" id="GestionFinanciamiento_precio3" class="form-control" onkeypress="return validateNumbers(event)" value="<?php
                                                        if ($fi == 2) {
                                                            echo $fin2->precio_vehiculo;
                                                        } else {
                                                            echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,$id_modelo);
                                                        }
                                                        ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Valor de Entrada</label>
                                                    <input type="text" name="GestionFinanciamiento3[entrada]" id="GestionFinanciamiento_entrada3" class="form-control" onkeypress="return validateNumbers(event)" value="<?php if ($fi == 2) {
                                                            echo $fin2->cuota_inicial;
                                                        }
                                                        ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Tiempo de seguro</label>
                                                    <select name="GestionFinanciamiento3[tiempo_seguro]" id="GestionFinanciamiento_tiempo_seguro3" class="form-control">
                                                        <option value="5" <?php if($fin2 && $fin2->ts == 5){echo "selected";} ?>>5 años</option>
                                                        <option value="4" <?php if($fin2 && $fin2->ts == 4){echo "selected";} ?>>4 años</option>
                                                        <option value="3" <?php if($fin2 && $fin2->ts == 3){echo "selected";} ?>>3 años</option>
                                                        <option value="2" <?php if($fin2 && $fin2->ts == 2){echo "selected";} ?>>2 años</option>
                                                        <option value="1" <?php if($fin2 && $fin2->ts == 1){echo "selected";} ?>>1 año</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Plazo</label>
                                                    <!--<input type="text" name="GestionFinanciamiento1[plazo]" id="GestionFinanciamiento_plazo" class="form-control" value="60" disabled=""/>-->
                                                    <select name="GestionFinanciamiento3[plazo]" id="GestionFinanciamiento_plazo3" class="form-control">
                                                        <option value="12" <?php if($fin2 && $fin2->plazos == 12){echo "selected";} ?>>12 meses</option>
                                                        <option value="24" <?php if($fin2 && $fin2->plazos == 24){echo "selected";} ?>>24 meses</option>
                                                        <option value="36" <?php if($fin2 && $fin2->plazos == 36){echo "selected";} ?>>36 meses</option>
                                                        <option value="48" <?php if($fin2 && $fin2->plazos == 48){echo "selected";} ?>>48 meses</option>
                                                        <option value="60" <?php if($fin2 && $fin2->plazos == 60){echo "selected";} ?>>60 meses</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Valor Financiamiento</label>
                                                    <input type="text" name="GestionFinanciamiento3[valor_financiamiento]" id="GestionFinanciamiento_valor_financiamiento3" class="form-control" onkeypress="return validateNumbers(event)" readonly="true"value="<?php
                                                        if ($fi == 2) {
                                                            echo $fin2->saldo_financiar;
                                                        }
                                                        ?>"/>
                                                    <label class="error error-entrada3" style="display: none;">Ingrese un valor de entrada igual o superior al 25% del Precio Total</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Tasa</label>
                                                    <input type="text" name="GestionFinanciamiento3[tasa]" id="GestionFinanciamiento_tasa3" class="form-control" value="16,06" disabled=""/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Seguro</label>
                                                    <input type="text" name="GestionFinanciamiento3[seguro]" id="GestionFinanciamiento_seguro3" class="form-control" readonly="true"value="<?php
                                                        if ($fi == 2) {
                                                            echo $fin2->seguro;
                                                        }
                                                        ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Cuota Mensual Aproximada</label>
                                                    <input type="text" name="GestionFinanciamiento3[cuota_mensual]" id="GestionFinanciamiento_cuota_mensual3" class="form-control" onkeypress="return validateNumbers(event)" readonly="true" value="<?php
                                                        if ($fi == 2) {
                                                            echo $fin2->cuota_mensual;
                                                        }
                                                        ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Observaciones</label>
                                                    <textarea name="GestionFinanciamiento3[observaciones]" id="GestionFinanciamiento_observaciones3" cols="30" rows="7" onKeyDown="valida_longitud()" onKeyUp="valida_longitud()"><?php echo $fin2->observaciones; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row" id="cont-edit3">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-default btn-xs" id="edit3" onclick="edit(3);">Editar</button>
                                                    <button type="button" class="btn btn-default btn-xs" id="save3" onclick="save(3);">Guardar</button>
                                                    <!--<button type="button" class="btn btn-default btn-xs" id="delete3" onclick="deleter(3);">Borrar</button>-->
                                                </div>
                                            </div>
                                        </div>
                                        <?php// } ?>
                                    </div>
                                </div>
                                <!-- ==================================FIN COTIZACION A CREDITO======================================-->
                                <?php else: ?>    

                                <!-- ==================================INICIO COTIZACION CONTADO=====================================-->
                                <div class="cont-contado">
                                    <div class="row">
                                        <h1 class="tl_seccion">Proforma</h1>
                                    </div>
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
                                                    <input type="text" name="GestionFinanciamiento1[precio_contado]" id="GestionFinanciamiento_precio_contado" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,$id_modelo); ?><?php //echo $model->precio_vehiculo; ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Tiempo de seguro</label>
                                                    <select name="GestionFinanciamiento1[tiempo_seguro_contado]" id="GestionFinanciamiento_tiempo_seguro_contado" class="form-control">
                                                        <option value="">----Seleccione tiempo----</option>
                                                        <option value="0" <?php if($model->ts == 0){echo "selected";} ?>>Ninguno</option>
                                                        <option value="1" <?php if($model->ts == 1){echo "selected";} ?>>1 año</option>
                                                        <option value="2" <?php if($model->ts == 2){echo "selected";} ?>>2 años</option>
                                                        <option value="3" <?php if($model->ts == 3){echo "selected";} ?>>3 años</option>
                                                        <option value="4" <?php if($model->ts == 4){echo "selected";} ?>>4 años</option>
                                                        <option value="5" <?php if($model->ts == 5){echo "selected";} ?>>5 años</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Seguro</label>
                                                    <input type="text" name="GestionFinanciamiento1[seguro_contado]" id="GestionFinanciamiento_seguro_contado" class="form-control" readonly="true" value="<?php echo $model->seguro; ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Total Vehículo Seguro</label>
                                                    <input type="text" name="GestionFinanciamiento1[precio_contado_total]" id="GestionFinanciamiento_precio_contado_total" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $model->precio_vehiculo; ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Observaciones</label>
                                                    <textarea name="GestionFinanciamiento1[observaciones_contado]" id="GestionFinanciamiento_observaciones_contado" cols="30" rows="7"><?php echo $model->observaciones; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row" id="cont-edit1">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-default btn-xs" id="edit1" onclick="edit(1);">Editar</button>
                                                    <button type="button" class="btn btn-default btn-xs" id="save1" onclick="save(1);">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                            $fin1 = GestionFinanciamientoOp::model()->find(array('condition' => "id_financiamiento=:match AND num_cotizacion = 3", 'params' => array(':match' => (int) $model->id)));
                                        ?>
                                        <div class="col-md-4 cont-options2" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 class="text-danger">Segunda Opción</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Vehículo</label>
                                                    <input type="text" name="GestionFinanciamiento2[precio_contado]" id="GestionFinanciamiento_precio_contado2" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo) ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Tiempo de seguro</label>
                                                    <select name="GestionFinanciamiento2[tiempo_seguro_contado]" id="GestionFinanciamiento_tiempo_seguro_contado2" class="form-control">
                                                        <option value="">----Seleccione tiempo----</option>
                                                        <option value="0" <?php if($fin1->ts == 0){echo "selected";} ?>>Ninguno</option>
                                                        <option value="1" <?php if($fin1->ts == 1){echo "selected";} ?>>1 año</option>
                                                        <option value="2" <?php if($fin1->ts == 2){echo "selected";} ?>>2 años</option>
                                                        <option value="3" <?php if($fin1->ts == 3){echo "selected";} ?>>3 años</option>
                                                        <option value="4" <?php if($fin1->ts == 4){echo "selected";} ?>>4 años</option>
                                                        <option value="5" <?php if($fin1->ts == 5){echo "selected";} ?>>5 años</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Seguro</label>
                                                    <input type="text" name="GestionFinanciamiento2[seguro_contado]" id="GestionFinanciamiento_seguro_contado2" class="form-control" readonly="true" value="<?php echo $fin1->seguro; ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Total Vehículo Seguro</label>
                                                    <input type="text" name="GestionFinanciamiento2[precio_contado_total]" id="GestionFinanciamiento_precio_contado_total2" class="form-control" onkeypress="return validateNumbers(event)"  value="<?php echo $fin1->precio_vehiculo; ?>"/>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Observaciones</label>
                                                    <textarea name="GestionFinanciamiento2[observaciones_contado]" id="GestionFinanciamiento_observaciones_contado2" cols="30" rows="7"><?php echo $fin1->observaciones; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row" id="cont-edit2">
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
                                                    <input type="text" name="GestionFinanciamiento3[precio_contado]" id="GestionFinanciamiento_precio_contado3" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo) ?>"/>
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
                                                    <input type="text" name="GestionFinanciamiento3[seguro_contado]" id="GestionFinanciamiento_seguro_contado3" class="form-control" readonly="true"/>
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
                                            <div class="row" id="cont-edit3">
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
                            
                            <div class="row buttons">
                                <div class="col-md-2">
                                    <input type="hidden" name="GestionFinanciamiento1[id_informacion]" id="GestionFinanciamiento_id_informacion" value="<?php echo $id_informacion; ?>" />
                                    <input type="hidden" name="GestionFinanciamiento1[id_vehiculo]" id="GestionFinanciamiento_id_vehiculo" value="<?php echo $id_vehiculo; ?>" />
                                    <input type="hidden" name="GestionFinanciamiento1[id_financiamiento]" id="GestionFinanciamiento_id_financiamiento" value="<?php echo $model->id; ?>" />
                                    <input type="hidden" name="GestionFinanciamiento1[flag]" id="GestionFinanciamiento_flag" value="0" />
                                    <input type="hidden" name="GestionFinanciamiento1[mod]" id="GestionFinanciamiento_mod" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acc1]" id="GestionFinanciamiento_acc1" value="<?php echo ($tipo == 1 && ($id_modelo != 90 && $id_modelo != 93)) ? '670-Kit Satelital@' : ''; ?>" />
                                    <input type="hidden" name="GestionFinanciamiento1[acc2]" id="GestionFinanciamiento_acc2" value="<?php echo ($tipo == 1 && ($id_modelo != 90 && $id_modelo != 93)) ? '670-Kit Satelital@' : ''; ?>" />
                                    <input type="hidden" name="GestionFinanciamiento1[acc3]" id="GestionFinanciamiento_acc3" value="<?php echo ($tipo == 1 && ($id_modelo != 90 && $id_modelo != 93)) ? '670-Kit Satelital@' : ''; ?>" />
                                    <input type="hidden" name="GestionFinanciamiento1[tipo_financiamiento]" id="GestionFinanciamiento_tipo_financiamiento" value="<?php echo $tipo; ?>" />
                                    <input class="btn btn-danger" id="finalizar" type="submit" name="yt0" value="Generar Proforma" onclick="send();" style="display: none;">
                                </div>

                            </div>
                            <div class="row">
                                <div id="generatepdf">
                                    <div class="col-md-2" id="contpdf" style="display: none;">
                                        <a href="<?php echo Yii::app()->createUrl('gestionVehiculo/proforma/', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" class="btn btn-warning" target="_blank" id="btngenerate">Proforma</a>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-warning" onclick="modProforma();" id="btnmodprof">Modificar Pdf</button>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-success" href="<?php echo Yii::app()->createUrl('site/proformaCliente/', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" target="_blank" id="btnverprf" style="display: none;">Ver Proforma</a>
                                    </div>
                                    <div class="col-md-2">
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
                    <div class="col-md-8">
                        <a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" class="btn btn-danger" id="btnagendamiento" style="display: none;">Agendar Seguimiento</a>
                        <a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/create/', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" class="btn btn-danger" style="display: none;" id="btn-continuar">Generar Solicitud de Crédito</a>
                        <a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" class="btn btn-danger" style="display: none;" id="btn-continuar-ct">Continuar</a>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-8  col-xs-12 links-tabs">
                        <div class="col-md-2 col-xs-4"><p>También puedes ir a:</p></div>
                        <div class="col-md-2 col-xs-4"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>
                        <div class="col-md-2 col-xs-4"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>" class="creacion-btn">RGD</a></div>                         <div class="col-md-3 col-xs-4"><a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>" class="directorio-btn">Directorio de Contactos</a></div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>
</div>