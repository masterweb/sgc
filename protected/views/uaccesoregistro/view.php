<?php
/* @var $this UaccesoregistroController */
/* @var $model Accesoregistro */

$this->breadcrumbs=array(
	'Accesoregistros'=>array('index'),
	$model->idconfirmado,
);

$this->menu=array(
	array('label'=>'List Accesoregistro', 'url'=>array('index')),
	array('label'=>'Create Accesoregistro', 'url'=>array('create')),
	array('label'=>'Update Accesoregistro', 'url'=>array('update', 'id'=>$model->idconfirmado)),
	array('label'=>'Delete Accesoregistro', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idconfirmado),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Accesoregistro', 'url'=>array('admin')),
);
?>

<h1>View Accesoregistro #<?php echo $model->idconfirmado; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idconfirmado',
		'usuarios_id',
		'administrador',
		'descripcion',
		'estado',
		'fecha',
	),
)); ?>
