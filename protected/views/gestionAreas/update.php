<?php
/* @var $this GestionAreasController */
/* @var $model GestionAreas */

$this->breadcrumbs=array(
	'Gestion Areases'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionAreas', 'url'=>array('index')),
	array('label'=>'Create GestionAreas', 'url'=>array('create')),
	array('label'=>'View GestionAreas', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionAreas', 'url'=>array('admin')),
);
?>

<h1>Update GestionAreas <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>