<?php
/* @var $this GestionAreasController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Areases',
);

$this->menu=array(
	array('label'=>'Create GestionAreas', 'url'=>array('create')),
	array('label'=>'Manage GestionAreas', 'url'=>array('admin')),
);
?>

<h1>Gestion Areases</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
