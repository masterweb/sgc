<?php
/* @var $this GestionInformacionController */
/* @var $model GestionInformacion */

$this->breadcrumbs=array(
	'Gestion Informacions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionInformacion', 'url'=>array('index')),
	array('label'=>'Create GestionInformacion', 'url'=>array('create')),
	array('label'=>'Update GestionInformacion', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionInformacion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionInformacion', 'url'=>array('admin')),
);
?>

<h1>View GestionInformacion #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombres',
		'apellidos',
		'cedula',
		'direccion',
		'email',
		'celular',
		'telefono',
		'ciudad',
		'modelo',
		'version',
		'concesionario',
		'fecha',
	),
)); ?>
