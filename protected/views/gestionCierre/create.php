<?php
/* @var $this GestionCierreController */
/* @var $model GestionCierre */

$this->breadcrumbs=array(
	'Gestion Cierres'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionCierre', 'url'=>array('index')),
	array('label'=>'Manage GestionCierre', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'id_informacion' => $id_informacion,'id_vehiculo' => $id_vehiculo,)); ?>