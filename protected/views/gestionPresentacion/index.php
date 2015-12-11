<?php
/* @var $this GestionPresentacionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Presentacions',
);

$this->menu=array(
	array('label'=>'Create GestionPresentacion', 'url'=>array('create')),
	array('label'=>'Manage GestionPresentacion', 'url'=>array('admin')),
);
?>

<h1>Gestion Presentacions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
