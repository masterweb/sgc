<?php
/* @var $this CencuestadospreguntasController */
/* @var $model Cencuestadospreguntas */

$this->breadcrumbs=array(
	'Cencuestadospreguntases'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Cencuestadospreguntas', 'url'=>array('index')),
	array('label'=>'Create Cencuestadospreguntas', 'url'=>array('create')),
	array('label'=>'Update Cencuestadospreguntas', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Cencuestadospreguntas', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cencuestadospreguntas', 'url'=>array('admin')),
);
?>

<h1>View Cencuestadospreguntas #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'pregunta_id',
		'respuesta',
		'fecha',
		'cencuestadoscquestionario_id',
	),
)); ?>
