<?php
/* @var $this GestionPresentacionController */
/* @var $model GestionPresentacion */

$this->breadcrumbs=array(
	'Gestion Presentacions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionPresentacion', 'url'=>array('index')),
	array('label'=>'Create GestionPresentacion', 'url'=>array('create')),
	array('label'=>'View GestionPresentacion', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionPresentacion', 'url'=>array('admin')),
);
?>

<h1>Update GestionPresentacion <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>