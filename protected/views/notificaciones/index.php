<?php
/* @var $this NotificacionesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Notificaciones',
);

$this->menu=array(
	array('label'=>'Create Notificaciones', 'url'=>array('create')),
	array('label'=>'Manage Notificaciones', 'url'=>array('admin')),
);
?>

<h1>Notificaciones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
