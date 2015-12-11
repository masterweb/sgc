<?php
/* @var $this GestionStockController */
/* @var $model GestionStock */

$this->breadcrumbs=array(
	'Gestion Stocks'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GestionStock', 'url'=>array('index')),
	array('label'=>'Create GestionStock', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-stock-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Gestion Stocks</h1>

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
	'id'=>'gestion-stock-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'fecha_w',
		'embarque',
		'bloque',
		'familia',
		'code',
		/*
		'version',
		'equip',
		'fsc',
		'referencia',
		'aeade',
		'segmento',
		'grupo',
		'concesionario',
		'color_origen',
		'color_plano',
		'my',
		'chasis',
		'edad',
		'rango',
		'fact',
		'cod_aeade',
		'pvc',
		'stock',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
