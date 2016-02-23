<?php
/* @var $this GestionPasoEntregaController */
/* @var $model GestionPasoEntrega */

$this->breadcrumbs=array(
	'Gestion Paso Entregas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionPasoEntrega', 'url'=>array('index')),
	array('label'=>'Create GestionPasoEntrega', 'url'=>array('create')),
	array('label'=>'View GestionPasoEntrega', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionPasoEntrega', 'url'=>array('admin')),
);
?>

<h1>Update GestionPasoEntrega <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form_1', array('model'=>$model)); ?>