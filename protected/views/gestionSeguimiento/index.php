<?php
/* @var $this GestionSeguimientoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Seguimientos',
);

$this->menu=array(
	array('label'=>'Create GestionSeguimiento', 'url'=>array('create')),
	array('label'=>'Manage GestionSeguimiento', 'url'=>array('admin')),
);
?>

<h1>Gestion Seguimientos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
