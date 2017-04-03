<?php

ini_set('display_errors', 1);
//echo '<pre>';
//print_r($vartrf['fechas_activas']);
//echo '</pre>';
//echo 'flag search: '.$vartrf['flag_search'];
//echo 'mes actual: '.$vartrf['mes_actual'].'<br />';
//echo 'mes anterior: '.$vartrf['mes_anterior'];
//die ($vartrf['mes_actual']);
$trafico0 = array();
$trafico1 = array();
$trafico2 = array();
$trafico3 = array();
$trafico4 = array();
$trafico5 = array();
$trafico6 = array();
$trafico7 = array();
$trafico8 = array();
$trafico9 = array();
$trafico10 = array();
$trafico11 = array();

$ventas0 = array();
$ventas1 = array();
$ventas2 = array();
$ventas3 = array();
$ventas4 = array();
$ventas5 = array();
$ventas6 = array();
$ventas7 = array();
$ventas8 = array();
$ventas9 = array();
$ventas10 = array();
$ventas11 = array();

$tasacierre0 = array();
$tasacierre1 = array();
$tasacierre2 = array();
$tasacierre3 = array();
$tasacierre4 = array();
$tasacierre5 = array();
$tasacierre6 = array();
$tasacierre7 = array();
$tasacierre8 = array();
$tasacierre9 = array();
$tasacierre10 = array();
$tasacierre11 = array();

$count = 0;
//echo 'count modelos: '.count($vartrf['modelos']);
$flag = 0;
foreach ($vartrf['modelos'] as $val){
    for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
        ${"trafico" . $i}[] = $this->getTraficoVersion($i, $val['id_versiones'], $vartrf['year_actual'], $vartrf['dia_actual'], 1, $vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable']);
        ${"ventas" . $i}[] = $this->getVentasVersion($i, $val['id_versiones'], $vartrf['year_actual'], $vartrf['dia_actual'], 1, $vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable']);
        ${"tasacierre". $i}[] = $this->getTasaCierreNormal($this->getVentasVersion($i, $val['id_versiones'], $vartrf['year_actual'], $vartrf['dia_actual'], 1, $vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable']), $this->getTraficoVersion($i, $val['id_versiones'], $vartrf['year_actual'], $vartrf['dia_actual'], 1, $vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable']));
        }
}


$trafico_total0 = array_sum($trafico0);
$trafico_total1 = array_sum($trafico1);
$trafico_total2 = array_sum($trafico2);
$trafico_total3 = array_sum($trafico3);
$trafico_total4 = array_sum($trafico4);
$trafico_total5 = array_sum($trafico5);
$trafico_total6 = array_sum($trafico6);
$trafico_total7 = array_sum($trafico7);
$trafico_total8 = array_sum($trafico8);
$trafico_total9 = array_sum($trafico9);
$trafico_total10 = array_sum($trafico10);
$trafico_total11 = array_sum($trafico11);

$ventas_total0 = array_sum($ventas0);
$ventas_total1 = array_sum($ventas1);
$ventas_total2 = array_sum($ventas2);
$ventas_total3 = array_sum($ventas3);
$ventas_total4 = array_sum($ventas4);
$ventas_total5 = array_sum($ventas5);
$ventas_total6 = array_sum($ventas6);
$ventas_total7 = array_sum($ventas7);
$ventas_total8 = array_sum($ventas8);
$ventas_total9 = array_sum($ventas9);
$ventas_total10 = array_sum($ventas10);
$ventas_total11 = array_sum($ventas11);
//echo $trafico_total11;

//echo '</pre>';
$k = 0;
foreach ($vartrf['modelos'] as $val) {
    for ($i = 0; $i < $vartrf['mes_actual']; $i++) { 
            //echo ${trafico.$i}[$k].',<br />';
    }
    $k++;
}
$k = 0;
// mes de enero
$vartrf['year_actual1'] = $vartrf['year_actual'];
if($vartrf['mes_actual'] == 1){
    $vartrf['year_actual1'] = 2016;
}

$flag = 0;
foreach ($vartrf['modelos'] as $val) {
    $j = 0;
    for ($i = 1; $i <= $vartrf['dia_actual']; $i++) {
        $d = $i; 
        if ($i < 10) {
                $d = '0' . $i;
        }
        //echo 'id: '.$val['id'].'<br>';
        ${"traficodetalle_mes_anterior_".$val['id']}[] = $this->getTraficoVersion($vartrf['mes_anterior'], $vartrf['versiones'][$flag], $vartrf['year_actual1'], $d, 0, $vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable']); 
        //echo 'Antes de la suma: '.${"traficodetalle_mes_anterior_".$val['id']}[$j].'<br>';
        if($j > 0){
            ${"traficodetalle_mes_anterior_".$val['id']}[$j] =  ${"traficodetalle_mes_anterior_".$val['id']}[$j] +  ${"traficodetalle_mes_anterior_".$val['id']}[$j-1];
        }
        
        $j++;
    }
    $flag++;
}

$flag = 0;
foreach ($vartrf['modelos'] as $val) {
    $j = 0;
    for ($i = 1; $i <= $vartrf['dia_actual']; $i++) {
        $d = $i;
        if ($i < 10) {
                $d = '0' . $i;
        }
        ${"traficodetalle_mes_actual_".$val['id']}[] = $this->getTraficoVersion($vartrf['mes_actual'], $vartrf['versiones'][$flag], $vartrf['year_actual'], $d, 0, $vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable']); 
        if($j > 0){
            ${"traficodetalle_mes_actual_".$val['id']}[$j] =  ${"traficodetalle_mes_actual_".$val['id']}[$j] +  ${"traficodetalle_mes_actual_".$val['id']}[$j-1];
        }
        $j++;
    }
    $flag++;
}


//echo '<pre>';
//print_r($traficodetalle_mes_actual_1);
//echo '</pre>';
//die();
?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/highcharts/highcharts-3d.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/highcharts/export-csv/export-csv.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/daterangepicker.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>

<script type="text/javascript">
    $(function () {
        var today = new Date();
        var startDate = new Date(today.getFullYear(), 02, 1);
        var endDate = new Date(today.getFullYear(), 02, 31);
        $("#trafico_fecha1").datepicker({dateFormat: 'dd',startDate: startDate,endDate: endDate,dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']});
        $("#trafico_fecha2").datepicker({dateFormat: 'dd',startDate: startDate,endDate: endDate,dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']});
        $('#trafico_provincia').change(function(){if ($(this).val() != ''){$('#provincia').val(1);}else{$('#provincia').val(0);}});
        $('#trafico_grupo').change(function(){if ($(this).val() != ''){$('#grupo').val(1);}else{$('#grupo').val(0);}});
        $('#trafico_concesionario').change(function(){if ($(this).val() != ''){$('#concesionario').val(1);} else{$('#concesionario').val(0);}});
        $('#trafico_responsable').change(function(){if ($(this).val() != ''){$('#responsable').val(1);} else{$('#responsable').val(0);}});
        $('#trafico_categoria').change(function(){if ($(this).val() != ''){$('#categoria').val(1);}else{$('#categoria').val(0);}});
        $('#trafico_year').change(function(){if ($(this).val() != ''){$('#year').val(1);}else{$('#year').val(0);}});
        $('#trafico_fecha1').change(function(){if ($(this).val() != ''){$('#fecha1').val(1);}else{$('#fecha1').val(0);}});
        $('#trafico_fecha2').change(function(){if ($(this).val() != ''){$('#fecha2').val(1);}else{$('#fecha2').val(0);}});
        $('#trafico_provincia').change(function(){
            var value = $(this).attr('value');
            var cargo_id = <?php echo $vartrf['cargo_id']; ?>;
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("trafico/getGrupos"); ?>',
                beforeSend: function (xhr) {$('#info5').show();  // #info must be defined somehwere
                },
                type: 'POST', dataType: 'json', data: {id: value},
                success: function (data) {
                    $('#info5').hide();
                    // GERENTE COMERCIAL
                    if(cargo_id == 69){$('#trafico_concesionario').html(data.data);}else{$('#trafico_grupo').html(data.data);}
                }
            });
        });
        $('#trafico_grupo').change(function(){
            var value = $(this).attr('value');
            var provincia = $('#trafico_provincia').val();
            //console.log(provincia);
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getConcesionarios"); ?>',
                beforeSend: function (xhr) {
                    $('#info3').show();  // #info must be defined somehwere
                },
                type: 'POST', data: {id: value,provincia: provincia},
                success: function (data) { $('#trafico_concesionario').html(data);}
            });
        });
        $('#trafico_concesionario').change(function(){
            var value = $(this).attr('value');
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("trafico/getAsesores"); ?>',
                beforeSend: function (xhr) {
                //$('#info-3').show();  // #bg_negro must be defined somewhere
                },
                type: 'POST',
                //dataType: 'json', 
                data: {dealer_id: value, tipo:'seg'},
                success: function (data) {
                        $('#trafico_responsable').html(data);
                }
            });
        });
        Highcharts.setOptions({
            colors: ['#30527C', '#365C87', '#406C9F', '#4672A9', '#4E80BB', '#89A3C9', '#9FB1D1','#AFBFD7','#BFCBDD','#CDD5E5','#1EA6E0']
        });
        var chart;
        <?php for ($i = $vartrf['mes_inicial']; $i < $vartrf['mes_actual']; $i++) { ?>
            Highcharts.chart('container'+<?php echo $i; ?>, {
            chart: {
                type: 'pie',
                options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                }
            },
            title: {
                text: 'Tráfico mes de '+'<?php echo $this->getNombreMesGraficas($i); ?> - <?php echo $vartrf['dia_actual']; ?>'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Versiones',
                colorByPoint: true,
                data: [
                    <?php 
                    $j = 0;
                    foreach ($vartrf['modelos'] as $val) {
                        //echo ${"trafico".$j}[$j];
                        echo "{";
                        echo "name: '".$val['nombre_modelo']."',";
                        echo "y: ".$this->getPorcentaje(${trafico.$i}[$j],${trafico_total.$i});
                        echo "},";
                        $j++;
                    } 
                    ?>    
                ]
            }]
        });
        <?php } ?>
        <?php for ($i = $vartrf['mes_inicial']; $i < $vartrf['mes_actual']; $i++) { ?>
            Highcharts.chart('containerv'+<?php echo $i; ?>, {
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                    text: 'Ventas mes de '+'<?php echo $this->getNombreMesGraficas($i); ?> - <?php echo $vartrf['dia_actual']; ?>'
            },
            tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Versiones',
                colorByPoint: true,
                data: [
                    <?php 
                    $j = 0;
                    foreach ($vartrf['modelos'] as $val) {
                        //echo ${"trafico".$j}[$j];
                        echo "{";
                        echo "name: '".$val['nombre_modelo']."',";
                        echo "y: ".$this->getPorcentaje(${ventas.$i}[$j],${ventas_total.$i});
                        echo "},";
                        $j++;
                    } 
                    ?>    
                ]
            }]
        });
        <?php } ?>
        <?php $k = 0; 
        foreach ($vartrf['modelos'] as $val) { ?>
        Highcharts.chart('containerg'+<?php echo $k; ?>, {
            colors: [
                '#2f7ed8','#0d233a','#8bbc21'
            ],
            title: {
                text: 'Tráfico Mes Actual vs Anterior por Modelo L',
                x: -20 //center
            },
            subtitle: {
                text: '<?php echo $val['nombre_modelo'] ?>',
                x: -20
            },
            xAxis: [{
                categories: [
                    <?php for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                        echo "'".$this->getNombreMesGraficas($i); ?> - <?php echo $vartrf['dia_actual']."',";
                    } ?>
                ]
            }], 
            yAxis: [{ // Primary Axis
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            { // Secondary yAxis
                title: {
                    text: 'Tasa de Cierre',
                    style: {
                        color: Highcharts.getOptions().colors[9]
                    }
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: Highcharts.getOptions().colors[9]
                    }
                },
                opposite: true

            }],
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true,
                        style:{
                                fontSize: '10px'
                        }
                    },
                    enableMouseTracking: false
                }
            },
            tooltip: {
                valueSuffix: ' %'
            },
            series: [{
                type: 'column', 
                yAxis: 1,
                name: 'Tasa de Cierre',
                data: [<?php for ($i = 0; $i < $vartrf['mes_actual']; $i++){
                    echo ${tasacierre.$i}[$k].',';
                } ?>
                ]
            },{
                name: 'Trafico',
                data: [
                    <?php for ($i = 0; $i < $vartrf['mes_actual']; $i++){
                        echo ${trafico.$i}[$k].',';
                    } ?>
                ]
            }, {
                name: 'Ventas',
                data: [<?php for ($i = 0; $i < $vartrf['mes_actual']; $i++){
                    echo ${ventas.$i}[$k].',';
                } ?>]
            }]
        });
        <?php $k++; } ?>
        <?php $k = 0;
        foreach ($vartrf['modelos'] as $val) { ?>
        Highcharts.chart('containerh'+<?php echo $k; ?>, {
            /*chart: {
                type: 'spline'
            },*/
            colors: [
                '#2f7ed8','#0d233a','#8bbc21'
            ],
            title: {
                text: 'Tráfico Mes Actual vs Anterior por Modelo',
                x: -20 //center
            },
            subtitle: {
                text: '<?php echo $val['nombre_modelo'] ?>',
                x: -20
            },
            xAxis: [{
                categories: [
                    <?php for ($i = 1; $i <= $vartrf['dia_actual']; $i++){
                        echo "'".$i."',";
                    } ?>
                ]
            }], 
            yAxis: [{ // Primary Axis
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            }],
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: false,
                        style:{
                            fontSize: '10px'
                        }
                    },
                    enableMouseTracking: true
                },
                spline: {
                    lineWidth: 2,
                    
                    marker: {
                        enabled: false
                    }
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            series: [{
                name: '<?php echo $this->getNombreMesGraficasG($vartrf['mes_anterior']); ?> - <?php echo $vartrf['dia_actual']; ?>',
                data: [<?php for ($i = 0; $i < $vartrf['dia_actual']; $i++){
                    echo ${traficodetalle_mes_anterior_.$val['id']}[$i].',';
                } ?>]
            },{
                name: '<?php echo $this->getNombreMesGraficasG($vartrf['mes_actual']); ?> - <?php echo $vartrf['dia_actual']; ?>',
                data: [
                    <?php for ($i = 0; $i < $vartrf['dia_actual']; $i++){
                            echo ${traficodetalle_mes_actual_.$val['id']}[$i].',';
                    } ?>
                ]
            }]
        });    
        <?php $k++; } ?>    
    });
</script>
<style type="text/css">
.ui-datepicker-calendar thead{display: none;}
@media (min-width: 1200px){
    .container {width: 1280px;max-width: 1400px;}
}
/*@media (min-width: 992px){
    .col-md-6 {
            width: 46%;
    }
}*/
.highcharts-container{border: 1px solid rgba(115, 135, 156, 0.33);}
.highcharts-credits{display: none;}
.highcharts-subtitle{color: #5985B8 !important;fill: #5985B8 !important;width: 516px;font-size: 15px;}
/* Link the series colors to axis colors */
.color0 {fill: #7cb5ec;}


</style>
<div id="bg_black" style="display: none;">
    <div class="cssload-aim"></div>
</div>

<div class="container">
    <div class="row">
        <h1 class="tl_seccion">GRÁFICAS</h1>
    </div>
    <div class="row">
        <div class="col-md-2">
                <a href="<?php echo Yii::app()->createUrl('trafico/inicio', array())?>" class="btn btn-danger">Tráfico</a>
        </div>
        <div class="col-md-12"><hr /></div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="highlight">
                <div class="form">
                    <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'casos-form',
                            'method' => 'get',
                            'action' => Yii::app()->createUrl('trafico/graficos'),
                            'enableAjaxValidation' => true,
                            'htmlOptions' => array(
                                'class' => 'form-horizontal form-search'
                            ),
                        ));
                        ?>
                    <h4>Búsqueda: (Seleccione solo uno de los filtros)</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="Trafico_fecha">Fecha 1</label>
                            <input type="text" name="GestionDiaria[fecha1]" id="trafico_fecha1" class="form-control"/>
                        </div>
                        <div class="col-md-4">
                            <label for="Trafico_fecha">Fecha 2</label>
                            <input type="text" name="GestionDiaria[fecha2]" id="trafico_fecha2" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label for="">Año</label>
                            <select name="GestionDiaria[year]" id="trafico_year" class="form-control">
                                <option value="2016">2016</option>
                                <option value="2017" selected="">2017</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <?php if($vartrf['cargo_id'] == 69 || $vartrf['area_id'] == 4 || $vartrf['area_id'] == 12 || $vartrf['area_id'] == 13 || $vartrf['area_id'] == 14): ?>
                        <div class="col-md-6">
                            <label for="Trafico_provincia">Provincia</label>
                            <select name="GestionDiaria[provincia]" id="trafico_provincia" class="form-control">
                                <?php echo $this->getProvincias($vartrf); ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <?php if($vartrf['area_id'] == 4 || $vartrf['area_id'] == 12 || $vartrf['area_id'] == 13 || $vartrf['area_id'] == 14): ?>
                        <div class="col-md-6">
                            <label for="">Grupo</label>
                            <div id="info5" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>

                            <select name="GestionDiaria[grupo]" id="trafico_grupo" class="form-control">
                                <option value="">--Seleccione Grupo--</option>
                                <option value="1000">TODOS</option>
                                <option value="6">AUTHESA</option>
                                <option value="2">GRUPO ASIAUTO</option>
                                <option value="5">GRUPO EMPROMOTOR</option>
                                <option value="3">GRUPO KMOTOR</option>
                                <option value="8">GRUPO MERQUIAUTO</option>
                                <option value="9">GRUPO MOTRICENTRO</option>
                                <option value="4">IOKARS</option>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <?php if($vartrf['cargo_id'] == 69 || $vartrf['cargo_id'] == 70 || $vartrf['area_id'] == 4 || $vartrf['area_id'] == 12 || $vartrf['area_id'] == 13 || $vartrf['area_id'] == 14): ?>
                        <div class="col-md-6">
                            <label for="">Concesionario</label>
                            <select name="GestionDiaria[concesionario]" id="trafico_concesionario" class="form-control">
                                <?php if($vartrf['cargo_id'] == 69){echo $this->getConcesionarios($vartrf);}?>
                                <?php if($vartrf['cargo_id'] == 70){echo $this->getConcesionariosSelected($vartrf);} ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <?php if($vartrf['cargo_id'] == 70 || $vartrf['cargo_id'] == 69 || $vartrf['area_id'] == 4 || $vartrf['area_id'] == 12 || $vartrf['area_id'] == 13 || $vartrf['area_id'] == 14): ?>
                        <div class="col-md-6">
                            <label for="">Responsable</label>
                            <select name="GestionDiaria[responsable]" id="trafico_responsable" class="form-control">
                                <?php if($vartrf['cargo_id'] == 70){echo $this->getResponsables($vartrf);} else { ?>
                                <option value="">--Seleccione responsable-</option>
                                <?php }?>
                            </select>

                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Tipo de Vehículo</label>
                            <select name="GestionDiaria[categoria]" id="trafico_categoria" class="form-control">
                                <option value="">--Seleccione Categoria--</option>
                                <option value="5" selected>Todos</option>
                                <option value="1">Autos</option>
                                <option value="2">SUV</option>
                                <option value="3">MPV</option>
                                <option value="4">Comerciales</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
                            <!--<button type="button" class="btn btn-warning" aria-label="Close" onclick="fnExcelReport();">Descargar Excel</button>-->
                            <input type="hidden" name="fecha1" id="fecha1" value="0" />
                            <input type="hidden" name="fecha2" id="fecha2" value="0" />
                            <input type="hidden" name="provincia" id="provincia" value="0" />
                            <input type="hidden" name="grupo" id="grupo" value="<?php echo ($area == 0 && ($vartrf['cargo_id'] == 69)) ? 1 : 0;  ?>" />
                            <input type="hidden" name="concesionario" id="concesionario" value="<?php echo ($vartrf['cargo_id'] == 70) ? 1 : 0 ?>" />
                            <input type="hidden" name="responsable" id="responsable" value="0" />
                            <input type="hidden" name="categoria" id="categoria" value="0" />
                            <input type="hidden" name="year" id="year" value="0" />
                        </div>
                    </div>

                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsives">
                <table class="table table-bordered" id="trafico_nacional" name="main_info_1">
                    <tr>
                        <th colspan="<?php echo $vartrf['mes_actual'] + 2; ?>" align="left" scope="col">TRÁFICO VENTAS - <?php echo $vartrf['search']['titulo']; ?></th>
                    </tr>
                </table>
            </div>
        </div>        
    </div>
    <div class="row">
        <h1 class="tl_seccion">TRÁFICO - VENTAS</h1>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php for ($i = $vartrf['mes_inicial']; $i < $vartrf['mes_actual']; $i++) {
                echo '<div id="container'.$i.'" class="panel-body"></div>'; 
            } ?>
        </div>
        <div class="col-md-6">
            <?php for ($i = $vartrf['mes_inicial']; $i < $vartrf['mes_actual']; $i++) {
                echo '<div id="containerv'.$i.'" class="panel-body"></div>';
            } ?>
        </div>
    </div> 
	<div class="row">
            <h1 class="tl_seccion">TRAFICO MES ACTUAL VS. ANTERIOR POR MODELO</h1>
	</div>
	<div class="row">
            <div class="col-md-6">
                <div id="containerh0" class="panel-body"></div>
                <div id="containerh1" class="panel-body"></div>
                <div id="containerh2" class="panel-body"></div>
                <div id="containerh3" class="panel-body"></div>
                <div id="containerh4" class="panel-body"></div>
                <div id="containerh5" class="panel-body"></div>
                <div id="containerh6" class="panel-body"></div>
                <div id="containerh7" class="panel-body"></div>
                <div id="containerh8" class="panel-body"></div>
                <div id="containerh9" class="panel-body"></div>
                <div id="containerh10" class="panel-body"></div>
                <div id="containerh11" class="panel-body"></div>
                <div id="containerh12" class="panel-body"></div>
                <div id="containerh13" class="panel-body"></div>
                <div id="containerh14" class="panel-body"></div>
                <div id="containerh15" class="panel-body"></div>
                <div id="containerh16" class="panel-body"></div>
            </div>
            <div class="col-md-6">
                <div id="containerg0" class="panel-body"></div>
                <div id="containerg1" class="panel-body"></div>
                <div id="containerg2" class="panel-body"></div>
                <div id="containerg3" class="panel-body"></div>
                <div id="containerg4" class="panel-body"></div>
                <div id="containerg5" class="panel-body"></div>
                <div id="containerg6" class="panel-body"></div>
                <div id="containerg7" class="panel-body"></div>
                <div id="containerg8" class="panel-body"></div>
                <div id="containerg9" class="panel-body"></div>
                <div id="containerg10" class="panel-body"></div>
                <div id="containerg11" class="panel-body"></div>
                <div id="containerg12" class="panel-body"></div>
                <div id="containerg13" class="panel-body"></div>
                <div id="containerg14" class="panel-body"></div>
                <div id="containerg15" class="panel-body"></div>
                <div id="containerg16" class="panel-body"></div>
            </div>
	</div>  
</div>

