<?php
/* @var $this GestionInformacionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Informacions',
);

$this->menu=array(
	array('label'=>'Create GestionInformacion', 'url'=>array('create')),
	array('label'=>'Manage GestionInformacion', 'url'=>array('admin')),
);
?>

<h1>Gestion Informacions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
