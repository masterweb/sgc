<?php
/* @var $this GestionMatriculaController */
/* @var $model GestionMatricula */

$this->breadcrumbs=array(
	'Gestion Matriculas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionMatricula', 'url'=>array('index')),
	array('label'=>'Manage GestionMatricula', 'url'=>array('admin')),
);
?>

<h1>Create GestionMatricula</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>