<?php
/* @var $this GestionStockController */
/* @var $model GestionStock */

$this->breadcrumbs=array(
	'Gestion Stocks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionStock', 'url'=>array('index')),
	array('label'=>'Manage GestionStock', 'url'=>array('admin')),
);
?>

<h1>Create GestionStock</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>