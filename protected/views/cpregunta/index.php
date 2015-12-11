<?php
/* @var $this CpreguntaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cpreguntas',
);

$this->menu=array(
	array('label'=>'Create Cpregunta', 'url'=>array('create')),
	array('label'=>'Manage Cpregunta', 'url'=>array('admin')),
);
?>

<h1>Cpreguntas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
