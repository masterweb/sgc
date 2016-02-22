<?php
/* @var $this GestionPasoEntregaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Paso Entregas',
);

$this->menu=array(
	array('label'=>'Create GestionPasoEntrega', 'url'=>array('create')),
	array('label'=>'Manage GestionPasoEntrega', 'url'=>array('admin')),
);
?>

<h1>Gestion Paso Entregas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
