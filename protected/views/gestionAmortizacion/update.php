<?php
/* @var $this GestionAmortizacionController */
/* @var $model GestionAmortizacion */

$this->breadcrumbs=array(
	'Gestion Amortizacions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionAmortizacion', 'url'=>array('index')),
	array('label'=>'Create GestionAmortizacion', 'url'=>array('create')),
	array('label'=>'View GestionAmortizacion', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionAmortizacion', 'url'=>array('admin')),
);
?>

<h1>Update GestionAmortizacion <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>