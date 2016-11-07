<?php
/* @var $this GestionVersionesController */
/* @var $model GestionVersiones */

$this->breadcrumbs=array(
	'Gestion Versiones'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionVersiones', 'url'=>array('index')),
	array('label'=>'Manage GestionVersiones', 'url'=>array('admin')),
);
?>

<h1>Create GestionVersiones</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>