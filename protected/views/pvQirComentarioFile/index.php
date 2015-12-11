<?php
/* @var $this PvQirComentarioFileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Qir Comentario Files',
);

$this->menu=array(
	array('label'=>'Create QirComentarioFile', 'url'=>array('create')),
	array('label'=>'Manage QirComentarioFile', 'url'=>array('admin')),
);
?>

<h1>Qir Comentario Files</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
