<?php
/* @var $this GestionInformacionController */
/* @var $model GestionInformacion */

$this->breadcrumbs=array(
	'Gestion Informacions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionInformacion', 'url'=>array('index')),
	array('label'=>'Create GestionInformacion', 'url'=>array('create')),
	array('label'=>'View GestionInformacion', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionInformacion', 'url'=>array('admin')),
);
?>

<div class="container">

<?php echo $this->renderPartial('_form_1', array('model'=>$model,'tipo' => $tipo)); ?>
</div>