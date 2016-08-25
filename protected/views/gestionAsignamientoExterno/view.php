<?php
/* @var $this GestionAsignamientoExternoController */
/* @var $model GestionAsignamientoExterno */

$this->breadcrumbs=array(
	'Gestion Asignamiento Externos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionAsignamientoExterno', 'url'=>array('index')),
	array('label'=>'Create GestionAsignamientoExterno', 'url'=>array('create')),
	array('label'=>'Update GestionAsignamientoExterno', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionAsignamientoExterno', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionAsignamientoExterno', 'url'=>array('admin')),
);
?>

<h1>View GestionAsignamientoExterno #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_area',
		'id_usuario',
		'grupo',
		'fecha',
	),
)); ?>
