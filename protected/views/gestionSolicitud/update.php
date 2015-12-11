<?php
/* @var $this GestionSolicitudController */
/* @var $model GestionSolicitud */

$this->breadcrumbs=array(
	'Gestion Solicituds'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionSolicitud', 'url'=>array('index')),
	array('label'=>'Create GestionSolicitud', 'url'=>array('create')),
	array('label'=>'View GestionSolicitud', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionSolicitud', 'url'=>array('admin')),
);
?>

<h1>Update GestionSolicitud <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>