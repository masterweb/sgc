<?php
/* @var $this GestionFilesController */
/* @var $model GestionFiles */

$this->breadcrumbs=array(
	'Gestion Files'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionFiles', 'url'=>array('index')),
	array('label'=>'Create GestionFiles', 'url'=>array('create')),
	array('label'=>'View GestionFiles', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionFiles', 'url'=>array('admin')),
);
?>

<h1>Update GestionFiles <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>