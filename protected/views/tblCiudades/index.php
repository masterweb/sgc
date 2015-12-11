<?php
/* @var $this TblCiudadesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tbl Ciudades',
);

$this->menu=array(
	array('label'=>'Create TblCiudades', 'url'=>array('create')),
	array('label'=>'Manage TblCiudades', 'url'=>array('admin')),
);
?>

<h1>Tbl Ciudades</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
