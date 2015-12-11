<?php
/* @var $this PvmodelosposventaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Modelosposventas',
);

$this->menu=array(
	array('label'=>'Create Modelosposventa', 'url'=>array('create')),
	array('label'=>'Manage Modelosposventa', 'url'=>array('admin')),
);
?>

<h1>Modelosposventas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
