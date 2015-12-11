<?php

/* @var $this GestionVehiculoController */
/* @var $model GestionVehiculo */

$this->breadcrumbs = array(
    'Gestion Vehiculos' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List GestionVehiculo', 'url' => array('index')),
    array('label' => 'Manage GestionVehiculo', 'url' => array('admin')),
);
?>
<?php echo $this->renderPartial('_form', array('model' => $model, 'id' => $id)); ?>
