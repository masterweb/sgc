<?php
/* @var $this GestionAmortizacionController */
/* @var $model GestionAmortizacion */

$this->breadcrumbs=array(
	'Gestion Amortizacions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionAmortizacion', 'url'=>array('index')),
	array('label'=>'Manage GestionAmortizacion', 'url'=>array('admin')),
);
?>

<h1>Create GestionAmortizacion</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>