<?php
/* @var $this DealercitiesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Dealercities',
);

$this->menu=array(
	array('label'=>'Create Dealercities', 'url'=>array('create')),
	array('label'=>'Manage Dealercities', 'url'=>array('admin')),
);
?>

<h1>Dealercities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
