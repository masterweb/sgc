<?php
/* @var $this GestionProspeccionRpController */
/* @var $model GestionProspeccionRp */

$this->breadcrumbs=array(
	'Gestion Prospeccion Rps'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GestionProspeccionRp', 'url'=>array('index')),
	array('label'=>'Create GestionProspeccionRp', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-prospeccion-rp-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Gestion Prospeccion Rps</h1>

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
	'id'=>'gestion-prospeccion-rp-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'preg1',
		'preg2',
		'preg3',
		'preg4',
		'preg5',
		/*
		'preg6',
		'preg3_sec1',
		'preg3_sec2',
		'preg3_sec3',
		'preg3_sec4',
		'preg4_sec1',
		'preg4_sec2',
		'preg4_sec3',
		'preg4_sec4',
		'preg5_sec1',
		'preg5_sec2',
		'preg5_sec3',
		'fecha',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
