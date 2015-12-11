<?php
/* @var $this PvQirfilesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Qirfiles',
);

$this->menu=array(
	array('label'=>'Create Qirfiles', 'url'=>array('create')),
	array('label'=>'Manage Qirfiles', 'url'=>array('admin')),
);
?>

<h1>Qirfiles</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
