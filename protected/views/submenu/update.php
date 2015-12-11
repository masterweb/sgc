<?php
/* @var $this SubmenuController */
/* @var $model Submenu */

$this->breadcrumbs=array(
	'Submenus'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Submenu', 'url'=>array('index')),
	array('label'=>'Create Submenu', 'url'=>array('create')),
	array('label'=>'View Submenu', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Submenu', 'url'=>array('admin')),
);
?>

<h1>Update Submenu <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>