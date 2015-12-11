<?php
/* @var $this PvvinMotorController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Vin Motors',
);

$this->menu=array(
	array('label'=>'Create VinMotor', 'url'=>array('create')),
	array('label'=>'Manage VinMotor', 'url'=>array('admin')),
);
?>

<h1>Vin Motors</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
