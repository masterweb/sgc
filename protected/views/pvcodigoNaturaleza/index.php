<?php
/* @var $this PvcodigoNaturalezaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Codigo Naturalezas',
);

$this->menu=array(
	array('label'=>'Create CodigoNaturaleza', 'url'=>array('create')),
	array('label'=>'Manage CodigoNaturaleza', 'url'=>array('admin')),
);
?>

<h1>Codigo Naturalezas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
