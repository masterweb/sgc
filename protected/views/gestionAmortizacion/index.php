<?php
/* @var $this GestionAmortizacionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Amortizacions',
);

$this->menu=array(
	array('label'=>'Create GestionAmortizacion', 'url'=>array('create')),
	array('label'=>'Manage GestionAmortizacion', 'url'=>array('admin')),
);
?>

<h1>Gestion Amortizacions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
