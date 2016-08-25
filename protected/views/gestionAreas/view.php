<?php
/* @var $this GestionAreasController */
/* @var $model GestionAreas */

$this->breadcrumbs=array(
	'Gestion Areases'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionAreas', 'url'=>array('index')),
	array('label'=>'Create GestionAreas', 'url'=>array('create')),
	array('label'=>'Update GestionAreas', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionAreas', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionAreas', 'url'=>array('admin')),
);
?>

<h1>View GestionAreas #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'area',
		'concesionario',
	),
)); ?>
