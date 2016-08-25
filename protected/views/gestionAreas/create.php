<?php
/* @var $this GestionAreasController */
/* @var $model GestionAreas */

$this->breadcrumbs=array(
	'Gestion Areases'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionAreas', 'url'=>array('index')),
	array('label'=>'Manage GestionAreas', 'url'=>array('admin')),
);
?>

<h1>Create GestionAreas</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>