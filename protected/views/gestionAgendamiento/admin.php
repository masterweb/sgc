<?php
/* @var $this GestionAgendamientoController */
/* @var $model GestionAgendamiento */

$this->breadcrumbs=array(
	'Gestion Agendamientos'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GestionAgendamiento', 'url'=>array('index')),
	array('label'=>'Create GestionAgendamiento', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-agendamiento-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Gestion Agendamientos</h1>

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
	'id'=>'gestion-agendamiento-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'id_informacion',
		'paso',
		'agendamiento',
		'observaciones',
		'fecha',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
