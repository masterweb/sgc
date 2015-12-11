<?php
/* @var $this GestionFinanciamientoOpController */
/* @var $model GestionFinanciamientoOp */

$this->breadcrumbs=array(
	'Gestion Financiamiento Ops'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionFinanciamientoOp', 'url'=>array('index')),
	array('label'=>'Manage GestionFinanciamientoOp', 'url'=>array('admin')),
);
?>

<h1>Create GestionFinanciamientoOp</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>