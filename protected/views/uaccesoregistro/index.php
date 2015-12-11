<?php
/* @var $this UaccesoregistroController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Accesoregistros',
);

$this->menu=array(
	array('label'=>'Create Accesoregistro', 'url'=>array('create')),
	array('label'=>'Manage Accesoregistro', 'url'=>array('admin')),
);
?>

<h1>Accesoregistros</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
