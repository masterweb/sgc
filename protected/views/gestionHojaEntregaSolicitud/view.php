<?php
/* @var $this GestionHojaEntregaSolicitudController */
/* @var $model GestionHojaEntregaSolicitud */

$this->breadcrumbs=array(
	'Gestion Hoja Entrega Solicituds'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionHojaEntregaSolicitud', 'url'=>array('index')),
	array('label'=>'Create GestionHojaEntregaSolicitud', 'url'=>array('create')),
	array('label'=>'Update GestionHojaEntregaSolicitud', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionHojaEntregaSolicitud', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionHojaEntregaSolicitud', 'url'=>array('admin')),
);
?>

<h1>View GestionHojaEntregaSolicitud #<?php echo $model->id; ?></h1>

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
