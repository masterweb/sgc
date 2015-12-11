<?php
/* @var $this GestionProspeccionPrController */
/* @var $model GestionProspeccionPr */

$this->breadcrumbs=array(
	'Gestion Prospeccion Prs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionProspeccionPr', 'url'=>array('index')),
	array('label'=>'Manage GestionProspeccionPr', 'url'=>array('admin')),
);
?>

<h1>Create GestionProspeccionPr</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>