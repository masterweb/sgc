<?php
/* @var $this CencuestadospreguntasController */
/* @var $model Cencuestadospreguntas */

$this->breadcrumbs=array(
	'Cencuestadospreguntases'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Cencuestadospreguntas', 'url'=>array('index')),
	array('label'=>'Create Cencuestadospreguntas', 'url'=>array('create')),
	array('label'=>'View Cencuestadospreguntas', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Cencuestadospreguntas', 'url'=>array('admin')),
);
?>

<h1>Update Cencuestadospreguntas <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>