<?php
/* @var $this CasosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Casoses',
);

$this->menu=array(
	array('label'=>'Create Casos', 'url'=>array('create')),
	array('label'=>'Manage Casos', 'url'=>array('admin')),
);
?>

<h1>Casoses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
