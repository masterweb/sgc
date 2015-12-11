<?php
/* @var $this GestionFinanciamientoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Financiamientos',
);

$this->menu=array(
	array('label'=>'Create GestionFinanciamiento', 'url'=>array('create')),
	array('label'=>'Manage GestionFinanciamiento', 'url'=>array('admin')),
);
?>

<h1>Gestion Financiamientos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
