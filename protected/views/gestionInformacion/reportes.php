<?php
$id_responsable = Yii::app()->user->getId();
//$dealer_id = $this->getDealerId($id_responsable);
$dealer_id = $this->getConcesionarioDealerId($id_responsable);
$grupo_id = (int) Yii::app()->user->getState('grupo_id');
//die('dealer id: '.$dealer_id);
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/daterangepicker.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/daterangepicker.css" type="text/css" />
<style type="text/css">
    .alert{margin-bottom: -5px !important;font-size: 16px;}
    .dif{color: red;}
    .calendar-table th{color: black;}
</style>
<script type="text/javascript">
    $(function () {
        $('#fecha-range1').daterangepicker(
                {
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                }
        );
        $('#fecha-range2').daterangepicker(
                {
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                }
        );
        $('#GestionInformacionConcesionario').change(function () {
            var value = $(this).attr('value');
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getAsesores"); ?>',
                beforeSend: function (xhr) {
                    //$('#info-3').show();  // #bg_negro must be defined somewhere
                },
                type: 'POST',
                //dataType: 'json', 
                data: {dealer_id: value},
                success: function (data) {
                    //$('#info-3').hide();
                    //alert(data);
                    $('#GestionDiariaresponsable').html(data);

                }
            });
        });
    });
</script>
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Reportes</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="highlight">
                <div class="form">
                    <h4>Filtros de búsqueda </h4>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'gestion-nueva-cotizacion-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('gestionInformacion/reportes'),
                        'enableAjaxValidation' => false,
                        'htmlOptions' => array(
                            //'onsubmit' => "return false;", /* Disable normal form submit */
                            'onkeypress' => " if(event.keyCode == 13){ send(); } ", /* Do ajax call when user presses enter key */
                            'class' => 'form-horizontal form-search',
                            'autocomplete' => 'off'
                        ),
                    ));
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Fecha 1</label>
                            <input type="text" name="GestionInformacion[fecha1]" class="form-control" id="fecha-range1"/>
                        </div>
                        <div class="col-md-6">
                            <label for="">Fecha 2</label>
                            <input type="text" name="GestionInformacion[fecha2]" class="form-control" id="fecha-range2"/>
                        </div>
                    </div>
                    <?php
                    $cargo_id = (int) Yii::app()->user->getState('cargo_id');
                    if ($cargo_id == 70):
                        ?>
                        <?php
                        // BUSQUEDA DE RESPONSABLE DE VENTAS CARGO ID 17 Y EL DEALER ID -> concesionarioid
                        $mod = new GestionDiaria;
                        $cre = new CDbCriteria();
                        $cre->condition = " cargo_id = 71 AND dealers_id = {$dealer_id} ";
                        $cre->order = " nombres ASC";
                        $usu = CHtml::listData(Usuarios::model()->findAll($cre), "id", "fullname");
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Asesor</label>
                                <?php echo $form->dropDownList($mod, 'responsable', $usu, array('class' => 'form-control', 'empty' => 'Seleccione un responsable')); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php
                    if ($cargo_id == 69):
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Concesionarios</label>
                                <?php
                                $info = new GestionInformacion;

                                $con = Yii::app()->db;
                                $sql = "SELECT * FROM gr_concesionarios WHERE id_grupo = {$grupo_id} ORDER BY nombre ASC";
                                $requestr1 = $con->createCommand($sql);
                                $requestr1 = $requestr1->queryAll();

                                //$conc = CHtml::listData(GrConcesionarios::model()->findAll($criteria2), "id_grupo", "nombre");
                                ?>
                                <?php //echo $form->dropDownList($info, 'concesionario', $conc, array('class' => 'form-control', 'empty' => 'Seleccione un concesionario')); ?>
                                <select name="GestionInformacion[concesionario]" id="GestionInformacionConcesionario" class="form-control">
                                    <option value="">--Seleccione Concesionario--</option>
                                    <?php
                                    foreach ($requestr1 as $value) {
                                        echo '<option value="' . $value['dealer_id'] . '">' . $value['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Asesor</label>
                                <select name="GestionDiaria[responsable]" id="GestionDiariaresponsable" class="form-control">
                                    <option value="">--Seleccione--</option>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row buttons">
                        <div class="col-md-6">
                            <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>

                            <?php
                            switch ($cargo_id) {
                                case 71:
                                    echo '<input type="hidden" name="GestionInformacion[tipousuario]" id="GestionInformaciontipousuario" accept="" value="1" />';
                                    break;
                                case 70:
                                    echo '<input type="hidden" name="GestionInformacion[tipousuario]" id="GestionInformaciontipousuario" accept="" value="2" />';
                                    break;
                                case 69:
                                    echo '<input type="hidden" name="GestionInformacion[tipousuario]" id="GestionInformaciontipousuario" accept="" value="3" />';
                                    break;

                                default:
                                    break;
                            }
                            ?>       

                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
    <br />
    <?php if (!empty($titulo)): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info" role="alert"><?php echo $titulo; ?></div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <h1 class="tl_seccion">Tabla de datos</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?php echo $nombre_mes_actual . '-2015'; ?></th>
                            <th><?php echo $nombre_mes_anterior . '-2015'; ?></th>
                            <th>VAR</th>
                            <th>DIF</th>
                        </tr>
                    <tbody>
                        <tr><td>TRÁFICO</td>
                            <td><?php echo $trafico_mes_actual; ?></td>
                            <td><?php echo $trafico_mes_anterior; ?></td>
                            <td>
                                <?php
                                $dif = $trafico_mes_actual - $trafico_mes_anterior;
                                if ($trafico_mes_anterior == 0) {
                                    $df = '100%';
                                } else {
                                    $df = ($dif * 100) / $trafico_mes_anterior;
                                    if ($df >= 0) {
                                        $df = round((($dif * 100) / $trafico_mes_anterior), 2);
                                        $df = '<span>' . $df . '%</span>';
                                    } else {
                                        $df = round((($dif * 100) / $trafico_mes_anterior), 2);
                                        $df = '<span class="dif">(' . abs($df) . '%)</span>';
                                    }
                                }
                                echo $df;
                                ?>
                            </td>
                            <td>
                                <?php
                                $dif = $trafico_mes_actual - $trafico_mes_anterior;
                                if ($dif > 0) {
                                    echo '<span>' . $dif . '</span>';
                                } else {
                                    echo '<span class="dif">(' . abs($dif) . ')</span>';
                                }
                                ?>    

                            </td>
                        </tr>
                        <tr><td>PROFORMA</td>
                            <td><?php echo $proforma_mes_actual; ?></td>
                            <td><?php echo $proforma_mes_anterior; ?></td>
                            <td>
                                <?php
                                $dif = $proforma_mes_actual - $proforma_mes_anterior;
                                if ($proforma_mes_anterior == 0) {
                                    $df = '100%';
                                } else {
                                    $df = ($dif * 100) / $proforma_mes_anterior;
                                    if ($df > 0) {
                                        $df = round((($dif * 100) / $proforma_mes_anterior), 2);
                                        $df = '<span>' . $df . '%</span>';
                                    } else {
                                        $df = round((($dif * 100) / $proforma_mes_anterior), 2);
                                        $df = '<span class="dif">(' . abs($df) . '%)</span>';
                                    }
                                }
                                echo $df;
                                ?>
                            </td>
                            <td>
                                <?php
                                $dif = $proforma_mes_actual - $proforma_mes_anterior;
                                if ($dif > 0) {
                                    echo '<span>' . $dif . '</span>';
                                } else {
                                    echo '<span class="dif">(' . abs($dif) . ')</span>';
                                }
                                ?> 
                            </td>
                        </tr>
                        <tr><td>TESTDRIVE</td>
                            <td><?php echo $td_mes_actual; ?></td>
                            <td><?php echo $td_mes_anterior; ?></td>
                            <td>
                                <?php
                                $dif = $td_mes_actual - $td_mes_anterior;
                                if ($td_mes_anterior == 0) {
                                    $df = '100%';
                                } else {
                                    $df = ($dif * 100) / $td_mes_anterior;
                                    if ($df > 0) {
                                        $df = round((($dif * 100) / $td_mes_anterior), 2);
                                        $df = '<span>' . $df . '%</span>';
                                    } else {
                                        $df = round((($dif * 100) / $td_mes_anterior), 2);
                                        $df = '<span class="dif">(' . abs($df) . '%)</span>';
                                    }
                                }
                                echo $df;
                                ?>
                            </td>
                            <td>
                                <?php
                                $dif = $td_mes_actual - $td_mes_anterior;
                                if ($dif > 0) {
                                    echo '<span>' . $dif . '</span>';
                                } else {
                                    echo '<span class="dif">(' . abs($dif) . ')</span>';
                                }
                                ?>   
                            </td>
                        </tr>
                        <tr><td>VENTAS</td>
                            <td><?php echo $vh_mes_actual; ?></td>
                            <td><?php echo $vh_mes_anterior; ?></td>
                            <td>
                                <?php
                                $dif = $vh_mes_actual - $vh_mes_anterior;
                                if ($vh_mes_anterior == 0) {
                                    $df = '100%';
                                } else {
                                    $df = ($dif * 100) / $vh_mes_anterior;
                                    if ($df > 0) {
                                        $df = round((($dif * 100) / $vh_mes_anterior), 2);
                                        $df = '<span>' . $df . '%</span>';
                                    } else {
                                        $df = round((($dif * 100) / $vh_mes_anterior), 2);
                                        $df = '<span class="dif">(' . abs($df) . '%)</span>';
                                    }
                                }
                                echo $df;
                                ?>
                            </td>
                            <td>
                                <?php
                                $dif = $vh_mes_actual - $vh_mes_anterior;
                                if ($dif > 0) {
                                    echo '<span>' . $dif . '</span>';
                                } else {
                                    echo '<span class="dif">(' . abs($dif) . ')</span>';
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <br />

    <!--NICOLAS VELA-->
    <div class="row">
        <div class="col-md-12">
            <style type="text/css">
                .barra{
                    /*height: 20px;*/
                    margin-bottom: 5px;
                    color: #fff;
                    padding: 5px;
                    font-size: 9px;
                }
                .barratit{
                    font-size: 12px;
                    font-style: italic;
                }
                .trojo{color: red;}
                .tazul{color: blue;}
                .roja{
                    background: red;
                }
                .azul{
                    background: blue;
                }
                hr{
                    border-color: #dedede;
                }
            </style>
            <?php
                $valores = array($trafico_mes_actual, $trafico_mes_anterior, $proforma_mes_actual, $proforma_mes_anterior, $td_mes_actual, $td_mes_anterior, $vh_mes_actual, $vh_mes_anterior);
                //$valores = array(22, 8, 7, 7, 4, 5, 0, 5);
                $valormax = max($valores);
                $keymax = array_keys($valores, $valormax);

                porcentGraph($valormax, $trafico_mes_actual, $trafico_mes_anterior, $proforma_mes_actual, $proforma_mes_anterior, $td_mes_actual, $td_mes_anterior, $vh_mes_actual, $vh_mes_anterior);

                function porcentGraph($maxvalue, $trafico_mes_actual, $trafico_mes_anterior, $proforma_mes_actual, $proforma_mes_anterior, $td_mes_actual, $td_mes_anterior, $vh_mes_actual, $vh_mes_anterior){        
                    $tmac = ($trafico_mes_actual * 100)/$maxvalue;
                    $tman = ($trafico_mes_anterior * 100)/$maxvalue;
                    $pmac = ($proforma_mes_actual * 100)/$maxvalue;
                    $pman = ($proforma_mes_anterior * 100)/$maxvalue;
                    $tdmac = ($td_mes_actual * 100)/$maxvalue;
                    $tdman = ($td_mes_anterior * 100)/$maxvalue;
                    $vmac = ($vh_mes_actual * 100)/$maxvalue;
                    $vman = ($vh_mes_anterior * 100)/$maxvalue;
           
                    echo '<span class="barratit trojo">Tráfico mes actual</span><div class="barra roja" style="width:'.$tmac.'%;">'.$trafico_mes_actual.'</div>'.
                     '<span class="barratit tazul">Tráfico mes anterior</span><div class="barra azul" style="width:'.$tman.'%;">'.$trafico_mes_anterior.'</div><hr/>'.
                     '<span class="barratit trojo">Proforma mes actual</span><div class="barra roja" style="width:'.$pmac.'%;">'.$proforma_mes_actual.'</div>'.
                     '<span class="barratit tazul">Proforma mes anterior</span><div class="barra azul" style="width:'.$pman.'%;">'.$proforma_mes_anterior.'</div><hr/>'.
                     '<span class="barratit trojo">Test Drive mes actual</span><div class="barra roja" style="width:'.$tdmac.'%;">'.$td_mes_actual.'</div>'.
                     '<span class="barratit tazul">Test Drive mes anterior</span><div class="barra azul" style="width:'.$tdman.'%;">'.$td_mes_anterior.'</div><hr/>'.
                     '<span class="barratit trojo">Ventas mes actual</span><div class="barra roja" style="width:'.$vmac.'%;">'.$vh_mes_actual.'</div>'.
                     '<span class="barratit tazul">Ventas mes anterior</span><div class="barra azul" style="width:'.$vman.'%;">'.$vh_mes_anterior.'</div><br/><br/>';
                }

            ?>
        </div>
    </div>
    <!--FIN NICOLAS VELA-->

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="table-fixed">
                    <tbody>
                        <tr>
                            <td>TASA DE PROFORMAS</td>
                            <td>
                                <?php
                                $ts1 = 0;
                                if($trafico_mes_actual > 0){
                                    $ts1 = ($proforma_mes_actual / $trafico_mes_actual) * 100;
                                    //die('ts: '.$ts);
                                    $ts1 = round($ts1, 2);
                                    echo $ts1 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $ts2 = 0;
                                if($trafico_mes_anterior > 0){
                                    $ts2 = ($proforma_mes_anterior / $trafico_mes_anterior) * 100;
                                    $ts2 = round($ts2, 2);
                                    echo $ts2 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $dfpr = $ts1 - $ts2;
                                if($df > 0){echo $dfpr.' %';}else{echo '<span class="dif">('.abs($dfpr).' %)</span>';}
                                ?>
                            </td>
                            
                        </tr>
                        <tr>
                            <td>TASA DE TEST DRIVE</td>
                            <td>
                                <?php
                                $td1 = 0;
                                if($trafico_mes_actual > 0){
                                    $td1 = ($td_mes_actual / $trafico_mes_actual)* 100;
                                    $td1 = round($td1, 2);
                                    echo $td1 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $td2 = 0;
                                if($trafico_mes_anterior > 0){
                                    $td2 = ($td_mes_anterior / $trafico_mes_anterior) * 100;
                                    $td2 = round($td2, 2);
                                    echo $td2 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $dftd = $td1 - $td2;
                                if($dftd > 0){echo $dftd.' %';}else{echo '<span class="dif">('.abs($dftd).' %)</span>';}
                                
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>TASA DE CIERRE</td>
                            <td>
                                <?php
                                $tc1 = 0;
                                if($trafico_mes_actual > 0){
                                    $tc1 = ($vh_mes_actual / $trafico_mes_actual) * 100;
                                    $tc1 = round($tc1, 2);
                                    echo $tc1 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $tc2 = 0;
                                if($trafico_mes_anterior > 0){
                                    $tc2 = ($vh_mes_anterior / $trafico_mes_anterior) * 100;
                                    $tc2 = round($tc2, 2);
                                    echo $tc2 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $dftc = $tc1 - $tc2;
                                if($dftc > 0){echo $dftc.' %';}else{echo '<span class="dif">('.abs($dftc).' %)</span>';}
                                ?>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="45%" scope="col">&nbsp;</th>
                            <th colspan="2" scope="col"><?php echo $nombre_mes_actual . '-2015'; ?></th>
                        </tr>
                    </thead>  
                    <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="t-caption">CKD</td>
                            <td class="t-caption">CBU</td>
                        </tr>
                        <tr>
                            <td>TRAFICO</td>
                            <td><?php echo $traficockd2; ?></td>
                            <td><?php echo $traficocbu2; ?></td>
                        </tr>
                        <tr>
                            <td>PROFORMA</td>
                            <td><?php echo $proformackd2; ?></td>
                            <td><?php echo $proformacbu2; ?></td>
                        </tr>
                        <tr>
                            <td>TESDRIVE</td>
                            <td><?php echo $tdckd2; ?></td>
                            <td><?php echo $tdcbu2; ?></td>
                        </tr>
                        <tr>
                            <td>VENTAS</td>
                            <td><?php echo $vhckd2; ?></td>
                            <td><?php echo $vhcbu2; ?></td>
                        </tr>
                        <tr>
                            <td>TASA PROFORMAS</td>
                            <td>
                                <?php
                                $tasapr2 = 0;
                                if ($traficockd2 > 0) {
                                    $tasapr2 = ($proformackd2 / $traficockd2) * 100;
                                    $tasapr2 = round($tasapr2, 2);
                                    echo $tasapr2 . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $tasapr2cbu = 0;
                                if ($traficocbu2 > 0) {
                                    $tasapr2cbu = ($proformacbu2 / $traficocbu2) * 100;
                                    $tasapr2cbu = round($tasapr2cbu, 2);
                                    echo $tasapr2cbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>TASA TESTDRIVE</td>
                            <td>
                                <?php
                                $tasatd2 = 0;
                                if ($traficockd2 > 0) {
                                    $tasatd2 = ($tdckd2 / $traficockd2) * 100;
                                    $tasatd2 = round($tasatd2, 2);
                                    echo $tasatd2 . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>    
                            </td>
                            <td>
                                <?php
                                $tasatd2cbu = 0;
                                if ($traficocbu2 > 0) {
                                    $tasatd2cbu = ($tdcbu2 / $traficocbu2) * 100;
                                    $tasatd2cbu = round($tasatd2cbu, 2);
                                    echo $tasatd2cbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>TASA CIERRE</td>
                            <td>
                                <?php
                                $tasac2 = 0;
                                if ($traficockd2 > 0) {
                                    $tasac2 = ($vhckd2 / $traficockd2) * 100;
                                    $tasac2 = round($tasac2, 2);
                                    echo $tasac2 . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $tasac2cbu = 0;
                                if ($traficocbu2 > 0) {
                                    $tasac2cbu = ($vhcbu2 / $traficocbu2) * 100;
                                    $tasac2cbu = round($tasac2cbu, 2);
                                    echo $tasac2cbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> 
        <div class="col-md-3">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" scope="col"><?php echo $nombre_mes_anterior . '-2015'; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="38%" class="t-caption">CKD</td>
                            <td width="39%" class="t-caption">CBU</td>
                        </tr>
                        <tr>
                            <td><?php echo $traficockd1; ?></td>
                            <td><?php echo $traficocbu1; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $proformackd1; ?></td>
                            <td><?php echo $proformacbu1; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $tdckd1; ?></td>
                            <td><?php echo $tdcbu1; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $vhckd1; ?></td>
                            <td><?php echo $vhcbu1; ?></td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $tasapr2ant = 0;
                                if ($traficockd1 > 0) {
                                    $tasapr2ant = ($proformackd1 / $traficockd1) * 100;
                                    $tasapr2ant = round($tasapr2ant, 2);
                                    echo $tasapr2ant . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $tasapr2antcbu = 0;
                                if ($traficocbu1 > 0) {
                                    $tasapr2antcbu = ($proformacbu1 / $traficocbu1) * 100;
                                    $tasapr2antcbu = round($tasapr2antcbu, 2);
                                    echo $tasapr2antcbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $tasatd2ant = 0;
                                if ($traficockd1 > 0) {
                                    $tasatd2ant = ($tdckd1 / $traficockd1) * 100;
                                    $tasatd2ant = round($tasatd2ant, 2);
                                    echo $tasatd2ant . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>    
                            </td>
                            <td>
                                <?php
                                $tasatd2antcbu = 0;
                                if ($traficocbu1 > 0) {
                                    $tasatd2antcbu = ($tdcbu1 / $traficocbu1) * 100;
                                    $tasatd2antcbu = round($tasatd2antcbu, 2);
                                    echo $tasatd2antcbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $tasac2ant = 0;
                                if ($traficockd1 > 0) {
                                    $tasac2ant = ($vhckd1 / $traficockd1) * 100;
                                    $tasac2ant = round($tasac2ant, 2);
                                    echo $tasac2ant . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                 $tasac2antcbu = 0;
                                if ($traficocbu1 > 0) {
                                    $tasac2antcbu = ($vhcbu1 / $traficocbu1) * 100;
                                    $tasac2antcbu = round($tasac2antcbu, 2);
                                    echo $tasac2antcbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>    
        </div>
        <div class="col-md-3">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" scope="col">DIFERENCIA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="38%" class="t-caption">CKD</td>
                            <td width="39%" class="t-caption">CBU</td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $dif1 = $traficockd2 - $traficockd1;
                                echo $dif1;
                                ?>
                            </td>
                            <td>
                                <?php
                                $dif1 = $traficocbu2 - $traficocbu1;
                                echo $dif1;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $dif1 = $proformackd2 - $proformackd1;
                                echo $dif1;
                                ?>
                            </td>
                            <td>
                                <?php
                                $dif1 = $proformacbu2 - $proformacbu1;
                                echo $dif1;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $dif1 = $tdckd2 - $tdckd1;
                                echo $dif1;
                                ?>
                            </td>
                            <td>
                                <?php
                                $dif1 = $tdcbu2 - $tdcbu1;
                                echo $dif1;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $dif1 = $vhckd2 - $vhckd1;
                                echo $dif1;
                                ?>
                            </td>
                            <td>
                                <?php
                                $dif1 = $vhcbu2 - $vhcbu1;
                                echo $dif1;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $difckd = $tasapr2 - $tasapr2ant;
                                echo $difckd .'%';
                                ?>
                            </td>
                            <td>
                                <?php
                                $difcbu = $tasapr2cbu - $tasapr2antcbu;
                                echo $difcbu.'%';;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                               <?php
                                $difckdtd = $tasatd2 - $tasatd2ant;
                                echo $difckdtd.'%';;
                                ?> 
                            </td>
                            <td>
                                <?php
                                $difcbutd = $tasatd2cbu - $tasatd2antcbu;
                                echo $difcbutd.'%';;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $difckdtc = $tasac2 - $tasac2ant;
                                echo $difckdtc.'%';;
                                ?> 
                            </td>
                            <td>
                                <?php
                                $difcbutc = $tasac2cbu - $tasac2antcbu;
                                echo $difcbutc.'%';;
                                ?>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>    
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!--            <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $trafico_mes_anterior; ?>" aria-valuemin="0" aria-valuemax="" style="width: 40%">
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>-->
        </div>
    </div>
</div>

