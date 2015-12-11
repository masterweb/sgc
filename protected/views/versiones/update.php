<?php
/* @var $this VersionesController */
/* @var $model Versiones */

$this->breadcrumbs=array(
	'Versiones'=>array('index'),
	$model->id_versiones=>array('view','id'=>$model->id_versiones),
	'Update',
);

$this->menu=array(
	array('label'=>'List Versiones', 'url'=>array('index')),
	array('label'=>'Create Versiones', 'url'=>array('create')),
	array('label'=>'View Versiones', 'url'=>array('view', 'id'=>$model->id_versiones)),
	array('label'=>'Manage Versiones', 'url'=>array('admin')),
);
?>

<h1>Update Versiones <?php echo $model->id_versiones; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>