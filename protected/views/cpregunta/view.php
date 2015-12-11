<?php
/* @var $this CpreguntaController */
/* @var $model Cpregunta */

$this->breadcrumbs=array(
	'Cpreguntas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Cpregunta', 'url'=>array('index')),
	array('label'=>'Create Cpregunta', 'url'=>array('create')),
	array('label'=>'Update Cpregunta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Cpregunta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cpregunta', 'url'=>array('admin')),
);
?>

<h1>View Cpregunta #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'descripcion',
		'fecha',
		'estado',
		'ctipopregunta_id',
		'cquestionario_id',
	),
)); ?>
