<?php
/* @var $this GestionPresentacionController */
/* @var $model GestionPresentacion */

$this->breadcrumbs=array(
	'Gestion Presentacions'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GestionPresentacion', 'url'=>array('index')),
	array('label'=>'Create GestionPresentacion', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-presentacion-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Gestion Presentacions</h1>

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
	'id'=>'gestion-presentacion-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'preg1_duda',
		'preg2_necesidades',
		'preg3_satisfecho',
		'preg1_sec1_duda',
		'preg2_sec1_necesidades',
		/*
		'fecha',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
