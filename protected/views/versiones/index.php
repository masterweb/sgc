<?php
/* @var $this VersionesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Versiones',
);

$this->menu=array(
	array('label'=>'Create Versiones', 'url'=>array('create')),
	array('label'=>'Manage Versiones', 'url'=>array('admin')),
);
?>

<h1>Versiones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
