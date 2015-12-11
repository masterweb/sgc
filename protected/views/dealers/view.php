<?php
/* @var $this DealersController */
/* @var $model Dealers */

$this->breadcrumbs=array(
	'Dealers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Dealers', 'url'=>array('index')),
	array('label'=>'Create Dealers', 'url'=>array('create')),
	array('label'=>'Update Dealers', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Dealers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Dealers', 'url'=>array('admin')),
);
?>

<h1>View Dealers #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'cityid',
		'name',
		'email',
		'statusid',
	),
)); ?>
