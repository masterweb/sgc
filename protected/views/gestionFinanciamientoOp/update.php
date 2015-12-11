<?php
/* @var $this GestionFinanciamientoOpController */
/* @var $model GestionFinanciamientoOp */

$this->breadcrumbs=array(
	'Gestion Financiamiento Ops'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionFinanciamientoOp', 'url'=>array('index')),
	array('label'=>'Create GestionFinanciamientoOp', 'url'=>array('create')),
	array('label'=>'View GestionFinanciamientoOp', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionFinanciamientoOp', 'url'=>array('admin')),
);
?>

<h1>Update GestionFinanciamientoOp <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>