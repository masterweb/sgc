<?php
/* @var $this GestionNotificacionesController */
/* @var $model GestionNotificaciones */

$this->breadcrumbs=array(
	'Gestion Notificaciones'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionNotificaciones', 'url'=>array('index')),
	array('label'=>'Manage GestionNotificaciones', 'url'=>array('admin')),
);
?>

<h1>Create GestionNotificaciones</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>