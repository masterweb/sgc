<?php
/* @var $this UaccesoregistroController */
/* @var $model Accesoregistro */

$this->breadcrumbs=array(
	'Accesoregistros'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Accesoregistro', 'url'=>array('index')),
	array('label'=>'Manage Accesoregistro', 'url'=>array('admin')),
);
?>

<h1>Create Accesoregistro</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>