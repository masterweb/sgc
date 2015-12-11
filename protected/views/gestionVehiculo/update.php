<?php
/* @var $this GestionVehiculoController */
/* @var $model GestionVehiculo */

$this->breadcrumbs=array(
	'Gestion Vehiculos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionVehiculo', 'url'=>array('index')),
	array('label'=>'Create GestionVehiculo', 'url'=>array('create')),
	array('label'=>'View GestionVehiculo', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionVehiculo', 'url'=>array('admin')),
);
?>

<h3 class="tl_seccion">Actualizar Vehículo <?php echo $this->getModel($model->modelo); ?></h3>
<div class="container">
    <?php echo $this->renderPartial('_form2', array('model'=>$model,'id_vehiculo' => $id_vehiculo, 'id_informacion' => $id_informacion)); ?>
</div>
