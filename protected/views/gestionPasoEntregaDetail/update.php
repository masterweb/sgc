<?php
/* @var $this GestionPasoEntregaDetailController */
/* @var $model GestionPasoEntregaDetail */

$this->breadcrumbs=array(
	'Gestion Paso Entrega Details'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionPasoEntregaDetail', 'url'=>array('index')),
	array('label'=>'Create GestionPasoEntregaDetail', 'url'=>array('create')),
	array('label'=>'View GestionPasoEntregaDetail', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionPasoEntregaDetail', 'url'=>array('admin')),
);
?>

<h1>Update GestionPasoEntregaDetail <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>