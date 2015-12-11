<?php
/* @var $this DealercitiesController */
/* @var $model Dealercities */

$this->breadcrumbs=array(
	'Dealercities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Dealercities', 'url'=>array('index')),
	array('label'=>'Manage Dealercities', 'url'=>array('admin')),
);
?>

<h1>Create Dealercities</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>