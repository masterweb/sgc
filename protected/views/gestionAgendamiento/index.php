<?php
/* @var $this GestionAgendamientoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Agendamientos',
);

$this->menu=array(
	array('label'=>'Create GestionAgendamiento', 'url'=>array('create')),
	array('label'=>'Manage GestionAgendamiento', 'url'=>array('admin')),
);
?>

<h1>Gestion Agendamientos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
