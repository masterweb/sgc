<?php
/* @var $this GestionFilesController */
/* @var $model GestionFiles */

$this->breadcrumbs=array(
	'Gestion Files'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionFiles', 'url'=>array('index')),
	array('label'=>'Manage GestionFiles', 'url'=>array('admin')),
);
?>

<!--<h1>Create GestionFiles</h1>-->

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>