<?php
/* @var $this GestionMatriculaController */
/* @var $model GestionMatricula */

$this->breadcrumbs=array(
	'Gestion Matriculas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GestionMatricula', 'url'=>array('index')),
	array('label'=>'Create GestionMatricula', 'url'=>array('create')),
	array('label'=>'Update GestionMatricula', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GestionMatricula', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GestionMatricula', 'url'=>array('admin')),
);
?>

<h1>View GestionMatricula #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'factura_ingreso',
		'envio_factura',
		'pago_consejo',
		'venta_credito',
		'entrega_documentos_gestor',
		'ente_regulador_placa',
		'vehiculo_matricula_placas',
		'id_informacion',
		'id_vehiculo',
		'fecha',
	),
)); ?>
