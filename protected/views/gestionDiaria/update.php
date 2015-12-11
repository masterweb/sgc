<?php
/* @var $this GestionDiariaController */
/* @var $model GestionDiaria */

$this->breadcrumbs=array(
	'Gestion Diarias'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionDiaria', 'url'=>array('index')),
	array('label'=>'Create GestionDiaria', 'url'=>array('create')),
	array('label'=>'View GestionDiaria', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionDiaria', 'url'=>array('admin')),
);
?>

<h1>Update GestionDiaria <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>