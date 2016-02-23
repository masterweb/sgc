<?php
/* @var $this GestionPasoEntregaDetailController */
/* @var $model GestionPasoEntregaDetail */

$this->breadcrumbs=array(
	'Gestion Paso Entrega Details'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionPasoEntregaDetail', 'url'=>array('index')),
	array('label'=>'Create GestionPasoEntregaDetail', 'url'=>array('create')),
	array('label'=>'Update GestionPasoEntregaDetail', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionPasoEntregaDetail', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionPasoEntregaDetail', 'url'=>array('admin')),
);
?>

<h1>View GestionPasoEntregaDetail #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_paso',
		'fecha_paso',
		'observaciones',
		'placa',
		'responsable',
		'foto_entrega',
		'foto_hoja_entrega',
		'fecha',
	),
)); ?>
