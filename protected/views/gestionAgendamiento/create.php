<?php
/* @var $this GestionAgendamientoController */
/* @var $model GestionAgendamiento */

$this->breadcrumbs=array(
	'Gestion Agendamientos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionAgendamiento', 'url'=>array('index')),
	array('label'=>'Manage GestionAgendamiento', 'url'=>array('admin')),
);
?>

<h1>Create GestionAgendamiento</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>