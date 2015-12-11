<?php
/* @var $this VersionesController */
/* @var $model Versiones */

$this->breadcrumbs=array(
	'Versiones'=>array('index'),
	$model->id_versiones,
);

$this->menu=array(
	array('label'=>'List Versiones', 'url'=>array('index')),
	array('label'=>'Create Versiones', 'url'=>array('create')),
	array('label'=>'Update Versiones', 'url'=>array('update', 'id'=>$model->id_versiones)),
	array('label'=>'Delete Versiones', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_versiones),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Versiones', 'url'=>array('admin')),
);
?>

<h1>View Versiones #<?php echo $model->id_versiones; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_versiones',
		'id_modelos',
		'id_categoria',
		'nombre_version',
		'precio',
		'orden',
	),
)); ?>
