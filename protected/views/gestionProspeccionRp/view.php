<?php
/* @var $this GestionProspeccionRpController */
/* @var $model GestionProspeccionRp */

$this->breadcrumbs=array(
	'Gestion Prospeccion Rps'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionProspeccionRp', 'url'=>array('index')),
	array('label'=>'Create GestionProspeccionRp', 'url'=>array('create')),
	array('label'=>'Update GestionProspeccionRp', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionProspeccionRp', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionProspeccionRp', 'url'=>array('admin')),
);
?>

<h1>View GestionProspeccionRp #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'preg1',
		'preg2',
		'preg3',
		'preg4',
		'preg5',
		'preg6',
		'preg3_sec1',
		'preg3_sec2',
		'preg3_sec3',
		'preg3_sec4',
		'preg4_sec1',
		'preg4_sec2',
		'preg4_sec3',
		'preg4_sec4',
		'preg5_sec1',
		'preg5_sec2',
		'preg5_sec3',
		'fecha',
	),
)); ?>
