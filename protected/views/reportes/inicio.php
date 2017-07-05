<?= $this->renderPartial('//reportes/modulos/header', array('title' => 'Reportes'));?>
<?php 
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
$grupo_id = (int) Yii::app()->user->getState('grupo_id');
$tipo_grupo = 0; // GRUPO KMOTOR Y ASIAUTO
$area = 0; 
// GRUPO AEKIA Y KMOTOR
if($grupo_id == 1 || $grupo_id == 3){
    $tipo_grupo = 1;
}
$area_id = (int) Yii::app()->user->getState('area_id');
if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) { // AEKIA USERS
    $area = 1;
}
//echo 'area id: '.$area;
//echo 'tipo grupo: '.$tipo_grupo;
//echo '<pre>';
//print_r($varView['fecha_inicial_actual']);
//echo '</pre>';
//echo '<pre>';
//print_r($varView['flag_search']);
//echo '</pre>';
//echo 'flag_search: '.$varView['flag_search']
//die();
?>
<div id="tabs_repo">
<!--    <ul class="nav nav-tabs tabs_triger">
        <li role="presentation" class="tit_repo active" triger="tab1"><a href="#">Embudo</a></li>
        <li role="presentation" class="tit_repo" triger="tab2"><a href="#">Encuestas</a></li>
        <li role="presentation" class="tit_repo" triger="tab3"><a href="#">Score Card</a></li>
        <li role="presentation" class="tit_repo" triger="tab4"><a href="#">Análisis de Ventas</a></li>
        <li role="presentation" class="tit_repo" triger="tab5"><a href="#">Noticias Kia</a></li>
    </ul>-->
    <div class="tab_repo active" id="tab1">        
        <!-- EMBUDO -->
        <br /><br />
        <divv class="cont_repo">
            <div class="row">
                <div class="col-md-12">
                    <button class="trigerFiltros btn btn-warning abrirFiltros"><b>Buscar por filtros</b></button>
                    <!--<button class="btn btn-warning" onclick="window.history.back()"><< Regresar</button>-->
                    <?php
//                    switch ($tipo) {
//                        case 'exhibicion':
//                            echo '<a class="btn btn-warning" href="'.Yii::app()->createUrl('Reportes/inicio').'">Tráfico</a>';
//                            if(($tipo_grupo == 1)  || $area == 1){
//                                if(($tipo_grupo == 1 && ($cargo_id == 85 || $cargo_id == 86)) || ($tipo_grupo == 0 && (($cargo_id == 70 || $cargo_id == 71) && ($cargo_adicional == 85 || $cargo_adicional == 86))) || $area == 1 ){
//                                    echo '<a class="btn btn-warning" href="'.Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'externas')).'">Ventas Web</a>';
//                                } 
//                            }
//                            if(($tipo_grupo == 1 || $tipo_grupo == 0) && ($cargo_id == 85 || $cargo_id == 86 || $cargo_adicional == 85)){ 
//                                echo '<a class="btn btn-warning" href="'.Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'prospeccionweb')).'">Prospección Web</a>';
//                            }
//                            break;
//                        case 'externas':
//                            if(($tipo_grupo == 1 || $tipo_grupo == 0) && ($cargo_id == 85 || $cargo_id == 86 || $cargo_adicional == 85)){ 
//                                echo '<a class="btn btn-warning" href="'.Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'prospeccionweb')).'">Prospección Web</a>';
//                            }
//                            break;
//                        case 'prospeccionweb':
//                            if(($tipo_grupo == 1)  || $area == 1){
//                                if(($tipo_grupo == 1 && ($cargo_id == 85 || $cargo_id == 86)) || ($tipo_grupo == 0 && (($cargo_id == 70 || $cargo_id == 71) && ($cargo_adicional == 85 || $cargo_adicional == 86))) || $area == 1 ){
//                                    echo '<a class="btn btn-warning" href="'.Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'externas')).'">Ventas Web</a>';
//                                } 
//                            }
//                            break;
//
//                        default:
//                            if($tipo == '' && $tipo != 'externas') { 
//                                echo '<a class="btn btn-warning" href="'.Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'exhibicion')).'">Exhibición</a>';
//                            }
//                            if(($tipo != 'externas') && ($tipo_grupo == 1)  || $area == 1){
//                                if(($tipo_grupo == 1 && ($cargo_id == 85 || $cargo_id == 86 || $cargo_id == 69)) || ($tipo_grupo == 0 && (($cargo_id == 70 || $cargo_id == 71) && ($cargo_adicional == 85 || $cargo_adicional == 86))) || $area == 1 ){
//                                    echo '<a class="btn btn-warning" href="'.Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'externas')).'">Ventas Web</a>';
//                                } 
//                            }
//                            if(($tipo != 'prospeccionweb') &&($tipo_grupo == 1) && ($cargo_id == 85 || $cargo_id == 86 || $area == 1 || $cargo_id == 69)){ 
//                                echo '<a class="btn btn-warning" href="'.Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'prospeccionweb')).'">Prospección Web</a>';
//                            }
//                            break;
//                    }
                    ?>
                    <?php if($tipo == 'exhibicion'){ ?>
                        <a class="btn btn-warning" href="<?php echo Yii::app()->createUrl('Reportes/inicio'); ?>">Tráfico</a>
                        <?php if(($tipo_grupo == 1 && ($cargo_id == 85 || $cargo_id == 86 || $cargo_id == 69)) || ($tipo_grupo == 0 && (($cargo_id == 70 || $cargo_id == 71) && ($cargo_adicional == 85 || $cargo_adicional == 86))) || $area == 1 ){ ?>
                            <a class="btn btn-warning" href="<?php echo Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'externas')); ?>">Ventas Web</a>
                        <?php } ?>
                    <?php } ?>
                    <?php if($tipo == '' && $tipo != 'externas') { ?>
                        <a class="btn btn-warning" href="<?php echo Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'exhibicion')); ?>">Exhibición</a>
                        <?php if(($tipo_grupo == 1 && ($cargo_id == 85 || $cargo_id == 86 || $cargo_id == 69)) || ($tipo_grupo == 0 && (($cargo_id == 70 || $cargo_id == 71) && ($cargo_adicional == 85 || $cargo_adicional == 86))) || $area == 1 ){ ?>
                            <a class="btn btn-warning" href="<?php echo Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'externas')); ?>">Ventas Web</a>
                        <?php } ?>
                    <?php } ?>
                    <?php if($tipo == 'externas'){ ?>
                        <?php if(($cargo_id == 70 || $cargo_id == 71 || $cargo_id == 69 || $cargo_id == 61) && $tipo_grupo == 1) { ?>
                        <a class="btn btn-warning" href="<?php echo Yii::app()->createUrl('Reportes/inicio'); ?>">Tráfico</a>
                        <a class="btn btn-warning" href="<?php echo Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'exhibicion')); ?>">Exhibición</a>
                        <?php } ?>
                    <?php } ?>
                    <?php if(($tipo != 'externas') && ($tipo_grupo == 1)  || $area == 1){
                    if(($tipo_grupo == 1 && ($cargo_id == 85 || $cargo_id == 86)) || ($tipo_grupo == 0 && (($cargo_id == 70 || $cargo_id == 71) && ($cargo_adicional == 85 || $cargo_adicional == 86))) || $area == 1 ){ ?>
                    <!--<a class="btn btn-warning" href="<?php echo Yii::app()->createUrl('Reportes/inicio',array('tipo' => 'externas')); ?>">Ventas Web</a>-->
                    <?php } 
                    }?>
                    <!-- ACCESO PARA REPORTE DIARIO DE TODOS LOS USUARIOS -->
                    <a class="btn btn-warning" href="<?php echo Yii::app()->createUrl('trafico/inicio'); ?>">Reporte Diario</a>
                    <form id="excel_form" method="post" class="pull-right">
                        <input type="submit" name="" value="Exportar a Excel" class="btn btn-warning" id="get_excel"/>
                    </form>
                    <div class="resultados_embudo bg-danger"></div>
                    <div class="highlight filtrosReportes">
                        <?= $this->renderPartial('//reportes/modulos/filtros', array('varView' => $varView,'tipo' => $tipo,'tipo_grupo' => $tipo_grupo));?>
                    </div>        
                </div>  
            </div>
            <br />
            <?= $this->renderPartial('//reportes/modulos/embudo', array('varView' => $varView,'tipo' => $tipo));?>
        </div>
        <!-- FIN EMBUDO -->
    </div>
    <!--<div class="tab_repo" id="tab2">        
        <div class="cont_repo">
            <h3>Encuestas</h3>
        </div>
    </div>
    <div class="tab_repo" id="tab3">        
        <div class="cont_repo">
            <h3>Score Card</h3>
        </div>
    </div>
    <div class="tab_repo" id="tab4">        
        <div class="cont_repo">
            <h3>Análisis de Ventas</h3>
        </div>
    </div>
    <div class="tab_repo" id="tab5">        
        <div class="cont_repo">
            <h3>Noticias Kia</h3>
        </div>
    </div>-->
</div>
<?= $this->renderPartial('//reportes/modulos/footer', array('varView' => $varView)); ?>