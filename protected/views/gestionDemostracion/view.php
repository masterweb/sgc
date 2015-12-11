<?php
/* @var $this GestionDemostracionController */
/* @var $model GestionDemostracion */

$this->breadcrumbs=array(
	'Gestion Demostracions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionDemostracion', 'url'=>array('index')),
	array('label'=>'Create GestionDemostracion', 'url'=>array('create')),
	array('label'=>'Update GestionDemostracion', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionDemostracion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionDemostracion', 'url'=>array('admin')),
);
?>

<h1>View GestionDemostracion #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'preg1',
		'preg1_licencia',
		'preg1_agendamiento',
		'id_informacion',
		'fecha',
	),
)); ?>
