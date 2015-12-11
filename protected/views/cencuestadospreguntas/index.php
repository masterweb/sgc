<?php
/* @var $this CencuestadospreguntasController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cencuestadospreguntases',
);

$this->menu=array(
	array('label'=>'Create Cencuestadospreguntas', 'url'=>array('create')),
	array('label'=>'Manage Cencuestadospreguntas', 'url'=>array('admin')),
);
?>

<h1>Cencuestadospreguntases</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
