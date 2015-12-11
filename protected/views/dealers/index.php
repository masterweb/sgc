<?php
/* @var $this DealersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Dealers',
);

$this->menu=array(
	array('label'=>'Create Dealers', 'url'=>array('create')),
	array('label'=>'Manage Dealers', 'url'=>array('admin')),
);
?>

<h1>Dealers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
