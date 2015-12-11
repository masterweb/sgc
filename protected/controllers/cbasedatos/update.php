<?php
/* @var $this CbasedatosController */
/* @var $model Cbasedatos */

$this->breadcrumbs=array(
	'Cbasedatoses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Cbasedatos', 'url'=>array('index')),
	array('label'=>'Create Cbasedatos', 'url'=>array('create')),
	array('label'=>'View Cbasedatos', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Cbasedatos', 'url'=>array('admin')),
);
?>

<h1>Update Cbasedatos <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>