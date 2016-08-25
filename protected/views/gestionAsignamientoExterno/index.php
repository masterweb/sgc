<?php
/* @var $this GestionAsignamientoExternoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Asignamiento Externos',
);

$this->menu=array(
	array('label'=>'Create GestionAsignamientoExterno', 'url'=>array('create')),
	array('label'=>'Manage GestionAsignamientoExterno', 'url'=>array('admin')),
);
?>

<h1>Gestion Asignamiento Externos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
