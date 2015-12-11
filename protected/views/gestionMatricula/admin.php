<?php
/* @var $this GestionMatriculaController */
/* @var $model GestionMatricula */

$this->breadcrumbs=array(
	'Gestion Matriculas'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GestionMatricula', 'url'=>array('index')),
	array('label'=>'Create GestionMatricula', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-matricula-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Gestion Matriculas</h1>

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
	'id'=>'gestion-matricula-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'factura_ingreso',
		'envio_factura',
		'pago_consejo',
		'venta_credito',
		'entrega_documentos_gestor',
		/*
		'ente_regulador_placa',
		'vehiculo_matricula_placas',
		'id_informacion',
		'id_vehiculo',
		'fecha',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
