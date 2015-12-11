<?php
/* @var $this GestionDiariaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Diarias',
);

$this->menu=array(
	array('label'=>'Create GestionDiaria', 'url'=>array('create')),
	array('label'=>'Manage GestionDiaria', 'url'=>array('admin')),
);
?>

<h1>Gestion Diarias</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
