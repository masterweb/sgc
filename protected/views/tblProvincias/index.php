<?php
/* @var $this TblProvinciasController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tbl Provinciases',
);

$this->menu=array(
	array('label'=>'Create TblProvincias', 'url'=>array('create')),
	array('label'=>'Manage TblProvincias', 'url'=>array('admin')),
);
?>

<h1>Tbl Provinciases</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
