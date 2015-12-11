<?php
/* @var $this GestionFichaPresentacionController */
/* @var $model GestionFichaPresentacion */

$this->breadcrumbs=array(
	'Gestion Ficha Presentacions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionFichaPresentacion', 'url'=>array('index')),
	array('label'=>'Create GestionFichaPresentacion', 'url'=>array('create')),
	array('label'=>'View GestionFichaPresentacion', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionFichaPresentacion', 'url'=>array('admin')),
);
?>

<h1>Update GestionFichaPresentacion <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>