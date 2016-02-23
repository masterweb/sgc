<?php
/* @var $this GestionPasoEntregaController */
/* @var $model GestionPasoEntrega */

$this->breadcrumbs=array(
	'Gestion Paso Entregas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionPasoEntrega', 'url'=>array('index')),
	array('label'=>'Create GestionPasoEntrega', 'url'=>array('create')),
	array('label'=>'Update GestionPasoEntrega', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionPasoEntrega', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionPasoEntrega', 'url'=>array('admin')),
);
?>

<h1>View GestionPasoEntrega #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_informacion',
		'id_vehiculo',
		'envio_factura',
		'emision_contrato',
		'agendar_firma',
		'alistamiento_unidad',
		'pago_matricula',
		'recepcion_contratos',
		'recepcion_matricula',
		'vehiculo_revisado',
		'entrega_vehiculo',
		'foto_entrega',
		'foto_hoja_entrega',
		'fecha',
	),
)); ?>
