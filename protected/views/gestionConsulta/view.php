<?php
/* @var $this GestionConsultaController */
/* @var $model GestionConsulta */

$this->breadcrumbs=array(
	'Gestion Consultas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionConsulta', 'url'=>array('index')),
	array('label'=>'Create GestionConsulta', 'url'=>array('create')),
	array('label'=>'Update GestionConsulta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionConsulta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionConsulta', 'url'=>array('admin')),
);
?>

<h1>View GestionConsulta #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'preg1_sec1',
		'preg1_sec2',
		'preg1_sec3',
		'preg1_sec4',
		'preg1_sec5',
		'preg2',
		'preg2_sec1',
		'preg3',
		'preg3_sec1',
		'preg3_sec2',
		'preg3_sec3',
		'preg3_sec4',
		'preg4',
		'preg5',
		'preg6',
		'fecha',
	),
)); ?>
