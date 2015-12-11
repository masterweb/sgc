<?php
/* @var $this CencuestadoscquestionarioController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cencuestadoscquestionarios',
);

$this->menu=array(
	array('label'=>'Create Cencuestadoscquestionario', 'url'=>array('create')),
	array('label'=>'Manage Cencuestadoscquestionario', 'url'=>array('admin')),
);
?>

<h1>Cencuestadoscquestionarios</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
