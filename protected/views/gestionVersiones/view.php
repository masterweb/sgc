<?php
/* @var $this GestionVersionesController */
/* @var $model GestionVersiones */

$this->breadcrumbs=array(
	'Gestion Versiones'=>array('index'),
	$model->id_versiones,
);

$this->menu=array(
	array('label'=>'List GestionVersiones', 'url'=>array('index')),
	array('label'=>'Create GestionVersiones', 'url'=>array('create')),
	array('label'=>'Update GestionVersiones', 'url'=>array('update', 'id'=>$model->id_versiones)),
	array('label'=>'Delete GestionVersiones', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_versiones),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionVersiones', 'url'=>array('admin')),
);
?>

<h1>View GestionVersiones #<?php echo $model->id_versiones; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_versiones',
		'id_modelos',
		'id_categoria',
		'nombre_version',
		'precio',
		'orden',
		'pdf',
	),
)); ?>
