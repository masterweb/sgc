<?php
/* @var $this GestionSolicitudController */
/* @var $model GestionSolicitud */

$this->breadcrumbs=array(
	'Gestion Solicituds'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionSolicitud', 'url'=>array('index')),
	array('label'=>'Manage GestionSolicitud', 'url'=>array('admin')),
);
?>

<h1>Create GestionSolicitud</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>