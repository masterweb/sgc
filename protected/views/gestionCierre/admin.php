<?php
/* @var $this GestionCierreController */
/* @var $model GestionCierre */

$this->breadcrumbs=array(
	'Gestion Cierres'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GestionCierre', 'url'=>array('index')),
	array('label'=>'Create GestionCierre', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-cierre-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Gestion Cierres</h1>

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
	'id'=>'gestion-cierre-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'numero_chasis',
		'numero_modelo',
		'nombre_propietario',
		'color_vehiculo',
		'factura',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
