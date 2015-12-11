<?php
/* @var $this DealercitiesController */
/* @var $model Dealercities */

$this->breadcrumbs=array(
	'Dealercities'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Dealercities', 'url'=>array('index')),
	array('label'=>'Create Dealercities', 'url'=>array('create')),
	array('label'=>'Update Dealercities', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Dealercities', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Dealercities', 'url'=>array('admin')),
);
?>

<h1>View Dealercities #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'id_provincia',
	),
)); ?>
