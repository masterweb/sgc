<?php
/* @var $this TblProvinciasController */
/* @var $model TblProvincias */

$this->breadcrumbs=array(
	'Tbl Provinciases'=>array('index'),
	$model->id_provincia,
);

$this->menu=array(
	array('label'=>'List TblProvincias', 'url'=>array('index')),
	array('label'=>'Create TblProvincias', 'url'=>array('create')),
	array('label'=>'Update TblProvincias', 'url'=>array('update', 'id'=>$model->id_provincia)),
	array('label'=>'Delete TblProvincias', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_provincia),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TblProvincias', 'url'=>array('admin')),
);
?>

<h1>View TblProvincias #<?php echo $model->id_provincia; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_provincia',
		'nombre',
	),
)); ?>
