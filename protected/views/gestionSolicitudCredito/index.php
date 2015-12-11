<?php
/* @var $this GestionSolicitudCreditoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Solicitud Creditos',
);

$this->menu=array(
	array('label'=>'Create GestionSolicitudCredito', 'url'=>array('create')),
	array('label'=>'Manage GestionSolicitudCredito', 'url'=>array('admin')),
);
?>

<h1>Gestion Solicitud Creditos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
