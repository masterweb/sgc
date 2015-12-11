<?php
/* @var $this CcampanaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ccampanas',
);

$this->menu=array(
	array('label'=>'Create Ccampana', 'url'=>array('create')),
	array('label'=>'Manage Ccampana', 'url'=>array('admin')),
);
?>

<h1>Ccampanas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
