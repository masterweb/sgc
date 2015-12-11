<?php
/* @var $this GestionFinanciamientoOpController */
/* @var $model GestionFinanciamientoOp */

$this->breadcrumbs=array(
	'Gestion Financiamiento Ops'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GestionFinanciamientoOp', 'url'=>array('index')),
	array('label'=>'Create GestionFinanciamientoOp', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-financiamiento-op-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Gestion Financiamiento Ops</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gestion-financiamiento-op-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'id_informacion',
		'id_vehiculo',
		'cuota_inicial',
		'saldo_financiar',
		'tarjeta_credito',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
