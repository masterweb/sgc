<?php
/* @var $this GestionCierreController */
/* @var $model GestionCierre */

$this->breadcrumbs=array(
	'Gestion Cierres'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionCierre', 'url'=>array('index')),
	array('label'=>'Create GestionCierre', 'url'=>array('create')),
	array('label'=>'View GestionCierre', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionCierre', 'url'=>array('admin')),
);
?>


<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>