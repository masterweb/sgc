<?php
/* @var $this GestionEntregaController */
/* @var $model GestionEntrega */

$this->breadcrumbs=array(
	'Gestion Entregas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionEntrega', 'url'=>array('index')),
	array('label'=>'Create GestionEntrega', 'url'=>array('create')),
	array('label'=>'View GestionEntrega', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionEntrega', 'url'=>array('admin')),
);
?>

<h1>Update GestionEntrega <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>