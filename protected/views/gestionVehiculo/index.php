<?php
/* @var $this GestionVehiculoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Gestion Vehiculos',
);

$this->menu = array(
    array('label' => 'Create GestionVehiculo', 'url' => array('create')),
    array('label' => 'Manage GestionVehiculo', 'url' => array('admin')),
);
?>

<h1>Gestion Vehiculos</h1>

<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view'
));
?>
