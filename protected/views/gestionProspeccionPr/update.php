<?php
/* @var $this GestionProspeccionPrController */
/* @var $model GestionProspeccionPr */

$this->breadcrumbs=array(
	'Gestion Prospeccion Prs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionProspeccionPr', 'url'=>array('index')),
	array('label'=>'Create GestionProspeccionPr', 'url'=>array('create')),
	array('label'=>'View GestionProspeccionPr', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionProspeccionPr', 'url'=>array('admin')),
);
?>

<h1>Update GestionProspeccionPr <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>