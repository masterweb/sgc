<?php
/* @var $this GestionAsignamientoExternoController */
/* @var $model GestionAsignamientoExterno */

$this->breadcrumbs=array(
	'Gestion Asignamiento Externos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionAsignamientoExterno', 'url'=>array('index')),
	array('label'=>'Manage GestionAsignamientoExterno', 'url'=>array('admin')),
);
?>

<h1>Create GestionAsignamientoExterno</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>