<?php
/* @var $this GestionProspeccionRpController */
/* @var $model GestionProspeccionRp */

$this->breadcrumbs=array(
	'Gestion Prospeccion Rps'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionProspeccionRp', 'url'=>array('index')),
	array('label'=>'Manage GestionProspeccionRp', 'url'=>array('admin')),
);
?>

<h1>Create GestionProspeccionRp</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>