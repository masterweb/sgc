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
//echo 'id informacion: '.$id_informacion.'S<br>';
//echo 'id vehiculo: '.$id_vehiculo;

$cri5 = new CDbCriteria;
$cri5->condition = "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo} ORDER BY id DESC";
$model = GestionFinanciamiento::model()->find($cri5);
$total_accesorios1 = $model->total_accesorios;
//echo 'total acceosrios: '.$model->total_accesorios;
//echo 'model financiamiento: '.$model->id;
//$id_financiamiento = $negociacion->id;
        
        
$id_modelo = $this->getIdModelo($id_vehiculo);
//echo 'id modelo: '.$id_modelo;
$tipo = $this->getFinanciamiento($id_informacion, $id_vehiculo);
//echo 'TIPO FINANCIAMIENTO: '.$tipo;
$id_version = $this->getIdVersion($id_vehiculo);
//echo 'id version: '.$id_version;
$fi = GestionFinanciamientoOp::model()->count(array('condition' => "id_financiamiento = {$model->id}"));
//echo 'fin: '.$fi;

// lista de accesorios principal de la primera opcion por defecto
$accesorios = GestionVehiculo::model()->find(array('condition' => "id=:match", 'params' => array(':match' => (int) $id_vehiculo)));
$stringAccesorios = $accesorios->accesorios;
$stringAccesoriosManual1 = '';
$stringAccesoriosManual2 = '';
$stringAccesoriosManual3 = '';
if(!empty($accesorios->accesorios_manual))
    $stringAccesoriosManual1 = substr($accesorios->accesorios_manual,0,-1);
//echo 'count: '.count($arrayAccesoriosManual);
$arrayAcc0 = $this->getListAccesorios($id_modelo, $id_version, $stringAccesorios);

//echo '<pre>';
//print_r($arrayAcc0);
//echo '</pre>';
//die();
//echo $stringAccesorios;
if ($fi == 1) {
    $fin1 = GestionFinanciamientoOp::model()->find(array('condition' => "id_financiamiento=:match AND num_cotizacion = 3", 'params' => array(':match' => (int) $model->id), 'order' => 'id DESC'));
    if(!empty($fin1->accesorios_manual))
        $stringAccesoriosManual2 = substr($fin1->accesorios_manual,0,-1);
    $stringAcc = $fin1->accesorios;
    $arrayAcc = $this->getListAccesorios($id_modelo, $id_version, $stringAcc);
    if($fin1->total_accesorios != 0){
       $total_accesorios2 = $fin1->total_accesorios; 
    }else{
       $total_accesorios2 = 0; 
    }
    
}
if ($fi == 2) {
    $fin1 = GestionFinanciamientoOp::model()->find(array('condition' => "id_financiamiento=:match AND num_cotizacion = 3", 'params' => array(':match' => (int) $model->id)));
    if(!empty($fin1->accesorios_manual))
        $stringAccesoriosManual2 = substr($fin1->accesorios_manual,0,-1);
    $stringAcc = $fin1->accesorios;
    //echo 'accesorios 1: '.$stringAcc;
    $arrayAcc = $this->getListAccesorios($id_modelo, $id_version, $stringAcc);
    $total_accesorios2 = $fin1->total_accesorios;
    //echo $total_accesorios2;

    $fin2 = GestionFinanciamientoOp::model()->find(array('condition' => "id_financiamiento=:match AND num_cotizacion = 4", 'params' => array(':match' => (int) $model->id)));
    if(!empty($fin2->accesorios_manual))
        $stringAccesoriosManual3 = substr($fin2->accesorios_manual,0,-1);
    $stringAcc2 = $fin2->accesorios;
    $arrayAcc2 = $this->getListAccesorios($id_modelo, $id_version, $stringAcc2);
    $total_accesorios3 = $fin2->total_accesorios;
    //echo $total_accesorios3;
}
?>
<style type="text/css">
    .ag-btn{padding-left: 0px !important;}
</style>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script type="text/javascript">
    <?php if ($fi == 0): ?>
        var acc1 = <?php echo json_encode($arrayAcc0); ?>;
        var acc2 = new Array();
        var acc3 = new Array();
        
    <?php endif; ?>
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

    //var acc1 = new Array();
    var accsum1 = 0;
    var accsum2 = 0;
    var accsum3 = 0;
    var preciovec1;
    var preciovec2;
    var preciovec3;
    var descripcion1 = '';
    var descripcion2 = '';
    var valorman1 = 0;
    var valorman2 = 0;
    var valorman3 = 0;
    var stringDesc1 = '';
    var stringDesc2 = '';
    var stringDesc3 = '';
    $(document).ready(function () {
       $('#GestionFinanciamiento_acc1').val('<?php echo $stringAccesorios; ?>'+'@'); 
<?php if ($fi == 0): ?>    
        $('.cont-options1').addClass('cont-options1-after');
        var lt = acc1.length;
        for (var i = 0; i <= lt; i++) {
            $('#accesorio-' + acc1[i]).attr('checked', true);
            // llamar funcion de financiamiento
            $('#accspan-' + acc1[i]).addClass('label-price');
        }
        var totalAccesorios = '<?php echo $total_accesorios1; ?>';
        var accManual1 = '<?php echo $stringAccesoriosManual1; ?>';
        var sum1 = 0; 
        var sum2 = 0;
        var sum3 = 0;
        if (accManual1 != ''){
            console.log('enter case one');
            strManual1 = accManual1.split('@');
            switch(strManual1.length){
                case 1:
                    val1 = strManual1[0].split('-');
                    valorman1 = parseInt(val1[0]);$('#valor_otro_accesorios1').val(format2(valorman1,'$'));
                    sum2 = sum2 + parseInt(val1[0]);
                    descripcion1 = val1[1];$('#otro_accesorios_nombre1').val(descripcion1);
                    stringDesc1 += descripcion1 + '@';
                    $('#options-cont-otro').val(2);$('#cont-otro2').val(2);
                    $('#cont-otro2').val(2);
                    break
                case 2:
                    val1 = strManual1[0].split('-');
                    valorman1 = parseInt(val1[0]);$('#valor_otro_accesorios1').val(format2(valorman1,'$'));
                    sum2 = sum2 + parseInt(val1[0]);
                    descripcion1 = val1[1];$('#otro_accesorios_nombre1').val(descripcion1);
                    val2 = strManual1[1].split('-');
                    valorman2 = parseInt(val2[0]);$('#valor_otro_accesorios2').val(format2(valorman2,'$'));
                    sum2 = sum2 + parseInt(val2[0]);
                    descripcion2 = val2[1];$('#otro_accesorios_nombre2').val(descripcion2);
                    stringDesc1 += descripcion1 + '@';
                    stringDesc1 += descripcion2 + '@';
                    $('#options-cont-otro').val(3);$('#cont-otro2').val(3);
                    $('#cont-otro2').val(3);
                    break;
                case 3:
                    val1 = strManual1[0].split('-');
                    valorman1 = parseInt(val1[0]);$('#valor_otro_accesorios1').val(format2(valorman1,'$'));
                    descripcion1 = val1[1];$('#otro_accesorios_nombre1').val(descripcion1);
                    sum2 = sum2 + parseInt(val1[0]);
                    val2 = strManual1[1].split('-');
                    valorman2 = parseInt(val2[0]);$('#valor_otro_accesorios2').val(format2(valorman2,'$'));
                    descripcion2 = val2[1];$('#otro_accesorios_nombre2').val(descripcion2);
                    sum2 = sum2 + parseInt(val2[0]);
                    val3 = strManual1[2].split('-');
                    valorman3 = parseInt(val3[0]);$('#valor_otro_accesorios3').val(format2(valorman3,'$'));
                    descripcion3 = val3[1];$('#otro_accesorios_nombre3').val(descripcion3);
                    sum2 = sum2 + parseInt(val3[0]);
                    stringDesc1 += descripcion1 + '@';
                    stringDesc1 += descripcion2 + '@';
                    stringDesc1 += descripcion3 + '@';
                    $('#options-cont-otro').val(4);$('#cont-otro2').val(4);
                    break;
                default:
                    break;
            }
            
            $('.btn-canc').show();$('#cont-acc2').val(1);
            $('#sum-accesorios2').val(format2(<?php echo $total_accesorios1; ?>,'$'));$('#sum-accesorios-total2').val(accManual1+'@');
            $('#sum-accesorios-res2').val(format2(<?php echo $total_accesorios1; ?>,'$'));$('#desc-accesorios2').val(stringDesc1);
            
            
        }
        if(totalAccesorios != 0){
            $('#precio_accesorios').val(totalAccesorios);
        }else{
            $('#precio_accesorios').val(0);
        }
        $('#total-acc1').val(format2(<?php echo $total_accesorios1; ?>,'$'));
<?php endif; ?>    
<?php if ($fi == 1): ?>
    // poner checks a los elementos correspondientes
        var lt = acc2.length;
        for (var i = 0; i <= lt; i++) {
            $('#accesorio-' + acc2[i]).attr('checked', true);
            // llamar funcion de financiamiento
            $('#accspan-' + acc2[i]).addClass('label-price');
        }
        $('#options-cont').val(3);
        $('.cont-options2').show();
        $('#GestionFinanciamiento_acc2').val('<?php echo $stringAcc; ?>'+'@');
        $('.cont-options2').addClass('cont-options1-after');
        var totalAccesorios = '<?php echo $total_accesorios2; ?>';
        var accManual1 = '<?php echo $stringAccesoriosManual1; ?>';
        var accManual2 = '<?php echo $stringAccesoriosManual2; ?>';
        var sum1 = 0; 
        var sum2 = 0;
        var sum3 = 0;
        
        if (accManual1 != ''){
            strManual1 = accManual1.split('@');
            //console.log('str manual 1: '+strManual1);
            switch(strManual1.length){
                case 1:
                    val1 = strManual1[0].split('-');
                    descripcion1 = val1[1];
                    sum1 = sum1 + parseInt(val1[0]);
                    stringDesc2 += descripcion1 + '@';
                    $('#desc-accesorios2').val(stringDesc2);$('#cont-otro2').val(2);
                    break;
                case 2:
                    val1 = strManual1[0].split('-');
                    descripcion1 = val1[1];
                    sum1 = sum1 + parseInt(val1[0]);
                    stringDesc2 += descripcion1 + '@';
                    val2 = strManual1[1].split('-');
                    descripcion2 = val2[1];
                    sum1 = sum1 + parseInt(val2[0]);
                    stringDesc2 += descripcion2 + '@';
                    $('#desc-accesorios2').val(stringDesc2);$('#cont-otro2').val(3);
                    break;
                case 3:
                    break; 
                default:
                    break;    
            }
            $('#sum-accesorios2').val(sum1);$('#cont-acc2').val(1);
            $('#sum-accesorios-total2').val(accManual1+'@');
            $('#sum-accesorios2').val(format2(sum1,'$'));$('#sum-accesorios-res2').val(format2(sum1,'$'));
            
        }
        $('#total-acc1').val(format2(<?php echo $total_accesorios1; ?>, '$'));
        
        if (accManual2 != ''){
            strManual2 = accManual2.split('@');
            switch(strManual2.length){
                case 1:
                    val1 = strManual2[0].split('-');
                    valorman1 = parseInt(val1[0]);$('#valor_otro_accesorios1').val(format2(valorman1,'$'));
                    sum2 = sum2 + parseInt(val1[0]);
                    descripcion1 = val1[1];$('#otro_accesorios_nombre1').val(descripcion1);
                    stringDesc2 += descripcion1 + '@';
                    $('#options-cont-otro').val(2);$('#cont-otro3').val(2);
                    break
                case 2:
                    val1 = strManual2[0].split('-');
                    valorman1 = parseInt(val1[0]);$('#valor_otro_accesorios1').val(format2(valorman1,'$'));
                    sum2 = sum2 + parseInt(val1[0]);
                    descripcion1 = val1[1];$('#otro_accesorios_nombre1').val(descripcion1);
                    val2 = strManual2[1].split('-');
                    valorman2 = parseInt(val2[0]);$('#valor_otro_accesorios2').val(format2(valorman2,'$'));
                    sum2 = sum2 + parseInt(val2[0]);
                    descripcion2 = val2[1];$('#otro_accesorios_nombre2').val(descripcion2);
                    stringDesc2 += descripcion1 + '@';
                    stringDesc2 += descripcion2 + '@';
                    $('#options-cont-otro').val(3);$('#cont-otro3').val(3);
                    break;
                case 3:
                    val1 = strManual2[0].split('-');
                    valorman1 = parseInt(val1[0]);$('#valor_otro_accesorios1').val(format2(valorman1,'$'));
                    descripcion1 = val1[1];$('#otro_accesorios_nombre1').val(descripcion1);
                    sum2 = sum2 + parseInt(val1[0]);
                    val2 = strManual2[1].split('-');
                    valorman2 = parseInt(val2[0]);$('#valor_otro_accesorios2').val(format2(valorman2,'$'));
                    descripcion2 = val2[1];$('#otro_accesorios_nombre2').val(descripcion2);
                    sum2 = sum2 + parseInt(val2[0]);
                    val3 = strManual2[2].split('-');
                    valorman3 = parseInt(val3[0]);$('#valor_otro_accesorios3').val(format2(valorman3,'$'));
                    descripcion3 = val3[1];$('#otro_accesorios_nombre3').val(descripcion3);
                    sum2 = sum2 + parseInt(val3[0]);
                    stringDesc2 += descripcion1 + '@';
                    stringDesc2 += descripcion2 + '@';
                    stringDesc2 += descripcion3 + '@';
                    $('#options-cont-otro').val(4);$('#cont-otro3').val(4);
                    break;
                default:
                    break;
            }
            $('#cont-otro3').val(3);$('.cont-opt-acc2').show();
            if(totalAccesorios != 0){
                $('#precio_accesorios').val(totalAccesorios);
            }else{
                $('#precio_accesorios').val(0);
            }            
            
            $('.btn-canc').show();$('#cont-acc3').val(1);
            $('#sum-accesorios3').val(format2(<?php echo $total_accesorios2; ?>,'$'));$('#sum-accesorios-total3').val(accManual2+'@');
            $('#sum-accesorios-res3').val(format2(<?php echo $total_accesorios2; ?>,'$'));$('#desc-accesorios3').val(stringDesc2);
            
        }
        $('#total-acc2').val(format2(<?php echo $total_accesorios2; ?>, '$'));
        $('#sum-accesorios2').val(format2(<?php echo $total_accesorios1; ?>, '$'));
        $('#sum-accesorios-res2').val(format2(<?php echo $total_accesorios1; ?>, '$'));
        
        
<?php endif; ?>
<?php if ($fi == 2): ?>
    // poner checks a los elementos correspondientes
    var lt = acc3.length;
    for (var i = 0; i <= lt; i++) {
        $('#accesorio-' + acc3[i]).attr('checked', true);
        // llamar funcion de financiamiento
        $('#accspan-' + acc3[i]).addClass('label-price');
    }
        $('#options-cont').val(4);
        $('.cont-options2').show();
        $('.cont-options3').show();
        $('#GestionFinanciamiento_acc2').val('<?php echo $stringAcc; ?>'+'@');
        $('#GestionFinanciamiento_acc3').val('<?php echo $stringAcc2; ?>'+'@');
        $('.cont-options3').addClass('cont-options1-after');
        var totalAccesorios = '<?php echo $total_accesorios3; ?>';
        var accManual1 = '<?php echo $stringAccesoriosManual1; ?>';
        var accManual2 = '<?php echo $stringAccesoriosManual2; ?>';
        var accManual3 = '<?php echo $stringAccesoriosManual3; ?>';
        var sum1 = 0; 
        var sum2 = 0;
        var sum3 = 0;
        if (accManual1 != ''){
            strManual1 = accManual1.split('@');
            //console.log('str manual 1: '+strManual1);
            switch(strManual1.length){
                case 1:
                    val1 = strManual1[0].split('-');
                    descripcion1 = val1[1];
                    sum1 = sum1 + parseInt(val1[0]);
                    stringDesc2 += descripcion1 + '@';
                    $('#desc-accesorios2').val(stringDesc2);$('#cont-otro2').val(2);
                    break;
                case 2:
                    val1 = strManual1[0].split('-');
                    descripcion1 = val1[1];
                    sum1 = sum1 + parseInt(val1[0]);
                    stringDesc2 += descripcion1 + '@';
                    val2 = strManual1[1].split('-');
                    descripcion2 = val2[1];
                    sum1 = sum1 + parseInt(val2[0]);
                    stringDesc2 += descripcion2 + '@';
                    $('#desc-accesorios2').val(stringDesc2);$('#cont-otro2').val(3);
                    break;
                case 3:
                    break; 
                default:
                    break;    
            }
            $('#sum-accesorios2').val(sum1);$('#cont-acc2').val(1);
            $('#sum-accesorios-total2').val(accManual1+'@');
            $('#sum-accesorios2').val(format2(sum1,'$'));$('#sum-accesorios-res2').val(format2(sum1,'$'));
            
        }
        $('#total-acc1').val(format2(<?php echo $total_accesorios1; ?>, '$'));
        if (accManual2 != ''){
            strManual2 = accManual2.split('@');
            switch(strManual2.length){
                case 1:
                    val1 = strManual2[0].split('-');
                    descripcion1 = val1[1];
                    sum2 = sum2 + parseInt(val1[0]);
                    stringDesc2 += descripcion1 + '@';$('#cont-otro3').val(2);
                    break
                case 2:
                    val1 = strManual2[0].split('-');
                    descripcion1 = val1[1];
                    sum2 = sum2 + parseInt(val1[0]);
                    val2 = strManual2[1].split('-');
                    descripcion2 = val2[1];
                    sum2 = sum2 + parseInt(val2[0]);
                    stringDesc2 += descripcion1 + '@';
                    stringDesc2 += descripcion2 + '@';$('#cont-otro3').val(3);
                    break;
                case 3:
                    val1 = strManual2[0].split('-');
                    descripcion1 = val1[1];
                    sum2 = sum2 + parseInt(val1[0]);
                    val2 = strManual2[1].split('-');
                    descripcion2 = val2[1];
                    sum2 = sum2 + parseInt(val2[0]);
                    val3 = strManual2[2].split('-');
                    descripcion3 = val3[1];
                    sum2 = sum2 + parseInt(val3[0]);
                    stringDesc2 += descripcion1 + '@';
                    stringDesc2 += descripcion2 + '@';
                    stringDesc2 += descripcion3 + '@';$('#cont-otro3').val(4);
                    break;
                default:
                    break;
            }
            console.log('sum3: '+sum2);$('#cont-acc3').val(1);
            $('#cont-acc3').val(1);
            $('#cont-otro3').val(3);$('.cont-opt-acc2').show();$('#precio_accesorios').val(totalAccesorios);$('.btn-canc').show();
            $('#sum-accesorios3').val(format2(sum2,'$'));$('#sum-accesorios-total3').val(accManual2+'@');
            $('#sum-accesorios-res3').val(format2(sum2,'$'));$('#desc-accesorios3').val(stringDesc2);
            
        }
        $('#total-acc2').val(format2(<?php echo $total_accesorios2; ?>, '$'));
        if (accManual3 != ''){
            strManual3 = accManual3.split('@');
            //console.log('length3:'+strManual3.length);
            switch(strManual3.length){
                case 1:
                    val1 = strManual3[0].split('-');
                    valorman1 = parseInt(val1[0]);$('#valor_otro_accesorios1').val(format2(valorman1,'$'));
                    sum3 = sum3 + parseInt(val1[0]);
                    descripcion1 = val1[1];$('#otro_accesorios_nombre1').val(descripcion1);
                    stringDesc3 += descripcion1 + '@';
                    $('#options-cont-otro').val(2);$('#cont-otro4').val(2);
                    break
                case 2:
                    val1 = strManual3[0].split('-');
                    valorman1 = parseInt(val1[0]);$('#valor_otro_accesorios1').val(format2(valorman1,'$'));
                    sum3 = sum3 + parseInt(val1[0]);
                    descripcion1 = val1[1];$('#otro_accesorios_nombre1').val(descripcion1);
                    val2 = strManual3[1].split('-');
                    valorman2 = parseInt(val2[0]);$('#valor_otro_accesorios2').val(format2(valorman2,'$'));
                    sum3 = sum3 + parseInt(val2[0]);
                    descripcion2 = val2[1];$('#otro_accesorios_nombre2').val(descripcion2);
                    stringDesc3 += descripcion1 + '@';
                    stringDesc3 += descripcion2 + '@';
                    $('#options-cont-otro').val(3);$('#cont-otro4').val(3);
                    break;
                case 3:
                    val1 = strManual3[0].split('-');
                    valorman1 = parseInt(val1[0]);$('#valor_otro_accesorios1').val(format2(valorman1,'$'));
                    descripcion1 = val1[1];$('#otro_accesorios_nombre1').val(descripcion1);
                    sum3 = sum3 + parseInt(val1[0]);
                    val2 = strManual3[1].split('-');
                    valorman2 = parseInt(val2[0]);$('#valor_otro_accesorios2').val(format2(valorman2,'$'));
                    descripcion2 = val2[1];$('#otro_accesorios_nombre2').val(descripcion2);
                    sum3 = sum3 + parseInt(val2[0]);
                    val3 = strManual3[2].split('-');
                    valorman3 = parseInt(val3[0]);$('#valor_otro_accesorios3').val(format2(valorman3,'$'));
                    descripcion3 = val3[1];$('#otro_accesorios_nombre3').val(descripcion3);
                    sum3 = sum3 + parseInt(val3[0]);
                    stringDesc3 += descripcion1 + '@';
                    stringDesc3 += descripcion2 + '@';
                    stringDesc3 += descripcion3 + '@';
                    $('#options-cont-otro').val(4);$('#cont-otro4').val(4);
                    break;
                default:
                    break;
            }
            $('#cont-acc4').val(1);
            $('#cont-otro3').val(3);$('.cont-opt-acc2').show();
            $('#precio_accesorios').val(<?php echo $total_accesorios3; ?>);
            $('.btn-canc').show();$('#cont-acc4').val(1);
            $('#sum-accesorios-total3').val(accManual2+'@');
            $('#desc-accesorios4').val(stringDesc3);
        }
        $('#total-acc3').val(format2(<?php echo $total_accesorios3; ?>, '$'));
        $('#sum-accesorios4').val(format2(sum3,'$'));$('#sum-accesorios-res4').val(format2(sum3, '$'));
        $('#sum-accesorios-total4').val(accManual3+'@');
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
        calcFinanciamientoContado(0);
    });
    $('#GestionFinanciamiento_precio_contado2').keyup(function () {
        calcFinanciamientoContado2(0);
    });
    $('#GestionFinanciamiento_precio_contado3').keyup(function () {
        calcFinanciamientoContado3(0);
    });

    // SUMAR VALORES DE ACCESORIOS INGRESADOS MANUALMENTE
    $('#valor_otro_accesorios1').keyup(function () {$('.error-accesorio1').hide();getvalortotal(1);});
    $('#valor_otro_accesorios2').keyup(function () {$('.error-accesorio2').hide();getvalortotal(2);});
    $('#valor_otro_accesorios3').keyup(function () {$('.error-accesorio3').hide();getvalortotal(3);});
    $('#otro_accesorios_nombre1').keyup(function () {$('.error-nombre1').hide();getvalortotal(3);});
    $('#otro_accesorios_nombre2').keyup(function () {$('.error-nombre2').hide();getvalortotal(3);});
    $('#otro_accesorios_nombre3').keyup(function () {$('.error-nombre3').hide();getvalortotal(3);});

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
        //cuotamensual = format2(cuotamensual, '$');
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
        
        //VALORES OPCIONES CONTADO
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
        if(isNaN(precioaccesorios)){
            var precioformatacc = format2(0, '$');
        }else{
            var precioformatacc = format2(precioaccesorios, '$');
        }
        
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
        
        var seguro2 = parseInt($('#GestionFinanciamiento_seguro_contado2').val());
        var seguro2 = format2(seguro2, '$');
        $('#GestionFinanciamiento_seguro_contado2').val(seguro2);
        
        var seguro3 = parseInt($('#GestionFinanciamiento_seguro_contado3').val());
        var seguro3 = format2(seguro3, '$');
        $('#GestionFinanciamiento_seguro_contado3').val(seguro3);



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
            //console.log('precio anterior2: '+precioanterior2);
            var valorFin = $('#GestionFinanciamiento_entrada').val();var valorFin2 = $('#GestionFinanciamiento_entrada2').val();var valorFin3 = $('#GestionFinanciamiento_entrada3').val();
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
                        var secure = $('#GestionFinanciamiento_tiempo_seguro_contado').val();
                        if(tipoFinanciamiento == 1){// credito
                            precionuevo = formatnumber($('#GestionFinanciamiento_precio').val())+ parseInt(accesorio2);
                        }else{
                            setContadoPrecio(secure, counter,accesorio2,1);
                        }
                        
                        //console.log('precio nuevo: '+precionuevo);
                        $('#GestionFinanciamiento_precio').val(format2(precionuevo, '$'));
                        
                        //console.log('precio nuevo: '+precionuevo);
                        preciovec1 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado(0);
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
                        var secure = $('#GestionFinanciamiento_tiempo_seguro_contado2').val();
                        if(tipoFinanciamiento == 1){// credito
                            precionuevo = formatnumber($('#GestionFinanciamiento_precio2').val())+ parseInt(accesorio2);
                        }else{
                            setContadoPrecio(secure, counter, accesorio2, 1);
                        }
                        
                        
                        //console.log('enter chk');
                        $('#GestionFinanciamiento_precio2').val(format2(precionuevo, '$'));
                        preciovec2 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado2(0);
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
                        
                        var secure = $('#GestionFinanciamiento_tiempo_seguro_contado3').val();
                        if(tipoFinanciamiento == 1){// credito
                            precionuevo = formatnumber($('#GestionFinanciamiento_precio3').val())+ parseInt(accesorio2);
                        }else{
                            setContadoPrecio(secure, counter, accesorio2, 1);
                        }
                        
                        $('#GestionFinanciamiento_precio3').val(format2(precionuevo, '$'));
                        $('#GestionFinanciamiento_precio_contado3').val(format2(precionuevo, '$'));
                        preciovec3 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado3(0);
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
                //console.log('precio nuevo: '+precionuevo);
                var accesorios_sum = parseInt(precioanterior) - parseInt(accesorio2);
                
                $('#precio_accesorios').val(format2(accesorios_sum, '$'));
                $('#precio_accesorios2').val(precionuevo);
                switch (counter) {
                    case '2':
                        acc1.length = 0;
                        var secure = $('#GestionFinanciamiento_tiempo_seguro_contado').val();
                        //console.log('tiempo seguro' + $('#GestionFinanciamiento_tiempo_seguro_contado').val());
                        //alert(secure);
                        if(tipoFinanciamiento == 1){// credito
                            precionuevo = formatnumber($('#GestionFinanciamiento_precio').val())- parseInt(accesorio2);
                        }else{
                            setContadoPrecio(secure, counter,accesorio2,0);
                        }
                        $('#GestionFinanciamiento_precio').val(format2(precionuevo, '$'));
                        preciovec1 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado(0);
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
                        var secure = $('#GestionFinanciamiento_tiempo_seguro_contado2').val();
                        if(tipoFinanciamiento == 1){// credito
                            precionuevo = formatnumber($('#GestionFinanciamiento_precio2').val())- parseInt(accesorio2);
                        }else{
                            setContadoPrecio(secure, counter,accesorio2,0);
                        }
                        //console.log('enter no check');
                        $('#GestionFinanciamiento_precio2').val(format2(precionuevo, '$'));
                        //$('#GestionFinanciamiento_precio_contado2').val(format2(precionuevo, '$'));
                        preciovec2 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado2(0);
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
                        var secure = $('#GestionFinanciamiento_tiempo_seguro_contado3').val();
                        if(tipoFinanciamiento == 1){// credito
                            precionuevo = formatnumber($('#GestionFinanciamiento_precio3').val())- parseInt(accesorio2);
                        }else{
                            setContadoPrecio(secure, counter,accesorio2,0);
                        }
                        $('#GestionFinanciamiento_precio3').val(format2(precionuevo, '$'));
                        //$('#GestionFinanciamiento_precio_contado3').val(format2(precionuevo, '$'));
                        preciovec3 = format2(precionuevo, '$');
                        if(tipoFinanciamiento == 0){// tipo contado
                            calcFinanciamientoContado3(0);
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
                //$('#GestionFinanciamiento_precio_contado').val(format2(precionuevo, '$'));
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
        console.log('options cont: '+options_cont);
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
    function scod(){
        var accmanuales;
        var sum2 = 0;
        tipo_finan = $('#GestionFinanciamiento_tipo_financiamiento').val();
        options_cont = $('#options-cont').val();
        $('.error-nombre1').hide();$('.error-nombre2').hide();$('.error-nombre3').hide();
        $('.error-accesorio1').hide();$('.error-accesorio2').hide();$('.error-accesorio3').hide();
        switch(options_cont){
            case '2':
                strManual1 = '<?php echo $stringAccesoriosManual1; ?>';
                sum2 = setDeleteAccesorios(strManual1);
                
                //console.log('sum2--------:'+sum2);
                sumaccres = sum2;
                accmanuales = sum2;
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
                console.log('precio accesorios: '+precioaccesorios);
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
                console.log('ANTER CASE 3');
                strManual1 = '<?php echo $stringAccesoriosManual2; ?>';
                console.log(strManual1);
                sum2 = setDeleteAccesorios(strManual1);
                sumaccres = sum2;
                accmanuales = sum2;
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
                strManual1 = '<?php echo $stringAccesoriosManual3; ?>';
                sum2 = setDeleteAccesorios(strManual1);
                sumaccres = sum2;
                accmanuales = sum2;
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
                
                // En actualizar datos poner datos de los accesorios manuales
                setAccesoriosManuales($('#sum-accesorios-total2').val());
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
                    //$('#valor_otro_accesorios1').val(accsum1);
                    //$('#sum-accesorios-val2').html(accsum1);
                }else{
                    finaciamiento = formatnumber($('#GestionFinanciamiento_precio_contado').val()) - formatnumber($('#precio_normal').val());
                    $('#precio_accesorios').val(format2(finaciamiento, '$'));
                }
                
                break;
            case '4':// volcar datos al segundo cotizador, checks 
                // En actualizar datos poner datos de los accesorios manuales
                setAccesoriosManuales($('#sum-accesorios-total3').val());
                
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
                $('#total-acc1').val($('#precio_accesorios').val());
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
                $("#btnverprf").show();$('#btnagendamiento').show();
                if($('#GestionFinanciamiento_tipo_financiamiento').val() == 1){
                    $('#btn-continuar').show();
                }else{
                    $('#btn-continuar-ct').show();
                }
                
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
    function calcFinanciamientoContado(edit) {
        //console.log('enter calc');
        //var valorEntrada1 = $('#GestionFinanciamiento_entrada3').attr('value');
        if($('#GestionFinanciamiento_tiempo_seguro_contado').val() == 0 || $('#GestionFinanciamiento_tiempo_seguro_contado').val() == ''){
            var valorVehiculo = formatnumber($('#GestionFinanciamiento_precio_contado').val());
        }else{
            var valorVehiculo = formatnumber($('#GestionFinanciamiento_precio_contado_total').val());
        }

        //var plazo = $('#GestionFinanciamiento_plazo3').val();
        var seguro = $('#GestionFinanciamiento_tiempo_seguro_contado').val();
        //console.log('valor vehiculo contado: ' + valorVehiculo);

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

        var precioAccesorios = formatnumber($('#precio_accesorios').val());
        var entrada = precioAccesorios / 4;
        var valorSeguro = format2(primaTotal, '$');
        var valorTotal = valorVehiculo + primaTotal;
        valorTotal = format2(valorTotal, '$');

        if(edit == 0){$('#GestionFinanciamiento_precio_contado_total').val(valorTotal);}
        //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
            $('#GestionFinanciamiento_seguro_contado').val(valorSeguro);
    }
    function calcFinanciamientoContado2(edit) {
        //var valorEntrada1 = $('#GestionFinanciamiento_entrada3').attr('value');
        if($('#GestionFinanciamiento_tiempo_seguro_contado2').val() == 0 || $('#GestionFinanciamiento_tiempo_seguro_contado2').val() == ''){
            var valorVehiculo = formatnumber($('#GestionFinanciamiento_precio_contado2').val());
        }else{
            var valorVehiculo = formatnumber($('#GestionFinanciamiento_precio_contado_total2').val());
        }
        
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
        var iva = subtotal * 0.12;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);

        var precioAccesorios = formatnumber($('#precio_accesorios').val());
        var entrada = precioAccesorios / 4;
        var valorSeguro = format2(primaTotal, '$');
        var valorTotal = valorVehiculo + primaTotal;
        valorTotal = format2(valorTotal, '$');
        //alert(valorTotal);
        if(edit == 0){$('#GestionFinanciamiento_precio_contado_total2').val(valorTotal);$('#GestionFinanciamiento_seguro_contado2').val(valorSeguro);}
        //$('#GestionFinanciamiento_valor_financiamiento').val(valorFinanciamiento);
        
    }
    function calcFinanciamientoContado3(edit) {
        //var valorEntrada1 = $('#GestionFinanciamiento_entrada3').attr('value');
        if($('#GestionFinanciamiento_tiempo_seguro_contado3').val() == 0 || $('#GestionFinanciamiento_tiempo_seguro_contado3').val() == ''){
            var valorVehiculo = formatnumber($('#GestionFinanciamiento_precio_contado3').val());
        }else{
            var valorVehiculo = formatnumber($('#GestionFinanciamiento_precio_contado_total3').val());
        }
        
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
        var iva = subtotal * 0.12;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);

        var precioAccesorios = formatnumber($('#precio_accesorios').val());
        var entrada = precioAccesorios / 4;

        var valorSeguro = format2(primaTotal, '$');
        var valorTotal = valorVehiculo + primaTotal;
        valorTotal = format2(valorTotal, '$');
        //alert(valorTotal);
        if(edit == 0){$('#GestionFinanciamiento_precio_contado_total3').val(valorTotal);}
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
        var iva = subtotal * 0.12;
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
        var iva = subtotal * 0.12;
        var primaTotal = primaNeta + superBancos + seguroCampesino + derechosEmision + iva;
        //console.log('----PRIMA TOTAL----: ' + primaTotal);
        
        precioEntrada = formatnumber(valorEntrada1);
        //var precioAccesorios = $('#precio_accesorios').val();
        var precioAccesorios = formatnumber($('#precio_normal').val());
        var entrada = precioAccesorios / 4;
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
        var iva = subtotal * 0.12;
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
        var sum_accesorios_total = ''; 
        var tipoFinanciamiento = $('#GestionFinanciamiento_tipo_financiamiento').val(); 
        //console.log('tipo financiamiento: '+tipoFinanciamiento);
        var accesorioscont = $('#accesorioscont').val();
        var precioanterior = formatnumber($('#precio_accesorios2').val());
        var accesorio2 = 0;
        //console.log('valor id: '+id);
        switch(id){
            case 1:
                $('#btn-accd').show();
                $('#options-cont').val(2);
                console.log('EDITAR ACCESORIOS 1: '+acc1);
                $('.cont-options1').addClass('cont-options1-after');
                $('.cont-options2').removeClass('cont-options1-after');
                $('.cont-options3').removeClass('cont-options1-after');
                $('#GestionFinanciamiento_mod').val(2);
                $('#GestionFinanciamiento_entrada').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo').removeAttr('disabled');
                sum_accesorios_total = $('#sum-accesorios-total2').val();
                
                // En actualizar datos poner datos de los accesorios manuales
                setAccesoriosManuales(sum_accesorios_total);
                
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
                        //if (param[1] != 'Kit Satelital') {
                            //console.log('enter checked false: '+ j);
                            $('#accesorio-' + j).attr('checked', false);
                            $('#accspan-' + j).removeClass('label-price');
                        //}
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
                //finaciamiento = formatnumber($('#GestionFinanciamiento_precio').val()) - formatnumber($('#precio_normal').val());
                var precionuevo = parseInt(precioanterior) + parseInt(accesorio2);
                $('#precio_accesorios').val($('#sum-accesorios2').val());
                if(tipoFinanciamiento == 0){ // TIPO CONTADO
                    secure = $('#GestionFinanciamiento_tiempo_seguro_contado').val();
                    if(secure != '' || secure != '0'){
                        calcFinanciamientoContado(1);
                    }
                }else{
                    //$('#GestionFinanciamiento_precio').val(format2(precionuevo, '$'));
                    calcFinanciamiento();
                }
                break;
            case 2:
                $('#btn-accd').show();
                $('#options-cont').val(3);
                console.log('EDITAR ACCESORIOS 2: '+acc2);
                $('#GestionFinanciamiento_mod').val(3);
                $('.cont-options2').addClass('cont-options1-after');
                $('.cont-options1').removeClass('cont-options1-after');
                $('.cont-options3').removeClass('cont-options1-after');
                $('#GestionFinanciamiento_entrada2').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro2').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo2').removeAttr('disabled');
                
                sum_accesorios_total = $('#sum-accesorios-total3').val();
                // En actualizar datos poner datos de los accesorios manuales
                setAccesoriosManuales(sum_accesorios_total);
                
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
                        //if (param[1] != 'Kit Satelital') {
                            //console.log('enter checked false: '+ j);
                            $('#accesorio-' + j).attr('checked', false);
                            $('#accspan-' + j).removeClass('label-price');
                        //}
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
                //finaciamiento = formatnumber($('#GestionFinanciamiento_precio2').val()) - formatnumber($('#precio_normal').val());
                $('#precio_accesorios').val($('#sum-accesorios3').val());

                if(tipoFinanciamiento == 0){ // TIPO CONTADO
                    secure = $('#GestionFinanciamiento_tiempo_seguro_contado2').val();
                    if(secure != '' || secure != '0'){
                        calcFinanciamientoContado2(1);
                    }
                }else{
                    //$('#GestionFinanciamiento_precio2').val(format2(precionuevo, '$'));
                    calcFinanciamiento2();
                }

                break;
            case 3:
                $('#btn-accd').show();
                $('#options-cont').val(4);
                console.log('EDITAR ACCESORIOS 2: '+acc3);
                $('#GestionFinanciamiento_mod').val(4);
                $('.cont-options3').addClass('cont-options1-after');
                $('.cont-options1').removeClass('cont-options1-after');
                $('.cont-options2').removeClass('cont-options1-after');
                $('#GestionFinanciamiento_entrada3').removeAttr('disabled');
                $('#GestionFinanciamiento_tiempo_seguro3').removeAttr('disabled');
                $('#GestionFinanciamiento_plazo3').removeAttr('disabled');
                
                sum_accesorios_total = $('#sum-accesorios-total4').val();
                // En actualizar datos poner datos de los accesorios manuales
                setAccesoriosManuales(sum_accesorios_total);
                
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
                        //if (param[1] != 'Kit Satelital') {
                            //console.log('enter checked false: '+ j);
                            $('#accesorio-' + j).attr('checked', false);
                            $('#accspan-' + j).removeClass('label-price');
                        //}
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
                //finaciamiento = formatnumber($('#GestionFinanciamiento_precio3').val()) - formatnumber($('#precio_normal').val());
                $('#precio_accesorios').val($('#sum-accesorios4').val());

                if(tipoFinanciamiento == 0){ // TIPO CONTADO
                    secure = $('#GestionFinanciamiento_tiempo_seguro_contado3').val();
                    if(secure != '' || secure != '0'){
                        calcFinanciamientoContado3(1);
                    }
                }else{
                    //$('#GestionFinanciamiento_precio3').val(format2(precionuevo, '$'));
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
    
     // FUNCION PARA ASIGNAR ACCESORIOS MANUALES EN EL BOTON EDITAR
    function setAccesoriosManuales(sum_accesorios_total){
        $('#btn-acc').prop('disabled',true);
        sum_accesorios_total = sum_accesorios_total.slice(0,-1);
        //console.log('sum acc total: '+sum_accesorios_total);
        sum_accesorios_total = sum_accesorios_total.split('@');
        switch(sum_accesorios_total.length){
            case 1:
                tot1 = sum_accesorios_total[0].split('-');
                if(tot1[0] == ''){
                    $('#valor_otro_accesorios1').val(format2(0, '$'));
                }else{
                    $('#valor_otro_accesorios1').val(format2(parseInt(tot1[0]), '$'));
                }
                $('#otro_accesorios_nombre1').val(tot1[1]);
                $('.cont-opt-acc1').show(); $('.cont-opt-acc2').hide();$('.cont-opt-acc3').hide();
                break;
            case 2:
                tot1 = sum_accesorios_total[0].split('-');
                $('#valor_otro_accesorios1').val(format2(parseInt(tot1[0]), '$'));
                $('#otro_accesorios_nombre1').val(tot1[1]);
                tot2 = sum_accesorios_total[1].split('-');
                $('#valor_otro_accesorios2').val(format2(parseInt(tot2[0]), '$'));
                $('#otro_accesorios_nombre2').val(tot2[1]);
                $('.cont-opt-acc1').show(); $('.cont-opt-acc2').show();$('.cont-opt-acc3').hide();
                break;    
            case 3:
                tot1 = sum_accesorios_total[0].split('-');
                $('#valor_otro_accesorios1').val(format2(parseInt(tot1[0]), '$'));
                $('#otro_accesorios_nombre1').val(tot1[1]);
                tot2 = sum_accesorios_total[1].split('-');
                $('#valor_otro_accesorios2').val(format2(parseInt(tot2[0]), '$'));
                $('#otro_accesorios_nombre2').val(tot2[1]);
                tot3 = sum_accesorios_total[2].split('-');
                $('#valor_otro_accesorios3').val(format2(parseInt(tot3[0]), '$'));
                $('#otro_accesorios_nombre3').val(tot3[1]);
                $('.cont-opt-acc1').show(); $('.cont-opt-acc2').show();$('.cont-opt-acc3').show();
                break; 
            default:
                break;
        }
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
    
    function setContadoPrecio(secure, counter, accesorio2,tipo){
    //console.log('secure: '+secure);
        if(counter == 2){
            counter = '';
        }else{
            counter--;
        }
        if(tipo == 1){
            //console.log('enter sum');
            switch(secure){
                case '':
                case '0':
                    precionuevo = formatnumber($('#GestionFinanciamiento_precio_contado'+counter).val())+ parseInt(accesorio2);
                    //console.log('precionuevo: '+ '#GestionFinanciamiento_precio_contado_total'+counter);
                    //console.log('precionuevo suma: '+precionuevo);
                    $('#GestionFinanciamiento_precio_contado'+counter).val(format2(precionuevo, '$'));
                    //alert('pone nuevo valor a precio contado');
                    break;
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                    precionuevo = formatnumber($('#GestionFinanciamiento_precio_contado_total'+counter).val())+ parseInt(accesorio2);
                    //console.log('precionuevo: '+ '#GestionFinanciamiento_precio_contado_total'+counter);
                    //console.log('precionuevo suma: '+precionuevo);
                    $('#GestionFinanciamiento_precio_contado_total'+counter).val(format2(precionuevo, '$'));
                    //alert('pone nuevo valor a precio contado');
                    break;
                default:
                break;    
            }
        }
        if(tipo == 0){
           //console.log('enter rest');
           switch(secure){
                case '0':
                    //alert('enter case 0');
                    precionuevo = formatnumber($('#GestionFinanciamiento_precio_contado'+counter).val())- parseInt(accesorio2);
                    //console.log('precio nuevo rest: '+precionuevo);
                    $('#GestionFinanciamiento_precio_contado'+counter).val(format2(precionuevo, '$'));
                    break;
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                    precionuevo = formatnumber($('#GestionFinanciamiento_precio_contado_total'+counter).val())- parseInt(accesorio2);
                    $('#GestionFinanciamiento_precio_contado_total'+counter).val(format2(precionuevo, '$'));
                    break;
                default:
                break;    
            } 
        }
        
        
    }
    /*
    * Function setDeleteAccesorios() deletes manual acc from vahicule value - price
    * return sum2 - integer value of new priceS
     */
    function setDeleteAccesorios(strManual1){
        sum2 = 0;
        strManual1 = strManual1.split('@');
        switch(strManual1.length){
            case 1:
                val1 = strManual1[0].split('-');
                valorman1 = parseInt(val1[0]);
                sum2 = sum2 + parseInt(valorman1);
                //console.log('SUM: '+sum2);
                break
            case 2:
                val1 = strManual1[0].split('-');
                valorman1 = parseInt(val1[0]);
                sum2 = sum2 + parseInt(valorman1);
                val2 = strManual1[1].split('-');
                valorman2 = parseInt(val2[0]);
                sum2 = sum2 + parseInt(valorman2);
                break;
            case 3:
                val1 = strManual1[0].split('-');
                valorman1 = parseInt(val1[0]);
                sum2 = sum2 + parseInt(valorman1);
                val2 = strManual1[1].split('-');
                valorman2 = parseInt(val2[0]);
                sum2 = sum2 + parseInt(valorman2);
                val3 = strManual1[2].split('-');
                valorman3 = parseInt(val3[0]);
                sum2 = sum2 + parseInt(valorman3);
                break;
            default:
                break;
        }
        return sum2;
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
                                                                        <input type="checkbox" value="<?php echo $value['precio'] . '-' . $value['accesorio']; ?>" name="accesorios[]" id="accesorio-<?php echo $count; ?>" <?php if ($value['codigo'] == 7 && $tipo == 1) { ?> <?php } ?><?php //$this->getCheckedAcc($value['precio'] . '-' . $value['accesorio'],$stringAccesorios );   ?>>
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
                                                                <input type="text" name="valor_otro_accesorios1" id="valor_otro_accesorios1" class="form-control" value="" data-symbol="$ " data-thousands="," data-decimal=".">
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
                                                                <input type="text" name="valor_otro_accesorios2" id="valor_otro_accesorios2" class="form-control" value="" data-symbol="$ " data-thousands="," data-decimal=".">
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
                                                                <input type="text" name="valor_otro_accesorios3" id="valor_otro_accesorios3" class="form-control" value="" data-symbol="$ " data-thousands="," data-decimal=".">
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
                            <?php if ($tipo == 1): // credito             ?>
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
                                                        <option value="1" <?php if($model->ts == 0){echo "selected";} ?>>Ninguno</option>
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
                                                    <input type="text" name="GestionFinanciamiento1[valor_financiamiento]" id="GestionFinanciamiento_valor_financiamiento" class="form-control" value="<?php echo $model->valor_financiamiento; ?>"/>
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
                                                    <input type="text" name="GestionFinanciamiento1[seguro]" id="GestionFinanciamiento_seguro" class="form-control" value="<?php echo $model->seguro; ?>" maxlength="10"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Cuota Mensual Aproximada</label>
                                                    <input type="text" name="GestionFinanciamiento1[cuota_mensual]" id="GestionFinanciamiento_cuota_mensual" class="form-control" value="<?php echo $model->cuota_mensual; ?>" maxlength="10"/>
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
                                                            <option value="0" <?php if($fin1 && $fin1->ts == 0){echo "selected";} ?>>Ninguno</option>
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
                                                        <input type="text" name="GestionFinanciamiento2[valor_financiamiento]" id="GestionFinanciamiento_valor_financiamiento2" class="form-control" onkeypress="return validateNumbers(event)" value="<?php
                                                        if ($fi == 1 || $fi == 2 ) {
                                                            echo $fin1->saldo_financiar;
                                                        }
                                                        ?>"/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Tasa</label>
                                                        <input type="text" name="GestionFinanciamiento2[tasa]" id="GestionFinanciamiento_tasa2" class="form-control" value="16,06" maxlength="5" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Seguro</label>
                                                        <input type="text" name="GestionFinanciamiento2[seguro]" id="GestionFinanciamiento_seguro2" class="form-control" value="<?php
                                                        if ($fi == 1 || $fi == 2) {
                                                            echo $fin1->seguro;
                                                        }
                                                        ?>"/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Cuota Mensual Aproximada</label>
                                                        <input type="text" name="GestionFinanciamiento2[cuota_mensual]" id="GestionFinanciamiento_cuota_mensual2" class="form-control" onkeypress="return validateNumbers(event)" value="<?php
                                                if ($fi == 1 || $fi == 2) {
                                                    echo $fin1->cuota_mensual;
                                                }
                                                ?>" maxlength="10"/>
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
                                                        <option value="0" <?php if($fin2 && $fin2->ts == 0){echo "selected";} ?>>Ninguno</option>
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
                                                    <input type="text" name="GestionFinanciamiento3[valor_financiamiento]" id="GestionFinanciamiento_valor_financiamiento3" class="form-control" onkeypress="return validateNumbers(event)" value="<?php
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
                                                    <input type="text" name="GestionFinanciamiento3[tasa]" id="GestionFinanciamiento_tasa3" class="form-control" value="16,06" maxlength="5" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Seguro</label>
                                                    <input type="text" name="GestionFinanciamiento3[seguro]" id="GestionFinanciamiento_seguro3" class="form-control" maxlength="10" value="<?php
                                                        if ($fi == 2) {
                                                            echo $fin2->seguro;
                                                        }
                                                        ?>"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Cuota Mensual Aproximada</label>
                                                    <input type="text" name="GestionFinanciamiento3[cuota_mensual]" id="GestionFinanciamiento_cuota_mensual3" class="form-control" onkeypress="return validateNumbers(event)" maxlength="10" value="<?php
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
                                        <div class="col-md-4 cont-options1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 class="text-danger">Primera Opción</h4>
                                                </div>
                                            </div>
                                            <?php if($model->seguro == 0 && ($this->getPrecio($id_vehiculo, $tipo,$id_modelo,$id_modelo == $model->precio_vehiculo))){ ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Vehículo</label>
                                                    <input type="text" name="GestionFinanciamiento1[precio_contado]" id="GestionFinanciamiento_precio_contado" class="form-control" onkeypress="return validateNumbers(event)" value="<?php //echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,$id_modelo); ?><?php echo $model->precio_vehiculo; ?>"/>
                                                </div>
                                            </div>
                                            <?php }else{ ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Vehículo</label>
                                                    <input type="text" name="GestionFinanciamiento1[precio_contado]" id="GestionFinanciamiento_precio_contado" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo,$id_modelo); ?>"/>
                                                </div>
                                            </div>
                                            <?php } ?>
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
                                                    <input type="text" name="GestionFinanciamiento1[seguro_contado]" id="GestionFinanciamiento_seguro_contado" class="form-control" value="<?php echo $model->seguro; ?>"/>
                                                </div>
                                            </div>
                                            <?php if($model->seguro == 0 ){ ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Total Vehículo Seguro</label>
                                                    <input type="text" name="GestionFinanciamiento1[precio_contado_total]" id="GestionFinanciamiento_precio_contado_total" class="form-control" onkeypress="return validateNumbers(event)" value="0"/>
                                                </div>
                                            </div>
                                            <?php }else{ ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Total Vehículo Seguro</label>
                                                    <input type="text" name="GestionFinanciamiento1[precio_contado_total]" id="GestionFinanciamiento_precio_contado_total" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $model->precio_vehiculo; ?>0"/>
                                                </div>
                                            </div>
                                            <?php } ?>
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
                                            <?php if($fin1->seguro == 0 && $this->getPrecio($id_vehiculo, $tipo,$id_modelo,$id_modelo == $fin1->precio_vehiculo)){ ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Vehículo</label>
                                                    <input type="text" name="GestionFinanciamiento2[precio_contado]" id="GestionFinanciamiento_precio_contado2" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $fin1->precio_vehiculo; ?>"/>
                                                </div>
                                            </div>
                                            <?php }else{ ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Vehículo</label>
                                                    <input type="text" name="GestionFinanciamiento2[precio_contado]" id="GestionFinanciamiento_precio_contado2" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo); ?>"/>
                                                </div>
                                            </div>
                                            <?php } ?>
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
                                                    <input type="text" name="GestionFinanciamiento2[seguro_contado]" id="GestionFinanciamiento_seguro_contado2" class="form-control" value="<?php echo $fin1->seguro; ?>"/>
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
                                            <?php if($fin2->seguro == 0 && $this->getPrecio($id_vehiculo, $tipo,$id_modelo,$id_modelo == $fin1->precio_vehiculo)){ ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Vehículo</label>
                                                    <input type="text" name="GestionFinanciamiento3[precio_contado]" id="GestionFinanciamiento_precio_contado3" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $fin2->precio_vehiculo; ?>"/>
                                                </div>
                                            </div>
                                            <?php }else{ ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="">Precio Vehículo</label>
                                                    <input type="text" name="GestionFinanciamiento3[precio_contado]" id="GestionFinanciamiento_precio_contado3" class="form-control" onkeypress="return validateNumbers(event)" value="<?php echo $this->getPrecio($id_vehiculo, $tipo,$id_modelo); ?>"/>
                                                </div>
                                            </div>
                                            <?php } ?>
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
                                    <input type="hidden" name="GestionFinanciamiento1[acc1]" id="GestionFinanciamiento_acc1" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acc2]" id="GestionFinanciamiento_acc2" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acc3]" id="GestionFinanciamiento_acc3" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acco1]" id="GestionFinanciamiento_acco1" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acco2]" id="GestionFinanciamiento_acco2" value="" />
                                    <input type="hidden" name="GestionFinanciamiento1[acco3]" id="GestionFinanciamiento_acco3" value="" />
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