<?php
/* @var $this GestionAgendamientoController */
/* @var $model GestionAgendamiento */

$this->breadcrumbs=array(
	'Gestion Agendamientos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionAgendamiento', 'url'=>array('index')),
	array('label'=>'Create GestionAgendamiento', 'url'=>array('create')),
	array('label'=>'View GestionAgendamiento', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionAgendamiento', 'url'=>array('admin')),
);
?>

<h1>Update GestionAgendamiento <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>