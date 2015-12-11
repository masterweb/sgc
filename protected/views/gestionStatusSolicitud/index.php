<?php
/* @var $this GestionStatusSolicitudController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Status Solicituds',
);

$this->menu=array(
	array('label'=>'Create GestionStatusSolicitud', 'url'=>array('create')),
	array('label'=>'Manage GestionStatusSolicitud', 'url'=>array('admin')),
);
?>

<h1>Gestion Status Solicituds</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
