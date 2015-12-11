<?php
/* @var $this GestionNuevaCotizacionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Nueva Cotizacions',
);

$this->menu=array(
	array('label'=>'Create GestionNuevaCotizacion', 'url'=>array('create')),
	array('label'=>'Manage GestionNuevaCotizacion', 'url'=>array('admin')),
);
?>

<h1>Gestion Nueva Cotizacions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
