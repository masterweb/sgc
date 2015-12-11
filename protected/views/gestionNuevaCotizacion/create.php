<?php
/* @var $this GestionNuevaCotizacionController */
/* @var $model GestionNuevaCotizacion */

$this->breadcrumbs = array(
    'Gestion Nueva Cotizacions' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List GestionNuevaCotizacion', 'url' => array('index')),
    array('label' => 'Manage GestionNuevaCotizacion', 'url' => array('admin')),
);
?>
<div class="container">
    <h2>Nueva Cotización</h2>

    <?php echo $this->renderPartial('_form', array('model' => $model)); ?>
</div>