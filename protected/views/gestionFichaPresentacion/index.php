<?php
/* @var $this GestionFichaPresentacionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Ficha Presentacions',
);

$this->menu=array(
	array('label'=>'Create GestionFichaPresentacion', 'url'=>array('create')),
	array('label'=>'Manage GestionFichaPresentacion', 'url'=>array('admin')),
);
?>

<h1>Gestion Ficha Presentacions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
