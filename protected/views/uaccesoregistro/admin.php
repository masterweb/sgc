<?php
/* @var $this UaccesoregistroController */
/* @var $model Accesoregistro */

$this->breadcrumbs=array(
	'Accesoregistros'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Accesoregistro', 'url'=>array('index')),
	array('label'=>'Create Accesoregistro', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#accesoregistro-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Accesoregistros</h1>

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
	'id'=>'accesoregistro-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'idconfirmado',
		'usuarios_id',
		'administrador',
		'descripcion',
		'estado',
		'fecha',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
