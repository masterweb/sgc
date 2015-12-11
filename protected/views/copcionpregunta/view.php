<?php
/* @var $this CopcionpreguntaController */
/* @var $model Copcionpregunta */

$this->breadcrumbs=array(
	'Copcionpreguntas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Copcionpregunta', 'url'=>array('index')),
	array('label'=>'Create Copcionpregunta', 'url'=>array('create')),
	array('label'=>'Update Copcionpregunta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Copcionpregunta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Copcionpregunta', 'url'=>array('admin')),
);
?>

<h1>View Copcionpregunta #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'detalle',
		'valor',
		'cpregunta_id',
	),
)); ?>
