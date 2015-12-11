<?php
/* @var $this PvQiradicionalController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Qiradicionals',
);

$this->menu=array(
	array('label'=>'Create Qiradicional', 'url'=>array('create')),
	array('label'=>'Manage Qiradicional', 'url'=>array('admin')),
);
?>

<h1>Qiradicionals</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
