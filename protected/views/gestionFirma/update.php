<?php
/* @var $this GestionFirmaController */
/* @var $model GestionFirma */

$this->breadcrumbs=array(
	'Gestion Firmas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionFirma', 'url'=>array('index')),
	array('label'=>'Create GestionFirma', 'url'=>array('create')),
	array('label'=>'View GestionFirma', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionFirma', 'url'=>array('admin')),
);
?>

<h1>Update GestionFirma <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>