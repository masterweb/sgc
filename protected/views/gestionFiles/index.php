<?php
/* @var $this GestionFilesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Files',
);

$this->menu=array(
	array('label'=>'Create GestionFiles', 'url'=>array('create')),
	array('label'=>'Manage GestionFiles', 'url'=>array('admin')),
);
?>

<h1>Gestion Files</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
