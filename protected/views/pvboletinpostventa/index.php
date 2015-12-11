<?php
/* @var $this PvboletinpostventaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Boletinpostventas',
);

$this->menu=array(
	array('label'=>'Create Boletinpostventa', 'url'=>array('create')),
	array('label'=>'Manage Boletinpostventa', 'url'=>array('admin')),
);
?>

<h1>Boletinpostventas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
