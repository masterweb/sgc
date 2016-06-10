<?php
/* @var $this GestionComentariosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gestion Comentarioses',
);

$this->menu=array(
	array('label'=>'Create GestionComentarios', 'url'=>array('create')),
	array('label'=>'Manage GestionComentarios', 'url'=>array('admin')),
);
?>

<h1>Gestion Comentarioses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
