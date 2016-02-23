<?php
/* @var $this GestionPasoEntregaDetailController */
/* @var $model GestionPasoEntregaDetail */

$this->breadcrumbs = array(
    'Gestion Paso Entrega Details' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List GestionPasoEntregaDetail', 'url' => array('index')),
    array('label' => 'Create GestionPasoEntregaDetail', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-paso-entrega-detail-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Gestion Paso Entrega Details</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'gestion-paso-entrega-detail-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'id_paso',
        'id_gestion',
        'fecha_paso',
        'observaciones',
        'placa',
        'responsable',
        'foto_entrega',
        'foto_hoja_entrega',
        'fecha',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
