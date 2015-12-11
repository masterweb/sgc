<?php
/* @var $this GestionNotificacionesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Notificaciones',
);

$this->menu=array(
	array('label'=>'Create GestionNotificaciones', 'url'=>array('create')),
	array('label'=>'Manage GestionNotificaciones', 'url'=>array('admin')),
);
?>

<h1>Gestion Notificaciones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
