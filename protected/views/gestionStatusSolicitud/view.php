<?php
/* @var $this GestionStatusSolicitudController */
/* @var $model GestionStatusSolicitud */

$this->breadcrumbs=array(
	'Gestion Status Solicituds'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionStatusSolicitud', 'url'=>array('index')),
	array('label'=>'Create GestionStatusSolicitud', 'url'=>array('create')),
	array('label'=>'Update GestionStatusSolicitud', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionStatusSolicitud', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionStatusSolicitud', 'url'=>array('admin')),
);
?>

<h1>View GestionStatusSolicitud #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_informacion',
		'id_vehiculo',
		'status',
		'fecha',
	),
)); ?>
