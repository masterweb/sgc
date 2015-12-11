<?php
/* @var $this GestionSolicitudController */
/* @var $model GestionSolicitud */

$this->breadcrumbs=array(
	'Gestion Solicituds'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionSolicitud', 'url'=>array('index')),
	array('label'=>'Create GestionSolicitud', 'url'=>array('create')),
	array('label'=>'Update GestionSolicitud', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionSolicitud', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionSolicitud', 'url'=>array('admin')),
);
?>

<h1>View GestionSolicitud #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_concesionario',
		'id_vehiculo',
		'fecha',
	),
)); ?>
