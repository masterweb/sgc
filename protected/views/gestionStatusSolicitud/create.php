<?php
/* @var $this GestionStatusSolicitudController */
/* @var $model GestionStatusSolicitud */

$this->breadcrumbs=array(
	'Gestion Status Solicituds'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionStatusSolicitud', 'url'=>array('index')),
	array('label'=>'Manage GestionStatusSolicitud', 'url'=>array('admin')),
);
?>

<h1>Create GestionStatusSolicitud</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>