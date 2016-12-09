<?= $this->renderPartial('//layouts/rgd/head'); ?>
<script type="text/javascript">
$(function () {
    $('#fecha-range').daterangepicker(
            {
                locale: {
                format: 'YYYY/MM/DD'
                }
            }
        );
});
</script>
<div class="form" >
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'filtros-form',
        'method' => 'get',
        'action' => Yii::app()->createUrl('fabricadeCredito/GridViewCreditoFiltro'),
        'enableAjaxValidation' => true,
        'htmlOptions' => array(
            'class' => 'form-horizontal form-search'
        ),
    ));
    ?>
    <!--<h4>Búsqueda:</h4>-->
    <div class="row">

        <div class="col-md-2">
            <label for="FabricadeCreditofecha">Fecha Ingreso</label>
            <input type="text" name="FabricadeCredito_fecha" id="fecha-range" class="form-control"/>
        </div>

        <div class="col-md-2">
            <label for="FabricadeCreditostatus">Status</label>
            <select type="text" name="FabricadeCredito_status" class="form-control" id="fabrica_credito_status">
                <option value="0">--Seleccione status--</option>
                <option value="1">Solicitud en movimiento</option>
                <option value="2">Solicitud Aprobada</option>
                <option value="3">Negada</option>
                <option value="4">Solicitud Condicionada</option>
            </select>
        </div>

        <div class="col-md-2">
            <label for="FabricadeCreditoconcesionario">Grupo de Concesionario</label>
            <select type="text" name="FabricadeCredito_gconcesionario" class="form-control" id="fabrica_credito_gconcesionario">
                <option value="0">--Seleccione Grupo--</option>
                <option value="PrimeraVisita">Primera Visita</option>
                <option value="Cierre">Cierre</option>
                <option value="Entrega">Entrega</option>
                <option value="Vendido">Seguimiento-Paso 10</option>
                <option value="Desiste">Desiste</option>
                <?php if($cargo_id == 85 || $cargo_id == 86 || $cargo_adicional == 86 || $cargo_adicional == 85): ?>
                <option value="Web">Web</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label for="FabricadeCreditoconcesionario">Concesionario</label>
            <select type="text" name="FabricadeCredito_concesionario" class="form-control" id="fabrica_credito_concesionario">
                <option value="0">--Seleccione Concesionario--</option>
                <option value="PrimeraVisita">Primera Visita</option>
                <option value="Cierre">Cierre</option>
                <option value="Entrega">Entrega</option>
                <option value="Vendido">Seguimiento-Paso 10</option>
                <option value="Desiste">Desiste</option>
                <?php if($cargo_id == 85 || $cargo_id == 86 || $cargo_adicional == 86 || $cargo_adicional == 85): ?>
                <option value="Web">Web</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label for="">Asesor de credito</label>
            <select name="FabricadeCredito_asesor" id="fabrica_credito_asesor" class="form-control">
                <option value="0">--Seleccione Asesor--</option>
                <option value="1">Hoy</option>
                <option value="2">Vacío</option>
                <option value="3">Rango de Fecha</option>
            </select>
        </div>

        <div class="col-md-2" style="margin-top: 15px">
            <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
        </div>
    </div>    
    <?php $this->endWidget(); ?>
</div>
