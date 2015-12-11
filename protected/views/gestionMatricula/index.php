<?php
/* @var $this GestionMatriculaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Matriculas',
);

$this->menu=array(
	array('label'=>'Create GestionMatricula', 'url'=>array('create')),
	array('label'=>'Manage GestionMatricula', 'url'=>array('admin')),
);
?>

<h1>Gestion Matriculas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
