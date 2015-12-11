<?php
/* @var $this ProvinciasController */
/* @var $model Provincias */

$this->breadcrumbs=array(
	'Provinciases'=>array('index'),
	$model->id_provincia,
);

$this->menu=array(
	array('label'=>'List Provincias', 'url'=>array('index')),
	array('label'=>'Create Provincias', 'url'=>array('create')),
	array('label'=>'Update Provincias', 'url'=>array('update', 'id'=>$model->id_provincia)),
	array('label'=>'Delete Provincias', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_provincia),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Provincias', 'url'=>array('admin')),
);
?>

<h1>View Provincias #<?php echo $model->id_provincia; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_provincia',
		'nombre',
	),
)); ?>
