<?php
/* @var $this GestionStockController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Stocks',
);

$this->menu=array(
	array('label'=>'Create GestionStock', 'url'=>array('create')),
	array('label'=>'Manage GestionStock', 'url'=>array('admin')),
);
?>

<h1>Gestion Stocks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
