<?php
/* @var $this GestionAmortizacionController */
/* @var $model GestionAmortizacion */

$this->breadcrumbs=array(
	'Gestion Amortizacions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionAmortizacion', 'url'=>array('index')),
	array('label'=>'Create GestionAmortizacion', 'url'=>array('create')),
	array('label'=>'Update GestionAmortizacion', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionAmortizacion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionAmortizacion', 'url'=>array('admin')),
);
?>

<h1>View GestionAmortizacion #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'interes',
		'capital_reducido',
		'capital',
		'seguro_desgravamen',
	),
)); ?>
