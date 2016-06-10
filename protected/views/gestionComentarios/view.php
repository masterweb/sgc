<?php
/* @var $this GestionComentariosController */
/* @var $model GestionComentarios */

$this->breadcrumbs=array(
	'Gestion Comentarioses'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionComentarios', 'url'=>array('index')),
	array('label'=>'Create GestionComentarios', 'url'=>array('create')),
	array('label'=>'Update GestionComentarios', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionComentarios', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionComentarios', 'url'=>array('admin')),
);
?>

<h1>View GestionComentarios #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_informacion',
		'id_responsable_recibido',
		'id_responsable_enviado',
		'comentario',
		'fecha',
		'img',
	),
)); ?>
