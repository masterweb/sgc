<?php
/* @var $this DealersController */
/* @var $model Dealers */

$this->breadcrumbs=array(
	'Dealers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Dealers', 'url'=>array('index')),
	array('label'=>'Manage Dealers', 'url'=>array('admin')),
);
?>

<h1>Create Dealers</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>