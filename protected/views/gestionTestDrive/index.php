<?php
/* @var $this GestionTestDriveController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Test Drives',
);

$this->menu=array(
	array('label'=>'Create GestionTestDrive', 'url'=>array('create')),
	array('label'=>'Manage GestionTestDrive', 'url'=>array('admin')),
);
?>

<h1>Gestion Test Drives</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
