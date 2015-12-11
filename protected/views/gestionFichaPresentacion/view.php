<?php
/* @var $this GestionFichaPresentacionController */
/* @var $model GestionFichaPresentacion */

$this->breadcrumbs=array(
	'Gestion Ficha Presentacions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionFichaPresentacion', 'url'=>array('index')),
	array('label'=>'Create GestionFichaPresentacion', 'url'=>array('create')),
	array('label'=>'Update GestionFichaPresentacion', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionFichaPresentacion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionFichaPresentacion', 'url'=>array('admin')),
);
?>

<h1>View GestionFichaPresentacion #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'titulo',
		'descripcion',
		'id_modelo',
		'orden',
	),
)); ?>
