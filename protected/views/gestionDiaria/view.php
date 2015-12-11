<?php
/* @var $this GestionDiariaController */
/* @var $model GestionDiaria */

$this->breadcrumbs=array(
	'Gestion Diarias'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionDiaria', 'url'=>array('index')),
	array('label'=>'Create GestionDiaria', 'url'=>array('create')),
	array('label'=>'Update GestionDiaria', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionDiaria', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionDiaria', 'url'=>array('admin')),
);
?>

<h1>View GestionDiaria #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_informacion',
		'status',
		'obervaciones',
		'medio_contacto',
		'fuente_contacto',
		'codigo_vehiculo',
		'proximo_seguimiento',
		'categoria',
		'test_drive',
		'exonerado',
		'venta_concretada',
		'forma_pago',
		'chasis',
		'fecha_venta',
		'fecha',
	),
)); ?>
