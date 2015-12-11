<?php
/* @var $this GestionTestDriveController */
/* @var $model GestionTestDrive */

$this->breadcrumbs=array(
	'Gestion Test Drives'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionTestDrive', 'url'=>array('index')),
	array('label'=>'Manage GestionTestDrive', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'id_informacion' => $id_informacion,'id' => $id)); ?>