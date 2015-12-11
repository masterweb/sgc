<?php
/* @var $this GestionProspeccionRpController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Prospeccion Rps',
);

$this->menu=array(
	array('label'=>'Create GestionProspeccionRp', 'url'=>array('create')),
	array('label'=>'Manage GestionProspeccionRp', 'url'=>array('admin')),
);
?>

<h1>Gestion Prospeccion Rps</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
