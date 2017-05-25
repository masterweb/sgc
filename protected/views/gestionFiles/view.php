<?php
/* @var $this GestionFilesController */
/* @var $model GestionFiles */

$this->breadcrumbs=array(
	'Gestion Files'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionFiles', 'url'=>array('index')),
	array('label'=>'Create GestionFiles', 'url'=>array('create')),
	array('label'=>'Update GestionFiles', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionFiles', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionFiles', 'url'=>array('admin')),
);
?>

<h1>View GestionFiles #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'descripcion',
		'tipo',
		'provincia',
		'modelo',
		'fecha_actualizacion',
		'fecha',
	),
)); ?>
