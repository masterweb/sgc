<?php
/* @var $this GestionPasoEntregaController */
/* @var $model GestionPasoEntrega */

$this->breadcrumbs=array(
	'Gestion Paso Entregas'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GestionPasoEntrega', 'url'=>array('index')),
	array('label'=>'Create GestionPasoEntrega', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-paso-entrega-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Gestion Paso Entregas</h1>

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
	'id'=>'gestion-paso-entrega-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
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
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
