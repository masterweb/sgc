<?php
/* @var $this GestionNuevaCotizacionController */
/* @var $model GestionNuevaCotizacion */

$this->breadcrumbs=array(
	'Gestion Nueva Cotizacions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionNuevaCotizacion', 'url'=>array('index')),
	array('label'=>'Create GestionNuevaCotizacion', 'url'=>array('create')),
	array('label'=>'Update GestionNuevaCotizacion', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionNuevaCotizacion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionNuevaCotizacion', 'url'=>array('admin')),
);
?>

<h1>View GestionNuevaCotizacion #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'fuente',
		'cedula',
		'fecha',
	),
)); ?>
