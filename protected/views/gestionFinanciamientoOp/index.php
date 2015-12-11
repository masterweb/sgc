<?php
/* @var $this GestionFinanciamientoOpController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Financiamiento Ops',
);

$this->menu=array(
	array('label'=>'Create GestionFinanciamientoOp', 'url'=>array('create')),
	array('label'=>'Manage GestionFinanciamientoOp', 'url'=>array('admin')),
);
?>

<h1>Gestion Financiamiento Ops</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
