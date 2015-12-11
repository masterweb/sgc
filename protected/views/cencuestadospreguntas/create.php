<?php
/* @var $this CencuestadospreguntasController */
/* @var $model Cencuestadospreguntas */

$this->breadcrumbs=array(
	'Cencuestadospreguntases'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Cencuestadospreguntas', 'url'=>array('index')),
	array('label'=>'Manage Cencuestadospreguntas', 'url'=>array('admin')),
);
?>

<h1>Create Cencuestadospreguntas</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>