<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatable/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatable/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatable/jszip.min.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/daterangepicker.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    $('#trafico_provincia').change(function(){if ($(this).val() != ''){$('#provincia').val(1);$('#provincia_onchange').val(1);} else{$('#provincia').val(0);$('#provincia_onchange').val(0);}});
    $('#trafico_grupo').change(function(){if($(this).val()!=''){$('#grupo').val(1)}else{$('#grupo').val(0);}});
    $('#trafico_concesionario').change(function(){if($(this).val()!=''){$('#concesionario').val(1);}else{$('#concesionario').val(0);}});
    $('#trafico_responsable').change(function(){if($(this).val()!=''){$('#responsable').val(1);}else{$('#responsable').val(0);}});
    $('#trafico_categoria').change(function(){if($(this).val()!=''){$('#categoria').val(1);}else{$('#categoria').val(0);}});
    $('#trafico_year').change(function(){if($(this).val()!=''){$('#year').val(1);}else{$('#year').val(0);}});
    $('#trafico_fecha1').change(function(){if($(this).val()!=''){$('#fecha1').val(1);}else{$('#fecha1').val(0);}});
    $('#trafico_fecha2').change(function(){if($(this).val()!=''){$('#fecha2').val(1);}else{$('#fecha2').val(0);}})
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
        $('#grupo').val(0);$('#concesionario').val(0);
        vaciar();
        var value = $(this).attr('value');
        var cargo_id = <?php echo $vartrf['cargo_id']; ?>;
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("trafico/getGrupos"); ?>',
            beforeSend: function (xhr) {
                $('#info5').show();  // #info must be defined somehwere
            },
            type: 'POST', dataType: 'json', data: {id: value,area_id : <?php echo $vartrf['area_id']; ?>, grupo_id: <?php echo $vartrf['grupo_id']; ?>, cargo_id: cargo_id},
            success: function (data) {
                $('#info5').hide();
                // GERENTE COMERCIAL
                if(cargo_id == 69){
                    $('#trafico_concesionario').html(data.data);
                }else{
                    $('#trafico_grupo').html(data.data);
                }
            }
        });
    });
    $('#trafico_grupo').change(function(){
        if($('#provincia_onchange').val() == 0){
            vaciarProvincia();
        }
        
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
    .ens{margin-bottom: 0;}
    .alert {
        font-size: 14px;
        margin-bottom: 15px !important;
    }
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
    .cir-19{background-color: #FF0000;}
    .cir-20{background-color: #7F00FF;}
    .cir-21{background-color: #D2322D;}
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
    .odd-mh > td.cir-19{background-color: #FF0000;}
    .odd-mh > td.cir-20{background-color: #7F00FF;}
    .odd-mh > td.cir-21{background-color: #D2322D;}
    button.close{padding: 0 4px !important; border: 1px solid;}
    .close {font-size: 19px;font-weight: normal;}
    #bg_black {background-color: #fff;bottom: 0;left: 0;opacity: 0.7;position: fixed;right: 0;top: 0;z-index: 1040;}
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
echo 'flag search: '.$vartrf['flag_search'];

//echo 'meses: '.$vartrf['mes_actual'];
//echo '<pre>';
//echo print_r($vartrf['versiones']);
//echo '</pre>';
//die();
$where = $vartrf['search']['where'];
$vartrf['id_modelos'];
$vartrf['venta_nacional_categoria'] = array();
$vartrf['testdrive_nacional_categoria'] = array();
//echo '<pre>';
//echo print_r($vartrf['fechas_date']);
//echo '</pre>';
//die();
//echo $vartrf['categoria'];
//echo $vartrf['categoria'];
//die();
?>
<div id="bg_black" style="display: none;">
    <div class="cssload-aim"></div>
</div>

<div class="container">
<div class="row">
    <h1 class="tl_seccion">Reporte de Tráfico Diario</h1>
</div>
<div class="row">
    <div class="col-md-2">
        <a href="<?php echo Yii::app()->createUrl('trafico/graficos', array())?>" class="btn btn-danger">Gráficos</a>

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
                        'action' => Yii::app()->createUrl('trafico/inicio'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array(
                            'class' => 'form-horizontal form-search'
                        ),
                    ));
                    ?>
                <h4>Búsqueda: </h4>
                <div class="row">
                    <div class="col-md-4">
                        <label for="Trafico_fecha">Fecha inicio</label>
                        <input type="text" name="GestionDiaria[fecha1]" id="trafico_fecha1" class="form-control" value="<?php echo $vartrf['dia_inicial']; ?>"/>
                    </div>
                    <div class="col-md-4">
                        <label for="Trafico_fecha">Fecha fin</label>
                        <input type="text" name="GestionDiaria[fecha2]" id="trafico_fecha2" class="form-control" value="<?php echo $vartrf['dia_actual']; ?>" />
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
                            <option value="1000">Todos</option>
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
                        <button type="button" class="btn btn-warning" aria-label="Close" onclick="tabletoExcel('trafico_nacional', 'trafico_nacional');">Descargar Excel</button>
                        <!--<button type="button" class="btn btn-warning" aria-label="Close" onclick="fnExcelReport();">Descargar Excel</button>-->
                        <input type="hidden" name="fecha1" id="fecha1" value="0" />
                        <input type="hidden" name="fecha2" id="fecha2" value="0" />
                        <input type="hidden" name="provincia" id="provincia" value="0" />
                        <input type="hidden" name="provincia_onchange" id="provincia_onchange" value="0" />
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
<br />
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
          <strong>Para visualizar el detalle del trafico debe dar click sobre cada mes</strong>         </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsives">
            <table class="table table-bordered" id="trafico_nacional" name="main_info_1">
                <tr>
                    <th colspan="<?php echo $vartrf['mes_actual'] + 2; ?>" align="left" scope="col">TRÁFICO NACIONAL - <?php echo $vartrf['search']['titulo']; ?></th>
                </tr>
                <?php
                $clas_cer = 1;
                $flag = 0;
                foreach ($vartrf['modelos'] as $val) {
                    //echo $flag.'<br />';
                    //echo $vartrf['versiones'][$flag].'<br />';
                    $vartrf['trafico_suma'] = 0;
                    $vartrf['proforma_suma'] = 0;
                    $vartrf['testdrive_suma'] = 0;
                    $vartrf['ventas_suma'] = 0;
                    $vartrf['trafico'] = array();
                    $vartrf['proforma'] = array();
                    $vartrf['testdrive'] = array();
                    $vartrf['ventas'] = array();
                    
                    ?>
                    <tr bgcolor="#848485">
                        <td colspan="<?php echo $vartrf['mes_actual'] + 2; ?>" class="oddtitle" id="cid_<?php echo $val['id']; ?>"><span class="cir cir-<?php echo $val['id']; ?>"></span><?php echo '<strong>' . $val['nombre_modelo'] . '</strong>'; ?></td>
                    </tr>
                    <tr>
                        <td bgcolor="#D9534F" class="tr-f">&nbsp;</td>
                        <?php for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td bgcolor="#D9534F" class="tr-f dde_' . $i . '_' . $val['id'] . '"  id="dde_' . $i . '_' . $val['id'] . '" onclick="detail(\'' . $vartrf['versiones'][$flag] . '\',\'' . $vartrf['dia_inicial'] . '\',\'' . $vartrf['dia_actual'] . '\',\'' . $vartrf['year_actual'] . '\',\'' . $vartrf['fechas_date'][$i] . '\',' . $val['id'] . ',\'' . $val['nombre_modelo'] . '\',' . $i . ','.$vartrf['categoria'].');" data-vec="0">' . $vartrf['fechas'][$i] . '</td>';
                        }
                        ?>
                        <td bgcolor="#D9534F" class="tr-f">Total</td>
                    </tr>
                    <tr>
                        <td>Tráfico</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            $vartrf['trafico'][] = count($this->getTraficoVersion($i, $vartrf['versiones'][$flag], $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable']));
                            $vartrf['trafico_suma'] += $vartrf['trafico'][$i];
                            $vartrf['trafico_suma_total'][$i] += $vartrf['trafico'][$i];
                            echo '<td>' . $vartrf['trafico'][$i] . '</td>';
                        }
                        ?>  
                        <td><?php echo $vartrf['trafico_suma']; ?><?php // echo '--'.print_r($vartrf['trafico_suma_total']); ?></td>
                    </tr>
                    <tr class="odd">
                        <td>Proforma</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            $vartrf['proforma'][] = $this->getProformaVersion($i, $vartrf['versiones'][$flag], $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable']);
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
                            $vartrf['testdrive'][] = $this->getTestDriveVersion($i, $vartrf['versiones'][$flag], $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable']);
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
                            $vartrf['ventas'][] = $this->getVentasVersion($i, $vartrf['versiones'][$flag], $vartrf['year_actual'], $vartrf['dia_actual'], 1, $vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable']);
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
                    $flag++;
                }
                ?>
                    <?php 
                    ?>
                <tr>
                        <td colspan="<?php echo $vartrf['mes_actual'] + 2; ?>" class="oddtitle-t">TOTAL</td>
                    </tr>  
                    <tr>
                        <td bgcolor="#D9534F" class="tr-f">&nbsp;</td>
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
                        //if($vartrf['categoria'] == 5){// si es categoria todos suma todos los modelos
                        //    for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                        //        $vartrf['trafico_nacional_total'][$i] = $this->getTraficoVersionTotal($i, $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable'],0,5);
                        //        echo '<td>' . $vartrf['trafico_nacional_total'][$i] . '</td>';
                        //        $vartrf['trafico_nacional'] += $vartrf['trafico_nacional_total'][$i];
                        //    }
                        //}else{
                            for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                                echo '<td>' . $vartrf['trafico_suma_total'][$i] . '</td>';
                                $vartrf['trafico_nacional'] += $vartrf['trafico_suma_total'][$i];
                            }
                        //}
                        ?>
                        <td><?php echo $vartrf['trafico_nacional']; ?></td>
                    </tr>
                    <tr class="odd">
                        <td>Proforma</td>
                        <?php
                        //if($vartrf['categoria'] == 5){// si es categoria todos suma todos los modelos
                        //    for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                        //        $vartrf['proforma_nacional_total'][$i] = $this->getProformaVersionTotal($i, $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable'],0,5);
                        //        echo '<td>' . $vartrf['proforma_nacional_total'][$i] . '</td>';
                        //        $vartrf['proforma_nacional'] += $vartrf['proforma_nacional_total'][$i];
                        //    }
                        //}else{
                            for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                                echo '<td>' . $vartrf['proforma_suma_total'][$i] . '</td>';
                                $vartrf['proforma_nacional'] += $vartrf['proforma_suma_total'][$i];
                            }
                        //}
                        
                        ?>
                        <td><?php echo $vartrf['proforma_nacional']; ?></td>
                    </tr>
                    <tr>
                        <td>Test Drive</td>
                        <?php
                        //if($vartrf['categoria'] == 5){// si es categoria todos suma todos los modelos
                        //    for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                        //        $vartrf['testdrive_nacional_total'][$i] = $this->getTestDriveVersionTotal($i, $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable'],0,5); 
                        //        echo '<td>' . $vartrf['testdrive_nacional_total'][$i] . '</td>';
                        //        $vartrf['testdrive_nacional'] += $vartrf['testdrive_nacional_total'][$i];
                        //    }
                        //}else{
                            for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                                echo '<td>' . $vartrf['testdrive_suma_total'][$i] . '</td>';
                                $vartrf['testdrive_nacional'] += $vartrf['testdrive_suma_total'][$i];
                            }
                        //}
                        
                        ?>
                        <td><?php echo $vartrf['testdrive_nacional']; ?></td>
                    </tr>
                    <tr class="odd">
                        <td>Ventas</td>
                        <?php
                        //if($vartrf['categoria'] == 5){// si es categoria todos suma todos los modelos
                        //    for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                        //        $vartrf['venta_nacional_total'][$i] = $this->getVentasVersionTotal($i, $vartrf['year_actual'], $vartrf['dia_actual'], 1, $vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable'],0,5);
                        //        echo '<td>' . $vartrf['venta_nacional_total'][$i] . '</td>';
                        //        $vartrf['ventas_nacional'] += $vartrf['venta_nacional_total'][$i];
                        //    }
                        //}else{
                            for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                                echo '<td>' . $vartrf['venta_suma_total'][$i] . '</td>';
                                $vartrf['ventas_nacional'] += $vartrf['venta_suma_total'][$i];
                            }
                        //}
                        
                        ?>
                        <td><?php echo $vartrf['ventas_nacional']; ?></td>
                    </tr>
                    <tr>
                        <td>Tasa de Test Drive</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td>' . $this->getTasaTD($vartrf['testdrive_nacional_total'][$i], $vartrf['trafico_nacional_total'][$i]) . '</td>';
                        }
                        ?>
                        <td><?php echo $this->getTasaTD($vartrf['testdrive_nacional'], $vartrf['trafico_nacional']); ?></td>
                    </tr>
                    <tr class="odd">
                        <td>Tasa de Cierre</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td>' . $this->getTasaCierre($vartrf['venta_nacional_total'][$i], $vartrf['trafico_nacional_total'][$i]) . '</td>';
                        }
                        ?>
                        <td><?php echo $this->getTasaCierre($vartrf['ventas_nacional'], $vartrf['trafico_nacional']); ?></td>
                    </tr> 
                    <tr><td></td></tr> 
                    <tr bgcolor="#888">
                        <td colspan="<?php echo $vartrf['mes_actual'] + 1; ?>" class="oddtitlet">Ensamblado Nacional (CKD) e internacional (CBU)</td>
                    </tr> 
                    <tr>
                        <td bgcolor="#D9534F" class="tr-f"></td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td bgcolor="#D9534F" class="tr-f dde_' . $i . '_' . $val['id'] . '"  id="dde_' . $i . '_' . $val['id'] . '" data-vec="0">' . $vartrf['fechas'][$i] . '</td>';
                        }
                        ?>
                    </tr>
                    <tr>
                        <td></td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            echo '<td><table class="ens"><tr><td>CKD</td><td>CBU</td></tr></table></td>';
                        }
                        ?>
                    </tr>

                    <tr class="odd">
                        <td>TRÁFICO</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            $traficockd1 = count($this->getTraficoVersionTotal($i, $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable'],1,$vartrf['categoria']));
                            //$traficocbu1 = $vartrf['trafico_nacional_total'][$i] - $traficockd1;
                            //$traficockd1 = $vartrf['trafico_suma_total'][$i];
                            if($traficockd1 == 0){
                                echo '<td><table class="ens"><tr><td>0</td><td>0</td></tr></table></td>';
                            }else{
                                $traficocbu1 = $vartrf['trafico_suma_total'][$i] - $traficockd1;
                                echo '<td><table class="ens"><tr><td>'.$traficockd1.'</td><td>'.$traficocbu1.'</td></tr></table></td>';
                            }
                            
                        }
                        ?>
                        
                    </tr>
                    <tr>
                        <td>PROFORMA</td><?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            $proformackd1 =  $this->getProformaVersionTotal($i, $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable'],1,$vartrf['categoria']);
                            if($proformackd1 == 0){
                                 echo '<td><table class="ens"><tr><td>0</td><td>0</td></tr></table></td>';
                            }else{
                                $proformacbu1 = $vartrf['proforma_suma_total'][$i] - $proformackd1;
                                echo '<td><table class="ens"><tr><td>'.$proformackd1.'</td><td>'.$proformacbu1.'</td></tr></table></td>';
                            }
                            
                        }
                        ?>
                    </tr>
                    <tr class="odd">
                        <td>TESTDRIVE</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {
                            $testdriveckd1 =  $this->getTestDriveVersionTotal($i, $vartrf['year_actual'], $vartrf['dia_actual'], 1,$vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable'],1,$vartrf['categoria']);
                            if($testdriveckd1 == 0){
                                echo '<td><table class="ens"><tr><td>0</td><td>0</td></tr></table></td>';
                            }else{
                                $testdrivecbu1 = $vartrf['testdrive_suma_total'][$i] - $testdriveckd1;
                                echo '<td><table class="ens"><tr><td>'.$testdriveckd1.'</td><td>'.$testdrivecbu1.'</td></tr></table></td>';
                            }
                            
                        }
                        ?>
                    </tr>
                    <tr>
                        <td>VENTAS</td>
                        <?php
                        for ($i = 0; $i < $vartrf['mes_actual']; $i++) {;
                            $ventasckd1 = $this->getVentasVersionTotal($i, $vartrf['year_actual'], $vartrf['dia_actual'], 1, $vartrf['search'],$vartrf['cargo_id'], $vartrf['dealer_id'], $vartrf['id_responsable'],1,$vartrf['categoria']);
                            if($ventasckd1 == 0){
                                echo '<td><table class="ens"><tr><td>0</td><td>0</td></tr></table></td>';
                            }else{
                                $ventascbu1 = $vartrf['venta_suma_total'][$i] - $ventasckd1;
                                echo '<td><table class="ens"><tr><td>'.$ventasckd1.'</td><td>'.$ventascbu1.'</td></tr></table></td>';
                            }
                            
                        }
                        ?>
                    </tr>
            </table>
            
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    setSelectedOption();

    // VACIAR SELECT DE PROVINCIAS
    function vaciarProvincia(){
        data = '<option value="">--Seleccione provincia--</option><option value="1000">Todos</option><option value="1">Azuay</option><option value="5">Chimborazo</option><option value="7">El Oro</option><option value="8">Esmeraldas</option><option value="10">Guayas</option><option value="11">Imbabura</option><option value="12">Loja</option><option value="13">Los Ríos</option><option value="14">Manabí</option><option value="16">Napo</option><option value="18">Pastaza</option><option value="19">Pichincha</option><option value="21">Tsachilas</option><option value="23">Tungurahua</option>';
        $('#trafico_provincia').html(data);$('#provincia').val(0);$('#concesionario').val(0);
        $('#trafico_responsable').html('<option value="">--Seleccione responsable-</option>');$('#responsable').val(0);
    }

    //vaciar selects
    function vaciar(){
        $('#trafico_concesionario').find('option').remove().end().append('<option value="">--Concesionario--</option>').val('');
        $('#trafico_responsable').find('option').remove().end().append('<option value="">--Responsable--</option>').val('');
        //$("#GestionInformacionGrupo option:selected").prop("selected", false);
        //$("#GestionInformacionProvincias option:selected").prop("selected", false);
    }
    function detail(versiones, dia_inicial, dia_actual, year, mes, id, nombre_modelo, i, categoria) {
        var where = '<?php echo $where; ?>';
        //console.log('i: '+i);
        fr = 0;
        var id_modelos = [<?php echo implode(',',  $vartrf['id_modelos'] ); ?>];
        //var id_modelos = [1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17];
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
                mes: mes, nombre_modelo: nombre_modelo, id: id, i:i,where : where, categoria: categoria},
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
                    $('.cer-19').append(data.data_sportage_xline);
                    $('.cer-20').append(data.data_niro_xline);
                    $('.cer-21').append(data.data_sorento);
                    $('#bg_black').hide();
                    var enlace  = $('#cid_'+id);
		    $('html, body').animate({
		        scrollTop: $(enlace).offset().top
		    }, 1000);
                    //$("body").css("overflow", "hidden");
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
        }

    }
    function closemodal(mes, i) {
        //alert('mes: '+mes+',i: '+i);
        var id_modelos = [<?php echo implode(',',  $vartrf['id_modelos'] ); ?>];
        //alert('id modelos: '+id_modelos);
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
    function fnExcelReport(){
        var tab_text = '<table border="1px" style="font-size:13px" ">';
        var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><?xml version="1.0" encoding="UTF-8" standalone="yes"?><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
        var textRange; 
        var j = 0;
        var tab = document.getElementById('main_info_1'); // id of table
        var lines = tab.rows.length;
        //tab_text = tab_text + template;
        // the first headline of the table
        if (lines > 0) {
            tab_text = tab_text + '<tr bgcolor="#DFDFDF">' + tab.rows[0].innerHTML + '</tr>';
        }

        // table data lines, loop starting from 1
        for (j = 1 ; j < lines; j++) {     
            tab_text = tab_text + "<tr>" + tab.rows[j].innerHTML + "</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");             //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi,"");                 // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, "");    // reomves input params
        // console.log(tab_text); // aktivate so see the result (press F12 in browser)

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE "); 

         // if Internet Explorer
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus(); 
            sa = txtArea1.document.execCommand("SaveAs", true, "DataTableExport.xlsx");
        }  
        else // other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

        return (sa);
    } 
    function setSelectedOption(){
        var fecha1 = '<?php echo isset($_GET['GestionDiaria']['fecha1']) ? $_GET['GestionDiaria']['fecha1'] : ''; ?>';
        var fecha2 = '<?php echo isset($_GET['GestionDiaria']['fecha2']) ? $_GET['GestionDiaria']['fecha2'] : ''; ?>';
        var provincia = '<?php echo isset($_GET['GestionDiaria']['provincia']) ? $_GET['GestionDiaria']['provincia'] : ''; ?>';
        var grupo = '<?php echo isset($_GET['GestionDiaria']['grupo']) ? $_GET['GestionDiaria']['grupo'] : ''; ?>';
        var concesionario = '<?php echo isset($_GET['GestionDiaria']['concesionario']) ? $_GET['GestionDiaria']['concesionario'] : ''; ?>';
        var responsable = '<?php echo isset($_GET['GestionDiaria']['responsable']) ? $_GET['GestionDiaria']['responsable'] : ''; ?>';
        var categoria = '<?php echo isset($_GET['GestionDiaria']['categoria']) ? $_GET['GestionDiaria']['categoria'] : ''; ?>';
        if(fecha1 !='' && fecha2 !=''){
            $('#trafico_fecha1').val(fecha1);$('#trafico_fecha2').val(fecha2);
            $('#fecha1').val(1);$('#fecha2').val(1);
        }
        if(provincia != ''){
            $("#trafico_provincia option").each(function(){
                if($(this).val() ==  provincia){$(this).prop("selected", true);}
            });
            $('#provincia').val(1);
        }
        if(grupo != ''){
            $("#trafico_grupo option").each(function(){
                //console.log('value: -----' + $(this).val());
                if($(this).val() ==  grupo){$(this).prop("selected", true);}
            });
            $('#grupo').val(1);
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("trafico/getConcesionariosGrupo"); ?>',
                beforeSend: function (xhr) {
                    //$('#bg_black').show();  // #info must be defined somehwere
                },
                type: 'POST',dataType: 'json',data: {dealer_id : concesionario, grupo_id : grupo},
                success: function (data) {
                    //console.log(data.options);
                    $('#trafico_concesionario').html(data.options);
                }
            });

        }
        if(concesionario != ''){
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("trafico/getConcesionariosGrupo"); ?>',
                beforeSend: function (xhr) {
                    //$('#bg_black').show();  // #info must be defined somehwere
                },
                type: 'POST',dataType: 'json',data: {dealer_id : concesionario, grupo_id : grupo},
                success: function (data) {
                    //console.log(data.options);
                    $('#trafico_concesionario').html(data.options);
                }
            });
            $('#concesionario').val(1);
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("trafico/getResponsablesConcecionario"); ?>',
                beforeSend: function (xhr) {
                    //$('#bg_black').show();  // #info must be defined somehwere
                },
                type: 'POST',dataType: 'json',data: {dealer_id : concesionario, responsable : responsable},
                success: function (data) {
                    //console.log(data.options);
                    $('#trafico_responsable').html(data.options);
                }
            });
        }
        if(responsable != ''){
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("trafico/getResponsablesConcecionario"); ?>',
                beforeSend: function (xhr) {
                    //$('#bg_black').show();  // #info must be defined somehwere
                },
                type: 'POST',dataType: 'json',data: {dealer_id : concesionario, responsable : responsable},
                success: function (data) {
                    //console.log(data.options);
                    $('#trafico_responsable').html(data.options);
                }
            });
            $('#responsable').val(1);
        }
        if(categoria != ''){
            $("#trafico_categoria option").each(function(){
                if($(this).val() ==  categoria){$(this).prop("selected", true);}
            }); 
        }
    }  
</script>


