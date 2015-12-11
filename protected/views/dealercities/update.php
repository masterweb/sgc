<?php
/* @var $this DealercitiesController */
/* @var $model Dealercities */

$this->breadcrumbs=array(
	'Dealercities'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Dealercities', 'url'=>array('index')),
	array('label'=>'Create Dealercities', 'url'=>array('create')),
	array('label'=>'View Dealercities', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Dealercities', 'url'=>array('admin')),
);
?>

<h1>Update Dealercities <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>