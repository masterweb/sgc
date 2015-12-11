<?php
/* @var $this GestionFirmaController */
/* @var $model GestionFirma */

$this->breadcrumbs=array(
	'Gestion Firmas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionFirma', 'url'=>array('index')),
	array('label'=>'Manage GestionFirma', 'url'=>array('admin')),
);
?>

<h1>Create GestionFirma</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>