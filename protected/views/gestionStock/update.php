<?php
/* @var $this GestionStockController */
/* @var $model GestionStock */

$this->breadcrumbs=array(
	'Gestion Stocks'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionStock', 'url'=>array('index')),
	array('label'=>'Create GestionStock', 'url'=>array('create')),
	array('label'=>'View GestionStock', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionStock', 'url'=>array('admin')),
);
?>

<h1>Update GestionStock <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>