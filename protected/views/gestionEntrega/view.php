<?php
/* @var $this GestionEntregaController */
/* @var $model GestionEntrega */

$this->breadcrumbs=array(
	'Gestion Entregas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionEntrega', 'url'=>array('index')),
	array('label'=>'Create GestionEntrega', 'url'=>array('create')),
	array('label'=>'Update GestionEntrega', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionEntrega', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionEntrega', 'url'=>array('admin')),
);
?>

<h1>View GestionEntrega #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'agendamiento1',
		'agendamiento2',
		'id_vehiculo',
		'id_informacion',
		'fecha',
	),
)); ?>
