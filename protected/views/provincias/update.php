<?php
/* @var $this ProvinciasController */
/* @var $model Provincias */

$this->breadcrumbs=array(
	'Provinciases'=>array('index'),
	$model->id_provincia=>array('view','id'=>$model->id_provincia),
	'Update',
);

$this->menu=array(
	array('label'=>'List Provincias', 'url'=>array('index')),
	array('label'=>'Create Provincias', 'url'=>array('create')),
	array('label'=>'View Provincias', 'url'=>array('view', 'id'=>$model->id_provincia)),
	array('label'=>'Manage Provincias', 'url'=>array('admin')),
);
?>

<h1>Update Provincias <?php echo $model->id_provincia; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>