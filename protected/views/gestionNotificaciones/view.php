<?php
/* @var $this GestionNotificacionesController */
/* @var $model GestionNotificaciones */

$this->breadcrumbs=array(
	'Gestion Notificaciones'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionNotificaciones', 'url'=>array('index')),
	array('label'=>'Create GestionNotificaciones', 'url'=>array('create')),
	array('label'=>'Update GestionNotificaciones', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionNotificaciones', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionNotificaciones', 'url'=>array('admin')),
);
?>

<h1>View GestionNotificaciones #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_informacion',
		'id_vehiculo',
		'id_dealer',
		'tipo',
		'paso',
		'descripcion',
		'leido',
		'fecha',
	),
)); ?>
