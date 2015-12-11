<?php
/* @var $this NotificacionesController */
/* @var $model Notificaciones */

$this->breadcrumbs=array(
	'Notificaciones'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Notificaciones', 'url'=>array('index')),
	array('label'=>'Manage Notificaciones', 'url'=>array('admin')),
);
?>

<h1>Create Notificaciones</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>