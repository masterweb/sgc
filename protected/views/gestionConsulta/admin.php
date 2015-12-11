<?php
/* @var $this GestionConsultaController */
/* @var $model GestionConsulta */

$this->breadcrumbs=array(
	'Gestion Consultas'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GestionConsulta', 'url'=>array('index')),
	array('label'=>'Create GestionConsulta', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-consulta-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Gestion Consultas</h1>

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
	'id'=>'gestion-consulta-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'preg1_sec1',
		'preg1_sec2',
		'preg1_sec3',
		'preg1_sec4',
		'preg1_sec5',
		/*
		'preg2',
		'preg2_sec1',
		'preg3',
		'preg3_sec1',
		'preg3_sec2',
		'preg3_sec3',
		'preg3_sec4',
		'preg4',
		'preg5',
		'preg6',
		'fecha',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
