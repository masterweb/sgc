<?php
/* @var $this GestionPresentacionController */
/* @var $model GestionPresentacion */

$this->breadcrumbs=array(
	'Gestion Presentacions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionPresentacion', 'url'=>array('index')),
	array('label'=>'Create GestionPresentacion', 'url'=>array('create')),
	array('label'=>'Update GestionPresentacion', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionPresentacion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionPresentacion', 'url'=>array('admin')),
);
?>

<h1>View GestionPresentacion #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'preg1_duda',
		'preg2_necesidades',
		'preg3_satisfecho',
		'preg1_sec1_duda',
		'preg2_sec1_necesidades',
		'fecha',
	),
)); ?>
