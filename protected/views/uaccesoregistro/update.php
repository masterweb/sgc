<?php
/* @var $this UaccesoregistroController */
/* @var $model Accesoregistro */

$this->breadcrumbs=array(
	'Accesoregistros'=>array('index'),
	$model->idconfirmado=>array('view','id'=>$model->idconfirmado),
	'Update',
);

$this->menu=array(
	array('label'=>'List Accesoregistro', 'url'=>array('index')),
	array('label'=>'Create Accesoregistro', 'url'=>array('create')),
	array('label'=>'View Accesoregistro', 'url'=>array('view', 'id'=>$model->idconfirmado)),
	array('label'=>'Manage Accesoregistro', 'url'=>array('admin')),
);
?>

<h1>Update Accesoregistro <?php echo $model->idconfirmado; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>