<?php
/* @var $this GestionEntregaController */
/* @var $model GestionEntrega */

$this->breadcrumbs=array(
	'Gestion Entregas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionEntrega', 'url'=>array('index')),
	array('label'=>'Manage GestionEntrega', 'url'=>array('admin')),
);
?>

<h1>Create GestionEntrega</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>