<?= $this->renderPartial('//reportes/modulos/header', array('title' => 'Reportes'));?>
<?php 
//echo 'tipo: '.$tipo;
?>
<div id="tabs_repo">
    <ul class="nav nav-tabs tabs_triger">
        <li role="presentation" class="tit_repo active" triger="tab1"><a href="#">Embudo</a></li>
    </ul>
    <div class="tab_repo active" id="tab1">        
        <!-- EMBUDO -->
        <br /><br />
        <div class="cont_repo">
            <div class="row">
                <div class="col-md-12">
                    <button class="trigerFiltros btn btn-warning abrirFiltros"><b>Buscar por filtros</b></button>
                    <button class="btn btn-warning" onclick="window.history.back()"><< Regresar</button>
                    <?php if($tipo == 'exhibicion'){ ?>
                    <a class="btn btn-warning" href="<?php echo Yii::app()->createUrl('Reportes/bdc'); ?>">Tráfico</a>
                    <?php } else { ?>
                    <a class="btn btn-warning" href="<?php echo Yii::app()->createUrl('Reportes/bdc',array('tipo' => 'exhibicion')); ?>">Exhibición</a>
                    <?php } ?>
                    <form id="excel_form" method="post" class="pull-right">
                        <input type="submit" name="" value="Exportar a Excel" class="btn btn-warning" id="get_excel"/>
                    </form>
                    <div class="resultados_embudo bg-danger"></div>
                    <div class="highlight filtrosReportes">
                        <?= $this->renderPartial('//reportes/modulos/filtros', array('varView' => $varView,'tipo' => $tipo));?>
                    </div>        
                </div>  
            </div>
            <br />
            <?= $this->renderPartial('//reportes/modulos/embudobdc', array('varView' => $varView,'tipo' => $tipo));?>
        </div>
        <!-- FIN EMBUDO -->
    </div>
    <div class="tab_repo" id="tab2">        
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
    </div>
</div>
<?= $this->renderPartial('//reportes/modulos/footer', array('varView' => $varView)); ?>