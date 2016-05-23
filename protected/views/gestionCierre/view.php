<?php
/* @var $this GestionCierreController */
/* @var $model GestionCierre */

$this->breadcrumbs=array(
	'Gestion Cierres'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionCierre', 'url'=>array('index')),
	array('label'=>'Create GestionCierre', 'url'=>array('create')),
	array('label'=>'Update GestionCierre', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionCierre', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionCierre', 'url'=>array('admin')),
);
?>

<h1>View GestionCierre #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'numero_chasis',
		'numero_modelo',
		'nombre_propietario',
		'color_vehiculo',
		'factura',
		'concesionario',
		'fecha_venta',
		'year',
		'color_origen',
		'identificacion',
		'precio_venta',
		'calle_principal',
		'numero_calle',
		'telefono_propietario',
		'grupo_concesionario',
		'forma_pago',
		'observacion',
		'fecha',
	),
)); ?>
