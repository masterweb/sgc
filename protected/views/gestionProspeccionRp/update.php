<?php
/* @var $this GestionProspeccionRpController */
/* @var $model GestionProspeccionRp */

$this->breadcrumbs=array(
	'Gestion Prospeccion Rps'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionProspeccionRp', 'url'=>array('index')),
	array('label'=>'Create GestionProspeccionRp', 'url'=>array('create')),
	array('label'=>'View GestionProspeccionRp', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionProspeccionRp', 'url'=>array('admin')),
);
?>

<h1>Update GestionProspeccionRp <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>