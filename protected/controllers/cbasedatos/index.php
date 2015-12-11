<?php
/* @var $this CbasedatosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cbasedatoses',
);

$this->menu=array(
	array('label'=>'Create Cbasedatos', 'url'=>array('create')),
	array('label'=>'Manage Cbasedatos', 'url'=>array('admin')),
);
?>

<h1>Cbasedatoses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
