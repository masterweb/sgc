<?php
/* @var $this GestionTestDriveController */
/* @var $model GestionTestDrive */

$this->breadcrumbs=array(
	'Gestion Test Drives'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionTestDrive', 'url'=>array('index')),
	array('label'=>'Create GestionTestDrive', 'url'=>array('create')),
	array('label'=>'Update GestionTestDrive', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionTestDrive', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionTestDrive', 'url'=>array('admin')),
);
?>

<h1>View GestionTestDrive #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_informacion',
		'id_vehiculo',
		'test_drive',
		'fecha',
	),
)); ?>
