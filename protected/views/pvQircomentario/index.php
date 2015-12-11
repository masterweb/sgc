<?php
/* @var $this PvQircomentarioController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Qircomentarios',
);

$this->menu=array(
	array('label'=>'Create Qircomentario', 'url'=>array('create')),
	array('label'=>'Manage Qircomentario', 'url'=>array('admin')),
);
?>

<h1>Qircomentarios</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
