<?php
/* @var $this GestionVersionesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Versiones',
);

$this->menu=array(
	array('label'=>'Create GestionVersiones', 'url'=>array('create')),
	array('label'=>'Manage GestionVersiones', 'url'=>array('admin')),
);
?>

<h1>Gestion Versiones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
