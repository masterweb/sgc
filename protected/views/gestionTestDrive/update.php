<?php
/* @var $this GestionTestDriveController */
/* @var $model GestionTestDrive */

$this->breadcrumbs=array(
	'Gestion Test Drives'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionTestDrive', 'url'=>array('index')),
	array('label'=>'Create GestionTestDrive', 'url'=>array('create')),
	array('label'=>'View GestionTestDrive', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionTestDrive', 'url'=>array('admin')),
);
?>

<h1>Update GestionTestDrive <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>