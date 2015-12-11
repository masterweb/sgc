<?php
/* @var $this GestionStockController */
/* @var $model GestionStock */

$this->breadcrumbs=array(
	'Gestion Stocks'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionStock', 'url'=>array('index')),
	array('label'=>'Create GestionStock', 'url'=>array('create')),
	array('label'=>'Update GestionStock', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionStock', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionStock', 'url'=>array('admin')),
);
?>

<h1>View GestionStock #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'fecha_w',
		'embarque',
		'bloque',
		'familia',
		'code',
		'version',
		'equip',
		'fsc',
		'referencia',
		'aeade',
		'segmento',
		'grupo',
		'concesionario',
		'color_origen',
		'color_plano',
		'my',
		'chasis',
		'edad',
		'rango',
		'fact',
		'cod_aeade',
		'pvc',
		'stock',
	),
)); ?>
