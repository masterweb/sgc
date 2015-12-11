<?php
/* @var $this GestionAgendamientoController */
/* @var $model GestionAgendamiento */

$this->breadcrumbs=array(
	'Gestion Agendamientos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionAgendamiento', 'url'=>array('index')),
	array('label'=>'Create GestionAgendamiento', 'url'=>array('create')),
	array('label'=>'Update GestionAgendamiento', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionAgendamiento', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionAgendamiento', 'url'=>array('admin')),
);
?>

<h1>View GestionAgendamiento #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_informacion',
		'paso',
		'agendamiento',
		'observaciones',
		'fecha',
	),
)); ?>
