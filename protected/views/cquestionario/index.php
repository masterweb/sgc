<?php
/* @var $this CquestionarioController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cquestionarios',
);

$this->menu=array(
	array('label'=>'Create Cquestionario', 'url'=>array('create')),
	array('label'=>'Manage Cquestionario', 'url'=>array('admin')),
);
?>

<h1>Cquestionarios</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
