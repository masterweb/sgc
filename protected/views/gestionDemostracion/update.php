<?php
/* @var $this GestionDemostracionController */
/* @var $model GestionDemostracion */

$this->breadcrumbs=array(
	'Gestion Demostracions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionDemostracion', 'url'=>array('index')),
	array('label'=>'Create GestionDemostracion', 'url'=>array('create')),
	array('label'=>'View GestionDemostracion', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionDemostracion', 'url'=>array('admin')),
);
?>

<h1>Update GestionDemostracion <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>