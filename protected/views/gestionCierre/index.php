<?php
/* @var $this GestionCierreController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Cierres',
);

$this->menu=array(
	array('label'=>'Create GestionCierre', 'url'=>array('create')),
	array('label'=>'Manage GestionCierre', 'url'=>array('admin')),
);
?>

<h1>Gestion Cierres</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
