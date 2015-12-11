<?php
/* @var $this GestionSeguimientoController */
/* @var $model GestionSeguimiento */

$this->breadcrumbs=array(
	'Gestion Seguimientos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionSeguimiento', 'url'=>array('index')),
	array('label'=>'Create GestionSeguimiento', 'url'=>array('create')),
	array('label'=>'Update GestionSeguimiento', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionSeguimiento', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionSeguimiento', 'url'=>array('admin')),
);
?>

<h1>View GestionSeguimiento #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'concesionario',
		'asesor',
		'fecha',
	),
)); ?>
