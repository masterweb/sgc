<?php
/* @var $this GestionAsignamientoExternoController */
/* @var $model GestionAsignamientoExterno */

$this->breadcrumbs=array(
	'Gestion Asignamiento Externos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionAsignamientoExterno', 'url'=>array('index')),
	array('label'=>'Create GestionAsignamientoExterno', 'url'=>array('create')),
	array('label'=>'View GestionAsignamientoExterno', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionAsignamientoExterno', 'url'=>array('admin')),
);
?>

<h1>Update GestionAsignamientoExterno <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>