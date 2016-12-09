<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/daterangepicker.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>
<script type="text/javascript">
    
$(document).ready(function() {
    $('#trafico_provincia').change(function(){
        if ($(this).val() != ''){
            $('#provincia').val(1);
        } else{
            $('#provincia').val(0);
        }
    });
    $('#trafico_grupo').change(function(){
        if ($(this).val() != ''){
            $('#grupo').val(1);
        } else{
            $('#grupo').val(0);
        }
    });
    $('#trafico_concesionario').change(function(){
        if ($(this).val() != ''){
            $('#concesionario').val(1);
        } else{
            $('#concesionario').val(0);
        }
    });
    $('#trafico_responsable').change(function(){
        if ($(this).val() != ''){
            $('#responsable').val(1);
        } else{
            $('#responsable').val(0);
        }
    });
    $('#trafico_fecha1').change(function(){
        if ($(this).val() != ''){
            $('#fecha1').val(1);
        } else{
            $('#fecha1').val(0);
        }
    });
    $('#trafico_fecha2').change(function(){
        if ($(this).val() != ''){
            $('#fecha2').val(1);
        } else{
            $('#fecha2').val(0);
        }
    });
    var today = new Date();
    var startDate = new Date(today.getFullYear(), 02, 1);
    var endDate = new Date(today.getFullYear(), 02, 31);
    $("#trafico_fecha1").datepicker({
       dateFormat: 'dd',
       startDate: startDate,
       endDate: endDate,
       dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']
    });
    $("#trafico_fecha2").datepicker({
       dateFormat: 'dd',
       startDate: startDate,
       endDate: endDate,
       dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']
    });
    
    
    $('#trafico_provincia').change(function(){
        var value = $(this).attr('value');
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("trafico/getGrupos"); ?>',
            beforeSend: function (xhr) {
                $('#info5').show();  // #info must be defined somehwere
            },
            type: 'POST', dataType: 'json', data: {id: value},
            success: function (data) {
                $('#info5').hide();
                $('#trafico_grupo').html(data.data);
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
});
</script>
<style type="text/css">
    @media (min-width: 1200px){
        .container {width: 1280px;}
    }
    .container {max-width: 1400px;}
    td {padding: 5px !important;}
    .tr-f{color: #FFF;cursor: pointer;}
    .odd{background-color: #f9f9f9;}
    .oddtitle{background-color: #848485;color: #fff;}
    .oddtitle-t{background-color: #333333;color: #fff;}
    .odd-mt > td{background-color: #333333; color: #fff;line-height: 1 !important;}
    .odd-mh > td{background-color: #888; color: #fff;line-height: 1 !important;}
    .odd-desc > td{line-height: 1 !important; background-color: #ffffff;border: 1px solid rgba(34, 34, 34, 0.22) !important;}
    .odd-mt > td, .odd-mh > td, .odd-desc > td{font-size: 11px;}
    .close{color: #fff;text-shadow: 0 1px 0 #ffffff;opacity: 0.9;filter: alpha(opacity=90);}
    .ui-datepicker-calendar thead{display: none;}
    .cir{
        width: 10px;height: 10px;-moz-border-radius: 50%;-webkit-border-radius: 50%;border-radius: 50%;display: inline-block;margin-right: 2px;
    }
    .cir-1{background-color: #FB0A2A;}
    .cir-2{background-color:#4080FF;}
    .cir-3{background-color: #33cc00;}
    .cir-4{background-color:#ff0033;}
    .cir-5{background-color: #B17F0C;}
    .cir-6{background-color:#660000;}
    .cir-7{background-color: #cccccc;}
    .cir-8{background-color: #003333;}
    .cir-9{background-color: #b1b14a;}
    .cir-10{background-color: #ffcc33;}
    .cir-11{background-color:#3333ff;}
    .cir-12{background-color: #1d219e;}
    .cir-13{background-color: #E1141B;}
    .cir-14{background-color: #197719;}
    .cir-15{background-color: #aaa62b;}
    .cir-16{background-color: #ad5b56;}
    .cir-17{background-color: #33cc00;}
    .cir-18{background-color: #000000;}
    .odd-mh > td.cir-1{background-color: #FB0A2A;}
    .odd-mh > td.cir-2{background-color:#4080FF;}
    .odd-mh > td.cir-3{background-color: #33cc00;}
    .odd-mh > td.cir-4{background-color:#ff0033;}
    .odd-mh > td.cir-5{background-color: #B17F0C;}
    .odd-mh > td.cir-6{background-color:#660000;}
    .odd-mh > td.cir-7{background-color: #cccccc;}
    .odd-mh > td.cir-8{background-color: #003333;}
    .odd-mh > td.cir-9{background-color: #b1b14a;}
    .odd-mh > td.cir-10{background-color: #ffcc33;}
    .odd-mh > td.cir-11{background-color:#3333ff;}
    .odd-mh > td.cir-12{background-color: #1d219e;}
    .odd-mh > td.cir-13{background-color: #E1141B;}
    .odd-mh > td.cir-14{background-color: #197719;}
    .odd-mh > td.cir-15{background-color: #aaa62b;}
    .odd-mh > td.cir-16{background-color: #ad5b56;}
    .odd-mh > td.cir-17{background-color: #33cc00;}
    .odd-mh > td.cir-18{background-color: #000000;}
    button.close{padding: 0 4px !important; border: 1px solid;}
    .close {font-size: 19px;font-weight: normal;}
    #bg_black {
        background-color: #fff;
        bottom: 0;
        left: 0;
        opacity: 0.7;
        position: fixed;
        right: 0;
        top: 0;
        z-index: 1040;
    }
    .cssload-aim{
	position: fixed;
	width: 20px;
	height: 20px;
	left: 35%;
	left: calc(50% - 42px);
		left: -o-calc(50% - 42px);
		left: -ms-calc(50% - 42px);
		left: -webkit-calc(50% - 42px);
		left: -moz-calc(50% - 42px);
	left: calc(50% - 42px);
        top: calc(50% - 42px);
        right: 0;
	border-radius: 18px;
	/*background-color: rgb(255,255,255);*/
	border-width: 18px;
	border-style: double;
	border-color:transparent rgb(170, 31, 44);
	box-sizing:border-box;
		-o-box-sizing:border-box;
		-ms-box-sizing:border-box;
		-webkit-box-sizing:border-box;
		-moz-box-sizing:border-box;
	transform-origin:	50% 50%;
		-o-transform-origin:	50% 50%;
		-ms-transform-origin:	50% 50%;
		-webkit-transform-origin:	50% 50%;
		-moz-transform-origin:	50% 50%;
	animation: cssload-aim 1.3s linear infinite;
		-o-animation: cssload-aim 1.3s linear infinite;
		-ms-animation: cssload-aim 1.3s linear infinite;
		-webkit-animation: cssload-aim 1.3s linear infinite;
		-moz-animation: cssload-aim 1.3s linear infinite;
        z-index: 1040;        
	
}



@keyframes cssload-aim{
		0%{transform:rotate(0deg);}
		100%{transform:rotate(360deg);}
}

@-o-keyframes cssload-aim{
		0%{-o-transform:rotate(0deg);}
		100%{-o-transform:rotate(360deg);}
}

@-ms-keyframes cssload-aim{
		0%{-ms-transform:rotate(0deg);}
		100%{-ms-transform:rotate(360deg);}
}

@-webkit-keyframes cssload-aim{
		0%{-webkit-transform:rotate(0deg);}
		100%{-webkit-transform:rotate(360deg);}
}

@-moz-keyframes cssload-aim{
		0%{-moz-transform:rotate(0deg);}
		100%{-moz-transform:rotate(360deg);}
}
    
</style>
<?php
$tipo_grupo = 0; // GRUPO KMOTOR Y ASIAUTO
$area = 0;
if ($grupo_id == 2 || $grupo_id == 3) {
    $tipo_grupo = 1;
}
$area_id = (int) Yii::app()->user->getState('area_id');
if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) { // AEKIA USERS
    $area = 1;
}
//echo '<pre>';
//echo print_r($vartrf['search']['dia_anterior']);
//echo '</pre>';
$where = $vartrf['search']['where'];
?>
<div id="bg_black" style="display: none;">
    <div class="cssload-aim"></div>
</div>

<div class="container">
<div class="row">
    <h1 class="tl_seccion">Reporte de Tráfico</h1>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="highlight">
            <div class="form">
                <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'casos-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('trafico/inicio'),
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
                            <option value="2016" selected="">2016</option>
                            <option value="2017">2017</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="Trafico_provincia">Provincia</label>
                        <select name="GestionDiaria[provincia]" id="trafico_provincia" class="form-control">
                            <option value="">--Seleccione provincia--</option>
                            <option value="1">Azuay</option>
                            <option value="5">Chimborazo</option>
                            <option value="7">El Oro</option>
                            <option value="8">Esmeraldas</option>
                            <option value="10">Guayas</option>
                            <option value="11">Imbabura</option>
                            <option value="12">Loja</option>
                            <option value="13">Los Ríos</option>
                            <option value="14">Manabí</option>
                            <option value="16">Napo</option>
                            <option value="18">Pastaza</option>
                            <option value="19">Pichincha</option>
                            <option value="21">Tsachilas</option>
                            <option value="23">Tungurahua</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="">Grupo</label>
                        <div id="info5" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                        <select name="GestionDiaria[grupo]" id="trafico_grupo" class="form-control">
                            <option value="">--Seleccione Grupo--</option>
                            <option value="6">AUTHESA</option>
                            <option value="2">GRUPO ASIAUTO</option>
                            <option value="5">GRUPO EMPROMOTOR</option>
                            <option value="3">GRUPO KMOTOR</option>
                            <option value="8">GRUPO MERQUIAUTO</option>
                            <option value="9">GRUPO MOTRICENTRO</option>
                            <option value="4">IOKARS</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Concesionario</label>
                        <select name="GestionDiaria[concesionario]" id="trafico_concesionario" class="form-control">
                            <option value="">--Seleccione concesionario--</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="">Responsable</label>
                        <select name="GestionDiaria[responsable]" id="trafico_responsable" class="form-control">
                            <option value="">--Seleccione responsable-</option>
                        </select>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
                        <button type="button" class="btn btn-warning" aria-label="Close" onclick="tabletoExcel('main_info_1', 'main_info_1');">Descargar Excel</button>
                        <input type="hidden" name="fecha1" id="fecha1" value="0" />
                        <input type="hidden" name="fecha2" id="fecha2" value="0" />
                        <input type="hidden" name="provincia" id="provincia" value="0" />
                        <input type="hidden" name="grupo" id="grupo" value="0" />
                        <input type="hidden" name="concesionario" id="concesionario" value="0" />
                        <input type="hidden" name="responsable" id="responsable" value="0" />
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>    
<br />

<div class="row">
    <div class="col-md-12">
        <div class="table-responsives">
            <table class="table table-bordered" id="main_info_1" name="main_info_1">
                <tr>
                    <th colspan="<?php echo $vartrf['mes_actual'] + 2; ?>" align="left" scope="col">TRÁFICO NACIONAL - <?php echo $vartrf['search']['titulo']; ?></th>
                </tr>
                <?php
                $clas_cer = 1;
                foreach ($vartrf['modelos'] as $val) {
                    $vartrf['trafico_suma'] = 0;
                    $vartrf['proforma_suma'] = 0;
                    $vartrf['testdrive_suma'] = 0;
                    $vartrf['ventas_suma'] = 0;
                    $vartrf['trafico'] = array();
                    $vartrf['proforma'] = array();
                    $vartrf['testdrive'] = array();
                    $vartrf['ventas'] = array();
                    ?>
                    <tr>
                        <td colspan="<?php echo $vartrf['mes_actual'] + 2; ?>" class="oddtitle"><span class="cir cir-<?php echo $val['id']; ?>"></span><?php echo '<strong>' . $val['nombre_modelo'] . '</strong>'; ?></td>
                    </tr>
                    <tr>
                        <td bgcolor="#D9534F" class="tr-f">Funnel</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td bgcolor="#D9534F" class="tr-f dde_' . $i . '_' . $val['id'] . '"  id="dde_' . $i . '_' . $val['id'] . '" onclick="detail(\'' . $val['id_versiones'] . '\',\'' . $vartrf['dia_inicial'] . '\',\'' . $vartrf['dia_actual'] . '\',\'' . $vartrf['year_actual'] . '\',\'' . $vartrf['fechas_date'][$i] . '\',' . $val['id'] . ',\'' . $val['nombre_modelo'] . '\',' . $i . ');" data-vec="0">' . $vartrf['fechas'][$i] . '</td>';
                        }
                        ?>
                        <td bgcolor="#D9534F" class="tr-f">Total</td>
                    </tr>
                    <tr>
                        <td>Tráfico</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            $vartrf['trafico'][] = $this->getTraficoVersion($i, $val['id_versiones'], $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search']);
                            $vartrf['trafico_suma'] += $vartrf['trafico'][$i];
                            $vartrf['trafico_suma_total'][$i] += $vartrf['trafico'][$i];
                            echo '<td>' . $vartrf['trafico'][$i] . '</td>';
                        }
                        ?>
                        <td><?php echo $vartrf['trafico_suma']; ?></td>
                    </tr>
                    <tr class="odd">
                        <td>Proforma</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            $vartrf['proforma'][] = $this->getProformaVersion($i, $val['id_versiones'], $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search']);
                            $vartrf['proforma_suma'] += $vartrf['proforma'][$i];
                            $vartrf['proforma_suma_total'][$i] += $vartrf['proforma'][$i];
                            echo '<td>' . $vartrf['proforma'][$i] . '</td>';
                        }
                        ?>
                        <td><?php echo $vartrf['proforma_suma']; ?></td>
                    </tr>
                    <tr>
                        <td>Test Drive</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            $vartrf['testdrive'][] = $this->getTestDriveVersion($i, $val['id_versiones'], $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search']);
                            $vartrf['testdrive_suma'] += $vartrf['testdrive'][$i];
                            $vartrf['testdrive_suma_total'][$i] += $vartrf['testdrive'][$i];
                            echo '<td>' . $vartrf['testdrive'][$i] . '</td>';
                        }
                        ?>
                        <td><?php echo $vartrf['testdrive_suma']; ?></td>
                    </tr>
                    <tr class="odd">
                        <td>Ventas</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            $vartrf['ventas'][] = $this->getVentasVersion($i, $val['id_versiones'], $vartrf['year_actual'], $vartrf['dia_actual'], 1, $vartrf['search']);
                            $vartrf['ventas_suma'] += $vartrf['ventas'][$i];
                            $vartrf['venta_suma_total'][$i] += $vartrf['ventas'][$i];
                            echo '<td>' . $vartrf['ventas'][$i] . '</td>';
                        }
                        ?>
                        <td><?php echo $vartrf['ventas_suma']; ?></td>
                    </tr>
                    <tr>
                        <td>Tasa de Test Drive</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td>' . $this->getTasaTD($vartrf['testdrive'][$i], $vartrf['trafico'][$i]) . '</td>';
                        }
                        ?>
                        <td><?php echo $this->getTasaTD($vartrf['testdrive_suma'], $vartrf['trafico_suma']); ?></td>
                    </tr>
                    <tr class="odd">
                        <td>Tasa de Cierre</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td>' . $this->getTasaCierre($vartrf['ventas'][$i], $vartrf['trafico'][$i]) . '</td>';
                        }
                        ?>
                        <td><?php echo $this->getTasaCierre($vartrf['ventas_suma'], $vartrf['trafico_suma']); ?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="<?php echo 'cer-' . $val['id']; ?>" colspan="<?php echo $vartrf['mes_actual'] + 2; ?>"></td>
                    </tr>
                    
                    <?php
                    $clas_cer++;
                }
                ?>
                    <?php 
//                    echo '<pre>';
//                    print_r($vartrf['trafico_suma_total']);
//                    echo '</pre>';
//                    echo '<pre>';
//                    print_r($vartrf['proforma_suma_total']);
//                    echo '</pre>';
//                    echo '<pre>';
//                    print_r($vartrf['testdrive_suma_total']);
//                    echo '</pre>';
//                    echo '<pre>';
//                    print_r($vartrf['venta_suma_total']);
//                    echo '</pre>';
                    ?>
                    <tr>
                        <td colspan="<?php echo $vartrf['mes_actual'] + 2; ?>" class="oddtitle-t">TOTAL</td>
                    </tr>  
                    <tr>
                        <td bgcolor="#D9534F" class="tr-f">Funnel</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td bgcolor="#D9534F" class="tr-f dde_' . $i . '_' . $val['id'] . '"  id="dde_' . $i . '_' . $val['id'] . '" data-vec="0">' . $vartrf['fechas'][$i] . '</td>';
                        }
                        ?>
                        <td bgcolor="#D9534F" class="tr-f">Total</td>
                    </tr>
                    <tr>
                        <td>Tráfico</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td>' . $vartrf['trafico_suma_total'][$i] . '</td>';
                            $vartrf['trafico_nacional'] += $vartrf['trafico_suma_total'][$i];
                        }
                        ?>
                        <td><?php echo $vartrf['trafico_nacional']; ?></td>
                    </tr>
                    <tr class="odd">
                        <td>Proforma</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td>' . $vartrf['proforma_suma_total'][$i] . '</td>';
                            $vartrf['proforma_nacional'] += $vartrf['proforma_suma_total'][$i];
                        }
                        ?>
                        <td><?php echo $vartrf['proforma_nacional']; ?></td>
                    </tr>
                    <tr>
                        <td>Test Drive</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td>' . $vartrf['testdrive_suma_total'][$i] . '</td>';
                            $vartrf['testdrive_nacional'] += $vartrf['testdrive_suma_total'][$i];
                        }
                        ?>
                        <td><?php echo $vartrf['testdrive_nacional']; ?></td>
                    </tr>
                    <tr class="odd">
                        <td>Ventas</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td>' . $vartrf['venta_suma_total'][$i] . '</td>';
                            $vartrf['ventas_nacional'] += $vartrf['venta_suma_total'][$i];
                        }
                        ?>
                        <td><?php echo $vartrf['ventas_nacional']; ?></td>
                    </tr>
                    <tr>
                        <td>Tasa de Test Drive</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td>' . $this->getTasaTD($vartrf['testdrive_suma_total'][$i], $vartrf['trafico_suma_total'][$i]) . '</td>';
                        }
                        ?>
                        <td><?php echo $this->getTasaTD($vartrf['testdrive_suma'], $vartrf['trafico_suma']); ?></td>
                    </tr>
                    <tr class="odd">
                        <td>Tasa de Cierre</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td>' . $this->getTasaCierre($vartrf['venta_suma_total'][$i], $vartrf['trafico_suma_total'][$i]) . '</td>';
                        }
                        ?>
                        <td><?php echo $this->getTasaCierre($vartrf['ventas_nacional'], $vartrf['trafico_nacional']); ?></td>
                    </tr>
                    
            </table>
            
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    function detail(versiones, dia_inicial, dia_actual, year, mes, id, nombre_modelo, i) {
        var where = '<?php echo $where; ?>';
        //console.log('i: '+i);
        fr = 0;
        var id_modelos = [1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17];
        for (j=0; j < id_modelos.length; j++){
            //console.log('dde_' + i + '_' + id_modelos[j]);
            vecobj = document.getElementById('dde_' + i + '_' + id_modelos[j]);
            datpl = vecobj.dataset.vec;
            if(datpl == 0){
               vecobj.dataset.vec = 1;
            }else{
                fr++;
            }
        }
        var vecobj = document.getElementById('dde_' + i + '_' + id);
        var datpl = vecobj.dataset.vec;
        if (fr == 0) {
            for (x=0; x < id_modelos.length; x++){
                $('.dde_' + i + '_' + id_modelos[x]).css('background-color', '#47A447');
            }
            //$('.dde_' + i + '_' + id).css('background-color', '#47A447');
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("trafico/getTraficoDiario"); ?>',
                beforeSend: function (xhr) {
                    $('#bg_black').show();  // #info must be defined somehwere
                },
                type: 'POST',
                dataType: 'json',
                data: {versiones: versiones, dia_inicial: dia_inicial, dia_actual: dia_actual,year: year,
                mes: mes, nombre_modelo: nombre_modelo, id: id, i:i,where : where},
                success: function (data) {
                    //console.log(data);
                    //$('.cer-' + id).append(data);
                    $('.cer-1').append(data.data_picanto);
                    $('.cer-2').append(data.data_carens);
                    $('.cer-3').append(data.data_cerato_forte);
                    $('.cer-4').append(data.data_cerato_koup);
                    $('.cer-5').append(data.data_cerato_r);
                    $('.cer-6').append(data.data_grand_carnival);
                    $('.cer-7').append(data.data_k3000);
                    $('.cer-8').append(data.data_optima);
                    $('.cer-10').append(data.data_rio_sedan);
                    $('.cer-11').append(data.data_rio_hb);
                    $('.cer-12').append(data.data_soul_ev);
                    $('.cer-13').append(data.data_soul_r);
                    $('.cer-14').append(data.data_sportage_active);
                    $('.cer-15').append(data.data_sportage_r);
                    $('.cer-16').append(data.data_sportage_gt);
                    $('.cer-17').append(data.data_sportage_r_ckd);
                    $('#bg_black').hide();
                }
            });
            //vecobj.dataset.vec = 1;
        }else{
          // recorrer todos los tds y poner data-vec en 0
          for (j=0; j < id_modelos.length; j++){
             vecobj = document.getElementById('dde_' + i + '_' + id_modelos[j]);
             vecobj.dataset.vec = 0;
             $('.dde_' + i + '_' + id_modelos[j]).css('background-color', '#D9534F');
             //$('.det_'+mes).remove();
             $('.det_'+mes).slideUp('slow',function(){
                 $('.det_'+mes).remove();
             });
          }
           //vecobj.dataset.vec = 0; 
           // colocar color original al td
           //$('.dde_' + i + '_' + id).css('background-color', '#D9534F');
           // borrar lo que este en el DOM
           //$('.det_'+mes).remove();
        }

    }
    function closemodal(mes, i) {
        var id_modelos = [1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17]
        $('.det_' + mes).slideUp('slow', function(){
            $(this).remove();
        });
        //remove();
        // poner color original a los tds y colocar data-vec en 0
        for (j=0; j < id_modelos.length; j++){
            //console.log('.dde_' + i + '_' + id_modelos[j]);
            $('.dde_' + i + '_' + id_modelos[j]).css('background-color', '#D9534F');
            vecobj = document.getElementById('dde_' + i + '_' + id_modelos[j]);
            vecobj.dataset.vec = 0;
        }
        
        //var vecobj = document.getElementById('dde_' + i + '_' + id);
        //console.log('dde_' + i + '_' + id);
        //var datpl = vecobj.dataset.vec;
        //console.log('id: ' + id);
        //$('.dde_' + i + '_' + id).css('background-color', '#D9534F');vecobj.dataset.vec = 0; 
        //$('.det_' + mes).remove();
    }

    function tabletoExcel(table, name) {
        var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><?xml version="1.0" encoding="UTF-8" standalone="yes"?><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) };
  
      if (!table.nodeType) table = document.getElementById(table)
      var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML };
        window.location.href = uri + base64(format(template, ctx));

    }
</script>


