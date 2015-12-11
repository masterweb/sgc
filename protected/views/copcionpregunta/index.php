<?php
/* @var $this CopcionpreguntaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Copcionpreguntas',
);

$this->menu=array(
	array('label'=>'Create Copcionpregunta', 'url'=>array('create')),
	array('label'=>'Manage Copcionpregunta', 'url'=>array('admin')),
);
?>

<h1>Copcionpreguntas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
