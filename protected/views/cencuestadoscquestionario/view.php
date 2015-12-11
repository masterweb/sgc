<?php
/* @var $this CencuestadoscquestionarioController */
/* @var $model Cencuestadoscquestionario */

$this->breadcrumbs=array(
	'Cencuestadoscquestionarios'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Cencuestadoscquestionario', 'url'=>array('index')),
	array('label'=>'Create Cencuestadoscquestionario', 'url'=>array('create')),
	array('label'=>'Update Cencuestadoscquestionario', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Cencuestadoscquestionario', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cencuestadoscquestionario', 'url'=>array('admin')),
);
?>

<h1>View Cencuestadoscquestionario #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'cencuestados_id',
		'cquestionario_id',
		'usuarios_id',
		'audio',
		'tiempoinicio',
		'tiempofinal',
		'estado',
		'observaciones',
	),
)); ?>
