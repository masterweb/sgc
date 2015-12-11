<?php
/* @var $this GestionNuevaCotizacionController */
/* @var $model GestionNuevaCotizacion */

$this->breadcrumbs=array(
	'Gestion Nueva Cotizacions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionNuevaCotizacion', 'url'=>array('index')),
	array('label'=>'Create GestionNuevaCotizacion', 'url'=>array('create')),
	array('label'=>'View GestionNuevaCotizacion', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionNuevaCotizacion', 'url'=>array('admin')),
);
?>

<h1>Update GestionNuevaCotizacion <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>