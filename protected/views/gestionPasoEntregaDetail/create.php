<?php
/* @var $this GestionPasoEntregaDetailController */
/* @var $model GestionPasoEntregaDetail */

$this->breadcrumbs=array(
	'Gestion Paso Entrega Details'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionPasoEntregaDetail', 'url'=>array('index')),
	array('label'=>'Manage GestionPasoEntregaDetail', 'url'=>array('admin')),
);
?>

<h1>Create GestionPasoEntregaDetail</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>