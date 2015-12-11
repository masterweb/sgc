<?php
/* @var $this DealersController */
/* @var $model Dealers */

$this->breadcrumbs=array(
	'Dealers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Dealers', 'url'=>array('index')),
	array('label'=>'Create Dealers', 'url'=>array('create')),
	array('label'=>'View Dealers', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Dealers', 'url'=>array('admin')),
);
?>

<h1>Update Dealers <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>