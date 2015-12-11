<?php
/* @var $this GestionConsultaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Consultas',
);

$this->menu=array(
	array('label'=>'Create GestionConsulta', 'url'=>array('create')),
	array('label'=>'Manage GestionConsulta', 'url'=>array('admin')),
);
?>

<h1>Gestion Consultas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
