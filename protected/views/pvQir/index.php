<?php
/* @var $this PvQirController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Qirs',
);

$this->menu=array(
	array('label'=>'Create Qir', 'url'=>array('create')),
	array('label'=>'Manage Qir', 'url'=>array('admin')),
);
?>

<h1>Qirs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
