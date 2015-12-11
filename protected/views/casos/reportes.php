<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>
<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
    $(function() {
        $( "#Casos_fecha" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2015'
        });
        $( "#Casos_fecha2" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2015'
        });
        $("#Casos_fecha2").hide();
        $('#Casos_tipo_fecha').change(function() {
            var value = $(this).attr('value');
            if(value == 'between'){
                $("#Casos_fecha2").show();
            }else{
                $("#Casos_fecha2").hide();
            }
            
        });
    });
</script>  
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Reportes</h1>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="highlight">
                <div class="form">
                    <h4>Filtrar por:</h4>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'casos-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('casos/reportes'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array('class' => 'form-horizontal form-search')
                            ));
                    ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="Casos_tema">Tema</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="Casos[tema]" id="Casos_tema">
                                <option value="">Selecciona un tema</option>
                                <option value="7">Asistencia Kia</option>
                                <option value="2">Colisiones</option>
                                <option value="1">Flotas</option>
                                <option value="3">Información Vehículos Nuevos</option>
                                <option value="5">Servicio Postventa</option>
                                <option value="6">Sugerencias e Inquietudes</option>
                                <option value="4">Vehículos Seminuevos</option>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label required" for="Casos_tema">Subtema</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="Casos[subtema]" id="Casos_subtema">
                                <option value="">Selecciona un subtema</option>
                            </select>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tipo Fecha</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="Casos[tipo_fecha]" id="Casos_tipo_fecha">
                                <option value="igual" selected>Igual</option>
                                <option value="between">Entre</option>
                            </select>
                        </div>
                        <label class="col-sm-1 control-label" for="Casos_estado">Fecha</label>
                        <div class="col-md-3">
                            <input size="10" maxlength="10" class="form-control" name="Casos[fecha]" id="Casos_fecha" type="text">
                        </div>
                        <div class="col-md-3">
                            <input size="10" maxlength="10" class="form-control" name="Casos[fecha2]" id="Casos_fecha2" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="Casos_estado">Estado</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="Casos[estado]" id="Casos_estado">
                                <option value="">Selecciona un estado</option>
                                <option value="ABIERTO">Abierto</option>
                                <option value="PROCESO">En Proceso</option>
                                <option value="CERRADO">Cerrado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row buttons">
                        <?php //echo CHtml::submitButton($model->isNewRecord ? 'Buscar' : 'Save', array('class' => 'btn btn-danger'));   ?>
                        <input class="btn btn-danger" type="submit" name="yt0" value="Generar Reporte">
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-md-offset-2">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'casos-excel',
                'method' => 'post',
                'action' => Yii::app()->createUrl('casos/exportExcel'),
                'enableAjaxValidation' => true,
                'htmlOptions' => array('class' => 'form-horizontal form-search')
                    ));
            ?>
            <input type="hidden" name="Casos[tema]" id="Casos_tema" value="<?php
            if (!empty($_GET["Casos"]['tema'])) {
                echo $_GET["Casos"]['tema'];
            } else {
                echo '';
            }
            ?>">
            <input type="hidden" name="Casos[subtema]" id="Casos_subtema" value="<?php
                   if (!empty($_GET["Casos"]['subtema'])) {
                       echo $_GET["Casos"]['subtema'];
                   } else {
                       echo '';
                   }
            ?>">
            <input type="hidden" name="Casos[fecha]" id="Casos_fecha" value="<?php
                   if (!empty($_GET["Casos"]['fecha'])) {
                       echo $_GET["Casos"]['fecha'];
                   } else {
                       echo '';
                   }
            ?>">
            <input type="hidden" name="Casos[estado]" id="Casos_estado" value="<?php
                   if (!empty($_GET["Casos"]['estado'])) {
                       echo $_GET["Casos"]['estado'];
                   } else {
                       echo '';
                   }
            ?>">
            <input type="hidden" name="Casos[ciudad]" id="Casos_ciudad" value="<?php
                   if (!empty($_GET["Casos"]['ciudad'])) {
                       echo $_GET["Casos"]['ciudad'];
                   } else {
                       echo '';
                   }
            ?>">
<!--            <input class="btn btn-primary" type="submit" name="yt0" value="Generar Gráfica" style="float: right;">-->
            <?php $this->endWidget(); ?>
            <?php //if ($case === 'default'):  ?>
                                                <!--<a href="<?php //echo Yii::app()->createUrl('casos/exportExcel', array('param' => $case));       ?>" class="btn btn-primary">Guardar en Excel</a>-->
            <?php //endif;  ?>
            <?php //if (isset($getParams)):  ?> 
                                                <!--<a href="<?php //echo Yii::app()->createUrl('casos/exportExcel', array('param' => $getParams));        ?>" class="btn btn-primary">Guardar en Excel</a>-->
            <?php //endif;  ?>    
        </div>
    </div>
    <div class="row">
        <div id="container" style="width: 100%; height: 400px; margin: 0 auto">

        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?php //$this->widget('CLinkPager', array('pages' => $pages)); ?>
        </div>
        <div class="col-md-10 links-tabs" style="margin-top: 8px;">
            <div class="col-md-3">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'casos-form-search',
                    'method' => 'post',
                    'action' => Yii::app()->createUrl('site/busqueda'),
                    'htmlOptions' => array('class' => 'form-search-case')
                        ));
                ?>
                <div id="custom-search-input">
                    <div class="input-group col-md-12">
                        <input type="text" class="search-query form-control" placeholder="Buscar" name="buscar"/>
                        <span class="input-group-btn">
                            <button class="btn btn-danger btn-search" type="submit">
                                <span class=" glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
            <div class="col-md-2" style="padding-left: 0px;"><p>También puedes ir a:</p></div>
            <div class="col-md-3"><a href="<?php echo Yii::app()->createUrl('casos/create'); ?>" class="creacion-btn">Creación de Casos</a></div>
            <div class="col-md-3 seg"><a href="<?php echo Yii::app()->createUrl('casos/seguimiento'); ?>" class="seguimiento-btn">Seguimiento de Casos</a></div>
            <div class="col-md-1"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>

        </div>
    </div>
</div>
<?php if (isset($model)): //echo 'TIPO: ' . $tipo; ?>
    <script type="text/javascript">
        $(function () {
            $('#container').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Consulta por Tema: <?php echo $this->getTema($id_tema); ?>'
                },
                subtitle: {
                    text: 'Subtemas'
                },
                xAxis: {
                    categories: [
                        '<?php echo $this->getTema($id_tema); ?>'
                    ]
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Porcentaje'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.f} </b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [
    <?php
    foreach ($model as $value):
        echo "{
               name: '" . $this->getSubtema($value['subtema']) . "',
               data: [" . $value['sub'] . "]},";
    endforeach;
    ?>
                ]
            });
                    
        });
                
    </script>
<?php endif; ?>
<?php if (isset($modeldf)): ?>
    <?php
    $criteria = new CDbCriteria;
    $criteria->order = 'name asc';
    $sql = "SELECT id, tema, count(*) AS 'tem' FROM casos GROUP BY tema HAVING COUNT(*) >= 1";
    $casos = Casos::model()->findAllBySQL($sql);
    ?>
    <script type="text/javascript">
        $(function () {
                
            var colors = Highcharts.getOptions().colors,
    <?php
    $arraySubcat = array();
    $arrayPorcentajeCat = array();
    $j = 0;
    $categories = "categories = [";
    foreach ($casos as $value) {
        $categories .= "'" . $this->getTema($value['tema']) . "',";
        $arraySubcat[$j] = $value['tema'];
        $arrayPorcentajeCat[$j] = $value['tem'];
        $j++;
    }
    $categories .= "],";
    echo $categories;
    ?>
                    name = 'Temas',
    <?php
    $numCat = count($arraySubcat); // id numero categoria 
    $numCat--;
    $dataH = "data = [{";
    for ($k = 0; $k < count($arraySubcat); $k++) {
        $sql = "SELECT tema,subtema, count(*) AS 'sub' FROM casos WHERE tema = {$arraySubcat[$k]} GROUP BY subtema HAVING COUNT(*) >= 1";
        $subtemas = Casos::model()->findAllBySQL($sql);
        $dataH .= "y: {$arrayPorcentajeCat[$k]},
                        color: colors[$k],
                        drilldown: {";
        $dataH .= "name:'{$this->getTema($arraySubcat[$k])}',";
        $dataH .= "categories: [";
        foreach ($subtemas as $value) {
            $dataH .= "'{$this->getSubtema($value['subtema'])}',";
        }
        $dataH .= "],
                       data:[";
        foreach ($subtemas as $value) {
            $dataH .= "{$value['sub']},";
        }

        $dataH .= "],
                        color: colors[{$k}]";
        if ($k === $numCat):
            $dataH .= "}
                    },";
        else:
            $dataH .= "}
                    },{";
        endif;
    }
    $dataH .= "];";
    echo $dataH;
    ?>
                    function setChart(name, categories, data, color) {
                        chart.xAxis[0].setCategories(categories, false);
                        chart.series[0].remove(false);
                        chart.addSeries({
                            name: name,
                            data: data,
                            color: color || 'white'
                        }, false);
                        chart.redraw();
                    }
                
                    var chart = $('#container').highcharts({
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Temas y Subtemas Vista General'
                        },
                        subtitle: {
                            text: 'Click en los temas para ver los subtemas. Click otra vez para ver los temas.'
                        },
                        xAxis: {
                            categories: categories
                        },
                        yAxis: {
                            title: {
                                text: 'Total de temas'
                            }
                        },
                        plotOptions: {
                            column: {
                                cursor: 'pointer',
                                point: {
                                    events: {
                                        click: function() {
                                            var drilldown = this.drilldown;
                                            if (drilldown) { // drill down
                                                setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                                            } else { // restore
                                                setChart(name, categories, data);
                                            }
                                        }
                                    }
                                },
                                dataLabels: {
                                    enabled: true,
                                    color: colors[0],
                                    style: {
                                        fontWeight: 'bold'
                                    },
                                    formatter: function() {
                                        return this.y;
                                    }
                                }
                            }
                        },
                        tooltip: {
                            formatter: function() {
                                var point = this.point,
                                s = this.x +':<b>'+ this.y +' ocurrencias</b><br/>';
                                if (point.drilldown) {
                                    s += 'Click para ver SubTemas: '+ point.category;
                                } else {
                                    s += 'Click para volver a los Temas';
                                }
                                return s;
                            }
                        },
                        series: [{
                                name: name,
                                data: data,
                                color: 'white'
                            }],
                        exporting: {
                            enabled: true
                        }
                    })
                    .highcharts(); // return chart
                });
                

    </script>
<?php endif; ?>
<?php if (isset($model_estado)): ?>
    <?php
    //echo 'TIPO: ' . $tipo;

    $dataText = '';
    $subTitle = '';
    if (isset($estado)):
        $dataText = "Consulta por Estado: {$estado}";
        $subTitle = "Estados y temas";
    endif;
    if (isset($fecha)):
        if ($tipo == 'fechas_between') {
            $dataText = "Consulta por Fecha Desde : {$fecha} --- Hasta : {$fecha2}";
        }else{
            $dataText = "Consulta por Fecha: {$fecha}";
        }
        
        if ($tipo == 'tema_fecha_between') {
            $dataText = "Consulta por Fecha Desde : {$fecha} --- Hasta : {$fecha2}";
            $subTitle = "Tema: ".$this->getTema($tema);
        }else if($tipo == 'tema_fecha'){
            $dataText = "Consulta por Fecha: {$fecha}";
            $subTitle = "Tema: ";
        }
        
        //$subTitle = "Fecha y temas";
    endif;
    ?>
    <script type="text/javascript">
        // CONSULTA DE GRAFICAS POR ESTADO O POR FECHAS
        $(function () {
            $('#container').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: '<?php echo $dataText; ?>' 
                },
                subtitle: {
                    text: '<?php echo $subTitle; ?>'
                },
                xAxis: {
                    categories: [
                        'Temas'
                    ]
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Cantidad'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.f} </b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [
    <?php
    foreach ($model_estado as $value):
        echo "{
                   name: '" . $this->getSubtema($value['tema']) . "',
                   data: [" . $value['tem'] . "]},";
    endforeach;
    ?>
                ]
            });

        });
                
    </script>
<?php endif; ?>    
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/highcharts/modules/exporting.js"></script>