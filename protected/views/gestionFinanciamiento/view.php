<?php
/* @var $this GestionFinanciamientoController */
/* @var $model GestionFinanciamiento */

$this->breadcrumbs=array(
	'Gestion Financiamientos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionFinanciamiento', 'url'=>array('index')),
	array('label'=>'Create GestionFinanciamiento', 'url'=>array('create')),
	array('label'=>'Update GestionFinanciamiento', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionFinanciamiento', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionFinanciamiento', 'url'=>array('admin')),
);
?>

<h1>View GestionFinanciamiento #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_informacion',
		'cuota_inicial',
		'saldo_financiar',
		'tarjeta_credito',
		'otro',
		'plazos',
		'cuota_mensual',
		'avaluo',
		'observaciones',
		'fecha',
	),
)); ?>
