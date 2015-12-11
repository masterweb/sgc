<?php
/* @var $this UaccesosistemaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Accesosistemas',
);

$this->menu=array(
	array('label'=>'Create Accesosistema', 'url'=>array('create')),
	array('label'=>'Manage Accesosistema', 'url'=>array('admin')),
);
?>

<h1>Accesosistemas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
