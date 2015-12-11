<?php
/* @var $this GestionProspeccionPrController */
/* @var $model GestionProspeccionPr */

$this->breadcrumbs=array(
	'Gestion Prospeccion Prs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionProspeccionPr', 'url'=>array('index')),
	array('label'=>'Create GestionProspeccionPr', 'url'=>array('create')),
	array('label'=>'Update GestionProspeccionPr', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionProspeccionPr', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionProspeccionPr', 'url'=>array('admin')),
);
?>

<h1>View GestionProspeccionPr #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'pregunta',
	),
)); ?>
