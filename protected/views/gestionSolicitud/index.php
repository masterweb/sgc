<?php
/* @var $this GestionSolicitudController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Solicituds',
);

$this->menu=array(
	array('label'=>'Create GestionSolicitud', 'url'=>array('create')),
	array('label'=>'Manage GestionSolicitud', 'url'=>array('admin')),
);
?>

<h1>Gestion Solicituds</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
