<?php
/* @var $this GestionProspeccionPrController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Prospeccion Prs',
);

$this->menu=array(
	array('label'=>'Create GestionProspeccionPr', 'url'=>array('create')),
	array('label'=>'Manage GestionProspeccionPr', 'url'=>array('admin')),
);
?>

<h1>Gestion Prospeccion Prs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
