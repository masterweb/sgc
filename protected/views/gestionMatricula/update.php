<?php
/* @var $this GestionMatriculaController */
/* @var $model GestionMatricula */

$this->breadcrumbs=array(
	'Gestion Matriculas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionMatricula', 'url'=>array('index')),
	array('label'=>'Create GestionMatricula', 'url'=>array('create')),
	array('label'=>'View GestionMatricula', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionMatricula', 'url'=>array('admin')),
);
?>

<h1>Update GestionMatricula <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>