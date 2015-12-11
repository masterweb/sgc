<?php
/* @var $this CbasedatosController */
/* @var $model Cbasedatos */

$this->breadcrumbs=array(
	'Cbasedatoses'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Cbasedatos', 'url'=>array('index')),
	array('label'=>'Create Cbasedatos', 'url'=>array('create')),
	array('label'=>'Update Cbasedatos', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Cbasedatos', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cbasedatos', 'url'=>array('admin')),
);
?>

<h1>View Cbasedatos #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'descripcion',
		'base',
		'usuario',
		'password',
		'tabla',
		'estado',
	),
)); ?>
