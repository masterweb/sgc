<?php
/* @var $this GestionDemostracionController */
/* @var $model GestionDemostracion */

$this->breadcrumbs=array(
	'Gestion Demostracions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionDemostracion', 'url'=>array('index')),
	array('label'=>'Manage GestionDemostracion', 'url'=>array('admin')),
);
?>

<h1>Create GestionDemostracion</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>