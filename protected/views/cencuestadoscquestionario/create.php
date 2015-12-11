<?php
/* @var $this CencuestadoscquestionarioController */
/* @var $model Cencuestadoscquestionario */

$this->breadcrumbs=array(
	'Cencuestadoscquestionarios'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Cencuestadoscquestionario', 'url'=>array('index')),
	array('label'=>'Manage Cencuestadoscquestionario', 'url'=>array('admin')),
);
?>

<h1>Create Cencuestadoscquestionario</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>