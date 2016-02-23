<?php
/* @var $this GestionPasoEntregaDetailController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Paso Entrega Details',
);

$this->menu=array(
	array('label'=>'Create GestionPasoEntregaDetail', 'url'=>array('create')),
	array('label'=>'Manage GestionPasoEntregaDetail', 'url'=>array('admin')),
);
?>

<h1>Gestion Paso Entrega Details</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
