<?php
/* @var $this GestionHojaEntregaSolicitudController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Hoja Entrega Solicituds',
);

$this->menu=array(
	array('label'=>'Create GestionHojaEntregaSolicitud', 'url'=>array('create')),
	array('label'=>'Manage GestionHojaEntregaSolicitud', 'url'=>array('admin')),
);
?>

<h1>Gestion Hoja Entrega Solicituds</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
