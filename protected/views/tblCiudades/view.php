<?php
/* @var $this TblCiudadesController */
/* @var $model TblCiudades */

$this->breadcrumbs=array(
	'Tbl Ciudades'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TblCiudades', 'url'=>array('index')),
	array('label'=>'Create TblCiudades', 'url'=>array('create')),
	array('label'=>'Update TblCiudades', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TblCiudades', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TblCiudades', 'url'=>array('admin')),
);
?>

<h1>View TblCiudades #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_provincia',
		'id_ciudad',
		'nombre',
	),
)); ?>
