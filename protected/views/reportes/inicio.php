<?= $this->renderPartial('//reportes/modulos/header', array('title' => 'Reportes'));?>
<div class="row">
    <div class="col-md-12">
        <button class="trigerFiltros btn btn-warning abrirFiltros"><b>Buscar por filtros</b></button>
        <button class="btn btn-warning" onclick="window.history.back()"><< Regresar</button>
        <div class="resultados_embudo bg-danger"></div>
        <div class="highlight filtrosReportes">
            <?= $this->renderPartial('//reportes/modulos/filtros', array('varView' => $varView));?>
        </div>        
    </div>
</div>
<br />

<div id="tabs_repo">
    <ul class="nav nav-tabs tabs_triger">
        <li role="presentation" class="tit_repo active" triger="tab1"><a href="#">Embudo</a></li>
        <li role="presentation" class="tit_repo" triger="tab2"><a href="#">Encuestas</a></li>
        <li role="presentation" class="tit_repo" triger="tab3"><a href="#">Score Card</a></li>
        <li role="presentation" class="tit_repo" triger="tab4"><a href="#">Análisis de Ventas</a></li>
        <li role="presentation" class="tit_repo" triger="tab5"><a href="#">Noticias Kia</a></li>
    </ul>
    <div class="tab_repo active" id="tab1">        
        <!-- EMBUDO -->
        <div class="cont_repo">
            <?= $this->renderPartial('//reportes/modulos/embudo', array('varView' => $varView));?>
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