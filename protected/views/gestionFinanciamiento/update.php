<?php
/* @var $this GestionFinanciamientoController */
/* @var $model GestionFinanciamiento */

$this->breadcrumbs=array(
	'Gestion Financiamientos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionFinanciamiento', 'url'=>array('index')),
	array('label'=>'Create GestionFinanciamiento', 'url'=>array('create')),
	array('label'=>'View GestionFinanciamiento', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionFinanciamiento', 'url'=>array('admin')),
);
?>

<h1>Update GestionFinanciamiento <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>