<?php
/* @var $this GestionFirmaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Firmas',
);

$this->menu=array(
	array('label'=>'Create GestionFirma', 'url'=>array('create')),
	array('label'=>'Manage GestionFirma', 'url'=>array('admin')),
);
?>

<h1>Gestion Firmas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
