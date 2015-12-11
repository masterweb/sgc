<?php
/* @var $this GestionDemostracionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Demostracions',
);

$this->menu=array(
	array('label'=>'Create GestionDemostracion', 'url'=>array('create')),
	array('label'=>'Manage GestionDemostracion', 'url'=>array('admin')),
);
?>

<h1>Gestion Demostracions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
