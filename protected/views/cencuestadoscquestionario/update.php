<?php
/* @var $this CencuestadoscquestionarioController */
/* @var $model Cencuestadoscquestionario */

$this->breadcrumbs=array(
	'Cencuestadoscquestionarios'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Cencuestadoscquestionario', 'url'=>array('index')),
	array('label'=>'Create Cencuestadoscquestionario', 'url'=>array('create')),
	array('label'=>'View Cencuestadoscquestionario', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Cencuestadoscquestionario', 'url'=>array('admin')),
);
?>

<h1>Update Cencuestadoscquestionario <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>