<?php
/* @var $this GestionEntregaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Entregas',
);

$this->menu=array(
	array('label'=>'Create GestionEntrega', 'url'=>array('create')),
	array('label'=>'Manage GestionEntrega', 'url'=>array('admin')),
);
?>

<h1>Gestion Entregas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
