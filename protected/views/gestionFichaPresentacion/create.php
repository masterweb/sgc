<?php
/* @var $this GestionFichaPresentacionController */
/* @var $model GestionFichaPresentacion */

$this->breadcrumbs=array(
	'Gestion Ficha Presentacions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionFichaPresentacion', 'url'=>array('index')),
	array('label'=>'Manage GestionFichaPresentacion', 'url'=>array('admin')),
);
?>

<h1>Create GestionFichaPresentacion</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>