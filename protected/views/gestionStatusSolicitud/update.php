<?php
/* @var $this GestionStatusSolicitudController */
/* @var $model GestionStatusSolicitud */

$this->breadcrumbs=array(
	'Gestion Status Solicituds'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionStatusSolicitud', 'url'=>array('index')),
	array('label'=>'Create GestionStatusSolicitud', 'url'=>array('create')),
	array('label'=>'View GestionStatusSolicitud', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionStatusSolicitud', 'url'=>array('admin')),
);
?>

<h1>Update GestionStatusSolicitud <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>