<?php
/* @var $this GestionFinanciamientoOpController */
/* @var $model GestionFinanciamientoOp */

$this->breadcrumbs=array(
	'Gestion Financiamiento Ops'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionFinanciamientoOp', 'url'=>array('index')),
	array('label'=>'Create GestionFinanciamientoOp', 'url'=>array('create')),
	array('label'=>'Update GestionFinanciamientoOp', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionFinanciamientoOp', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionFinanciamientoOp', 'url'=>array('admin')),
);
?>

<h1>View GestionFinanciamientoOp #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_informacion',
		'id_vehiculo',
		'cuota_inicial',
		'saldo_financiar',
		'tarjeta_credito',
		'otro',
		'plazos',
		'cuota_mensual',
		'avaluo',
		'categoria',
		'fecha_cita',
		'observaciones',
		'precio_vehiculo',
		'tasa',
		'seguro',
		'valor_financiamiento',
		'forma_pago',
		'entidad_financiera',
		'fecha',
	),
)); ?>
