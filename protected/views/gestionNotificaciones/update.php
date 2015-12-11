<?php
/* @var $this GestionNotificacionesController */
/* @var $model GestionNotificaciones */

$this->breadcrumbs=array(
	'Gestion Notificaciones'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionNotificaciones', 'url'=>array('index')),
	array('label'=>'Create GestionNotificaciones', 'url'=>array('create')),
	array('label'=>'View GestionNotificaciones', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionNotificaciones', 'url'=>array('admin')),
);
?>

<h1>Update GestionNotificaciones <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>