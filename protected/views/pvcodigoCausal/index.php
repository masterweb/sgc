<?php
/* @var $this PvcodigoCausalController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Codigo Causals',
);

$this->menu=array(
	array('label'=>'Create CodigoCausal', 'url'=>array('create')),
	array('label'=>'Manage CodigoCausal', 'url'=>array('admin')),
);
?>

<h1>Codigo Causals</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
