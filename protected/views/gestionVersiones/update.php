<?php
/* @var $this GestionVersionesController */
/* @var $model GestionVersiones */

$this->breadcrumbs=array(
	'Gestion Versiones'=>array('index'),
	$model->id_versiones=>array('view','id'=>$model->id_versiones),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionVersiones', 'url'=>array('index')),
	array('label'=>'Create GestionVersiones', 'url'=>array('create')),
	array('label'=>'View GestionVersiones', 'url'=>array('view', 'id'=>$model->id_versiones)),
	array('label'=>'Manage GestionVersiones', 'url'=>array('admin')),
);
?>

<h1>Update GestionVersiones <?php echo $model->id_versiones; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>